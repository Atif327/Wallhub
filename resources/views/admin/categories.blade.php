<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}" sizes="32x32">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

        /* Categories Table */
        .categories-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .categories-header h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: #FFC300;
        }

        .add-category-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            color: #0d0d0d;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .add-category-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 195, 0, 0.4);
        }

        .categories-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(30, 34, 43, 0.8);
            border: 1px solid rgba(255, 195, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .categories-table thead {
            background: rgba(255, 195, 0, 0.05);
            border-bottom: 1px solid rgba(255, 195, 0, 0.1);
        }

        .categories-table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #FFC300;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .categories-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 195, 0, 0.05);
            font-size: 14px;
        }

        .categories-table tbody tr:hover {
            background: rgba(255, 195, 0, 0.05);
        }

        .category-name {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .category-icon {
            font-size: 18px;
        }

        .category-actions {
            display: flex;
            gap: 10px;
        }

        .edit-btn,
        .delete-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .edit-btn {
            background: rgba(100, 200, 255, 0.2);
            color: #64C8FF;
            border: 1px solid rgba(100, 200, 255, 0.3);
        }

        .edit-btn:hover {
            background: rgba(100, 200, 255, 0.3);
            transform: translateY(-2px);
        }

        .delete-btn {
            background: rgba(255, 107, 107, 0.2);
            color: #FF6B6B;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }

        .delete-btn:hover {
            background: rgba(255, 107, 107, 0.3);
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 48px;
            color: #A3A3A3;
            margin-bottom: 15px;
        }

        .empty-state-text {
            color: #A3A3A3;
            margin-bottom: 20px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: slideInDown 0.3s ease-out;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: #4CAF50;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                position: static;
                height: auto;
                border-right: none;
                border-bottom: 1px solid rgba(255, 195, 0, 0.1);
            }

            .admin-main {
                margin-left: 0;
            }

            .categories-header {
                flex-direction: column;
                gap: 15px;
            }

            .categories-table {
                font-size: 12px;
            }

            .categories-table th,
            .categories-table td {
                padding: 10px;
            }

            .category-actions {
                flex-direction: column;
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
                <a href="{{ route('admin.categories') }}" class="active">
                    <i class="fas fa-folder-open"></i> Categories
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
                <h1>Categories</h1>
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
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="categories-header">
                    <h2>Manage Categories</h2>
                    <a href="{{ route('admin.categories.create') }}" class="add-category-btn" style="text-decoration: none; display: inline-block;">
                        <i class="fas fa-plus"></i> Add Category
                    </a>
                </div>

                @if ($categories->count() > 0)
                    <table class="categories-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Wallpapers</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                @if($category->parent_id === null)
                                    <tr>
                                        <td>
                                            <div class="category-name" style="display: flex; align-items: center; gap: 8px;">
                                                @if($category->children && $category->children->count() > 0)
                                                    <button type="button" onclick="toggleSubcategories({{ $category->id }})" style="background: none; border: none; cursor: pointer; color: #FFC300; font-size: 14px; padding: 0;" title="Expand/Collapse">
                                                        <i class="fas fa-chevron-right" id="toggle-icon-{{ $category->id }}"></i>
                                                    </button>
                                                @endif
                                                <span class="category-icon">{{ $category->icon ?? '📁' }}</span>
                                                <strong>{{ $category->name }}</strong>
                                                @if($category->children && $category->children->count() > 0)
                                                    <span style="font-size: 11px; color: #888;">({{ $category->children->count() }} subs)</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span style="background: rgba(255, 195, 0, 0.1); color: #FFC300; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                                                Parent
                                            </span>
                                        </td>
                                        <td style="max-width: 300px; color: #A3A3A3; font-size: 13px;">
                                            {{ substr($category->description ?? 'No description', 0, 50) }}{{ strlen($category->description ?? '') > 50 ? '...' : '' }}
                                        </td>
                                        <td>
                                            <span style="background: rgba(255, 195, 0, 0.1); color: #FFC300; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                                {{ $category->total_wallpapers_count ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="category-actions">
                                                <a href="{{ route('admin.categories.edit', $category) }}" class="edit-btn">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.categories.delete', $category) }}" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-btn">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Subcategories -->
                                    @if($category->children && $category->children->count() > 0)
                                        @foreach($category->children as $subcategory)
                                            <tr class="subcategory-{{ $category->id }}" style="background: rgba(255, 255, 255, 0.02); display: none;">
                                                <td>
                                                    <div class="category-name" style="padding-left: 30px;">
                                                        <span class="category-icon">{{ $subcategory->icon ?? '📌' }}</span>
                                                        {{ $subcategory->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span style="background: rgba(100, 200, 255, 0.1); color: #64C8FF; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                                                        Sub
                                                    </span>
                                                </td>
                                                <td style="max-width: 300px; color: #A3A3A3; font-size: 13px;">
                                                    {{ substr($subcategory->description ?? 'No description', 0, 50) }}{{ strlen($subcategory->description ?? '') > 50 ? '...' : '' }}
                                                </td>
                                                <td>
                                                    <span style="background: rgba(100, 200, 255, 0.1); color: #64C8FF; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                                        {{ $subcategory->wallpapers_count ?? 0 }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="category-actions">
                                                        <a href="{{ route('admin.categories.edit', $subcategory) }}" class="edit-btn">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.categories.delete', $subcategory) }}" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="delete-btn">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p class="empty-state-text">No categories found. Create your first category to get started.</p>
                        <a href="{{ route('admin.categories.create') }}" class="add-category-btn" style="text-decoration: none; display: inline-block;">
                            <i class="fas fa-plus"></i> Create Category
                        </a>
                    </div>
                @endif
            </section>
        </main>
    </div>
    <script>
        function toggleSubcategories(categoryId) {
            const icon = document.getElementById('toggle-icon-' + categoryId);
            const subcategoryRows = document.querySelectorAll('.subcategory-' + categoryId);
            
            subcategoryRows.forEach(row => {
                if (row.style.display === 'none') {
                    row.style.display = 'table-row';
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-down');
                } else {
                    row.style.display = 'none';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-right');
                }
            });
        }
    </script>
</body>
</html>
