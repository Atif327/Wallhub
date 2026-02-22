<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wallpaper Hub</title>
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
            
        }

        .admin-logo span {
            line-height: 1;
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .admin-nav a,
        .admin-nav button {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: transparent;
            border: 1px solid transparent;
            color: #A3A3A3;
            text-decoration: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
        }

        .admin-nav a:hover,
        .admin-nav button:hover {
            background: rgba(255, 195, 0, 0.1);
            color: #FFC300;
            border-color: rgba(255, 195, 0, 0.2);
        }

        .admin-nav a.active {
            background: rgba(255, 195, 0, 0.2);
            color: #FFC300;
            border-color: #FFC300;
        }

        .logout-btn {
            margin-top: auto;
            background: rgba(255, 107, 107, 0.1);
            color: #FF6B6B;
            border: 1px solid rgba(255, 107, 107, 0.2);
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

        /* Dashboard Stats */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(30, 34, 43, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 195, 0, 0.1);
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            border-color: #FFC300;
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d0d0d;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-label {
            color: #A3A3A3;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #FFC300;
            font-family: 'Poppins', sans-serif;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            color: #0d0d0d;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 195, 0, 0.4);
        }

        .action-btn-secondary {
            background: rgba(30, 34, 43, 0.8);
            color: #FFC300;
            border: 1px solid #FFC300;
        }

        .action-btn-secondary:hover {
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

            .admin-nav {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .admin-header {
                flex-direction: column;
                gap: 15px;
            }

            .dashboard-stats {
                grid-template-columns: 1fr;
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
                <a href="{{ route('admin.dashboard') }}" class="active">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('admin.wallpapers') }}">
                    <i class="fas fa-images"></i> All Wallpapers
                </a>
                <a href="{{ route('admin.bulk.upload') }}">
                    <i class="fas fa-cloud-upload-alt"></i> Bulk Upload
                </a>
                <a href="{{ route('admin.categories') }}">
                    <i class="fas fa-folder-open"></i> Categories
                </a>
                <a href="{{ route('admin.users') }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </nav>

            <form method="POST" action="{{ route('admin.logout') }}" style="margin-top: 20px;">
                @csrf
                <button type="submit" class="logout-btn" style="width: 100%;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>Dashboard</h1>
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
                <div class="dashboard-stats">
                    <a href="{{ route('admin.wallpapers') }}" style="text-decoration: none; color: inherit;">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="stat-label">Total Wallpapers</div>
                            <div class="stat-value">{{ $totalWallpapers }}</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.categories') }}" style="text-decoration: none; color: inherit;">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <div class="stat-label">Total Categories</div>
                            <div class="stat-value">{{ $totalCategories }}</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.users') }}" style="text-decoration: none; color: inherit;">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-label">Total Users</div>
                            <div class="stat-value">{{ $totalUsers }}</div>
                        </div>
                    </a>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-label">Total Views</div>
                        <div class="stat-value">{{ number_format($totalViews) }}</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-label">Total Likes</div>
                        <div class="stat-value">{{ number_format($totalLikes) }}</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <div class="stat-label">Total Downloads</div>
                        <div class="stat-value">{{ number_format($totalDownloads) }}</div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div style="margin-top: 40px;">
                    <h2 style="font-family: 'Poppins', sans-serif; font-size: 20px; margin-bottom: 20px; color: #FFC300;">Recent Wallpapers</h2>
                    <div style="background: rgba(30, 34, 43, 0.8); border: 1px solid rgba(255, 195, 0, 0.1); border-radius: 15px; overflow: hidden;">
                        @foreach($recentWallpapers as $wallpaper)
                        <div style="padding: 15px; border-bottom: 1px solid rgba(255, 195, 0, 0.1); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="color: #FFC300;">{{ $wallpaper->name }}</strong>
                                <span style="color: #A3A3A3; font-size: 13px; margin-left: 10px;">by {{ $wallpaper->user->name ?? 'Unknown' }}</span>
                            </div>
                            <span style="color: #A3A3A3; font-size: 12px;">{{ $wallpaper->created_at->diffForHumans() }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Popular Wallpapers -->
                <div style="margin-top: 40px;">
                    <h2 style="font-family: 'Poppins', sans-serif; font-size: 20px; margin-bottom: 20px; color: #FFC300;">Most Popular</h2>
                    <div style="background: rgba(30, 34, 43, 0.8); border: 1px solid rgba(255, 195, 0, 0.1); border-radius: 15px; overflow: hidden;">
                        @foreach($popularWallpapers as $wallpaper)
                        <div style="padding: 15px; border-bottom: 1px solid rgba(255, 195, 0, 0.1); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="color: #FFC300;">{{ $wallpaper->name }}</strong>
                                <span style="color: #A3A3A3; font-size: 13px; margin-left: 10px;">
                                    <i class="fas fa-eye"></i> {{ number_format($wallpaper->views) }} views
                                </span>
                            </div>
                            <a href="{{ route('wallpaper.show', ['name' => $wallpaper->filename ?? $wallpaper->slug ?? $wallpaper->id]) }}" class="action-btn-secondary" style="padding: 8px 15px; text-decoration: none; font-size: 12px;">View</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Active Users -->
                <div style="margin-top: 40px;">
                    <h2 style="font-family: 'Poppins', sans-serif; font-size: 20px; margin-bottom: 20px; color: #FFC300;">Most Active Users</h2>
                    <div style="background: rgba(30, 34, 43, 0.8); border: 1px solid rgba(255, 195, 0, 0.1); border-radius: 15px; overflow: hidden;">
                        @foreach($activeUsers as $user)
                        <div style="padding: 15px; border-bottom: 1px solid rgba(255, 195, 0, 0.1); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong style="color: #FFC300;">{{ $user->name }}</strong>
                                <span style="color: #A3A3A3; font-size: 13px; margin-left: 10px;">{{ $user->email }}</span>
                            </div>
                            <span style="color: #A3A3A3; font-size: 13px;">
                                <i class="fas fa-images"></i> {{ $user->wallpapers_count }} wallpapers
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div style="margin-top: 40px;">
                    <h2 style="font-family: 'Poppins', sans-serif; font-size: 20px; margin-bottom: 20px; color: #FFC300;">Quick Actions</h2>
                    <div class="quick-actions">
                        <a href="{{ route('admin.categories') }}" class="action-btn">
                            <i class="fas fa-cogs"></i> Manage Categories
                        </a>
                        <a href="{{ route('wallpapers.create') }}" class="action-btn action-btn-secondary">
                            <i class="fas fa-plus"></i> Upload Wallpaper
                        </a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
