<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Wallpapers - Wallpaper Hub Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" sizes="32x32">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1E222B 0%, #16191F 100%);
            border-right: 1px solid rgba(255, 195, 0, 0.1);
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .admin-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #FFC300;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 195, 0, 0.1);
        }

        .admin-logo img {
            width: 136px;
            height: 39px;
            object-fit: contain;
            border-radius: 8px;
            padding: 4px;
        }

        .admin-logo span {
            line-height: 1;
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: transparent;
            border: 1px solid transparent;
            color: #A3A3A3;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .admin-nav a:hover {
            background: rgba(255, 195, 0, 0.1);
            color: #FFC300;
            border-color: rgba(255, 195, 0, 0.2);
        }

        .logout-btn {
            margin-top: auto;
            background: rgba(255, 107, 107, 0.1);
            color: #FF6B6B;
            border: 1px solid rgba(255, 107, 107, 0.2);
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 107, 107, 0.2);
            border-color: #FF6B6B;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 260px;
        }

        .admin-header {
            background: rgba(30, 34, 43, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 195, 0, 0.1);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: #FFC300;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-user-name {
            font-size: 14px;
            font-weight: 500;
        }

        .admin-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d0d0d;
            font-weight: 700;
        }

        .admin-content {
            padding: 30px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            background: rgba(255, 195, 0, 0.1);
            color: #FFC300;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .back-btn:hover {
            background: rgba(255, 195, 0, 0.2);
        }

        .user-info {
            background: rgba(30, 34, 43, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 195, 0, 0.1);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d0d0d;
            font-weight: 700;
            font-size: 24px;
        }

        .user-info-details h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            color: #FFC300;
            margin-bottom: 5px;
        }

        .user-info-email {
            color: #A3A3A3;
            font-size: 13px;
        }

        /* Wallpapers Grid */
        .wallpapers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .wallpaper-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: rgba(30, 34, 43, 0.8);
            border: 1px solid rgba(255, 195, 0, 0.1);
            transition: all 0.3s ease;
            group: 1;
        }

        .wallpaper-card:hover {
            border-color: #FFC300;
            transform: translateY(-5px);
        }

        .wallpaper-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .wallpaper-info {
            padding: 12px;
        }

        .wallpaper-name {
            color: #EDEDED;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .wallpaper-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .wallpaper-card:hover .wallpaper-overlay {
            opacity: 1;
        }

        .overlay-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .view-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .view-btn:hover {
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }

        .delete-btn {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF5252 100%);
            color: white;
        }

        .delete-btn:hover {
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
        }

        .no-wallpapers {
            text-align: center;
            padding: 60px 20px;
            color: #A3A3A3;
        }

        .no-wallpapers i {
            font-size: 48px;
            color: #FFC300;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: rgba(30, 34, 43, 0.95);
            border: 1px solid rgba(255, 195, 0, 0.2);
            border-radius: 15px;
            padding: 30px;
            max-width: 400px;
            text-align: center;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-content h2 {
            color: #FFC300;
            margin-bottom: 15px;
            font-family: 'Poppins', sans-serif;
        }

        .modal-content p {
            color: #A3A3A3;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .modal-btn-confirm,
        .modal-btn-cancel {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .modal-btn-confirm {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF5252 100%);
            color: white;
        }

        .modal-btn-confirm:hover {
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
        }

        .modal-btn-cancel {
            background: rgba(255, 255, 255, 0.05);
            color: #FFC300;
            border: 1px solid rgba(255, 195, 0, 0.2);
        }

        .modal-btn-cancel:hover {
            background: rgba(255, 195, 0, 0.1);
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: static;
                border-right: none;
                border-bottom: 1px solid rgba(255, 195, 0, 0.1);
            }

            .admin-main {
                margin-left: 0;
            }

            .wallpapers-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .user-info {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <img src="{{ asset('images/logo.png') }}" alt="WallHub logo">
            </div>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.users') }}">
                    <i class="fas fa-users"></i> Users
                </a>
                <a href="{{ route('admin.categories') }}">
                    <i class="fas fa-folder-open"></i> Categories
                </a>
            </nav>

            <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: 20px;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>User Wallpapers</h1>
                <div class="admin-user">
                    <span class="admin-user-name">
                        {{ Auth::guard('admin')->user()->name }}
                    </span>
                    <div class="admin-user-avatar">
                        {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <section class="admin-content">
                <a href="{{ route('admin.users') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>

                <!-- User Info -->
                <div class="user-info">
                    <div class="user-info-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="user-info-details">
                        <h2>{{ $user->name }}</h2>
                        <p class="user-info-email">{{ $user->email }}</p>
                    </div>
                </div>

                @if($wallpapers->count() > 0)
                    <h2 style="font-family: 'Poppins', sans-serif; font-size: 18px; color: #FFC300; margin-bottom: 20px;">
                        Uploaded Wallpapers ({{ $wallpapers->count() }})
                    </h2>
                    <div class="wallpapers-grid">
                        @foreach($wallpapers as $wallpaper)
                            <div class="wallpaper-card">
                                @if(str_starts_with($wallpaper->mime ?? '', 'video/'))
                                    <video src="{{ $wallpaper->github_url }}" class="wallpaper-image" muted playsinline preload="metadata" autoplay loop></video>
                                    <span class="video-badge" style="position: absolute; top: 10px; right: 10px; background: #f3c623; color: #000; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">Video</span>
                                @else
                                    <img src="{{ $wallpaper->github_url }}" alt="{{ $wallpaper->name }}" class="wallpaper-image" loading="lazy">
                                @endif
                                <div class="wallpaper-overlay">
                                    <a href="{{ route('wallpaper.show', $wallpaper->filename) }}" class="overlay-btn view-btn" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <button class="overlay-btn delete-btn" onclick="showDeleteModal({{ $wallpaper->id }}, '{{ $wallpaper->name }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                                <div class="wallpaper-info">
                                    <div class="wallpaper-name" title="{{ $wallpaper->name }}">
                                        {{ $wallpaper->name }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-wallpapers">
                        <i class="fas fa-image"></i>
                        <p>This user hasn't uploaded any wallpapers yet</p>
                    </div>
                @endif
            </section>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h2>
            <p>Are you sure you want to delete this wallpaper? This action cannot be undone.</p>
            <div class="modal-buttons">
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-btn-confirm">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
                <button class="modal-btn-cancel" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(wallpaperId, wallpaperName) {
            document.getElementById('deleteForm').action = `/admin/wallpapers/${wallpaperId}`;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
