<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallpaper extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'filename', 'mime', 'size', 'user_id', 'views', 'likes', 'downloads', 'github_url', 'category_folder', 'width', 'height'
    ];

    // Always return CDN-backed URL for GitHub assets
    public function getGithubUrlAttribute($value)
    {
        if (!$value) {
            return $value;
        }

        if (str_contains($value, 'cdn.jsdelivr.net')) {
            return $value;
        }

        if (preg_match('/https?:\/\/raw\.githubusercontent\.com\/([^\/]+)\/([^\/]+)\/([^\/]+)\/(.+)/', $value, $matches)) {
            $owner = $matches[1];
            $repo = $matches[2];
            $branch = $matches[3];
            $path = $matches[4];
            return "https://cdn.jsdelivr.net/gh/{$owner}/{$repo}@{$branch}/{$path}";
        }

        return $value;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_wallpaper');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'wallpaper_likes')->withTimestamps();
    }

    public function isLikedBy($user)
    {
        if (!$user) return false;
        return $this->likedBy()->where('user_id', $user->id)->exists();
    }
}
