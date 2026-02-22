<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Http;
use App\Models\Wallpaper;

class WallpaperController extends Controller
{
    public function show(string $name)
    {
        // Get wallpaper from database
        $wallpaper = Wallpaper::where('filename', $name)->first();
        
        if (!$wallpaper) {
            abort(404);
        }

        // Track view
        if ($wallpaper) {
            $wallpaper->increment('views');
        }
        // Determine if this is a video
        $isVideo = str_starts_with($wallpaper->mime ?? '', 'video/');

        // Get original dimensions from database (stored at upload time)
        $originalWidth = $wallpaper->width ?? 0;
        $originalHeight = $wallpaper->height ?? 0;
        
        // For videos without stored dimensions, use default
        if ($isVideo && $originalWidth == 0) {
            $originalWidth = 1920;
            $originalHeight = 1080;
        }

        // Build available sizes
        $allSizes = [
            'original' => [$originalWidth, $originalHeight],
            '8k' => [7680, 4320],
            '4k' => [3840, 2160],
            '1080p' => [1920, 1080],
        ];

        // For images: filter by original dimensions; for videos: show original only
        $sizes = [];
        
        if ($isVideo) {
            // Videos: show original only (videos cannot be resized on-the-fly)
            $sizes['original'] = [$originalWidth, $originalHeight];
        } else {
            // Images: show original + selected sizes that fit within it
            if ($originalWidth > 0 && $originalHeight > 0) {
                // Add sizes in order (largest to smallest) that fit within original
                foreach ($allSizes as $key => $dimensions) {
                    if ($originalWidth >= $dimensions[0] && $originalHeight >= $dimensions[1]) {
                        $sizes[$key] = $dimensions;
                    }
                }
            } else {
                // If dimensions fetch failed, show 1080p only as safe fallback
                $sizes['1080p'] = [1920, 1080];
            }
        }

        return view('wallpaper', [
            'name' => $name,
            'sizes' => $sizes,
            'wallpaper' => $wallpaper,
                'isVideo' => $isVideo,
            'originalWidth' => $originalWidth,
            'originalHeight' => $originalHeight,
        ]);
    }

    public function download(string $name, string $size)
    {
        // Increase execution time for downloads
        set_time_limit(600); // 10 minutes for large file downloads
        
        // Get wallpaper from database
        $wallpaper = Wallpaper::where('filename', $name)->first();
        
        if (!$wallpaper) {
            abort(404);
        }

        // Track download
        if ($wallpaper) {
            $wallpaper->increment('downloads');
        }

        $isVideo = str_starts_with($wallpaper->mime, 'video/');

        // Handle original size download (no resize)
        if ($size === 'original') {
            // For videos, redirect to GitHub URL directly for better performance
            if ($isVideo) {
                // Increment download counter
                // Redirect to GitHub CDN for direct download (avoids memory/timeout issues)
                return redirect($wallpaper->github_url);
            }
            
            // For images, use longer timeout
            $timeout = 120;
            
            try {
                // For images, load into memory (they're much smaller than videos)
                $githubResponse = Http::timeout($timeout)->get($wallpaper->github_url);
                
                if (!$githubResponse->successful()) {
                    abort(404, 'File not found on CDN');
                }

                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $mimeMap = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'webp' => 'image/webp',
                ];
                $type = $mimeMap[$ext] ?? 'application/octet-stream';

                return response($githubResponse->body())
                    ->header('Content-Type', $type)
                    ->header('Content-Disposition', 'attachment; filename="' . $name . '"')
                    ->header('Cache-Control', 'public, max-age=31536000');
            } catch (\Exception $e) {
                \Log::error('Download failed for ' . $name . ': ' . $e->getMessage());
                abort(503, 'Download failed. The file may be temporarily unavailable. Please try again.');
            }
        }

        if ($isVideo) {
            abort(400, 'Only original size is available for videos');
        }

        $map = [
            '8k' => [7680, 4320],
            '6k' => [6016, 3384],
            '4k' => [3840, 2160],
            '2k' => [2560, 1440],
            '1080p' => [1920, 1080],
            '720p' => [1280, 720],
        ];

        if (!isset($map[$size])) {
            abort(400, 'Invalid size');
        }

        [$w, $h] = $map[$size];

        try {
            // Download image from GitHub
            $githubResponse = Http::timeout(60)->get($wallpaper->github_url);
            
            if (!$githubResponse->successful()) {
                abort(404, 'Image not found on GitHub');
            }

            $imageContent = $githubResponse->body();
            $tempOriginal = tempnam(sys_get_temp_dir(), 'wallpaper_orig_');
            file_put_contents($tempOriginal, $imageContent);

            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            
            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                    $src = @imagecreatefromjpeg($tempOriginal);
                    if (!$src) abort(500, 'Failed to load JPEG image');
                    $type = 'image/jpeg';
                    $suffix = '.jpg';
                    break;
                case 'png':
                    $src = @imagecreatefrompng($tempOriginal);
                    if (!$src) abort(500, 'Failed to load PNG image');
                    $type = 'image/png';
                    $suffix = '.png';
                    break;
                case 'webp':
                    if (!function_exists('imagecreatefromwebp')) {
                        abort(500, 'WEBP not supported by PHP GD');
                    }
                    $src = @imagecreatefromwebp($tempOriginal);
                    if (!$src) abort(500, 'Failed to load WEBP image');
                    $type = 'image/webp';
                    $suffix = '.webp';
                    break;
                default:
                    abort(400, 'Unsupported image format: ' . $ext);
            }

            $origW = imagesx($src);
            $origH = imagesy($src);

            // Fit to target while preserving aspect ratio
            $srcRatio = $origW / $origH;
            $dstRatio = $w / $h;

            if ($srcRatio > $dstRatio) {
                $newH = $origH;
                $newW = (int)($origH * $dstRatio);
                $srcX = (int)(($origW - $newW) / 2);
                $srcY = 0;
            } else {
                $newW = $origW;
                $newH = (int)($origW / $dstRatio);
                $srcX = 0;
                $srcY = (int)(($origH - $newH) / 2);
            }

            $dst = imagecreatetruecolor($w, $h);
            
            // Preserve transparency for PNG/WEBP
            if ($ext === 'png' || $ext === 'webp') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
                imagefilledrectangle($dst, 0, 0, $w, $h, $transparent);
                imagealphablending($dst, true);
            }

            imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $w, $h, $newW, $newH);

            // Generate output file
            $tmp = tempnam(sys_get_temp_dir(), 'wallpaper_');
            $outfile = $tmp . $suffix;
            
            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($dst, $outfile, 90);
                    break;
                case 'png':
                    imagepng($dst, $outfile, 8);
                    break;
                case 'webp':
                    imagewebp($dst, $outfile, 90);
                    break;
            }

            imagedestroy($src);
            imagedestroy($dst);
            
            // Clean up original temp file (only if it was a temporary file from GitHub)
            if ($wallpaper->github_url && file_exists($tempOriginal) && strpos($tempOriginal, sys_get_temp_dir()) === 0) {
                unlink($tempOriginal);
            }

            if (!file_exists($outfile)) {
                abort(500, 'Failed to generate resized image');
            }

            return response()->download($outfile, pathinfo($name, PATHINFO_FILENAME) . '_' . $size . $suffix, [
                'Content-Type' => $type,
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            abort(500, 'Image processing error: ' . $e->getMessage());
        }
    }

    public function like(Request $request, $id)
    {
        $wallpaper = Wallpaper::findOrFail($id);
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Please login to like wallpapers'], 401);
        }

        // Check if already liked
        $isLiked = $wallpaper->likedBy()->where('user_id', $user->id)->exists();
        
        if ($isLiked) {
            // Unlike
            $wallpaper->likedBy()->detach($user->id);
            $wallpaper->likes = max(0, $wallpaper->likes - 1);
            $wallpaper->save();
            
            return response()->json([
                'liked' => false,
                'likes' => $wallpaper->likes
            ]);
        } else {
            // Like - increment both likes and views
            $wallpaper->likedBy()->attach($user->id);
            $wallpaper->likes = $wallpaper->likes + 1;
            $wallpaper->increment('views'); // Add view when liking
            $wallpaper->save();
            
            // Create notification for wallpaper owner
            if ($wallpaper->user_id && $wallpaper->user_id !== $user->id) {
                \App\Models\Notification::create([
                    'user_id' => $wallpaper->user_id,
                    'type' => 'like',
                    'title' => 'New Like',
                    'message' => $user->name . ' liked your wallpaper "' . $wallpaper->name . '"',
                    'wallpaper_id' => $wallpaper->id,
                    'from_user_id' => $user->id,
                    'read' => false,
                ]);
            }
            
            return response()->json([
                'liked' => true,
                'likes' => $wallpaper->likes
            ]);
        }
    }

    public function thumbnail(string $name)
    {
        $wallpaper = Wallpaper::where('filename', $name)->first();
        
        if (!$wallpaper) {
            abort(404);
        }

        // Only generate thumbnails for videos > 17MB
        if (!str_starts_with($wallpaper->mime ?? '', 'video/') || $wallpaper->size <= 17825792) {
            abort(400, 'Thumbnails only available for large videos (>17MB)');
        }

        // Check if thumbnail is already cached
        $thumbnailPath = storage_path('app/thumbnails/' . md5($name) . '.jpg');
        if (file_exists($thumbnailPath)) {
            return response()->file($thumbnailPath, [
                'Content-Type' => 'image/jpeg',
                'Cache-Control' => 'public, max-age=31536000'
            ]);
        }

        // Generate thumbnail from video
        try {
            // Try to download video from GitHub CDN
            $timeout = 300;
            $downloadUrl = $wallpaper->github_url;
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
                        sleep(3);
                    }
                }
            }

            // Create thumbnails directory if it doesn't exist
            @mkdir(storage_path('app/thumbnails'), 0755, true);

            if (!$videoResponse || !$videoResponse->successful()) {
                // Create placeholder thumbnail
                $this->createPlaceholderThumbnail($thumbnailPath, $wallpaper);
                return response()->file($thumbnailPath, [
                    'Content-Type' => 'image/jpeg',
                    'Cache-Control' => 'public, max-age=31536000'
                ]);
            }

            // Save video to temporary location
            $tempVideo = tempnam(sys_get_temp_dir(), 'video_thumb_');
            file_put_contents($tempVideo, $videoResponse->body());

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

            if ($returnCode !== 0 || !file_exists($thumbnailTemp . '.jpg')) {
                // FFmpeg failed, create placeholder
                $this->createPlaceholderThumbnail($thumbnailPath, $wallpaper);
            } else {
                // Move thumbnail to storage
                rename($thumbnailTemp . '.jpg', $thumbnailPath);
            }

            return response()->file($thumbnailPath, [
                'Content-Type' => 'image/jpeg',
                'Cache-Control' => 'public, max-age=31536000'
            ]);

        } catch (\Exception $e) {
            \Log::error('Thumbnail generation error for ' . $name . ': ' . $e->getMessage());
            abort(503, 'Failed to generate thumbnail');
        }
    }

    private function createPlaceholderThumbnail($path, $wallpaper)
    {
        // Create a simple placeholder thumbnail
        $width = 320;
        $height = 180;
        $sizeText = round($wallpaper->size / 1024 / 1024, 1) . ' MB';
        $videoName = preg_replace('/\.mp4$|\.webm$|\.mkv$/i', '', $wallpaper->filename);

        // Try ImageMagick first
        $command = sprintf(
            'convert -size %dx%d xc:"#1a1a1a" -background "#1a1a1a" -fill "#ffc107" -gravity center ' .
            '-pointsize 16 -annotate +0+20 "Video File" ' .
            '-pointsize 12 -annotate +0+50 "%s" ' .
            '-fill "#666" -pointsize 11 -annotate +0+80 "%s" "%s" 2>&1',
            $width,
            $height,
            escapeshellarg($sizeText),
            escapeshellarg($videoName),
            escapeshellarg($path)
        );

        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            // Fallback: Create using PHP GD
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
