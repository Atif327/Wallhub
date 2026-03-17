<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GitHubWallpaperService;

class VerifyGithubSetup extends Command
{
    protected $signature = 'github:verify';
    protected $description = 'Verify GitHub integration configuration';

    public function handle()
    {
        $this->line('');
        $this->line('═══════════════════════════════════════════');
        $this->line('  GitHub Integration - Configuration Check');
        $this->line('═══════════════════════════════════════════');
        $this->line('');

        // Check environment variables
        $token = config('services.github.token');
        $owner = config('services.github.owner');
        $repo = config('services.github.repo');

        $this->line('📋 Configuration Status:');
        $this->line('');

        // Check token
        if (!$token) {
            $this->error('❌ GITHUB_TOKEN is not set in .env file');
        } elseif ($token === 'your_github_token_here') {
            $this->error('❌ GITHUB_TOKEN is using default placeholder value');
            $this->line('   Action: Replace with your actual GitHub token');
        } else {
            $this->info('✅ GITHUB_TOKEN is configured');
            // Show partial token for verification
            $display = substr($token, 0, 6) . '...' . substr($token, -6);
            $this->line('   Token (partial): ' . $display);
        }

        $this->line('');

        // Check owner
        if (!$owner) {
            $this->error('❌ GITHUB_OWNER is not set');
        } elseif ($owner !== 'Atif327') {
            $this->warn('⚠️  GITHUB_OWNER is set to: ' . $owner);
            $this->line('   Note: Usually should be "Atif327"');
        } else {
            $this->info('✅ GITHUB_OWNER is configured: ' . $owner);
        }

        $this->line('');

        // Check repo
        if (!$repo) {
            $this->error('❌ GITHUB_REPO is not set');
        } elseif ($repo !== 'WallpaperCave.com') {
            $this->warn('⚠️  GITHUB_REPO is set to: ' . $repo);
            $this->line('   Note: Usually should be "WallpaperCave.com"');
        } else {
            $this->info('✅ GITHUB_REPO is configured: ' . $repo);
        }

        $this->line('');
        $this->line('═══════════════════════════════════════════');
        $this->line('');

        // Validate using service
        $service = new GitHubWallpaperService();
        $validation = $service->validateConfig();

        if ($validation['valid']) {
            $this->info('✅ All configuration is valid!');
            $this->line('');
            $this->line('You can now try uploading a wallpaper.');
            $this->line('');
            $this->line('Repository URL:');
            $this->line('https://github.com/' . $owner . '/' . $repo);
            $this->line('');
        } else {
            $this->error('❌ Configuration Error:');
            $this->error($validation['error']);
            $this->line('');
            $this->line('Please fix the issue and run this command again:');
            $this->line('php artisan github:verify');
        }

        $this->line('═══════════════════════════════════════════');
        $this->line('');
    }
}
