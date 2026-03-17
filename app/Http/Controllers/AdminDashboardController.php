<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Wallpaper;
use App\Models\User;
use App\Services\FacebookWallpaperService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AdminDashboardController extends BaseController
{
    public function index()
    {
        $categories = Category::withCount('wallpapers')->get();
        $totalWallpapers = Wallpaper::count();
        $totalCategories = Category::whereNull('parent_id')->count();
        $totalUsers = User::count();
        
        // Statistics
        $totalViews = Wallpaper::sum('views');
        $totalLikes = Wallpaper::sum('likes');
        $totalDownloads = Wallpaper::sum('downloads');
        
        // Recent wallpapers
        $recentWallpapers = Wallpaper::with('user')->latest()->take(5)->get();
        
        // Most popular wallpapers
        $popularWallpapers = Wallpaper::with('user')
            ->orderByDesc('views')
            ->take(5)
            ->get();
        
        // Most active users
        $activeUsers = User::withCount('wallpapers')
            ->orderByDesc('wallpapers_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'categories' => $categories,
            'totalWallpapers' => $totalWallpapers,
            'totalCategories' => $totalCategories,
            'totalUsers' => $totalUsers,
            'totalViews' => $totalViews,
            'totalLikes' => $totalLikes,
            'totalDownloads' => $totalDownloads,
            'recentWallpapers' => $recentWallpapers,
            'popularWallpapers' => $popularWallpapers,
            'activeUsers' => $activeUsers,
        ]);
    }

    public function showCategories()
    {
        // Only get parent categories (those without parent_id) with their children
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->withCount('wallpapers')->orderByDesc('wallpapers_count');
            }])
            ->withCount('wallpapers')
            ->get()
            ->map(function($category) {
                // Calculate total wallpapers: direct wallpapers + all from children
                $directWallpapers = $category->wallpapers_count ?? 0;
                $childWallpaperCount = $category->children->sum('wallpapers_count');
                $category->total_wallpapers_count = $directWallpapers + $childWallpaperCount;
                return $category;
            })
            ->sortByDesc('total_wallpapers_count')
            ->values();
        
        return view('admin.categories', ['categories' => $categories]);
    }

    public function createCategory()
    {
        // Get all categories that can be parents (those without parent_id)
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.create-category', ['parentCategories' => $parentCategories]);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories')->with('success', 'Category created successfully');
    }

    public function editCategory(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.edit-category', ['category' => $category, 'parentCategories' => $parentCategories]);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $category->id,
            'subcategories' => 'nullable|string',
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        // Handle adding subcategories (only if it's a parent category)
        if (!$category->parent_id && !empty($validated['subcategories'])) {
            $subcategoryNames = array_filter(array_map('trim', explode("\n", $validated['subcategories'])));
            
            foreach ($subcategoryNames as $subName) {
                if (!empty($subName)) {
                    // Check if subcategory already exists under this parent
                    $exists = Category::where('parent_id', $category->id)
                        ->where('name', $subName)
                        ->exists();
                    
                    if (!$exists) {
                        Category::create([
                            'name' => $subName,
                            'parent_id' => $category->id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully');
    }

    public function showUsers()
    {
        $users = User::latest()->get();
        return view('admin.users', ['users' => $users]);
    }

    public function viewUserWallpapers($userId)
    {
        $user = User::findOrFail($userId);
        $wallpapers = Wallpaper::where('user_id', $userId)->latest()->get();
        return view('admin.user-wallpapers', ['user' => $user, 'wallpapers' => $wallpapers]);
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $githubService = app(\App\Services\GitHubWallpaperService::class);
        
        // Delete all user's wallpapers from GitHub and local storage
        $wallpapers = Wallpaper::where('user_id', $userId)->get();
        foreach ($wallpapers as $wallpaper) {
            // Delete from local storage if it exists
            if (file_exists(public_path('images/' . $wallpaper->filename))) {
                unlink(public_path('images/' . $wallpaper->filename));
            }
            
            // Delete from GitHub
            if ($wallpaper->github_url) {
                $githubService->deleteWallpaper($wallpaper->filename, $wallpaper->category_folder);
            }
        }
        
        // Delete wallpaper records from database
        Wallpaper::where('user_id', $userId)->delete();
        
        // Delete user account
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User account and all wallpapers deleted successfully from all storage');
    }

    public function deleteUserWallpaper($wallpaperId)
    {
        $wallpaper = Wallpaper::findOrFail($wallpaperId);
        $userId = $wallpaper->user_id;
        
        // Delete from local storage if it exists
        if (file_exists(public_path('images/' . $wallpaper->filename))) {
            unlink(public_path('images/' . $wallpaper->filename));
        }
        
        // Delete from GitHub if it has a github_url
        if ($wallpaper->github_url) {
            $githubService = app(\App\Services\GitHubWallpaperService::class);
            $githubService->deleteWallpaper($wallpaper->filename, $wallpaper->category_folder);
        }
        
        // Delete the wallpaper record
        $wallpaper->delete();

        return redirect()->back()->with('success', 'Wallpaper deleted successfully from both local and GitHub storage');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully');
    }

    public function showWallpapers()
    {
        $wallpapers = Wallpaper::with(['categories' => function($query) {
                $query->select('categories.id', 'categories.name', 'categories.parent_id')
                      ->with('parent:id,name');
            }])
            ->select('id', 'name', 'filename', 'mime', 'size', 'github_url', 'created_at')
            ->latest()
            ->paginate(16);

        // Get parent categories with wallpaper counts for the edit modal
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->withCount('wallpapers')->orderByDesc('wallpapers_count');
            }])
            ->withCount('wallpapers')
            ->get()
            ->map(function($category) {
                // Calculate total wallpapers: direct wallpapers + all from children
                $directWallpapers = $category->wallpapers_count ?? 0;
                $childWallpaperCount = $category->children->sum('wallpapers_count');
                $category->total_wallpapers_count = $directWallpapers + $childWallpaperCount;
                return $category;
            })
            ->sortByDesc('total_wallpapers_count')
            ->values();

        return view('admin.wallpapers', [
            'wallpapers' => $wallpapers,
            'categories' => $categories,
        ]);
    }

    public function bulkDeleteWallpapers(Request $request)
    {
        $request->validate([
            'wallpaper_ids' => 'required|string',
        ]);

        $ids = collect(explode(',', $request->input('wallpaper_ids')))
            ->filter()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return redirect()->back()->withErrors(['bulk' => 'No wallpapers selected.']);
        }

        $githubService = app(\App\Services\GitHubWallpaperService::class);

        foreach (Wallpaper::whereIn('id', $ids)->get() as $wallpaper) {
            if (file_exists(public_path('images/' . $wallpaper->filename))) {
                @unlink(public_path('images/' . $wallpaper->filename));
            }

            if ($wallpaper->github_url) {
                $githubService->deleteWallpaper($wallpaper->filename, $wallpaper->category_folder);
            }

            $wallpaper->delete();
        }

        return redirect()->back()->with('success', 'Selected wallpapers deleted successfully.');
    }

    public function bulkUpdateWallpapers(Request $request)
    {
        $validated = $request->validate([
            'wallpaper_ids' => 'required|string',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $ids = collect(explode(',', $validated['wallpaper_ids']))
            ->filter()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return redirect()->back()->withErrors(['bulk' => 'No wallpapers selected.']);
        }

        $categoryIds = $validated['categories'];

        foreach (Wallpaper::whereIn('id', $ids)->get() as $wallpaper) {
            $wallpaper->categories()->sync($categoryIds);
        }

        return redirect()->back()->with('success', 'Selected wallpapers updated successfully.');
    }

    public function editWallpaper($wallpaperId)
    {
        $wallpaper = Wallpaper::with('categories')->findOrFail($wallpaperId);
        $categories = Category::orderBy('name')->get();
        $selectedCategoryIds = $wallpaper->categories->pluck('id')->toArray();
        
        return view('admin.edit-wallpaper', [
            'wallpaper' => $wallpaper,
            'categories' => $categories,
            'selectedCategoryIds' => $selectedCategoryIds
        ]);
    }

    public function updateWallpaper(Request $request, $wallpaperId)
    {
        $wallpaper = Wallpaper::findOrFail($wallpaperId);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        // Update wallpaper details
        $wallpaper->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // Sync categories
        if (!empty($validated['categories'])) {
            $wallpaper->categories()->sync($validated['categories']);
        } else {
            $wallpaper->categories()->detach();
        }

        return redirect()->route('admin.wallpapers')->with('success', 'Wallpaper updated successfully');
    }

    public function bulkUpload()
    {
        // Get only parent categories with their children, ordered by wallpaper count
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->withCount('wallpapers')->orderByDesc('wallpapers_count');
            }])
            ->withCount('wallpapers')
            ->get()
            ->map(function($category) {
                // Calculate total wallpapers: direct wallpapers + all from children
                $directWallpapers = $category->wallpapers_count ?? 0;
                $childWallpaperCount = $category->children->sum('wallpapers_count');
                $category->total_wallpapers_count = $directWallpapers + $childWallpaperCount;
                return $category;
            })
            ->sortByDesc('total_wallpapers_count')
            ->values();
        return view('admin.bulk-upload', ['categories' => $categories]);
    }

    public function storeBulkUpload(Request $request)
    {
        // Increase execution time for bulk uploads
        set_time_limit(300); // 5 minutes
        ini_set('max_execution_time', 300);
        
        $request->validate([
            // Allow images and mp4 videos up to 24.9MB (25549 KB)
            'wallpapers.*' => 'required|mimes:jpeg,png,jpg,webp,mp4|max:25549',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        $uploadedCount = 0;
        $errors = [];
        $categoryIds = $request->input('categories', []);

        if ($request->hasFile('wallpapers')) {
            $githubService = app(\App\Services\GitHubWallpaperService::class);
            $facebookService = new FacebookWallpaperService();
            
            // Get category folder for GitHub (handle parent/child structure)
            $categoryFolder = 'Uncategorized';
            $folderParts = ['Uncategorized'];
            
            if (!empty($categoryIds)) {
                // Get all selected categories
                $selectedCategories = Category::whereIn('id', $categoryIds)->get();
                
                // Prioritize subcategories over parent categories
                $subcategory = $selectedCategories->first(function($cat) {
                    return $cat->parent_id !== null;
                });
                
                if ($subcategory) {
                    // Use the subcategory and its parent for the path
                    $parent = Category::find($subcategory->parent_id);
                    if ($parent) {
                        $categoryFolder = $parent->name . '/' . $subcategory->name;
                        $folderParts = [$parent->name, $subcategory->name];
                    } else {
                        // Parent not found, just use subcategory name
                        $categoryFolder = $subcategory->name;
                        $folderParts = [$subcategory->name];
                    }
                } else {
                    // No subcategory selected, use first parent category
                    $primaryCategory = $selectedCategories->first();
                    if ($primaryCategory) {
                        $categoryFolder = $primaryCategory->name;
                        $folderParts = [$primaryCategory->name];
                    }
                }
            }

            foreach ($request->file('wallpapers') as $index => $file) {
                try {
                    $mimeType = $file->getMimeType();
                    $width = null;
                    $height = null;

                    // Extract dimensions for images and videos
                    if (str_starts_with($mimeType, 'image/')) {
                        list($width, $height) = getimagesize($file->getRealPath());
                        if ($width < 500 || $height < 500) {
                            $errors[] = "{$file->getClientOriginalName()}: Image is too small. Min size: 500x500 pixels";
                            continue;
                        }
                    } elseif (str_starts_with($mimeType, 'video/')) {
                        // Extract video dimensions using getID3
                        $getID3 = new \getID3();
                        $fileInfo = $getID3->analyze($file->getRealPath());
                        
                        if (isset($fileInfo['video']['resolution_x']) && isset($fileInfo['video']['resolution_y'])) {
                            $width = $fileInfo['video']['resolution_x'];
                            $height = $fileInfo['video']['resolution_y'];
                        }
                    }

                    $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);

                    // Upload to GitHub with timeout handling
                    try {
                        $githubResponse = $githubService->uploadWallpaper(
                            $file->getRealPath(),
                            $filename,
                            $folderParts
                        );

                        if (!is_array($githubResponse) || empty($githubResponse['success'])) {
                            $message = is_array($githubResponse) ? ($githubResponse['error'] ?? 'Unknown GitHub error') : 'Unknown GitHub error';
                            $errors[] = "{$file->getClientOriginalName()}: GitHub upload failed - {$message}";
                            continue;
                        }

                        $githubUrl = $githubResponse['github_url'] ?? null;
                        if (!$githubUrl) {
                            $errors[] = "{$file->getClientOriginalName()}: GitHub upload missing URL";
                            continue;
                        }
                    } catch (\Exception $githubException) {
                        $errors[] = "{$file->getClientOriginalName()}: GitHub upload failed - {$githubException->getMessage()}";
                        continue;
                    }

                    // Create wallpaper record
                    $wallpaper = Wallpaper::create([
                        'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        'filename' => $filename,
                        'mime' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'user_id' => auth('admin')->id(),
                        'github_url' => $githubUrl,
                        'category_folder' => $categoryFolder,
                        'views' => 0,
                        'likes' => 0,
                        'downloads' => 0,
                        'width' => $width,
                        'height' => $height,
                    ]);

                    // Attach categories
                    if (!empty($categoryIds)) {
                        $wallpaper->categories()->attach($categoryIds);
                    }

                    // Post to Facebook Page
                    try {
                        $facebookResult = $facebookService->postWallpaper($wallpaper);
                        if (!$facebookResult['success']) {
                            \Log::warning("Facebook posting failed for wallpaper {$wallpaper->id}: {$facebookResult['message']}");
                        }
                    } catch (\Exception $facebookException) {
                        \Log::error("Facebook service error for wallpaper {$wallpaper->id}: {$facebookException->getMessage()}");
                    }

                    $uploadedCount++;
                    
                    // Clear memory after each upload
                    gc_collect_cycles();
                    
                } catch (\Exception $e) {
                    $errors[] = "{$file->getClientOriginalName()}: {$e->getMessage()}";
                }
            }
        }

        if ($uploadedCount > 0) {
            $message = "Successfully uploaded {$uploadedCount} wallpaper(s)";
            if (!empty($errors)) {
                $message .= ". " . count($errors) . " failed.";
            }
            return redirect()->route('admin.wallpapers')->with('success', $message);
        }

        return back()->withErrors($errors)->with('error', 'Failed to upload wallpapers');
    }
}
