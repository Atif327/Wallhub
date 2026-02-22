<?php

namespace App\Http\Controllers;

use App\Models\Wallpaper;
use App\Models\Category;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $wallpapers = Wallpaper::select('filename', 'updated_at')->latest()->get();
        $categories = Category::select('slug', 'updated_at')->get();

        return response()->view('sitemap.xml', [
            'wallpapers' => $wallpapers,
            'categories' => $categories,
            'lastModified' => Carbon::now()->toIso8601String(),
        ])->header('Content-Type', 'application/xml');
    }
}
