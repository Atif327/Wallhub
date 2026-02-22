@extends('layouts.app')

@section('content')
<style>
    .trending-header {
        margin-top: 100px;
        margin-bottom: 40px;
        text-align: center;
    }

    .trending-header h1 {
        font-size: 36px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .trending-header p {
        font-size: 16px;
        color: #666;
        margin-bottom: 0;
    }

    .trending-filters {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 16px;
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #666;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: linear-gradient(135deg, #f5c542, #f9d97c);
        border-color: #f5c542;
        color: white;
    }

    .wallpapers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
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

    .wallpaper-title {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 5px;
    }

    .wallpaper-stats {
        display: flex;
        gap: 15px;
        margin-bottom: 5px;
        font-size: 11px;
    }

    .wallpaper-user {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        opacity: 0.9;
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

    .pagination-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 40px;
        padding: 20px 0;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        text-decoration: none;
        color: #666;
        font-size: 13px;
        font-weight: 600;
    }

    .pagination a:hover {
        background: linear-gradient(135deg, #f5c542, #f9d97c);
        color: white;
        border-color: #f5c542;
    }

    .pagination .active span {
        background: linear-gradient(135deg, #f5c542, #f9d97c);
        color: white;
        border-color: #f5c542;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #666;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
</style>

<div class="container">
    <div class="trending-header">
        <h1>🔥 Trending Wallpapers</h1>
        <p>Most viewed and liked wallpapers this month</p>
    </div>

    @if(count($wallpapers) > 0)
        @php($videoPoster = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='225' viewBox='0 0 400 225'%3E%3Crect width='400' height='225' fill='%23111111'/%3E%3Ctext x='50%25' y='50%25' fill='%23aaaaaa' font-size='18' text-anchor='middle' dominant-baseline='middle'%3ELoading video...%3C/text%3E%3C/svg%3E")
        <div class="wallpapers-grid">
            @foreach($wallpapers as $wallpaper)
                @php $isVideo = isset($wallpaper['mime']) && str_starts_with($wallpaper['mime'], 'video/'); @endphp
                <div class="wallpaper-card">
                    @if($isVideo)
                        <video src="{{ $wallpaper['github_url'] }}" class="wallpaper-image" muted playsinline preload="metadata" autoplay loop style="background:#111;"></video>
                    @else
                        <img src="{{ $wallpaper['github_url'] }}" alt="{{ $wallpaper['name'] }}" class="wallpaper-image" loading="lazy">
                    @endif
                    <div class="wallpaper-overlay">
                        <a href="{{ route('wallpaper.show', $wallpaper['name']) }}" class="overlay-btn">View</a>
                        <a href="{{ route('wallpaper.download', [$wallpaper['name'], $isVideo ? 'original' : '1080p']) }}" class="overlay-btn">Download</a>
                    </div>
                    <div class="wallpaper-info">
                        <div class="wallpaper-title">{{ $wallpaper['name'] }}@if($isVideo) · Video@endif</div>
                        <div class="wallpaper-stats">
                            <span>👁️ {{ number_format($wallpaper['views']) }}</span>
                            <span>❤️ {{ number_format($wallpaper['likes']) }}</span>
                            <span>⬇️ {{ number_format($wallpaper['downloads']) }}</span>
                        </div>
                        @if($wallpaper['user'])
                            <div class="wallpaper-user">
                                by <a href="{{ route('profile.show', $wallpaper['user']['id']) }}" style="color: inherit; text-decoration: underline;">
                                    {{ $wallpaper['user']['name'] }}
                                </a>
                            </div>
                        @endif
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

        @if($pagination && $pagination->lastPage() > 1)
            <div class="pagination-container">
                {{ $pagination->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📊</div>
            <h4>No Trending Wallpapers Yet</h4>
            <p>Check back soon as more wallpapers gain popularity!</p>
        </div>
    @endif
</div>

<script>
    // Like functionality
    async function toggleLike(wallpaperId) {
        try {
            const response = await fetch(`/wallpaper/${wallpaperId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                console.error('Like request failed');
                return;
            }

            const data = await response.json();
            const likeBtn = document.querySelector(`[data-like-btn="${wallpaperId}"]`);
            
            if (likeBtn) {
                if (data.liked) {
                    likeBtn.classList.add('liked');
                } else {
                    likeBtn.classList.remove('liked');
                }
                // Update like count
                const likeCount = likeBtn.querySelector('.like-count');
                if (likeCount) {
                    likeCount.textContent = data.likes;
                }
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Add scroll animation
        const cards = document.querySelectorAll('.wallpaper-card');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeIn 0.5s ease-out';
                }
            });
        });
        cards.forEach(card => observer.observe(card));
    });
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
