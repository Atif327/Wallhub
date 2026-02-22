<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wallpaper - Admin Panel</title>
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
            max-width: 800px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #FFC300;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            background: rgba(30, 34, 43, 0.8);
            border: 1px solid rgba(255, 195, 0, 0.2);
            border-radius: 8px;
            color: #EDEDED;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #FFC300;
            background: rgba(30, 34, 43, 0.95);
            box-shadow: 0 0 10px rgba(255, 195, 0, 0.2);
        }

        .form-control::placeholder {
            color: #707070;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .category-checkbox {
            display: none;
        }

        .category-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            background: rgba(30, 34, 43, 0.8);
            border: 2px solid rgba(255, 195, 0, 0.2);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 13px;
            font-weight: 500;
            color: #A3A3A3;
            min-height: 50px;
        }

        .category-checkbox:checked + .category-label {
            background: rgba(255, 195, 0, 0.2);
            border-color: #FFC300;
            color: #FFC300;
        }

        .category-label:hover {
            border-color: #FFC300;
            background: rgba(255, 195, 0, 0.1);
        }

        .wallpaper-preview {
            margin: 20px 0;
            text-align: center;
        }

        .wallpaper-preview img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            border: 1px solid rgba(255, 195, 0, 0.2);
        }

        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-save {
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            color: #0d0d0d;
            flex: 1;
        }

        .btn-save:hover {
            box-shadow: 0 4px 15px rgba(255, 195, 0, 0.4);
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: rgba(255, 255, 255, 0.05);
            color: #FFC300;
            border: 1px solid rgba(255, 195, 0, 0.2);
            flex: 1;
        }

        .btn-cancel:hover {
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

            .admin-content {
                padding: 20px;
            }

            .categories-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
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
                <h1>Edit Wallpaper</h1>
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
                <a href="{{ route('admin.wallpapers') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Wallpapers
                </a>

                <!-- Wallpaper Preview -->
                <div class="wallpaper-preview">
                    @if(str_starts_with($wallpaper->mime ?? '', 'video/'))
                        <video src="{{ $wallpaper->github_url }}" style="width: 100%; height: auto; border-radius: 12px; object-fit: cover;" controls muted preload="metadata"></video>
                    @else
                        <img src="{{ $wallpaper->github_url }}" alt="{{ $wallpaper->name }}">
                    @endif
                </div>

                <!-- Edit Form -->
                <form method="POST" action="{{ route('admin.wallpapers.update', $wallpaper->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Wallpaper Name -->
                    <div class="form-group">
                        <label class="form-label">Wallpaper Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $wallpaper->name }}" required>
                        @error('name')
                            <small style="color: #FF6B6B;">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Add a description (optional)">{{ $wallpaper->description }}</textarea>
                        @error('description')
                            <small style="color: #FF6B6B;">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Categories -->
                    <div class="form-group">
                        <label class="form-label">Assign Categories</label>
                        <div class="categories-grid">
                            @foreach($categories as $category)
                                <div style="display: flex; align-items: center;">
                                    <input 
                                        type="checkbox" 
                                        id="category_{{ $category->id }}" 
                                        name="categories[]" 
                                        value="{{ $category->id }}"
                                        class="category-checkbox"
                                        {{ in_array($category->id, $selectedCategoryIds) ? 'checked' : '' }}
                                    >
                                    <label for="category_{{ $category->id }}" class="category-label">
                                        {{ $category->icon ?? '📁' }} {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('categories')
                            <small style="color: #FF6B6B;">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="form-buttons">
                        <a href="{{ route('admin.wallpapers') }}" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
