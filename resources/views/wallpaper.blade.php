<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <x-meta-tags 
    :title="$name . ' - Download Wallpaper'" 
    :description="'Download ' . $name . ' in high quality. Free HD and 4K wallpaper for desktop and mobile.'"
    :image="$wallpaper->github_url ?? asset('images/default-og.jpg')"
  />
  
  @if($wallpaper && $wallpaper->github_url)
    <link rel="preload" href="{{ $wallpaper->github_url }}" as="{{ $isVideo ? 'video' : 'image' }}" crossorigin>
  @endif
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  @vite(['resources/css/style.css'])
  <style>
    body {
      background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0f0f0f 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Roboto, sans-serif;
    }

    .glass-card {
      background: rgba(30, 30, 30, 0.6);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      padding: 20px;
      transition: all 0.3s ease;
    }

    .glass-card:hover {
      border-color: rgba(255, 193, 7, 0.3);
      box-shadow: 0 12px 40px rgba(255, 193, 7, 0.15);
    }

    .wallpaper-container {
      position: relative;
      overflow: hidden;
      border-radius: 18px;
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
      margin-bottom: 30px;
      background: #111;
      min-height: 320px; /* keep space reserved while video metadata loads */
      aspect-ratio: 16 / 9;
    }

    .wallpaper-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 18px;
      transition: transform 0.4s ease;
      cursor: zoom-in;
      display: block;
    }

    .wallpaper-img:hover {
      transform: scale(1.02);
    }

    .download-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 15px;
      margin-bottom: 30px;
    }

    .download-btn {
      background: rgba(40, 40, 40, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 14px;
      padding: 18px 20px;
      text-decoration: none;
      color: #fff;
      transition: all 0.25s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      position: relative;
      overflow: hidden;
    }

    .download-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 193, 7, 0.1), transparent);
      transition: left 0.5s ease;
    }

    .download-btn:hover::before {
      left: 100%;
    }

    .download-btn:hover {
      background: rgba(60, 60, 60, 0.9);
      transform: translateY(-3px);
      border-color: rgba(255, 193, 7, 0.5);
      box-shadow: 0 8px 25px rgba(255, 193, 7, 0.2);
      color: #ffc107;
    }

    .download-btn i {
      font-size: 24px;
      color: #ffc107;
    }

    .download-label {
      font-weight: 600;
      font-size: 16px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .download-resolution {
      font-size: 12px;
      color: #aaa;
    }

    /* Social Share Buttons */
    .social-share-buttons {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 12px;
    }

    .share-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 12px 20px;
      border-radius: 10px;
      border: 2px solid rgba(255, 255, 255, 0.1);
      background: rgba(30, 30, 30, 0.8);
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .share-btn i {
      font-size: 18px;
    }

    .share-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .share-twitter:hover {
      background: #1DA1F2;
      border-color: #1DA1F2;
      color: #fff;
    }

    .share-facebook:hover {
      background: #1877F2;
      border-color: #1877F2;
      color: #fff;
    }

    .share-pinterest:hover {
      background: #E60023;
      border-color: #E60023;
      color: #fff;
    }

    .share-reddit:hover {
      background: #FF4500;
      border-color: #FF4500;
      color: #fff;
    }

    .share-link:hover {
      background: #ffc107;
      border-color: #ffc107;
      color: #000;
    }

    .info-stats {
      background: rgba(25, 25, 25, 0.8);
      backdrop-filter: blur(15px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 25px;
    }

    .stat-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .stat-row:last-child {
      border-bottom: none;
    }

    .stat-label {
      color: #888;
      font-size: 14px;
    }

    .stat-value {
      color: #ffc107;
      font-weight: 600;
    }

    .back-btn {
      background: rgba(30, 30, 30, 0.8);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 10px 20px;
      color: #fff;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .back-btn:hover {
      background: rgba(50, 50, 50, 0.9);
      border-color: rgba(255, 193, 7, 0.4);
      color: #ffc107;
      transform: translateX(-5px);
    }

    .floating-download-all {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: linear-gradient(135deg, #ffc107, #ff9800);
      color: #000;
      padding: 15px 25px;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 10px 30px rgba(255, 193, 7, 0.4);
      transition: all 0.3s ease;
      z-index: 1000;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .floating-download-all:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(255, 193, 7, 0.6);
      color: #000;
    }

    .fullscreen-icon {
      position: absolute;
      top: 15px;
      right: 15px;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 10px;
      padding: 10px 15px;
      color: #fff;
      cursor: pointer;
      transition: all 0.3s ease;
      opacity: 0;
    }

    .wallpaper-container:hover .fullscreen-icon {
      opacity: 1;
    }

    .fullscreen-icon:hover {
      background: rgba(255, 193, 7, 0.9);
      color: #000;
    }

    @media (max-width: 768px) {
      .download-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
  </style>
</head>
<body class="text-light">
  <div class="container py-4">
    <div class="mb-4">
      <a href="{{ url('/') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        Back to Gallery
      </a>
    </div>

    <!-- Wallpaper Preview -->
    <div class="glass-card">
      <div class="wallpaper-container" style="position: relative;">
        @if(isset($isVideo) && $isVideo)
          <!-- Video: Stream with fallback -->
          <video class="wallpaper-img" id="wallpaperImage" loop autoplay muted playsinline preload="metadata" controlsList="nodownload noplaybackrate noremoteplayback" onerror="handleVideoError()" style="background: #1a1a1a;">
            <source src="{{ $wallpaper->github_url ?? asset('images/' . $name) }}" type="{{ $wallpaper->mime ?? 'video/mp4' }}">
            <source src="/download/{{ $name }}/original" type="{{ $wallpaper->mime ?? 'video/mp4' }}">
            Your browser does not support the video tag.
          </video>
        @else
          <img src="{{ $wallpaper->github_url ?? asset('images/' . $name) }}" alt="Wallpaper" class="wallpaper-img" id="wallpaperImage" fetchpriority="high" />
        @endif
        <div class="fullscreen-icon" onclick="openFullscreen()">
          <i class="fas fa-expand"></i> Fullscreen
        </div>
      </div>
    </div>

    <!-- Download Options Grid -->
    <div class="glass-card mb-3 mt-4">
      <h4 class="mb-4 fw-bold" style="color: #ffc107;">
        <i class="fas fa-download me-2"></i>Download Options
      </h4>
      @if($isVideo)
        <p class="text-muted mb-3" style="font-size: 14px;">
          <i class="fas fa-info-circle me-1"></i>Videos are available in original quality only
        </p>
      @endif
      <div class="download-grid">
        @foreach($sizes as $sizeKey => $dimensions)
          @php
            $labels = [
              'original' => ['icon' => $isVideo ? 'fas fa-video' : 'fas fa-image', 'label' => 'Original', 'desc' => ($originalWidth && $originalHeight) ? "{$originalWidth} × {$originalHeight}" : 'Full Quality'],
              '4k' => ['icon' => 'fas fa-desktop', 'label' => '4K', 'desc' => '3840 × 2160'],
              '2k' => ['icon' => 'fas fa-tv', 'label' => '2K', 'desc' => '2560 × 1440'],
              '1080p' => ['icon' => 'fas fa-laptop', 'label' => '1080p', 'desc' => '1920 × 1080'],
              '720p' => ['icon' => 'fas fa-mobile-alt', 'label' => '720p', 'desc' => '1280 × 720'],
            ];
            $info = $labels[$sizeKey] ?? ['icon' => 'fas fa-download', 'label' => strtoupper($sizeKey), 'desc' => "{$dimensions[0]} × {$dimensions[1]}"];
          @endphp
          <a href="{{ route('wallpaper.download', ['name' => $name, 'size' => $sizeKey]) }}" class="download-btn" title="{{ $info['label'] }} · {{ $info['desc'] }}">
            <i class="{{ $info['icon'] }}"></i>
            <span class="download-label">{{ $info['label'] }}</span>
            <span class="download-resolution">{{ $info['desc'] }}</span>
          </a>
        @endforeach
      </div>
    </div>

    <!-- Social Sharing -->
    <div class="info-stats">
      <h5 class="mb-3 fw-bold" style="color: #ffc107;">
        <i class="fas fa-share-alt me-2"></i>Share This Wallpaper
      </h5>
      <div class="social-share-buttons">
        <a href="https://twitter.com/intent/tweet?text=Check%20out%20this%20wallpaper!&url={{ urlencode(request()->url()) }}" target="_blank" class="share-btn share-twitter" title="Share on Twitter">
          <i class="fab fa-twitter"></i>
          <span>Twitter</span>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-btn share-facebook" title="Share on Facebook">
          <i class="fab fa-facebook-f"></i>
          <span>Facebook</span>
        </a>
        <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}&media={{ urlencode($wallpaper->github_url ?? '') }}&description={{ urlencode($wallpaper->name ?? '') }}" target="_blank" class="share-btn share-pinterest" title="Pin it">
          <i class="fab fa-pinterest"></i>
          <span>Pinterest</span>
        </a>
        <a href="https://reddit.com/submit?url={{ urlencode(request()->url()) }}&title={{ urlencode($wallpaper->name ?? '') }}" target="_blank" class="share-btn share-reddit" title="Share on Reddit">
          <i class="fab fa-reddit"></i>
          <span>Reddit</span>
        </a>
        <button onclick="copyToClipboard('{{ request()->url() }}')" class="share-btn share-link" title="Copy Link">
          <i class="fas fa-link"></i>
          <span>Copy Link</span>
        </button>
      </div>
    </div>

    <!-- Info Stats -->
    <div class="info-stats">
      <h5 class="mb-3 fw-bold" style="color: #ffc107;">
        <i class="fas fa-info-circle me-2"></i>Wallpaper Information
      </h5>
      <div class="stat-row">
        <span class="stat-label"><i class="fas fa-file-image me-2"></i>Filename</span>
        <span class="stat-value">{{ $name }}</span>
      </div>
      <div class="stat-row">
        <span class="stat-label"><i class="fas fa-expand-arrows-alt me-2"></i>Available Sizes</span>
        <span class="stat-value">{{ count($sizes) }} {{ count($sizes) === 1 ? 'Resolution' : 'Resolutions' }}</span>
      </div>
      <div class="stat-row">
        <span class="stat-label"><i class="fas fa-file-code me-2"></i>Format</span>
        <span class="stat-value">{{ strtoupper(pathinfo($name, PATHINFO_EXTENSION)) }}</span>
      </div>
      <div class="stat-row">
        <span class="stat-label"><i class="fas fa-tag me-2"></i>Quality</span>
        <span class="stat-value">Premium HD</span>
      </div>
    </div>
  </div>

  <!-- Floating Download All Button -->
  <a href="{{ asset('images/' . $name) }}" download class="floating-download-all">
    <i class="fas fa-download"></i>
    Download Original
  </a>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function openFullscreen() {
      const img = document.getElementById('wallpaperImage');
      if (img.requestFullscreen) {
        img.requestFullscreen();
      } else if (img.webkitRequestFullscreen) {
        img.webkitRequestFullscreen();
      } else if (img.msRequestFullscreen) {
        img.msRequestFullscreen();
      }
    }

    // Copy to clipboard function
    function copyToClipboard(text) {
      navigator.clipboard.writeText(text).then(() => {
        // Show success message
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i><span>Copied!</span>';
        btn.style.background = '#4CAF50';
        btn.style.borderColor = '#4CAF50';
        
        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.style.background = '';
          btn.style.borderColor = '';
        }, 2000);
      }).catch(err => {
        console.error('Failed to copy:', err);
        alert('Failed to copy link');
      });
    }
  </script>
</body>
</html>