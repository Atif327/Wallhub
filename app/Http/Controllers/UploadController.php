<?php

namespace App\Http\Controllers;

use App\Models\Wallpaper;
use App\Models\Category;
use App\Services\GitHubWallpaperService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    protected $githubService;

    public function __construct(GitHubWallpaperService $githubService)
    {
        $this->githubService = $githubService;
    }

    public function create()
    {
        // Get only parent categories, ordered by wallpaper count
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
        return view('wallpapers.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Increase execution time for large video uploads
        set_time_limit(300); // 5 minutes
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'categories' => 'nullable|array',
            'categories.*' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            // Allow images up to 8MB (8192 KB), videos up to 17MB (17408 KB)
            'image' => 'required|mimes:jpeg,png,jpg,webp,mp4|max:17408',
        ]);

        $file = $validated['image'];
        $mimeType = $file->getClientMimeType();
        $fileSize = $file->getSize();
        
        // Check image size limit (8MB = 8388608 bytes)
        if (str_starts_with($mimeType, 'image/') && $fileSize > 8388608) {
            return back()->withErrors([
                'image' => 'Image files must be 8MB or smaller. Your image is ' . round($fileSize / 1024 / 1024, 2) . ' MB.'
            ]);
        }
        
        // Check video size limit (17MB = 17825792 bytes)
        if (str_starts_with($mimeType, 'video/') && $fileSize > 17825792) {
            return back()->withErrors([
                'image' => 'Video files must be 17MB or smaller. Your video is ' . round($fileSize / 1024 / 1024, 2) . ' MB.'
            ]);
        }
        
        $width = null;
        $height = null;

        // Extract dimensions for images and videos
        if (str_starts_with($mimeType, 'image/')) {
            $imagePath = $file->getPathname();
            $imageSize = getimagesize($imagePath);
            
            if ($imageSize === false) {
                return back()->withErrors(['image' => 'Invalid image file.']);
            }
            
            $width = $imageSize[0];
            $height = $imageSize[1];
            
            // Check if image is at least 1280x720 (720p)
            if ($width < 1280 || $height < 720) {
                return back()->withErrors([
                    'image' => 'Image resolution must be at least 1280x720 (720p). Your image is ' . $width . 'x' . $height . '.'
                ]);
            }
        } elseif (str_starts_with($mimeType, 'video/')) {
            // Extract video dimensions using getID3
            $getID3 = new \getID3();
            $fileInfo = $getID3->analyze($file->getPathname());
            
            if (isset($fileInfo['video']['resolution_x']) && isset($fileInfo['video']['resolution_y'])) {
                $width = $fileInfo['video']['resolution_x'];
                $height = $fileInfo['video']['resolution_y'];
            }
        }
        
        // Create filename from wallpaper name (sanitize for filesystem)
        $cleanName = preg_replace('/[^A-Za-z0-9\-\_\s]/', '', $validated['name']);
        $cleanName = preg_replace('/\s+/', '_', trim($cleanName));
        $filename = $cleanName . '.' . $file->getClientOriginalExtension();

        // Capture metadata
        $mime = $file->getClientMimeType();
        $size = $file->getSize();

        // Get category names and build folder path from parent/child
        $categoryIds = $validated['categories'] ?? [];
        $categoryFolder = null;
        $folderParts = []; // For GitHub upload
        
        \Log::info('Upload - Selected category IDs:', $categoryIds);
        
        if (!empty($categoryIds)) {
            // Get all selected categories
            $selectedCategories = Category::whereIn('id', $categoryIds)->get();
            
            \Log::info('Upload - Selected categories:', $selectedCategories->toArray());
            
            // Prioritize subcategories over parent categories
            // If there's a subcategory selected, use it; otherwise use a parent category
            $subcategory = $selectedCategories->first(function($cat) {
                return $cat->parent_id !== null;
            });
            
            if ($subcategory) {
                // Use the subcategory and its parent for the path
                $parent = Category::find($subcategory->parent_id);
                if ($parent) {
                    $categoryFolder = $parent->name . '/' . $subcategory->name;
                    $folderParts = [$parent->name, $subcategory->name];
                    \Log::info('Upload - Subcategory found. Folder parts:', $folderParts);
                } else {
                    // Parent not found, just use subcategory name
                    $categoryFolder = $subcategory->name;
                    $folderParts = [$subcategory->name];
                    \Log::warning('Upload - Subcategory parent not found. Using subcategory only:', $folderParts);
                }
            } else {
                // No subcategory selected, use first parent category
                $parentCategory = $selectedCategories->first();
                if ($parentCategory) {
                    $categoryFolder = $parentCategory->name;
                    $folderParts = [$parentCategory->name];
                    \Log::info('Upload - No subcategory found. Using parent category:', $folderParts);
                }
            }
        }

        // Create temporary file for GitHub upload
        $tempPath = sys_get_temp_dir() . '/' . $filename;
        $file->move(sys_get_temp_dir(), $filename);

        // Upload to GitHub (required, not optional)
        $githubResult = $this->githubService->uploadWallpaper(
            $tempPath,
            $filename,
            $folderParts
        );

        // Clean up temporary file
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }

        // If GitHub upload fails, don't allow the upload
        if (!$githubResult['success']) {
            return back()->withErrors([
                'image' => 'Failed to upload to GitHub: ' . $githubResult['error'] . '. Please try again.'
            ]);
        }

        // Get primary category folder
        $categoryFolder = $categoryFolder ?? null;

        // Create wallpaper record with GitHub URL
        $wallpaper = Wallpaper::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'filename' => $filename,
            'mime' => $mime,
            'size' => $size,
            'user_id' => Auth::id(),
            'github_url' => $githubResult['github_url'],
            'category_folder' => $categoryFolder,
            'width' => $width,
            'height' => $height,
        ]);

        // Attach selected categories to wallpaper
        if (!empty($categoryIds)) {
            $wallpaper->categories()->attach($categoryIds);
        }

        // Return JSON redirect for AJAX uploads; fallback to standard redirect
        $redirectUrl = route('wallpaper.show', ['name' => $wallpaper->filename]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'redirect' => $redirectUrl,
                'status' => 'Wallpaper uploaded successfully!'
            ]);
        }

        return redirect()->to($redirectUrl)
            ->with('status', 'Wallpaper uploaded successfully to GitHub in the "' . ($categoryFolder ?? 'wallpapers') . '" folder!');
    }

    public function edit(Wallpaper $wallpaper)
    {
        $categories = Category::orderBy('name')->pluck('name')->toArray();
        return view('wallpapers.edit', compact('wallpaper','categories'));
    }

    public function update(Request $request, Wallpaper $wallpaper)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
        ]);

        $wallpaper->update($validated);
        return redirect()->route('wallpaper.show', ['name' => $wallpaper->filename])
            ->with('status', 'Wallpaper updated successfully!');
    }
}
