<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Home page -->
    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ $lastModified }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Trending page -->
    <url>
        <loc>{{ route('wallpapers.trending') }}</loc>
        <lastmod>{{ $lastModified }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Categories -->
    @foreach ($categories as $category)
    <url>
        <loc>{{ route('category.show', $category->slug) }}</loc>
        <lastmod>{{ $category->updated_at->toIso8601String() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    <!-- Wallpapers -->
    @foreach ($wallpapers as $wallpaper)
    <url>
        <loc>{{ route('wallpaper.show', $wallpaper->filename) }}</loc>
        <lastmod>{{ $wallpaper->updated_at->toIso8601String() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
</urlset>
