<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Users - Wallpaper Hub</title>
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

        /* Users Table */
        .users-table-container {
            background: rgba(30, 34, 43, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 195, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table thead {
            background: rgba(20, 20, 20, 0.8);
            border-bottom: 2px solid rgba(255, 195, 0, 0.1);
        }

        .users-table th {
            padding: 15px 20px;
            text-align: left;
            color: #FFC300;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .users-table tbody tr {
            border-bottom: 1px solid rgba(255, 195, 0, 0.05);
            transition: background 0.2s ease;
        }

        .users-table tbody tr:hover {
            background: rgba(255, 195, 0, 0.05);
        }

        .users-table td {
            padding: 15px 20px;
            color: #EDEDED;
            font-size: 14px;
        }

        .user-id {
            color: #A3A3A3;
            font-size: 12px;
        }

        .user-avatar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFC300 0%, #FFD700 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d0d0d;
            font-weight: 700;
            font-size: 14px;
        }

        .verified-badge {
            display: inline-block;
            padding: 4px 10px;
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .unverified-badge {
            display: inline-block;
            padding: 4px 10px;
            background: rgba(255, 193, 7, 0.1);
            color: #FFC300;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .no-users {
            text-align: center;
            padding: 60px 20px;
            color: #A3A3A3;
        }

        .no-users i {
            font-size: 48px;
            color: #FFC300;
            margin-bottom: 15px;
            opacity: 0.5;
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

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .view-btn,
        .delete-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .view-btn {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .view-btn:hover {
            background: rgba(76, 175, 80, 0.2);
            border-color: #4CAF50;
        }

        .delete-btn {
            background: rgba(255, 107, 107, 0.1);
            color: #FF6B6B;
            border: 1px solid rgba(255, 107, 107, 0.2);
        }

        .delete-btn:hover {
            background: rgba(255, 107, 107, 0.2);
            border-color: #FF6B6B;
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

            .admin-nav {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .users-table {
                font-size: 12px;
            }

            .users-table td,
            .users-table th {
                padding: 10px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .view-btn,
            .delete-btn {
                width: 100%;
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
                <a href="{{ route('admin.users') }}" class="active">
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
                <h1>Users Management</h1>
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
                <a href="{{ route('admin.dashboard') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>

                @if($users->count() > 0)
                    <div class="users-table-container">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <span class="user-id">#{{ $user->id }}</span>
                                        </td>
                                        <td>
                                            <div class="user-avatar">
                                                <div class="user-avatar-img">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $user->email }}
                                        </td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="verified-badge">
                                                    <i class="fas fa-check-circle"></i> Verified
                                                </span>
                                            @else
                                                <span class="unverified-badge">
                                                    <i class="fas fa-clock"></i> Unverified
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.user.wallpapers', $user->id) }}" class="view-btn" title="View user wallpapers">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <button class="delete-btn" onclick="showDeleteModal({{ $user->id }}, '{{ $user->name }}')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="users-table-container">
                        <div class="no-users">
                            <i class="fas fa-users"></i>
                            <p>No users registered yet</p>
                        </div>
                    </div>
                @endif
            </section>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h2>
            <p>Are you sure you want to delete user <strong id="userName"></strong>? This action will also delete all their wallpapers and cannot be undone.</p>
            <div class="modal-buttons">
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-btn-confirm">
                        <i class="fas fa-trash"></i> Delete User & Wallpapers
                    </button>
                </form>
                <button class="modal-btn-cancel" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(userId, userName) {
            document.getElementById('userName').textContent = userName;
            document.getElementById('deleteForm').action = `/admin/users/${userId}`;
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
