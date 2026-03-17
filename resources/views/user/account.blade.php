<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - WallHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0d0d0d;
            color: #EDEDED;
            min-height: 100vh;
            padding-top: 0 !important;
        }

        .navbar {
            background: rgba(13, 13, 13, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 195, 0, 0.1);
            padding: 1rem 0;
            position: relative;
            z-index: 100;
        }

        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #FFC300 !important;
        }

        .nav-link {
            color: #A3A3A3 !important;
            font-weight: 500;
            margin-left: 10px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: #FFC300 !important;
        }

        /* Profile Cover */
        .profile-cover {
            background: url('/images/cover.jpg') center/cover no-repeat;
            height: 260px;
            position: relative;
            margin-top: 70px;
        }

        .profile-cover .overlay {
            background: rgba(0, 0, 0, 0.5);
            position: absolute;
            inset: 0;
        }

        .profile-info {
            position: absolute;
            bottom: 30px;
            width: 100%;
            text-align: center;
            color: white;
            z-index: 2;
        }

        .avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid white;
            background: linear-gradient(135deg, #f7c948 0%, #e6b10e 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: 700;
            color: #0d0d0d;
            margin-bottom: 10px;
        }

        .profile-info h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 700;
            margin: 10px 0 8px 0;
            color: white;
        }

        .joined {
            background: rgba(0, 0, 0, 0.6);
            padding: 6px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 10px;
            font-size: 14px;
            color: #f7c948;
        }

        /* Edit Profile Button */
        .edit-profile-btn {
            position: absolute;
            top: 20px;
            right: 40px;
            padding: 8px 20px;
            background: #444c56;
            color: white;
            border-radius: 6px;
            font-size: 14px;
            text-decoration: none;
            z-index: 3;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .edit-profile-btn:hover {
            background: #59616d;
            color: white;
        }

        /* Stats Bar */
        .stats-bar {
            background: #1b1e23;
            padding: 20px;
            display: flex;
            justify-content: space-around;
            color: white;
            border-bottom: 1px solid #333;
        }

        .stat {
            text-align: center;
            color: #fff;
        }

        .stat i {
            font-size: 28px;
            color: #f7c948;
            margin-bottom: 8px;
        }

        .stat h4 {
            font-size: 20px;
            margin: 5px 0;
            font-weight: 700;
        }

        .stat span {
            font-size: 14px;
            opacity: 0.75;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Wallpapers Section */
        .wallpapers-section {
            padding: 40px;
        }

        .wallpapers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        /* Upload Card */
        .upload-card {
            background: #f7c948;
            border-radius: 6px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            color: #0d0d0d;
        }

        .upload-card:hover {
            background: #ffd760;
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(247, 201, 72, 0.4);
            color: #0d0d0d;
        }

        .upload-card span {
            font-size: 60px;
            line-height: 1;
            margin-bottom: 10px;
        }

        .upload-card p {
            font-size: 16px;
            letter-spacing: 1px;
            margin: 0;
        }

        /* Wallpaper Card */
        .wallpaper-card {
            position: relative;
            border-radius: 6px;
            overflow: hidden;
            background: #14171c;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .wallpaper-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 18px rgba(0, 0, 0, 0.45);
        }

        .wallpaper-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
            border-radius: 6px;
        }

        .wallpaper-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.7) 50%, transparent 100%);
            padding: 40px 12px 12px 12px;
            color: white;
        }

        .wallpaper-name {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .wallpaper-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .category-tag {
            background: rgba(247, 201, 72, 0.9);
            color: #0d0d0d;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
        }

        .wallpaper-menu {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
        }

        .menu-btn {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.55);
            color: #fff;
            transition: all 0.2s ease;
        }

        .menu-btn:hover {
            background: rgba(0, 0, 0, 0.75);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
        }

        .view-btn {
            background: #4CAF50;
        }

        .view-btn:hover {
            background: #45a049;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }

        .delete-btn {
            background: #FF6B6B;
        }

        .delete-btn:hover {
            background: #FF5252;
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
        }

        .no-wallpapers {
            text-align: center;
            padding: 60px 20px;
            background: rgba(30, 34, 43, 0.8);
            border: 1px solid rgba(255, 195, 0, 0.1);
            border-radius: 12px;
            color: #A3A3A3;
        }

        .no-wallpapers i {
            font-size: 48px;
            color: #FFC300;
            margin-bottom: 15px;
            opacity: 0.5;
            display: block;
        }

        .no-wallpapers p {
            margin-bottom: 20px;
        }

        .no-wallpapers a {
            display: inline-block;
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            color: #0d0d0d;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .no-wallpapers a:hover {
            box-shadow: 0 4px 12px rgba(255, 195, 0, 0.4);
            transform: translateY(-2px);
        }

        /* Modal */
        .modal-content {
            background: rgba(30, 34, 43, 0.95);
            border: 1px solid rgba(255, 195, 0, 0.2);
            border-radius: 12px;
        }

        .modal-content h5 {
            color: #FFC300;
            font-family: 'Poppins', sans-serif;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-footer .btn {
            padding: 8px 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-danger {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF5252 100%);
            border: none;
            color: white;
        }

        .btn-danger:hover {
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #FFC300;
            border: 1px solid rgba(255, 195, 0, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 195, 0, 0.1);
        }

        /* Footer */
        footer {
            background: rgba(30, 34, 43, 0.8);
            border-top: 1px solid rgba(255, 195, 0, 0.1);
            color: #A3A3A3;
            padding: 30px 0;
            margin-top: 50px;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .wallpapers-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
                padding: 20px;
            }

            .profile-cover {
                height: 200px;
            }

            .avatar {
                width: 70px;
                height: 70px;
                font-size: 32px;
            }

            .profile-info h2 {
                font-size: 22px;
            }

            .stats-bar {
                flex-wrap: wrap;
                gap: 20px;
            }

            .stat {
                flex: 1 1 45%;
            }

            .edit-profile-btn {
                top: 10px;
                right: 20px;
                padding: 6px 14px;
                font-size: 12px;
            }

            .wallpapers-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wallpapers.create') }}">
                            <i class="fas fa-cloud-upload-alt"></i> Upload
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('user.account') }}">
                            <i class="fas fa-user"></i> My Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Cover Section -->
    <div class="profile-cover">
        <div class="overlay"></div>
        <a href="#" class="edit-profile-btn" title="Profile editing coming soon">✎ EDIT PROFILE</a>
        <div class="profile-info">
            <div class="avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2>{{ $user->name }}</h2>
            <span class="joined">Joined {{ $user->created_at->diffForHumans() }}</span>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="stat">
            <i class="fas fa-images"></i>
            <h4>{{ $wallpapers->count() }}</h4>
            <span>wallpapers</span>
        </div>

        <div class="stat">
            <i class="fas fa-eye"></i>
            <h4>{{ number_format($totalViews) }}</h4>
            <span>views</span>
        </div>

        <div class="stat">
            <i class="fas fa-download"></i>
            <h4>{{ number_format($totalDownloads) }}</h4>
            <span>downloads</span>
        </div>

        <div class="stat">
            <i class="fas fa-heart"></i>
            <h4>{{ number_format($totalLikes) }}</h4>
            <span>favs</span>
        </div>
    </div>

    <!-- Wallpapers Grid Section -->
    <div class="wallpapers-section">
        @php($videoPoster = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='225' viewBox='0 0 400 225'%3E%3Crect width='400' height='225' fill='%23111111'/%3E%3Ctext x='50%25' y='50%25' fill='%23aaaaaa' font-size='18' text-anchor='middle' dominant-baseline='middle'%3ELoading video...%3C/text%3E%3C/svg%3E")
        <div class="wallpapers-grid">
            <!-- Upload Card -->
            <a href="{{ route('wallpapers.create') }}" class="upload-card">
                <span>+</span>
                <p>UPLOAD</p>
            </a>

            <!-- User Wallpapers -->
            @foreach($wallpapers as $wallpaper)
                <div class="wallpaper-card" onclick="window.location='{{ route('wallpaper.show', $wallpaper->filename) }}'">
                    @php($isVideo = str_starts_with($wallpaper->mime, 'video/'))

                    <div class="wallpaper-menu dropdown" onclick="event.stopPropagation();">
                        <button class="menu-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $wallpaper->id }}">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </div>

                    @if($isVideo)
                        <video src="{{ $wallpaper->github_url }}" class="wallpaper-image" muted playsinline preload="metadata" autoplay loop style="width: 100%; height: 100%; object-fit: cover; background:#111;"></video>
                    @else
                        <img src="{{ $wallpaper->github_url }}" alt="{{ $wallpaper->name }}" loading="lazy">
                    @endif
                    <div class="wallpaper-info">
                        <div class="wallpaper-name" title="{{ $wallpaper->name }}">
                            {{ $wallpaper->name }}
                        </div>
                        @if($wallpaper->categories->count() > 0)
                            <div class="wallpaper-categories">
                                @foreach($wallpaper->categories->take(3) as $category)
                                    <span class="category-tag">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal{{ $wallpaper->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle"></i> Delete Wallpaper
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong>{{ $wallpaper->name }}</strong>?</p>
                                <p style="color: #A3A3A3; font-size: 13px;">This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form method="POST" action="{{ route('user.wallpapers.delete', $wallpaper->id) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <p>&copy; 2025 WallHub. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
