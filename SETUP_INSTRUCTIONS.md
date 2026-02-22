# WallpaperCave - GitHub Integration Setup

## Current Status
Your application is configured to store all wallpapers on GitHub, organized by category folders.

## Required Setup

### 1. Generate GitHub Personal Access Token

**Follow these exact steps:**

1. Go to: https://github.com/settings/tokens
2. Click **"Generate new token"** → **"Generate new token (classic)"**
3. **Token name:** `WallpaperCave`
4. **Select scopes:**
   - ✅ `repo` (Full control of private repositories)
   - ✅ `workflow` (optional)
5. Click **"Generate token"** button
6. **IMPORTANT:** Copy the token immediately (you won't see it again!)

### 2. Update Environment File

**Edit `c:\laravel\Comsats\.env`:**

Find these lines (at the end of the file):
```env
# GitHub Integration
GITHUB_TOKEN=your_github_token_here
GITHUB_OWNER=Atif327
GITHUB_REPO=WallpaperCave.com
```

Replace `your_github_token_here` with your actual token:
```env
# GitHub Integration
GITHUB_TOKEN=ghp_1234567890abcdefghijklmnopqrstuvwxyz
GITHUB_OWNER=Atif327
GITHUB_REPO=WallpaperCave.com
```

**Example token format:**
```
ghp_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### 3. Verify GitHub Repository Exists

Check that your repository exists at:
- https://github.com/Atif327/WallpaperCave.com

If it doesn't exist:
1. Go to https://github.com/new
2. Enter name: `WallpaperCave.com`
3. Click "Create repository"
4. Can be public or private

### 4. Test the Setup

```bash
cd c:\laravel\Comsats
php artisan cache:clear
```

Then try uploading a wallpaper through the web interface at `http://127.0.0.1:8000/upload`

## How It Works

### Upload Process
1. User selects wallpaper + categories (e.g., "Anime, 4K")
2. Image validated (minimum 1280x720 resolution)
3. Progress bar shows validation
4. File uploaded to GitHub in: `wallpapers/Anime/filename.jpg`
5. GitHub URL saved to database
6. No local storage used

### Folder Structure in GitHub
```
WallpaperCave.com/
└── wallpapers/
    ├── Anime/
    │   ├── wallpaper1.jpg
    │   └── wallpaper2.png
    ├── Nature/
    │   └── landscape.jpg
    └── 4K/
        └── ultra_hd.jpg
```

## Troubleshooting

### Error: "Failed to upload to GitHub: Bad credentials"
**Solution:**
- Token is invalid or not set
- Action: Generate new token, update .env file

### Error: "GitHub token not configured"
**Solution:**
- GITHUB_TOKEN is set to default value
- Action: Replace with actual token in .env

### Error: "Repository not found"
**Solution:**
- Repository doesn't exist
- Action: Create repository at github.com/Atif327/WallpaperCave.com

### Error: "Not Found" when uploading
**Solution:**
- Repository exists but token doesn't have access
- Action: Regenerate token with `repo` scope

## Security Notes

⚠️ **IMPORTANT:**

1. **Never commit .env to GitHub**
   - Ensure `.gitignore` contains `.env`

2. **Never share your token**
   - Treat it like a password
   - Delete if accidentally exposed

3. **If token is compromised:**
   - Go to https://github.com/settings/tokens
   - Delete the exposed token
   - Generate a new one
   - Update .env file

4. **Token permissions:**
   - Only has access to repositories you specify
   - Can be revoked anytime

## File Cleanup

After setup, you can safely delete local wallpapers since everything is on GitHub:

```bash
# Optional: Remove local wallpapers directory
# rm -r c:\laravel\Comsats\public\images\
```

(Keep `public/images/` directory but it will be empty - it's created fresh each upload)

## Next Steps

1. ✅ Generate GitHub token
2. ✅ Update .env file
3. ✅ Verify repository exists
4. ✅ Clear cache: `php artisan cache:clear`
5. ✅ Try uploading a wallpaper
6. ✅ Check GitHub repository for uploaded file

## Support

If you encounter issues:
1. Check .env file has correct values
2. Verify token has `repo` scope
3. Ensure repository exists
4. Check GITHUB_OWNER spelling (Atif327)
5. Clear cache: `php artisan cache:clear`

Everything should work once the token is properly configured!
