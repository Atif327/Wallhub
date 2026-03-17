<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Wallpaper;
use Illuminate\Support\Facades\Http;

class GenerateVideoThumbnails extends Command
{
    protected $signature = 'videos:generate-thumbnails {--force : Force regeneration of existing thumbnails}';
    protected $description = 'Generate thumbnails for all large videos (>17MB)';

    public function handle()
    {
        $force = $this->option('force');
        
        // Get all videos larger than 17MB
        $videos = Wallpaper::where('mime', 'like', 'video/%')
            ->where('size', '>', 17825792)
            ->get();

        if ($videos->isEmpty()) {
            $this->info('No large videos found.');
            return 0;
        }

        $this->info("Found {$videos->count()} large videos. Generating thumbnails...\n");

        $successCount = 0;
        $failureCount = 0;

        foreach ($videos as $video) {
            $thumbnailPath = storage_path('app/thumbnails/' . md5($video->filename) . '.jpg');
            
            // Skip if thumbnail exists and not forcing regeneration
            if (file_exists($thumbnailPath) && !$force) {
                $this->line("⏭️  Skipped: {$video->filename} (thumbnail exists)");
                continue;
            }

            $this->line("⏳ Processing: {$video->filename} ({$this->formatBytes($video->size)})...");

            try {
                // Create thumbnails directory if it doesn't exist
                @mkdir(storage_path('app/thumbnails'), 0755, true);

                // Try to download video from GitHub CDN (with retry logic)
                $timeout = 300;
                $downloadUrl = $video->github_url;
                $videoResponse = null;
                $maxRetries = 2;
                
                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    try {
                        $videoResponse = Http::timeout($timeout)->get($downloadUrl);
                        if ($videoResponse->successful()) {
                            break;
                        }
                    } catch (\Exception $e) {
                        if ($attempt < $maxRetries) {
                            $this->line("   Retry {$attempt} failed, waiting 5 seconds...");
                            sleep(5);
                        }
                    }
                }
                
                if (!$videoResponse || !$videoResponse->successful()) {
                    // Fallback: Create a placeholder thumbnail with video info
                    $this->line("   CDN unavailable, creating placeholder thumbnail...");
                    $this->createPlaceholderThumbnail($thumbnailPath, $video);
                    $this->line("✅ Placeholder: {$video->filename}");
                    $successCount++;
                    continue;
                }

                // Save video to temporary location
                $tempVideo = tempnam(sys_get_temp_dir(), 'video_thumb_');
                $bytesWritten = file_put_contents($tempVideo, $videoResponse->body());
                
                if ($bytesWritten === false) {
                    throw new \Exception('Failed to write temp video file');
                }

                // Use FFmpeg to extract thumbnail at 5 seconds
                $thumbnailTemp = tempnam(sys_get_temp_dir(), 'thumb_');
                $command = sprintf(
                    'ffmpeg -ss 5 -i "%s" -vf "scale=320:-1" -vframes 1 -q:v 2 "%s" 2>&1',
                    escapeshellarg($tempVideo),
                    escapeshellarg($thumbnailTemp . '.jpg')
                );

                $output = [];
                $returnCode = 0;
                exec($command, $output, $returnCode);

                // Clean up temp video
                @unlink($tempVideo);

                if ($returnCode !== 0) {
                    // FFmpeg failed, create placeholder instead
                    $this->line("   FFmpeg failed, creating placeholder thumbnail...");
                    $this->createPlaceholderThumbnail($thumbnailPath, $video);
                    $this->line("✅ Placeholder: {$video->filename}");
                    $successCount++;
                    continue;
                }

                // Move thumbnail to storage
                if (!file_exists($thumbnailTemp . '.jpg')) {
                    throw new \Exception('Thumbnail file not created');
                }
                
                rename($thumbnailTemp . '.jpg', $thumbnailPath);

                $this->line("✅ Success: {$video->filename}");
                $successCount++;

            } catch (\Exception $e) {
                $this->line("❌ Failed: {$video->filename} - {$e->getMessage()}");
                $failureCount++;
            }
        }

        $this->info("\n" . str_repeat('=', 60));
        $this->info("Thumbnail Generation Complete!");
        $this->info("✅ Success: {$successCount}");
        $this->info("❌ Failed: {$failureCount}");
        $this->info("=" . str_repeat('=', 59));

        return 0;
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function createPlaceholderThumbnail($path, $video)
    {
        // Create a simple placeholder thumbnail using ImageMagick
        $width = 320;
        $height = 180;
        $sizeText = $this->formatBytes($video->size);
        
        $command = sprintf(
            'convert -size %dx%d xc:"#1a1a1a" -background "#1a1a1a" -fill "#ffc107" -gravity center ' .
            '-pointsize 16 -annotate +0+20 "Video File" ' .
            '-pointsize 12 -annotate +0+50 "%s" ' .
            '-fill "#666" -pointsize 11 -annotate +0+80 "%s" "%s" 2>&1',
            $width,
            $height,
            escapeshellarg($sizeText),
            escapeshellarg(preg_replace('/\.mp4$|\.webm$|\.mkv$/i', '', $video->filename)),
            escapeshellarg($path)
        );

        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            // Fallback: Create a simple JPEG thumbnail
            $image = imagecreatetruecolor($width, $height);
            $bgColor = imagecolorallocate($image, 26, 26, 26);
            $textColor = imagecolorallocate($image, 255, 193, 7);
            imagefill($image, 0, 0, $bgColor);
            imagestring($image, 3, 10, 10, 'Video: ' . $sizeText, $textColor);
            imagejpeg($image, $path, 80);
            imagedestroy($image);
        }
    }
}
