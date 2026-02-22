<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WallpaperController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\SitemapController;
use App\Models\Wallpaper;
use App\Models\Category;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    $user = auth()->user();
    $wallpapers = Wallpaper::with(['categories.parent'])->inRandomOrder()->paginate(24);
    
    // Convert to array format with all needed fields
    $wallpapersData = [];
    foreach ($wallpapers as $wallpaper) {
        $wallpapersData[] = [
            'id' => $wallpaper->id,
            'name' => $wallpaper->name,
            'filename' => $wallpaper->filename,
            'description' => $wallpaper->description,
            'mime' => $wallpaper->mime,
            'size' => $wallpaper->size ?? 0,
            'views' => $wallpaper->views ?? 0,
            'likes' => $wallpaper->likes ?? 0,
            'downloads' => $wallpaper->downloads ?? 0,
            'user_liked' => $user ? $wallpaper->isLikedBy($user) : false,
            'github_url' => $wallpaper->github_url,
            'categories' => $wallpaper->categories->map(function($cat) {
                $parentName = $cat->parent ? $cat->parent->name : null;
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'parent_id' => $cat->parent_id,
                    'parent_name' => $parentName,
                    'display_name' => $parentName ? "{$parentName} ({$cat->name})" : $cat->name,
                ];
            })
        ];
    }
    
    // Popular categories for homepage strip (top by wallpaper count)
    $popularCategories = Category::withCount('wallpapers')
        ->orderByDesc('wallpapers_count')
        ->take(5)
        ->get();

    // All parent categories (with children) for modal, sorted by total wallpapers (direct + subcategories)
    $allCategories = Category::whereNull('parent_id')
        ->with(['children' => function ($query) {
            $query->withCount('wallpapers')->orderByDesc('wallpapers_count');
        }])
        ->withCount('wallpapers')
        ->get()
        ->map(function ($category) {
            $direct = $category->wallpapers_count ?? 0;
            $fromChildren = $category->children->sum('wallpapers_count');
            $category->total_wallpapers_count = $direct + $fromChildren;
            return $category;
        })
        ->sortByDesc('total_wallpapers_count')
        ->values();

    return view('index', [
        'wallpapers' => $wallpapersData,
        'popularCategories' => $popularCategories,
        'allCategories' => $allCategories,
        'pagination' => [
            'current_page' => $wallpapers->currentPage(),
            'last_page' => $wallpapers->lastPage(),
            'per_page' => $wallpapers->perPage(),
            'total' => $wallpapers->total(),
            'prev_page_url' => $wallpapers->previousPageUrl(),
            'next_page_url' => $wallpapers->nextPageUrl(),
        ],
    ]);
})->name('home');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy.policy');

Route::get('/category/{slug}', function ($slug) {
    $user = auth()->user();
    $category = Category::where('slug', $slug)->firstOrFail();
    $wallpapers = $category->wallpapers()->with('categories')->latest()->get();
    
    // Convert to array format with all needed fields
    $wallpapersData = [];
    foreach ($wallpapers as $wallpaper) {
        $wallpapersData[] = [
            'id' => $wallpaper->id,
            'name' => $wallpaper->name,
            'filename' => $wallpaper->filename,
            'description' => $wallpaper->description,
            'mime' => $wallpaper->mime,
            'size' => $wallpaper->size ?? 0,
            'views' => $wallpaper->views ?? 0,
            'likes' => $wallpaper->likes ?? 0,
            'downloads' => $wallpaper->downloads ?? 0,
            'user_liked' => $user ? $wallpaper->isLikedBy($user) : false,
            'github_url' => $wallpaper->github_url,
            'categories' => $wallpaper->categories->map(function($cat) {
                return ['id' => $cat->id, 'name' => $cat->name, 'slug' => $cat->slug];
            })
        ];
    }
    
    $popularCategories = Category::withCount('wallpapers')
        ->orderByDesc('wallpapers_count')
        ->take(5)
        ->get();

    $allCategories = Category::withCount('wallpapers')->orderBy('name')->get();

    return view('index', [
        'wallpapers' => $wallpapersData,
        'popularCategories' => $popularCategories,
        'allCategories' => $allCategories,
        'category' => $category,
    ]);
})->name('category.show');

Route::get('/api/wallpapers', function () {
    // Return only essential fields for grid display
    $wallpapers = Wallpaper::select('id', 'name', 'filename', 'mime', 'github_url', 'views', 'likes', 'downloads')
        ->with('categories:id,name,slug')
        ->latest()
        ->paginate(20);
    
    return response()->json($wallpapers)->header('Cache-Control', 'public, max-age=300');
});

Route::get('/search', function () {
    $query = request('q');
    
    // Return only essential fields for search results
    $wallpapers = Wallpaper::select('id', 'name', 'filename', 'mime', 'github_url', 'views', 'likes', 'downloads', 'slug')
        ->where(function($q) use ($query) {
            // Search by name or description
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        })
        ->orWhereHas('categories', function ($q) use ($query) {
            // Search by direct category name
            $q->where('name', 'like', "%{$query}%");
        })
        ->orWhereHas('categories', function ($q) use ($query) {
            // Search by parent category name (for child categories)
            $q->whereHas('parent', function ($parent) use ($query) {
                $parent->where('name', 'like', "%{$query}%");
            });
        })
        ->orWhereHas('categories.children', function ($q) use ($query) {
            // Search by child category of a parent
            $q->where('name', 'like', "%{$query}%");
        })
        ->with('categories:id,name,slug')
        ->limit(50)
        ->get();
        
    return response()->json($wallpapers);
})->name('search');

Route::get('/wallpaper/{name}', [WallpaperController::class, 'show'])->name('wallpaper.show');
Route::get('/thumbnail/{name}', [WallpaperController::class, 'thumbnail'])->name('wallpaper.thumbnail');
Route::get('/download/{name}/{size}', [WallpaperController::class, 'download'])->name('wallpaper.download');
Route::post('/wallpaper/{id}/like', [WallpaperController::class, 'like'])->middleware('auth')->name('wallpaper.like');

// Upload (Protected Routes - requires authentication)
Route::get('/upload', [UploadController::class, 'create'])->middleware('auth')->name('wallpapers.create');
Route::post('/upload', [UploadController::class, 'store'])->middleware('auth')->name('wallpapers.store');

// Categories API
Route::get('/api/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/api/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/api/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/api/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Admin Authentication Routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('/admin/register', [AdminAuthController::class, 'register'])->name('admin.register');
    Route::post('/admin/register', [AdminAuthController::class, 'store'])->name('admin.register.submit');
});

// Admin Protected Routes
Route::middleware('auth:admin')->group(function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/wallpapers', [AdminDashboardController::class, 'showWallpapers'])->name('admin.wallpapers');
    Route::get('/admin/wallpapers/{wallpaperId}/edit', [AdminDashboardController::class, 'editWallpaper'])->name('admin.wallpapers.edit');
    Route::put('/admin/wallpapers/{wallpaperId}', [AdminDashboardController::class, 'updateWallpaper'])->name('admin.wallpapers.update');
    Route::post('/admin/wallpapers/bulk-delete', [AdminDashboardController::class, 'bulkDeleteWallpapers'])->name('admin.wallpapers.bulk-delete');
    Route::post('/admin/wallpapers/bulk-update', [AdminDashboardController::class, 'bulkUpdateWallpapers'])->name('admin.wallpapers.bulk-update');
    Route::get('/admin/bulk-upload', [AdminDashboardController::class, 'bulkUpload'])->name('admin.bulk.upload');
    Route::post('/admin/bulk-upload', [AdminDashboardController::class, 'storeBulkUpload'])->name('admin.bulk.upload.store');
    Route::get('/admin/users', [AdminDashboardController::class, 'showUsers'])->name('admin.users');
    Route::get('/admin/users/{userId}/wallpapers', [AdminDashboardController::class, 'viewUserWallpapers'])->name('admin.user.wallpapers');
    Route::delete('/admin/users/{userId}', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.delete');
    Route::delete('/admin/wallpapers/{wallpaperId}', [AdminDashboardController::class, 'deleteUserWallpaper'])->name('admin.wallpapers.delete');
    Route::get('/admin/categories', [AdminDashboardController::class, 'showCategories'])->name('admin.categories');
    Route::get('/admin/categories/create', [AdminDashboardController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/admin/categories/store', [AdminDashboardController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('/admin/categories/{category}/edit', [AdminDashboardController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [AdminDashboardController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [AdminDashboardController::class, 'deleteCategory'])->name('admin.categories.delete');
});

// User Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.submit');
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [UserAuthController::class, 'logout'])->middleware('auth')->name('logout');

// User Account Routes
Route::middleware('auth')->group(function () {
    Route::get('/my-account', [UserController::class, 'account'])->name('user.account');
    Route::delete('/my-wallpapers/{wallpaperId}', [UserController::class, 'deleteWallpaper'])->name('user.wallpapers.delete');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::get('/api/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
});

// User Profile Routes
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/trending', [ProfileController::class, 'trending'])->name('wallpapers.trending');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'Email verified successfully!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.xml');
