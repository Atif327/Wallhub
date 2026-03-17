<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class GitHubWallpaperService
{
    private $token;
    private $owner;
    private $repo;
    private $branch = 'main';
    private $basePath = 'wallpapers';

    public function __construct()
    {
        $this->token = config('services.github.token');
        $this->owner = config('services.github.owner');
        $this->repo = config('services.github.repo');
    }

    /**
     * Validate GitHub configuration
     */
    public function validateConfig()
    {
        if (!$this->token || $this->token === 'your_github_token_here') {
            return [
                'valid' => false,
                'error' => 'GitHub token not configured. Set GITHUB_TOKEN in .env file.'
            ];
        }
        
        if (!$this->owner) {
            return [
                'valid' => false,
                'error' => 'GitHub owner not configured. Set GITHUB_OWNER in .env file.'
            ];
        }
        
        if (!$this->repo) {
            return [
                'valid' => false,
                'error' => 'GitHub repository not configured. Set GITHUB_REPO in .env file.'
            ];
        }
        
        return ['valid' => true];
    }

    /**
     * Upload a wallpaper image to GitHub in category folder
     */
    public function uploadWallpaper($filePath, $filename, $categories = [])
    {
        // Validate configuration first
        $validation = $this->validateConfig();
        if (!$validation['valid']) {
            return [
                'success' => false,
                'error' => $validation['error'],
            ];
        }

        try {
            // Read the file content
            $fileContent = file_get_contents($filePath);
            $base64Content = base64_encode($fileContent);

            // Determine folder path based on category array
            // $categories can be: ['ParentName'] or ['ParentName', 'ChildName']
            $folder = $this->basePath;
            $categoryNames = []; // For commit message
            
            if (!empty($categories) && is_array($categories)) {
                // Join all category parts with / to build the full path
                $categoryPath = implode('/', $categories);
                $folder = $this->basePath . '/' . $categoryPath;
                $categoryNames = $categories;
            }

            // GitHub API path
            $githubPath = "{$folder}/{$filename}";
            $apiUrl = "https://api.github.com/repos/{$this->owner}/{$this->repo}/contents/{$githubPath}";

            // Check if file already exists
            $existingFile = $this->getFileInfo($githubPath);
            $sha = $existingFile ? $existingFile['sha'] : null;

            // Prepare payload
            $payload = [
                'message' => "Add wallpaper: {$filename}" . (!empty($categoryNames) ? " (Category: " . implode(" > ", $categoryNames) . ")" : ""),
                'content' => $base64Content,
                'branch' => $this->branch,
            ];

            if ($sha) {
                $payload['sha'] = $sha;
            }

            // Make API request with extended timeout and retry logic
            $response = Http::timeout(60) // Increased from default
                ->connectTimeout(30) // Connection timeout
                ->retry(3, 2000) // Retry 3 times with 2 second delay
                ->withHeaders([
                    'Authorization' => "Bearer {$this->token}",
                    'Accept' => 'application/vnd.github.v3+json',
                ])->put($apiUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $cdnUrl = "https://cdn.jsdelivr.net/gh/{$this->owner}/{$this->repo}@{$this->branch}/{$githubPath}";

                return [
                    'success' => true,
                    'url' => $data['content']['html_url'],
                    'path' => $data['content']['path'],
                    'github_url' => $cdnUrl,
                    'folder' => $folder,
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Unknown error occurred',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get file information from GitHub
     */
    private function getFileInfo($filePath)
    {
        try {
            $apiUrl = "https://api.github.com/repos/{$this->owner}/{$this->repo}/contents/{$filePath}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->token}",
                'Accept' => 'application/vnd.github.v3+json',
            ])->get($apiUrl, ['ref' => $this->branch]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Delete a wallpaper from GitHub
     */
    public function deleteWallpaper($filename, $categoryFolder = null)
    {
        try {
            $folder = $this->basePath;
            if ($categoryFolder) {
                $folder = $this->basePath . '/' . $categoryFolder;
            }

            $githubPath = "{$folder}/{$filename}";
            $fileInfo = $this->getFileInfo($githubPath);

            if (!$fileInfo) {
                return ['success' => false, 'error' => 'File not found on GitHub'];
            }

            $apiUrl = "https://api.github.com/repos/{$this->owner}/{$this->repo}/contents/{$githubPath}";

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->token}",
                'Accept' => 'application/vnd.github.v3+json',
            ])->delete($apiUrl, [
                'message' => "Delete wallpaper: {$filename}",
                'sha' => $fileInfo['sha'],
                'branch' => $this->branch,
            ]);

            if ($response->successful()) {
                return ['success' => true];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Unknown error occurred',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get wallpaper URL from GitHub
     */
    public function getWallpaperUrl($filename, $categoryFolder = null)
    {
        $folder = $this->basePath;
        if ($categoryFolder) {
            $folder = $this->basePath . '/' . $categoryFolder;
        }
        return "https://cdn.jsdelivr.net/gh/{$this->owner}/{$this->repo}@{$this->branch}/{$folder}/{$filename}";
    }
}
