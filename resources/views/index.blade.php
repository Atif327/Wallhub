<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <x-meta-tags 
    :title="isset($category) ? $category->name . ' Wallpapers' : 'Latest Wallpapers'" 
    :description="isset($category) ? 'Browse ' . $category->name . ' wallpapers. Download free HD and 4K wallpapers.' : 'Download free HD and 4K wallpapers for desktop and mobile devices. Latest wallpapers uploaded daily.'"
    :image="!empty($wallpapers) && is_array($wallpapers) && count($wallpapers) > 0 ? ($wallpapers[0]->github_url ?? asset('images/default-og.jpg')) : asset('images/default-og.jpg')"
  />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  @vite(['resources/css/style.css', 'resources/css/homepage.css', 'resources/css/footer.css', 'resources/css/categories.css', 'resources/css/skeleton.css', 'resources/js/script.js', 'resources/js/performance.js'])
  <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" sizes="32x32">
  
  <script>
    // Pass wallpapers data to JavaScript
    window.wallpapersData = @json($wallpapers ?? []);
    window.paginationData = @json($pagination ?? null);
    console.log('Raw wallpapers data from server:', window.wallpapersData);
    if (window.wallpapersData && window.wallpapersData.length > 0) {
      console.log('First wallpaper:', window.wallpapersData[0]);
    }
  </script>
</head>
  
<body>  
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="{{ asset('images/logo.png') }}" alt="Logo" height="35" />
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/"><i class="fa-solid fa-clock me-2"></i>Latest</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('wallpapers.trending') }}"><i class="fa-solid fa-fire me-2"></i>Trending</a></li>
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#categoriesModal"><i class="fa-solid fa-layer-group me-2"></i>Categories</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('wallpapers.create') }}"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload</a></li>
      </ul>

      @guest
        <a href="{{ route('login') }}" class="btn btn-auth btn-outline-light me-2">
          <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
        </a>
        <a href="{{ route('register') }}" class="btn btn-auth btn-warning">
          <i class="fa-solid fa-user-plus me-2"></i>Create Account
        </a>
      @else
        <a href="{{ route('user.account') }}" class="btn btn-auth btn-outline-light me-2">
          <i class="fa-solid fa-user me-2"></i>My Account
        </a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
          @csrf
          <button type="submit" class="btn btn-auth btn-outline-light">
            <i class="fa-solid fa-sign-out-alt me-2"></i>Logout
          </button>
        </form>
      @endguest
    </div>
  </div>
</nav>


<section class="hero-section">
  <!-- Carousel Container -->
  <div class="hero-carousel">
    <div class="carousel-track">
      <div class="carousel-slide active" style="background-image: url('{{ asset('images/Hero.jpg') }}');"></div>
      <div class="carousel-slide" style="background-image: url('{{ asset('images/Dark shadow.jpg') }}');"></div>
      <div class="carousel-slide" style="background-image: url('{{ asset('images/Goku ultran instant.jpg') }}');"></div>
      <div class="carousel-slide" style="background-image: url('{{ asset('images/Hunter x Hunter-Aesthetic.jpg') }}');"></div>
      <div class="carousel-slide" style="background-image: url('{{ asset('images/Jin woo Desktop Wallpaper.jpg') }}');"></div>
      <div class="carousel-slide" style="background-image: url('{{ asset('images/jin woo vs Beru.png') }}');"></div>
      <div class="carousel-slide" style="background-image: url('{{ asset('images/JJK Nanami wallpapers.jpg') }}');"></div>
      <div class="carousel-slide" style="background-image: url('{{ asset('images/Naruto Baryon Mood.jpg') }}');"></div>
    </div>
  </div>
  
  <script>
    // Hero carousel functionality
    (function() {
      const slides = document.querySelectorAll('.carousel-slide');
      let currentSlide = 0;
      
      function nextSlide() {
        // Remove active and exiting classes from all slides
        slides.forEach(slide => {
          slide.classList.remove('active', 'exiting');
        });
        
        // Mark current slide as exiting
        slides[currentSlide].classList.add('exiting');
        
        // Move to next slide (circular)
        currentSlide = (currentSlide + 1) % slides.length;
        
        // Mark next slide as active
        slides[currentSlide].classList.add('active');
      }
      
      // Change slide every 3 seconds
      setInterval(nextSlide, 3000);
    })();
  </script>
  
  <div class="hero-content">
    <h1 class="fw-bold mb-4">Discover Amazing Wallpapers</h1>
    <div class="input-group hero-search">
      <input
        type="text"
        class="form-control"
        id="mainSearch"
        placeholder="Search for stunning wallpapers..."
      />
      <button class="btn" id="mainSearchBtn">
        <i class="fa-solid fa-search me-2"></i>Search
      </button>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-bar" style="margin-top: 20px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
      <select id="categoryFilter" class="form-select" style="width: auto; min-width: 150px; background-color: rgba(0, 0, 0, 0.6); color: #fff; border: 1px solid rgba(255, 195, 0, 0.3);">
        <option value="">All Categories</option>
        @foreach ($popularCategories ?? [] as $cat)
          <option value="{{ $cat->id }}" {{ isset($category) && $category->id === $cat->id ? 'selected' : '' }}>
            {{ $cat->name }}
          </option>
        @endforeach
      </select>
      
      <select id="sortFilter" class="form-select" style="width: auto; min-width: 150px; background-color: rgba(0, 0, 0, 0.6); color: #fff; border: 1px solid rgba(255, 195, 0, 0.3);">
        <option value="latest">Latest</option>
        <option value="popular">Most Popular</option>
        <option value="views">Most Viewed</option>
        <option value="likes">Most Liked</option>
      </select>
      
      
    </div>
  </div>
</section>



  <section class="trending-section text-light text-center">
    <div class="container">
      <h2 class="mb-2">Browse by Category</h2>
      <p class="subtitle mb-4">Explore wallpapers by category</p>
      <div class="d-flex flex-wrap justify-content-center gap-3">
        @foreach ($popularCategories ?? [] as $cat)
          <a href="{{ route('category.show', $cat->slug) }}" 
             class="btn btn-sm {{ isset($category) && $category->id === $cat->id ? 'active' : '' }}" 
             style="text-decoration: none;">
            {{ $cat->icon ?? '📁' }} {{ $cat->name }}
          </a>
        @endforeach
      </div>
    </div>
  </section>


<section class="featured-section text-light text-center">
  <div class="container">
    @if (isset($category))
      <h2>{{ $category->name }} Wallpapers</h2>
      <p class="subtitle">Explore beautiful {{ strtolower($category->name) }} wallpapers</p>
    @else
      <h2>Featured Desktop Wallpapers</h2>
      <p class="subtitle">Handpicked 4K wallpapers updated daily</p>
    @endif

    <!-- Search Results Header -->
    <div id="searchResultsHeader" style="display: none; margin-bottom: 20px;">
      <h3 style="color: #FFC300; font-size: 20px; margin-bottom: 10px;">
        <i class="fa-solid fa-magnifying-glass me-2"></i>Search Results for: <span id="searchQuery" style="color: #fff;"></span>
      </h3>
      <button id="clearSearchBtn" class="btn btn-outline-warning btn-sm">
        <i class="fa-solid fa-times me-2"></i>Clear Search
      </button>
    </div>

    <!-- Skeleton Loaders -->
    <div id="skeletonLoaders" class="skeleton-grid">
      @for ($i = 0; $i < 8; $i++)
        <x-skeleton-card />
      @endfor
    </div>

    <div id="wallpaperContainer" class="row g-4"></div>

    <div class="pagination-container">
      <button class="btn" id="prevBtn">
        <i class="fas fa-chevron-left me-2"></i>Previous
      </button>
      <span id="pageIndicator"></span>
      <button class="btn" id="nextBtn">
        Next<i class="fas fa-chevron-right ms-2"></i>
      </button>
    </div>
  </div>
</section>


<footer class="footer">
  <div class="container">
    <div class="row gy-5 align-items-start">
      <!-- About Section -->
      <div class="col-lg-3 col-md-6">
        <div class="footer-brand">
          <h4 class="fw-bold mb-3">WallpaperCave</h4>
          <p class="footer-description">
            Explore, download, and share high-quality wallpapers. Your ultimate destination for HD, 4K, and aesthetic visuals — updated daily.
          </p>
          <div class="contact-info mt-4">
            <p class="footer-contact-item">
              <i class="fas fa-envelope me-2" style="color: #F1C40F;"></i>
              <span>support@wallpapercave.com</span>
            </p>
          </div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="col-lg-2 col-md-6">
        <h6 class="footer-heading mb-4">Quick Links</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#" class="footer-link">Latest</a></li>
          <li><a href="#" class="footer-link">Contact Us</a></li>
          <li><a href="{{ route('privacy.policy') }}" class="footer-link">Privacy Policy</a></li>
        </ul>
      </div>

      <!-- Categories -->
      <div class="col-lg-2 col-md-6">
        <h6 class="footer-heading mb-4">Categories</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#" class="footer-link">Nature</a></li>
          <li><a href="#" class="footer-link">Anime</a></li>
          <li><a href="#" class="footer-link">Gaming</a></li>
        </ul>
      </div>

      <!-- Follow Us -->
      <div class="col-lg-3 col-md-6">
        <h6 class="footer-heading mb-4">Follow Us</h6>
        <div class="social-icons">
          <a href="#" class="social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-icon" title="Twitter"><i class="fab fa-x-twitter"></i></a>
          <a href="#" class="social-icon" title="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-icon" title="Pinterest"><i class="fab fa-pinterest"></i></a>
          <a href="#" class="social-icon" title="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
    </div>

    <!-- Divider -->
    <div class="footer-divider"></div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
      <p class="footer-copyright">
        © 2025 <span class="brand-name">WallpaperCave</span>. All rights reserved.
      </p>
      <p class="footer-credit">
        Designed with <span class="heart">❤️</span> by <span class="creator">Atif Ayyoub</span>
      </p>
    </div>
  </div>
</footer>


  <!-- Authentication Modals -->
  <div id="auth-modals">
    <!-- include modals from script.js -->
  </div>

  <!-- Categories Modal -->
  <div class="modal fade" id="categoriesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content" style="background: linear-gradient(135deg, #0d0d0d 0%, #1a1a1a 100%); border: 1px solid rgba(255, 195, 0, 0.2); border-radius: 20px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);">
        <div class="modal-header border-0 pb-2" style="padding: 30px 30px 10px 30px;">
          <div>
            <h5 class="modal-title" style="font-size: 26px; font-weight: 800; letter-spacing: -0.8px; color: #EDEDED; margin-bottom: 8px;">
              <i class="fas fa-layer-group" style="color: #FFC300; margin-right: 12px; font-size: 24px;"></i>Explore Categories
            </h5>
            <p style="font-size: 13px; color: #A3A3A3; margin: 0; font-weight: 500; letter-spacing: 0.3px;">Discover wallpapers organized by theme</p>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="opacity: 0.6; filter: brightness(1.5);"></button>
        </div>
        <div class="modal-body" style="padding: 20px 30px 30px 30px;">
          <!-- Categories Grid -->
          <div id="categoriesList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px;">
            @forelse ($allCategories ?? [] as $cat)
              <div class="category-card" data-category-id="{{ $cat->id }}" style="
                position: relative;
                padding: 20px;
                background: linear-gradient(135deg, rgba(30, 34, 43, 0.4) 0%, rgba(20, 24, 33, 0.6) 100%);
                border: 1.5px solid rgba(255, 255, 255, 0.08);
                border-radius: 16px;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                overflow: hidden;
                min-height: 110px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
              " 
              onmouseenter="this.style.transform='translateY(-4px) scale(1.02)'; this.style.borderColor='rgba(255, 195, 0, 0.5)'; this.style.boxShadow='0 12px 28px rgba(255, 195, 0, 0.15), 0 0 40px rgba(255, 195, 0, 0.08)';"
              onmouseleave="this.style.transform='translateY(0) scale(1)'; this.style.borderColor='rgba(255, 255, 255, 0.08)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.2)';">
                
                <!-- Icon Glow Background -->
                <div style="position: absolute; top: -20px; left: -20px; width: 80px; height: 80px; background: radial-gradient(circle, rgba(255, 195, 0, 0.15) 0%, transparent 70%); filter: blur(20px); pointer-events: none;"></div>
                
                <div style="position: relative; z-index: 1;">
                  <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <a href="{{ route('category.show', $cat->slug) }}" style="text-decoration: none; color: inherit; flex: 1;">
                      <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="font-size: 36px; line-height: 1; filter: drop-shadow(0 2px 8px rgba(255, 195, 0, 0.3));">{{ $cat->icon ?? '📁' }}</div>
                        <div style="flex: 1;">
                          <div style="color: #EDEDED; font-weight: 700; font-size: 16px; line-height: 1.4; margin-bottom: 6px; letter-spacing: -0.3px;">{{ $cat->name }}</div>
                          <span style="
                            display: inline-block;
                            background: linear-gradient(135deg, rgba(255, 195, 0, 0.15) 0%, rgba(255, 195, 0, 0.08) 100%);
                            color: #FFC300;
                            padding: 4px 10px;
                            border-radius: 20px;
                            font-size: 11px;
                            font-weight: 600;
                            letter-spacing: 0.3px;
                            border: 1px solid rgba(255, 195, 0, 0.2);
                          ">{{ $cat->total_wallpapers_count ?? 0 }}</span>
                        </div>
                      </div>
                    </a>
                    @if($cat->children && $cat->children->count() > 0)
                      <button class="expand-btn" style="
                        color: #FFC300;
                        background: rgba(255, 195, 0, 0.1);
                        border: 1px solid rgba(255, 195, 0, 0.2);
                        padding: 6px 10px;
                        border-radius: 8px;
                        transition: all 0.2s ease;
                        font-size: 12px;
                        cursor: pointer;
                        margin-left: 8px;
                      " 
                      onclick="event.stopPropagation(); toggleCategoryChildren('{{ $cat->id }}')"
                      onmouseenter="this.style.background='rgba(255, 195, 0, 0.2)'; this.style.borderColor='rgba(255, 195, 0, 0.4)';"
                      onmouseleave="this.style.background='rgba(255, 195, 0, 0.1)'; this.style.borderColor='rgba(255, 195, 0, 0.2)';">
                        <i class="fas fa-chevron-down" id="toggleIcon_{{ $cat->id }}" style="transition: transform 0.3s ease;"></i>
                      </button>
                    @endif
                  </div>

                  @if($cat->children && $cat->children->count() > 0)
                    <div id="children_{{ $cat->id }}" style="
                      display: none;
                      margin-top: 14px;
                      padding-top: 14px;
                      border-top: 1px solid rgba(255, 195, 0, 0.15);
                      animation: slideDown 0.3s ease;
                    ">
                      @foreach($cat->children as $child)
                        <a href="{{ route('category.show', $child->slug) }}" style="
                          display: flex;
                          justify-content: space-between;
                          align-items: center;
                          padding: 10px 12px;
                          text-decoration: none;
                          color: #EDEDED;
                          border-radius: 10px;
                          background: rgba(255, 255, 255, 0.03);
                          margin-bottom: 8px;
                          border: 1px solid rgba(255, 255, 255, 0.05);
                          transition: all 0.2s ease;
                          font-size: 14px;
                        "
                        onmouseenter="this.style.background='rgba(255, 195, 0, 0.12)'; this.style.borderColor='rgba(255, 195, 0, 0.3)'; this.style.transform='translateX(4px)';"
                        onmouseleave="this.style.background='rgba(255, 255, 255, 0.03)'; this.style.borderColor='rgba(255, 255, 255, 0.05)'; this.style.transform='translateX(0)';">
                          <span style="font-weight: 500;">{{ $child->icon ?? '📌' }} {{ $child->name }}</span>
                          <span style="
                            background: rgba(255, 195, 0, 0.15);
                            color: #FFC300;
                            padding: 3px 8px;
                            border-radius: 12px;
                            font-size: 11px;
                            font-weight: 600;
                          ">{{ $child->wallpapers_count ?? 0 }}</span>
                        </a>
                      @endforeach
                    </div>
                  @endif
                </div>
              </div>
            @empty
              <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #A3A3A3;">
                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                <p style="font-size: 16px; font-weight: 500; margin: 0;">No categories available yet</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .category-card {
      will-change: transform, box-shadow;
    }

    .expand-btn {
      will-change: background, border-color;
    }

    @media (max-width: 768px) {
      #categoriesList {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)) !important;
        gap: 12px !important;
      }
      
      .category-card {
        padding: 16px !important;
        min-height: 100px !important;
      }
    }
  </style>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function toggleCategoryChildren(id) {
      const container = document.getElementById(`children_${id}`);
      const icon = document.getElementById(`toggleIcon_${id}`);
      if (!container || !icon) return;
      
      const isOpen = container.style.display === 'block';
      
      // Toggle display with animation
      if (isOpen) {
        container.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => {
          container.style.display = 'none';
        }, 250);
      } else {
        container.style.display = 'block';
        container.style.animation = 'slideDown 0.3s ease';
      }
      
      // Rotate icon
      icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }
  </script>
  
  <!-- Register Service Worker for offline caching -->
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js').catch(() => {
          // Service worker not available, continue without caching
        });
      });
    }
  </script>
</body>
</html>
