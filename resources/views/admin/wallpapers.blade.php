<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallpapers Management - Admin Panel</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            background: linear-gradient(135deg, rgba(30, 34, 43, 0.95) 0%, rgba(26, 30, 39, 0.95) 100%);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 195, 0, 0.15);
            padding: 22px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .admin-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #FFC300;
            letter-spacing: -0.5px;
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
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d0d0d;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(255, 195, 0, 0.3);
        }

        .admin-content {
            padding: 35px 40px;
        }

        /* Wallpapers Grid */
        .wallpapers-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-top: 25px;
            margin-bottom: 35px;
        }

        @media (max-width: 1200px) {
            .wallpapers-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 900px) {
            .wallpapers-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .wallpaper-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: rgba(30, 34, 43, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .wallpaper-checkbox {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 3;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            padding: 4px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: none;
        }

        .selection-mode .wallpaper-checkbox {
            display: block;
        }

        .wallpaper-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #FFC300;
        }

        .wallpaper-card:hover {
            border-color: rgba(255, 195, 0, 0.5);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 195, 0, 0.2);
        }

        /* ===== Toolbar Dropdown Menu ===== */
        .toolbar-shell {
            padding: 14px 16px;
            margin-bottom: 18px;
            background: rgba(30, 34, 43, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .toolbar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .toolbar-title {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #EDEDED;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar-title span {
            color: #A3A3A3;
            font-weight: 500;
            font-size: 16px;
        }

        .action-menu-wrapper {
            position: relative;
            display: inline-flex;
        }

        .action-menu-toggle {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #EDEDED;
            padding: 10px 12px;
            border-radius: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .action-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .action-menu {
            position: absolute;
            top: 44px;
            right: 0;
            background: #15181E;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
            min-width: 200px;
            padding: 8px;
            display: none;
            z-index: 10;
        }

        .action-menu.show {
            display: block;
        }

        .action-menu button {
            width: 100%;
            background: transparent;
            border: none;
            color: #EDEDED;
            text-align: left;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-menu button:hover {
            background: rgba(255, 193, 7, 0.12);
            color: #FFC107;
        }

        .action-menu .menu-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin: 6px 0;
        }

        .action-menu .menu-label {
            color: #A3A3A3;
            font-size: 12px;
            padding: 8px 10px;
            display: block;
            text-align: left;
        }

        .wallpaper-card:hover .wallpaper-image {
            transform: scale(1.05);
        }

        .wallpaper-image {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .wallpaper-info {
            padding: 12px;
        }

        .wallpaper-name {
            color: #EDEDED;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.4;
            letter-spacing: -0.2px;
        }

        .wallpaper-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 8px;
        }

        .category-tag {
            background: rgba(230, 167, 0, 0.15);
            color: #E6A700;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 600;
            border: 1px solid rgba(230, 167, 0, 0.2);
            letter-spacing: 0.3px;
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
            padding: 9px 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.3px;
        }

        .view-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .view-btn:hover {
            box-shadow: 0 4px 16px rgba(76, 175, 80, 0.45);
            transform: translateY(-2px);
        }

        .delete-btn {
            background: linear-gradient(135deg, #E85D5D 0%, #D84848 100%);
            color: white;
        }

        .delete-btn:hover {
            box-shadow: 0 4px 16px rgba(232, 93, 93, 0.45);
            transform: translateY(-2px);
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

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 11px 18px;
            background: rgba(255, 195, 0, 0.12);
            color: #FFC300;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 28px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid rgba(255, 195, 0, 0.2);
        }

        .back-btn:hover {
            background: rgba(255, 195, 0, 0.2);
            transform: translateX(-3px);
            border-color: rgba(255, 195, 0, 0.4);
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
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 18px;
            }

            .admin-content {
                padding: 25px 20px;
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
                <a href="{{ route('admin.wallpapers') }}" class="active">
                    <i class="fas fa-images"></i> Wallpapers
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
                <div>
                    <h1><i class="fas fa-images" style="margin-right: 12px; font-size: 26px;"></i>Wallpapers Management</h1>
                </div>
                <div style="display: flex; align-items: center; gap: 1.2rem;">
                    <a href="{{ route('admin.bulk.upload') }}" class="btn" style="background: linear-gradient(135deg, #ffc107, #ff9800); color: #000; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 3px 10px rgba(255, 193, 7, 0.3); transition: all 0.3s ease;" onmouseenter="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(255, 193, 7, 0.4)';" onmouseleave="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 10px rgba(255, 193, 7, 0.3)';">
                        <i class="fas fa-cloud-upload-alt"></i> Bulk Upload
                    </a>
                    <div class="admin-user">
                        <span class="admin-user-name" style="font-weight: 600; font-size: 13px;">
                            {{ Auth::guard('admin')->user()->name }}
                        </span>
                        <div class="admin-user-avatar">
                            {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <section class="admin-content">
                @if($wallpapers->count() > 0)
                    <div class="toolbar-shell">
                        <div class="toolbar-row">
                            <div class="toolbar-title">
                                All Wallpapers <span>({{ $wallpapers->total() }})</span>
                            </div>
                            <div class="action-menu-wrapper">
                                <button type="button" id="actionMenuToggle" class="action-menu-toggle" aria-label="More actions">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="5" r="1.5"></circle>
                                    <circle cx="12" cy="12" r="1.5"></circle>
                                        <circle cx="12" cy="19" r="1.5"></circle>
                                    </svg>
                                </button>
                                <div id="actionMenu" class="action-menu">
                                    <button type="button" id="actionSelectMode">
                                        <i class="fas fa-mouse-pointer"></i> Select Mode
                                    </button>
                                    <button type="button" id="actionSelectAll">
                                        <i class="fas fa-check-double"></i> Select All
                                    </button>
                                    <button type="button" id="actionDelete">
                                        <i class="fas fa-trash-alt"></i> Delete Selected
                                    </button>
                                    <button type="button" id="actionEdit">
                                        <i class="fas fa-tags"></i> Edit Categories
                                    </button>
                                    <div class="menu-divider"></div>
                                    <button type="button" id="actionCancelSelection" style="color: #FF6B6B;">
                                        <i class="fas fa-times-circle"></i> Cancel Selection
                                    </button>
                                    <div class="menu-divider"></div>
                                    <span class="menu-label">Selected: <span id="actionSelectedLabel">0 selected</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wallpapers-grid">
                        @foreach($wallpapers as $wallpaper)
                            <div class="wallpaper-card">
                                <div class="wallpaper-checkbox">
                                    <input type="checkbox" class="wallpaper-select" value="{{ $wallpaper->id }}">
                                </div>
                                @if(str_starts_with($wallpaper->mime ?? '', 'video/'))
                                    <video src="{{ $wallpaper->github_url }}" class="wallpaper-image" muted playsinline preload="metadata" autoplay loop style="object-fit: cover; background:#111;"></video>
                                    <span class="video-badge" style="position: absolute; top: 10px; right: 10px; background: #f3c623; color: #000; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">Video</span>
                                @else
                                    <img src="{{ $wallpaper->github_url }}" alt="{{ $wallpaper->name }}" class="wallpaper-image" loading="lazy">
                                @endif
                                <div class="wallpaper-overlay">
                                    <a href="{{ route('wallpaper.show', $wallpaper->filename) }}" class="overlay-btn view-btn" target="_blank">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.wallpapers.edit', $wallpaper->id) }}" class="overlay-btn" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="overlay-btn delete-btn" onclick="showDeleteModal({{ $wallpaper->id }}, '{{ addslashes($wallpaper->name) }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                                <div class="wallpaper-info">
                                    <div class="wallpaper-name" title="{{ $wallpaper->name }}">
                                        {{ $wallpaper->name }}
                                    </div>
                                    @if($wallpaper->categories->count() > 0)
                                        <div class="wallpaper-categories">
                                            @foreach($wallpaper->categories->take(2) as $category)
                                                <span class="category-tag">
                                                    <i class="fas fa-folder" style="font-size: 9px; margin-right: 3px; opacity: 0.8;"></i>
                                                    @if($category->parent)
                                                        {{ $category->parent->name }} ({{ $category->name }})
                                                    @else
                                                        {{ $category->name }}
                                                    @endif
                                                </span>
                                            @endforeach
                                            @if($wallpaper->categories->count() > 2)
                                                <span class="category-tag" style="background: rgba(255, 195, 0, 0.12); color: #FFC300;">+{{ $wallpaper->categories->count() - 2 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($wallpapers->hasPages())
                        <div class="pagination-container" style="display:flex; align-items:center; justify-content:center; gap:12px; margin-top:20px;">
                            <a
                                href="{{ $wallpapers->onFirstPage() ? 'javascript:void(0);' : $wallpapers->previousPageUrl() }}"
                                class="btn btn-sm {{ $wallpapers->onFirstPage() ? 'disabled' : '' }}"
                                aria-disabled="{{ $wallpapers->onFirstPage() ? 'true' : 'false' }}"
                                style="display:flex; align-items:center; gap:6px; background:#1e1e1e; border:1px solid #ffc300; color:#ffc300; padding:8px 14px; border-radius:10px; text-decoration:none; {{ $wallpapers->onFirstPage() ? 'opacity:0.5; pointer-events:none; cursor:default;' : '' }}"
                            >
                                <i class="fas fa-chevron-left"></i> Prev
                            </a>
                            <span style="color:#ccc; font-size:13px;">Page {{ $wallpapers->currentPage() }} of {{ $wallpapers->lastPage() }}</span>
                            <a
                                href="{{ $wallpapers->hasMorePages() ? $wallpapers->nextPageUrl() : 'javascript:void(0);' }}"
                                class="btn btn-sm {{ $wallpapers->hasMorePages() ? '' : 'disabled' }}"
                                aria-disabled="{{ $wallpapers->hasMorePages() ? 'false' : 'true' }}"
                                style="display:flex; align-items:center; gap:6px; background:#ffc300; border:1px solid #ffc300; color:#000; padding:8px 14px; border-radius:10px; text-decoration:none; {{ $wallpapers->hasMorePages() ? '' : 'opacity:0.5; pointer-events:none; cursor:default;' }}"
                            >
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="no-wallpapers">
                        <i class="fas fa-image"></i>
                        <p>No wallpapers found</p>
                    </div>
                @endif
            </section>
        </main>
    </div>

    <!-- Bulk Action Forms -->
    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.wallpapers.bulk-delete') }}" style="display:none;">
        @csrf
        <input type="hidden" name="wallpaper_ids" id="bulkDeleteIds">
    </form>

    <form id="bulkCategoryForm" method="POST" action="{{ route('admin.wallpapers.bulk-update') }}">
        @csrf
        <input type="hidden" name="wallpaper_ids" id="bulkCategoryIds">
        <div id="bulkCategoryModal" class="modal" style="display:none; overflow-y: auto;">
            <div class="modal-content" style="max-width: 520px; padding: 0; overflow: visible; max-height: 90vh; margin: auto; background: #1A1D23; border-radius: 20px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4); animation: modalSlideIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);">
                
                <!-- Modal Header (Light Section Separation) -->
                <div style="padding: 28px 32px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); background: #1A1D23;">
                    <h2 style="margin: 0; font-family: 'Segoe UI', 'Roboto', sans-serif; font-size: 24px; font-weight: 700; color: #FFFFFF; letter-spacing: -0.3px;">Edit Categories</h2>
                    <p style="color: #B8B9BF; font-size: 14px; margin: 8px 0 0 0; font-weight: 400; line-height: 1.5;">Select which categories apply to the selected wallpapers</p>
                </div>

                <!-- Search Area -->
                <div style="padding: 20px 32px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); background: #1A1D23;">
                    <div style="position: relative;">
                        <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #8B8D93; width: 18px; height: 18px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input 
                            type="text" 
                            id="categorySearchInput" 
                            placeholder="Search categories..." 
                            style="width: 100%; padding: 12px 16px 12px 44px; background: #24272E; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; color: #FFFFFF; font-size: 14px; font-family: 'Segoe UI', 'Roboto', sans-serif; transition: all 0.25s ease; outline: none;"
                            onfocus="this.style.borderColor='rgba(255, 255, 255, 0.16)'; this.style.background='#2A2D35';"
                            onblur="this.style.borderColor='rgba(255, 255, 255, 0.08)'; this.style.background='#24272E';"
                            oninput="filterCategories(this.value)"
                        />
                    </div>
                </div>

                <!-- Scrollable Category List -->
                <div id="categoriesListContainer" style="max-height: 320px; overflow-y: auto; padding: 16px 32px; background: #1A1D23; scroll-behavior: smooth;">
                    @foreach($categories as $category)
                        <label class="category-checkbox-item" data-category-name="{{ strtolower($category->name) }}" style="display: flex; align-items: center; gap: 14px; padding: 14px 16px; margin-bottom: 10px; background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 12px; cursor: pointer; transition: all 0.2s ease; user-select: none; animation: fadeIn 0.25s ease;">
                            <input 
                                type="checkbox" 
                                name="categories[]" 
                                value="{{ $category->id }}" 
                                class="category-checkbox-input"
                                style="width: 20px; height: 20px; cursor: pointer; accent-color: #FFC107; flex-shrink: 0; appearance: none; -webkit-appearance: none; background: #24272E; border: 2px solid rgba(255, 193, 7, 0.4); border-radius: 6px; transition: all 0.2s ease; position: relative;"
                            />
                            <span style="font-size: 22px; line-height: 1; flex-shrink: 0; filter: opacity(0.9);">{{ $category->icon ?? '📁' }}</span>
                            <div style="flex: 1; min-width: 0;">
                                <div style="color: #FFFFFF; font-size: 14px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-family: 'Segoe UI', 'Roboto', sans-serif;">{{ $category->name }}</div>
                                @if($category->total_wallpapers_count ?? 0 > 0)
                                    <div style="color: #8B8D93; font-size: 12px; margin-top: 2px; font-family: 'Segoe UI', 'Roboto', sans-serif;">{{ $category->total_wallpapers_count }} wallpaper{{ $category->total_wallpapers_count != 1 ? 's' : '' }}</div>
                                @else
                                    <div style="color: #6B6D73; font-size: 12px; margin-top: 2px; font-family: 'Segoe UI', 'Roboto', sans-serif;">No wallpapers</div>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- Selection Status Badge -->
                <div id="selectedCategoriesCount" style="margin: 0 32px; padding: 12px 14px; background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.2); border-radius: 10px; text-align: center; font-size: 13px; color: #FFC107; font-weight: 600; display: none; font-family: 'Segoe UI', 'Roboto', sans-serif; animation: fadeIn 0.25s ease;">
                    <svg style="display: inline; margin-right: 6px; width: 14px; height: 14px; vertical-align: middle;" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    <span id="selectedCategoriesText">0 categories selected</span>
                </div>

                <!-- Sticky Action Buttons Area -->
                <div style="padding: 20px 32px 28px 32px; border-top: 1px solid rgba(255, 255, 255, 0.05); background: #1A1D23; display: flex; gap: 12px; justify-content: center;">
                    <button type="button" class="edit-category-btn-cancel" onclick="closeBulkCategoryModal()" style="flex: 1; padding: 13px 24px; font-size: 14px; font-weight: 600; border-radius: 12px; border: 1.5px solid rgba(255, 255, 255, 0.15); background: rgba(255, 255, 255, 0.03); color: #FFFFFF; cursor: pointer; font-family: 'Segoe UI', 'Roboto', sans-serif; transition: all 0.2s ease;">
                        <svg style="display: inline; margin-right: 6px; width: 16px; height: 16px; vertical-align: middle;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        Cancel
                    </button>
                    <button type="submit" class="edit-category-btn-apply" style="flex: 1; padding: 13px 24px; font-size: 14px; font-weight: 600; border-radius: 12px; border: none; background: #FFC107; color: #FFFFFF; cursor: pointer; font-family: 'Segoe UI', 'Roboto', sans-serif; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.25);">
                        <svg style="display: inline; margin-right: 6px; width: 16px; height: 16px; vertical-align: middle;" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Apply Changes
                    </button>
                </div>
            </div>
        </div>
    </form>

    <style>
        /* ===== MODAL ANIMATIONS ===== */
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* ===== MODAL BACKGROUND ===== */
        #bulkCategoryModal {
            background: rgba(0, 0, 0, 0.65) !important;
        }

        /* ===== CUSTOM SCROLLBAR - Premium Dark Theme ===== */
        #categoriesListContainer::-webkit-scrollbar {
            width: 8px;
        }

        #categoriesListContainer::-webkit-scrollbar-track {
            background: transparent;
        }

        #categoriesListContainer::-webkit-scrollbar-thumb {
            background: rgba(255, 193, 7, 0.3);
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        #categoriesListContainer::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 193, 7, 0.5);
        }

        /* ===== CHECKBOX CUSTOM STYLING ===== */
        .category-checkbox-input:checked {
            background: #FFC107;
            border-color: #FFC107;
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M13.78 4.22a.75.75 0 010 1.06l-7.25 7.25a.75.75 0 01-1.06 0L2.22 9.28a.75.75 0 011.06-1.06L6 10.94l6.72-6.72a.75.75 0 011.06 0z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100%;
        }

        .category-checkbox-input:focus {
            outline: none;
            border-color: #FFC107;
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.15);
        }

        /* ===== CATEGORY ITEM HOVER STATE ===== */
        .category-checkbox-item:hover {
            background: rgba(255, 193, 7, 0.12) !important;
            border-color: rgba(255, 193, 7, 0.35) !important;
            transform: translateX(3px);
        }

        /* ===== CATEGORY ITEM CHECKED STATE (Clean & Confident) ===== */
        .category-checkbox-item:has(input:checked) {
            background: rgba(255, 193, 7, 0.12) !important;
            border-color: rgba(255, 193, 7, 0.3) !important;
        }

        /* ===== BUTTON HOVER & ACTIVE STATES ===== */
        .edit-category-btn-cancel:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        .edit-category-btn-cancel:active {
            transform: translateY(0);
        }

        .edit-category-btn-apply:hover {
            background: #FFB300;
            box-shadow: 0 6px 16px rgba(255, 193, 7, 0.35);
            transform: translateY(-1px);
        }

        .edit-category-btn-apply:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.25);
        }

        /* ===== SEARCH INPUT PLACEHOLDER ===== */
        #categorySearchInput::placeholder {
            color: #6B6D73;
        }

        /* ===== RESPONSIVE ADJUSTMENTS ===== */
        @media (max-width: 600px) {
            .modal-content {
                max-width: 95vw !important;
            }
        }
    </style>

    <script>
        // Filter categories based on search input
        function filterCategories(searchTerm) {
            const items = document.querySelectorAll('.category-checkbox-item');
            const lowerSearch = searchTerm.toLowerCase().trim();
            let visibleCount = 0;

            items.forEach(item => {
                const categoryName = item.getAttribute('data-category-name');
                if (categoryName.includes(lowerSearch)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show "no results" message if needed
            const container = document.getElementById('categoriesListContainer');
            let noResultsMsg = container.querySelector('.no-results-msg');
            
            if (visibleCount === 0) {
                if (!noResultsMsg) {
                    noResultsMsg = document.createElement('div');
                    noResultsMsg.className = 'no-results-msg';
                    noResultsMsg.style.cssText = 'text-align: center; padding: 40px 20px; color: #A3A3A3; font-size: 14px;';
                    noResultsMsg.innerHTML = '<i class="fas fa-search" style="font-size: 32px; opacity: 0.5; margin-bottom: 10px; display: block;"></i>No categories found';
                    container.appendChild(noResultsMsg);
                }
            } else if (noResultsMsg) {
                noResultsMsg.remove();
            }
        }

        // Update selected categories count
        function updateSelectedCategoriesCount() {
            const checkboxes = document.querySelectorAll('.category-checkbox-item input[type="checkbox"]:checked');
            const count = checkboxes.length;
            const countDisplay = document.getElementById('selectedCategoriesCount');
            const countText = document.getElementById('selectedCategoriesText');

            if (count > 0) {
                countDisplay.style.display = 'block';
                countText.textContent = `${count} ${count === 1 ? 'category' : 'categories'} selected`;
            } else {
                countDisplay.style.display = 'none';
            }
        }

        // Attach event listeners to checkboxes
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('.category-checkbox-item input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCategoriesCount);
            });
        });
    </script>

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
        const wallpaperCheckboxes = document.querySelectorAll('.wallpaper-select');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        const bulkDeleteIds = document.getElementById('bulkDeleteIds');
        const bulkCategoryForm = document.getElementById('bulkCategoryForm');
        const bulkCategoryIds = document.getElementById('bulkCategoryIds');
        const bulkCategoryModal = document.getElementById('bulkCategoryModal');

        const actionMenuToggle = document.getElementById('actionMenuToggle');
        const actionMenu = document.getElementById('actionMenu');
        const actionSelectMode = document.getElementById('actionSelectMode');
        const actionSelectAll = document.getElementById('actionSelectAll');
        const actionDelete = document.getElementById('actionDelete');
        const actionEdit = document.getElementById('actionEdit');
        const actionCancelSelection = document.getElementById('actionCancelSelection');
        const actionSelectedLabel = document.getElementById('actionSelectedLabel');

        function setSelectionMode(on) {
            if (on) {
                document.body.classList.add('selection-mode');
            } else {
                document.body.classList.remove('selection-mode');
            }
        }

        function getSelectedIds() {
            return Array.from(document.querySelectorAll('.wallpaper-select:checked')).map(cb => cb.value);
        }

        function updateSelectedCount() {
            const count = getSelectedIds().length;
            if (actionSelectedLabel) {
                actionSelectedLabel.textContent = `${count} selected`;
            }
        }

        wallpaperCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                updateSelectedCount();
            });
        });

        actionSelectMode?.addEventListener('click', () => {
            setSelectionMode(true);
            actionMenu?.classList.remove('show');
        });

        actionCancelSelection?.addEventListener('click', () => {
            wallpaperCheckboxes.forEach(cb => cb.checked = false);
            setSelectionMode(false);
            updateSelectedCount();
            actionMenu?.classList.remove('show');
        });

        actionMenuToggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            actionMenu?.classList.toggle('show');
        });

        document.addEventListener('click', (e) => {
            if (!actionMenu) return;
            if (!actionMenu.contains(e.target) && e.target !== actionMenuToggle) {
                actionMenu.classList.remove('show');
            }
        });

        actionSelectAll?.addEventListener('click', () => {
            setSelectionMode(true);
            wallpaperCheckboxes.forEach(cb => cb.checked = true);
            updateSelectedCount();
            actionMenu.classList.remove('show');
        });

        actionDelete?.addEventListener('click', () => {
            setSelectionMode(true);
            const ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Select at least one wallpaper.');
                actionMenu?.classList.remove('show');
                return;
            }
            if (!confirm(`Delete ${ids.length} selected wallpaper(s)? This cannot be undone.`)) {
                actionMenu?.classList.remove('show');
                return;
            }
            bulkDeleteIds.value = ids.join(',');
            actionMenu?.classList.remove('show');
            bulkDeleteForm.submit();
        });

        actionEdit?.addEventListener('click', () => {
            setSelectionMode(true);
            const ids = getSelectedIds();
            if (ids.length === 0) {
                alert('Select at least one wallpaper.');
                actionMenu?.classList.remove('show');
                return;
            }
            bulkCategoryIds.value = ids.join(',');
            bulkCategoryModal.style.display = 'block';
            bulkCategoryModal.classList.add('show');
            actionMenu?.classList.remove('show');
        });

        function closeBulkCategoryModal() {
            if (bulkCategoryModal) {
                bulkCategoryModal.style.display = 'none';
                bulkCategoryModal.classList.remove('show');
            }
        }

        bulkCategoryModal?.addEventListener('click', (e) => {
            if (e.target === bulkCategoryModal) {
                closeBulkCategoryModal();
            }
        });

        bulkCategoryForm?.addEventListener('submit', (e) => {
            const ids = getSelectedIds();
            if (ids.length === 0) {
                e.preventDefault();
                alert('Select at least one wallpaper.');
                return;
            }
            const selectedCats = bulkCategoryForm.querySelectorAll('input[name="categories[]"]:checked');
            if (selectedCats.length === 0) {
                e.preventDefault();
                alert('Select at least one category to apply.');
                return;
            }
            bulkCategoryIds.value = ids.join(',');
        });

        function showDeleteModal(wallpaperId, wallpaperName) {
            document.getElementById('deleteForm').action = `/admin/wallpapers/${wallpaperId}`;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        document.getElementById('deleteModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
