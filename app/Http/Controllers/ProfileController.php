<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallpaper;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show a user's public profile with their wallpapers
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $wallpapers = $user->wallpapers()->with('categories')->latest()->get();
        
        // Convert to array format with all fields
        $wallpapersData = [];
        $currentUser = auth()->user();
        foreach ($wallpapers as $wallpaper) {
            $wallpapersData[] = [
                'id' => $wallpaper->id,
                'name' => $wallpaper->name,
                'filename' => $wallpaper->filename,
                'description' => $wallpaper->description,
                'mime' => $wallpaper->mime,
                'views' => $wallpaper->views ?? 0,
                'likes' => $wallpaper->likes ?? 0,
                'downloads' => $wallpaper->downloads ?? 0,
                'user_liked' => $currentUser ? $wallpaper->isLikedBy($currentUser) : false,
                'github_url' => $wallpaper->github_url,
                'categories' => $wallpaper->categories->map(function($cat) {
                    return ['id' => $cat->id, 'name' => $cat->name, 'slug' => $cat->slug];
                })
            ];
        }
        
        return view('profile.show', [
            'user' => $user,
            'wallpapers' => $wallpapersData,
            'totalViews' => $wallpapers->sum('views'),
            'totalDownloads' => $wallpapers->sum('downloads'),
            'totalLikes' => $wallpapers->sum('likes')
        ]);
    }

    /**
     * Show trending wallpapers
     */
    public function trending()
    {
        $trendingWallpapers = Wallpaper::with('categories', 'user')
            ->orderBy('views', 'desc')
            ->orderBy('likes', 'desc')
            ->latest()
            ->paginate(20);
        
        // Convert to array format
        $wallpapersData = [];
        $currentUser = auth()->user();
        foreach ($trendingWallpapers as $wallpaper) {
            $wallpapersData[] = [
                'id' => $wallpaper->id,
                'name' => $wallpaper->name,
                'filename' => $wallpaper->filename,
                'description' => $wallpaper->description,
                'views' => $wallpaper->views ?? 0,
                'likes' => $wallpaper->likes ?? 0,
                'downloads' => $wallpaper->downloads ?? 0,
                'user_liked' => $currentUser ? $wallpaper->isLikedBy($currentUser) : false,
                'github_url' => $wallpaper->github_url,
                'user' => $wallpaper->user ? ['id' => $wallpaper->user->id, 'name' => $wallpaper->user->name] : null,
                'categories' => $wallpaper->categories->map(function($cat) {
                    return ['id' => $cat->id, 'name' => $cat->name, 'slug' => $cat->slug];
                })
            ];
        }
        
        return view('trending', [
            'wallpapers' => $wallpapersData,
            'pagination' => $trendingWallpapers
        ]);
    }
}

