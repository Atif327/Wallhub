<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wallpaper;

class WallpaperSeeder extends Seeder
{
    public function run(): void
    {
        $wallpapers = [
            ['filename' => 'box1.webp', 'name' => 'Digital Waves', 'category' => 'Abstract'],
            ['filename' => 'box2.webp', 'name' => 'Mountain Sunrise', 'category' => 'Nature'],
            ['filename' => 'box3.webp', 'name' => 'Anime Hero', 'category' => 'Anime'],
            ['filename' => 'box4.webp', 'name' => 'Gaming Arena', 'category' => 'Gaming'],
            ['filename' => 'box5.webp', 'name' => 'Cosmic Nebula', 'category' => 'Space'],
            ['filename' => 'box6.webp', 'name' => 'Pastel Dreams', 'category' => 'Aesthetic'],
            ['filename' => 'box7.webp', 'name' => 'Forest Path', 'category' => 'Nature'],
            ['filename' => 'box8.webp', 'name' => 'Color Burst', 'category' => 'Abstract'],
            ['filename' => 'box9.webp', 'name' => 'Anime Warriors', 'category' => 'Anime'],
            ['filename' => 'box10.webp', 'name' => 'Cyberpunk City', 'category' => 'Gaming'],
            ['filename' => 'box11.webp', 'name' => 'Galaxy Spiral', 'category' => 'Space'],
            ['filename' => 'box12.webp', 'name' => 'Neon Vibes', 'category' => 'Aesthetic'],
            ['filename' => 'box13.webp', 'name' => 'Ocean Waves', 'category' => 'Nature'],
            ['filename' => 'box14.webp', 'name' => 'Geometric Art', 'category' => 'Abstract'],
            ['filename' => 'box15.webp', 'name' => 'Anime Landscape', 'category' => 'Anime'],
            ['filename' => 'box16.webp', 'name' => 'Game Characters', 'category' => 'Gaming'],
            ['filename' => 'box17.jpg', 'name' => 'Starry Night', 'category' => 'Space'],
            ['filename' => 'box18.jpg', 'name' => 'Minimal Pink', 'category' => 'Aesthetic'],
            ['filename' => 'box19.jpg', 'name' => 'Autumn Forest', 'category' => 'Nature'],
            ['filename' => 'box20.jpg', 'name' => 'Fluid Motion', 'category' => 'Abstract'],
            ['filename' => 'box21.jpg', 'name' => 'Anime Characters', 'category' => 'Anime'],
            ['filename' => 'box22.jpg', 'name' => 'Battle Scene', 'category' => 'Gaming'],
            ['filename' => 'box23.jpg', 'name' => 'Planet Earth', 'category' => 'Space'],
            ['filename' => 'box24.jpg', 'name' => 'Retro Aesthetic', 'category' => 'Aesthetic'],
            ['filename' => 'box25.jpeg', 'name' => 'Wildlife Safari', 'category' => 'Nature'],
            ['filename' => 'box26.png', 'name' => 'Abstract Lines', 'category' => 'Abstract'],
            ['filename' => 'box27.jpg', 'name' => 'Manga Style', 'category' => 'Anime'],
            ['filename' => 'box28.jpg', 'name' => 'Racing Game', 'category' => 'Gaming'],
            ['filename' => 'box29.jpg', 'name' => 'Milky Way', 'category' => 'Space'],
            ['filename' => 'box30.jpg', 'name' => 'Vintage Colors', 'category' => 'Aesthetic'],
            ['filename' => 'box31.jpeg', 'name' => 'Desert Dunes', 'category' => 'Nature'],
        ];

        foreach ($wallpapers as $data) {
            if (!Wallpaper::where('filename', $data['filename'])->exists()) {
                $path = public_path('images/' . $data['filename']);
                if (file_exists($path)) {
                    Wallpaper::create([
                        'name' => $data['name'],
                        'category' => $data['category'],
                        'description' => 'Beautiful ' . strtolower($data['category']) . ' wallpaper',
                        'filename' => $data['filename'],
                        'mime' => mime_content_type($path),
                        'size' => filesize($path)
                    ]);
                }
            }
        }
    }
}
