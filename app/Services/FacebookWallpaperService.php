<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookWallpaperService
{
    private $pageAccessToken;
    private $pageId;
    private $apiUrl = 'https://graph.facebook.com/v18.0';

    public function __construct()
    {
        $this->pageAccessToken = config('services.facebook.page_token');
        $this->pageId = config('services.facebook.page_id');
    }

    /**
     * Post wallpaper to Facebook Page
     */
    public function postWallpaper($wallpaper)
    {
        try {
            if (!$this->pageAccessToken || !$this->pageId) {
                Log::warning('Facebook credentials not configured');
                return ['success' => false, 'message' => 'Facebook not configured'];
            }

            $imageUrl = $wallpaper->github_url;
            $caption = $this->buildCaption($wallpaper);
            
            Log::info('Attempting to post to Facebook', [
                'wallpaper_id' => $wallpaper->id,
                'page_id' => $this->pageId,
            ]);

            // Try to post as a feed post (works with page access tokens)
            $response = Http::timeout(30)->post(
                "{$this->apiUrl}/{$this->pageId}/feed",
                [
                    'message' => $caption,
                    'link' => route('wallpaper.show', $wallpaper->id),
                    'picture' => $imageUrl,
                    'access_token' => $this->pageAccessToken,
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Wallpaper posted to Facebook', [
                    'wallpaper_id' => $wallpaper->id,
                    'facebook_post_id' => $data['id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message' => 'Posted to Facebook',
                    'facebook_post_id' => $data['id'] ?? null,
                ];
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Unknown error';
                $errorCode = $errorData['error']['code'] ?? null;
                
                Log::warning('Facebook post failed - trying alternative method', [
                    'wallpaper_id' => $wallpaper->id,
                    'status' => $response->status(),
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage,
                ]);

                // If feed post fails, try posting to photos endpoint
                if ($errorCode === 100 || strpos($errorMessage, 'not exist') !== false) {
                    return $this->postAsPhoto($wallpaper, $imageUrl, $caption);
                }

                return [
                    'success' => false,
                    'message' => 'Failed to post to Facebook: ' . $errorMessage,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Facebook service error', [
                'error' => $e->getMessage(),
                'wallpaper_id' => $wallpaper->id ?? null,
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Post as photo post
     */
    private function postAsPhoto($wallpaper, $imageUrl, $caption)
    {
        try {
            $response = Http::timeout(30)->post(
                "{$this->apiUrl}/{$this->pageId}/photos",
                [
                    'url' => $imageUrl,
                    'caption' => $caption,
                    'access_token' => $this->pageAccessToken,
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Wallpaper posted to Facebook as photo', [
                    'wallpaper_id' => $wallpaper->id,
                    'facebook_post_id' => $data['id'] ?? null,
                ]);

                return [
                    'success' => true,
                    'message' => 'Posted to Facebook',
                    'facebook_post_id' => $data['id'] ?? null,
                ];
            } else {
                $errorData = $response->json();
                Log::error('Facebook photo post failed', [
                    'wallpaper_id' => $wallpaper->id,
                    'status' => $response->status(),
                    'error' => $errorData,
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to post to Facebook: ' . ($errorData['error']['message'] ?? 'Unknown error'),
                ];
            }
        } catch (\Exception $e) {
            Log::error('Facebook photo post error', [
                'error' => $e->getMessage(),
                'wallpaper_id' => $wallpaper->id ?? null,
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Build caption for Facebook post
     */
    private function buildCaption($wallpaper)
    {
        $caption = "📸 {$wallpaper->name}\n\n";
        
        if ($wallpaper->description) {
            $caption .= "{$wallpaper->description}\n\n";
        }

        if ($wallpaper->category) {
            $caption .= "Category: {$wallpaper->category}\n";
        }

        $caption .= "\n🔗 Download on WallpaperCave: " . route('wallpaper.show', $wallpaper->id);

        return $caption;
    }
}

