<?php

namespace App\Console\Commands;

use App\Models\Wallpaper;
use App\Services\FacebookWallpaperService;
use Illuminate\Console\Command;

class TestFacebookPost extends Command
{
    protected $signature = 'test:facebook-post {--wallpaper-id=}';
    protected $description = 'Test Facebook posting for a wallpaper';

    public function handle()
    {
        $wallpaperId = $this->option('wallpaper-id');
        
        if (!$wallpaperId) {
            // Use the first wallpaper
            $wallpaper = Wallpaper::first();
            if (!$wallpaper) {
                $this->error('No wallpapers found in database');
                return 1;
            }
        } else {
            $wallpaper = Wallpaper::find($wallpaperId);
            if (!$wallpaper) {
                $this->error("Wallpaper with ID {$wallpaperId} not found");
                return 1;
            }
        }

        $this->info("Testing Facebook post for wallpaper: {$wallpaper->name}");
        $this->info("GitHub URL: {$wallpaper->github_url}");

        $facebookService = new FacebookWallpaperService();
        $result = $facebookService->postWallpaper($wallpaper);

        if ($result['success']) {
            $this->info("✓ Successfully posted to Facebook!");
            $this->info("Post ID: {$result['facebook_post_id']}");
        } else {
            $this->error("✗ Failed to post to Facebook");
            $this->error("Error: {$result['message']}");
            return 1;
        }

        return 0;
    }
}
