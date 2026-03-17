@extends('layouts.app')

@section('content')
<style>
    .profile-cover {
        height: 260px;
        background-image: url('/images/cover.jpeg');
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        position: relative;
        margin-top: 70px;
    }

    .profile-cover::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
    }

    .profile-header {
        position: relative;
        text-align: center;
        margin-top: -45px;
        margin-bottom: 30px;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f5c542, #f9d97c);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        border: 4px solid white;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        font-size: 40px;
        color: white;
    }

    .profile-info h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .profile-joined {
        display: inline-block;
        background: linear-gradient(135deg, #f5c542, #f9d97c);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin: 30px 0;
        padding: 20px;
        background: rgba(245, 197, 66, 0.1);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(245, 197, 66, 0.2);
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: white;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #f5c542;
    }

    .stat-label {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .wallpapers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .wallpaper-card {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        aspect-ratio: 16/9;
        background: #f0f0f0;
    }

    .wallpaper-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .wallpaper-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .wallpaper-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .wallpaper-card:hover .wallpaper-overlay {
        opacity: 1;
    }

    .overlay-btn {
        padding: 8px 16px;
        background: linear-gradient(135deg, #f5c542, #f9d97c);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: transform 0.2s;
    }

    .overlay-btn:hover {
        transform: scale(1.05);
    }

    .wallpaper-info {
        position: absolute;
        bottom: 10px;
        left: 10px;
        right: 10px;
        color: white;
        font-size: 12px;
        z-index: 2;
    }

    .wallpaper-stats {
        display: flex;
        gap: 15px;
        margin-top: 5px;
        font-size: 11px;
    }

    .wallpaper-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 5px;
    }

    .category-tag {
        background: rgba(245, 197, 66, 0.8);
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
</style>

<div class="profile-cover"></div>

<div class="container">
    <div class="profile-header">
        <div class="profile-avatar">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="profile-info">
            <h1>{{ $user->name }}</h1>
            <span class="profile-joined">Joined {{ $user->created_at->diffForHumans() }}</span>
        </div>
    </div>

    <div class="profile-stats">
        <div class="stat-item">
            <div class="stat-value">{{ count($wallpapers) }}</div>
            <div class="stat-label">Wallpapers</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($totalViews) }}</div>
            <div class="stat-label">Total Views</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($totalDownloads) }}</div>
            <div class="stat-label">Downloads</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($totalLikes) }}</div>
            <div class="stat-label">Likes</div>
        </div>
    </div>

    <div>
        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 20px; color: #1a1a1a;">
            {{ $user->name }}'s Wallpapers
        </h3>

        @if(count($wallpapers) > 0)
            <div class="wallpapers-grid">
                @foreach($wallpapers as $wallpaper)
                    @php($isVideo = isset($wallpaper['mime']) && str_starts_with($wallpaper['mime'], 'video/'))
                    <div class="wallpaper-card">
                        <img src="{{ $wallpaper['github_url'] }}" alt="{{ $wallpaper['name'] }}" class="wallpaper-image" loading="lazy">
                        <div class="wallpaper-overlay">
                            <a href="{{ route('wallpaper.show', $wallpaper['name']) }}" class="overlay-btn">View</a>
                            <a href="{{ route('wallpaper.download', [$wallpaper['name'], $isVideo ? 'original' : '1080p']) }}" class="overlay-btn">Download</a>
                        </div>
                        <div class="wallpaper-info">
                            <div style="font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $wallpaper['name'] }}
                            </div>
                            <div class="wallpaper-stats">
                                <span>👁️ {{ number_format($wallpaper['views']) }}</span>
                                <span>❤️ {{ number_format($wallpaper['likes']) }}</span>
                                <span>⬇️ {{ number_format($wallpaper['downloads']) }}</span>
                            </div>
                            @if($wallpaper['categories'] && count($wallpaper['categories']) > 0)
                                <div class="wallpaper-categories">
                                    @foreach($wallpaper['categories'] as $category)
                                        <span class="category-tag">{{ $category['name'] }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📸</div>
                <h4>No Wallpapers Yet</h4>
                <p>This user hasn't uploaded any wallpapers yet.</p>
            </div>
        @endif
    </div>
</div>

<script>
    // Initialize page same as homepage
    document.addEventListener('DOMContentLoaded', function() {
        // Event delegation for category filtering
        const categoryLinks = document.querySelectorAll('[data-category]');
        categoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const category = this.dataset.category;
                filterByCategory(category);
            });
        });
    });
</script>
@endsection
