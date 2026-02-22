<?php

namespace App\Http\Controllers;

use App\Models\Wallpaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show user account page with their wallpapers
     */
    public function account()
    {
        $user = Auth::user();
        $wallpapers = Wallpaper::where('user_id', $user->id)->with('categories')->latest()->get();
        
        // Calculate total stats
        $totalViews = $wallpapers->sum('views');
        $totalDownloads = $wallpapers->sum('downloads');
        $totalLikes = $wallpapers->sum('likes');
        
        return view('user.account', [
            'user' => $user,
            'wallpapers' => $wallpapers,
            'totalViews' => $totalViews,
            'totalDownloads' => $totalDownloads,
            'totalLikes' => $totalLikes,
        ]);
    }

    /**
     * Delete a user's wallpaper
     */
    public function deleteWallpaper($wallpaperId)
    {
        $wallpaper = Wallpaper::findOrFail($wallpaperId);
        
        // Check if user owns this wallpaper
        if ($wallpaper->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

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

        return redirect()->route('user.account')->with('success', 'Wallpaper deleted successfully from all storage');
    }
}
