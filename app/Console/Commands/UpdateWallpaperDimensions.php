<?php

namespace App\Console\Commands;

use App\Models\Wallpaper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use getID3;

class UpdateWallpaperDimensions extends Command
{
    protected $signature = 'wallpapers:update-dimensions';
    protected $description = 'Update width and height for existing wallpapers';

    public function handle()
    {
        $wallpapers = Wallpaper::whereNull('width')->orWhereNull('height')->get();
        
        if ($wallpapers->isEmpty()) {
            $this->info('No wallpapers need dimension updates.');
            return 0;
        }

        $this->info("Found {$wallpapers->count()} wallpapers to update...");
        $bar = $this->output->createProgressBar($wallpapers->count());
        $bar->start();

        $updated = 0;
        $failed = 0;

        foreach ($wallpapers as $wallpaper) {
            try {
                $isVideo = str_starts_with($wallpaper->mime, 'video/');
                
                if ($isVideo) {
                    // Fetch video from GitHub and extract dimensions
                    try {
                        $response = Http::timeout(60)->get($wallpaper->github_url);
                        
                        if ($response->successful()) {
                            $tempFile = sys_get_temp_dir() . '/' . uniqid() . '_' . $wallpaper->filename;
                            file_put_contents($tempFile, $response->body());
                            
                            $getID3 = new getID3();
                            $fileInfo = $getID3->analyze($tempFile);
                            
                            if (isset($fileInfo['video']['resolution_x']) && isset($fileInfo['video']['resolution_y'])) {
                                $wallpaper->update([
                                    'width' => $fileInfo['video']['resolution_x'],
                                    'height' => $fileInfo['video']['resolution_y'],
                                ]);
                                $updated++;
                            } else {
                                // Fallback to default if extraction fails
                                $wallpaper->update([
                                    'width' => 1920,
                                    'height' => 1080,
                                ]);
                                $updated++;
                            }
                            
                            if (file_exists($tempFile)) {
                                unlink($tempFile);
                            }
                        } else {
                            $failed++;
                        }
                    } catch (\Exception $videoException) {
                        // Fallback to default dimensions on any error
                        $wallpaper->update([
                            'width' => 1920,
                            'height' => 1080,
                        ]);
                        $updated++;
                    }
                } else {
                    // Fetch dimensions for images from GitHub
                    $response = Http::timeout(30)->get($wallpaper->github_url);
                    
                    if ($response->successful()) {
                        $imageSize = @getimagesizefromstring($response->body());
                        
                        if ($imageSize) {
                            $wallpaper->update([
                                'width' => $imageSize[0],
                                'height' => $imageSize[1],
                            ]);
                            $updated++;
                        } else {
                            $failed++;
                        }
                    } else {
                        $failed++;
                    }
                }
                
                $bar->advance();
                
            } catch (\Exception $e) {
                $failed++;
                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Updated: {$updated} wallpapers");
        
        if ($failed > 0) {
            $this->warn("Failed: {$failed} wallpapers");
        }

        return 0;
    }
}
