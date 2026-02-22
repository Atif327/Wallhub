<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display all categories with subcategories
     */
    public function index()
    {
        // Get only parent categories with their children, ordered by wallpaper count (desc)
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->withCount('wallpapers')->orderByDesc('wallpapers_count');
            }])
            ->withCount('wallpapers')
            ->get()
            ->map(function ($category) {
                // Total wallpapers = direct wallpapers + all child wallpapers
                $direct = $category->wallpapers_count ?? 0;
                $fromChildren = $category->children->sum('wallpapers_count');
                $category->total_wallpapers_count = $direct + $fromChildren;
                return $category;
            })
            ->sortByDesc('total_wallpapers_count')
            ->values();

        return response()->json($categories);
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'category' => $category
        ], 201);
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'category' => $category
        ]);
    }

    /**
     * Delete category
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!'
        ]);
    }
}
