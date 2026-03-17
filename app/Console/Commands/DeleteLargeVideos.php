<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Wallpaper;
use App\Services\GitHubWallpaperService;

class DeleteLargeVideos extends Command
{
    protected $signature = 'videos:delete-large {--force : Skip confirmation}';
    protected $description = 'Delete all videos larger than 17MB from database and GitHub';

    protected $githubService;

    public function __construct(GitHubWallpaperService $githubService)
    {
        parent::__construct();
        $this->githubService = $githubService;
    }

    public function handle()
    {
        // Get all videos larger than 17MB
        $largeVideos = Wallpaper::where('mime', 'like', 'video/%')
            ->where('size', '>', 17825792)
            ->get();

        if ($largeVideos->isEmpty()) {
            $this->info('No large videos found (>17MB).');
            return 0;
        }

        $this->warn("Found {$largeVideos->count()} large videos (>17MB):\n");
        
        foreach ($largeVideos as $video) {
            $sizeMB = round($video->size / 1024 / 1024, 2);
            $this->line("  • {$video->filename} ({$sizeMB} MB)");
        }

        if (!$this->option('force')) {
            if (!$this->confirm("\nAre you sure you want to delete these videos from database and GitHub?", false)) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info("\nDeleting large videos...\n");

        $successCount = 0;
        $failureCount = 0;

        foreach ($largeVideos as $video) {
            $sizeMB = round($video->size / 1024 / 1024, 2);
            $this->line("⏳ Deleting: {$video->filename} ({$sizeMB} MB)...");

            try {
                // Delete from GitHub first
                if ($video->github_url) {
                    $deleteResult = $this->githubService->deleteWallpaper($video->filename, $video->category_folder);
                    
                    if (!$deleteResult['success']) {
                        $this->line("⚠️  GitHub deletion failed: {$deleteResult['error']}");
                    }
                }

                // Delete thumbnail if exists
                $thumbnailPath = storage_path('app/thumbnails/' . md5($video->filename) . '.jpg');
                if (file_exists($thumbnailPath)) {
                    @unlink($thumbnailPath);
                }

                // Delete from database
                $video->delete();

                $this->line("✅ Deleted: {$video->filename}");
                $successCount++;

            } catch (\Exception $e) {
                $this->line("❌ Failed: {$video->filename} - {$e->getMessage()}");
                $failureCount++;
            }
        }

        $this->info("\n" . str_repeat('=', 60));
        $this->info("Deletion Complete!");
        $this->info("✅ Successfully deleted: {$successCount}");
        $this->info("❌ Failed: {$failureCount}");
        $this->info("=" . str_repeat('=', 59));

        return 0;
    }
}
