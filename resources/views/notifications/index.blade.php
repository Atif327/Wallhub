<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <x-meta-tags 
    title="Notifications" 
    description="View your notifications and updates"
  />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  @vite(['resources/css/style.css', 'resources/css/homepage.css'])
  
  <style>
    body {
      background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
      min-height: 100vh;
      padding-top: 80px;
    }
    
    .notification-card {
      background: rgba(30, 30, 30, 0.8);
      border: 1px solid rgba(255, 195, 0, 0.1);
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 15px;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .notification-card:hover {
      border-color: #FFC300;
      transform: translateX(5px);
    }
    
    .notification-card.unread {
      background: rgba(255, 195, 0, 0.05);
      border-color: rgba(255, 195, 0, 0.3);
    }
    
    .notification-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin-right: 15px;
    }
    
    .notification-icon.like {
      background: linear-gradient(135deg, #ff6b6b, #ff4757);
      color: white;
    }
    
    .notification-icon.view {
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: white;
    }
    
    .notification-time {
      color: #A3A3A3;
      font-size: 13px;
    }
  </style>
</head>
  
<body>  
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="/">
      <img src="{{ asset('images/logo.png') }}" alt="Logo" height="35" />
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/"><i class="fa-solid fa-clock me-2"></i>Latest</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('wallpapers.trending') }}"><i class="fa-solid fa-fire me-2"></i>Trending</a></li>
        <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-layer-group me-2"></i>Categories</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('wallpapers.create') }}"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Upload</a></li>
      </ul>

      @auth
        <div class="d-flex align-items-center">
          <a href="{{ route('notifications.index') }}" class="btn btn-outline-warning me-2 position-relative">
            <i class="fa-solid fa-bell"></i>
            @if(auth()->user()->unreadNotificationsCount() > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ auth()->user()->unreadNotificationsCount() }}
              </span>
            @endif
          </a>
          <a href="{{ route('user.account') }}" class="btn btn-outline-light me-2">
            <i class="fa-solid fa-user me-2"></i>{{ auth()->user()->name }}
          </a>
          <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
              <i class="fa-solid fa-right-from-bracket"></i>
            </button>
          </form>
        </div>
      @else
        <a href="{{ route('login') }}" class="btn btn-auth btn-outline-light me-2">
          <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
        </a>
        <a href="{{ route('register') }}" class="btn btn-auth btn-warning">
          <i class="fa-solid fa-user-plus me-2"></i>Create Account
        </a>
      @endauth
    </div>
  </div>
</nav>

  <div class="container" style="margin-top: 30px;">
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 style="color: #FFC300; font-weight: 700;">Notifications</h1>
          @if($notifications->where('read', false)->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-outline-warning">
                <i class="fa-solid fa-check-double me-2"></i>Mark All as Read
              </button>
            </form>
          @endif
        </div>

        @if($notifications->isEmpty())
          <div class="text-center py-5">
            <i class="fa-solid fa-bell-slash" style="font-size: 64px; color: #A3A3A3;"></i>
            <h3 class="mt-3" style="color: #EDEDED;">No notifications yet</h3>
            <p style="color: #A3A3A3;">You'll see notifications here when someone likes your wallpapers</p>
          </div>
        @else
          @foreach($notifications as $notification)
            <div class="notification-card {{ !$notification->read ? 'unread' : '' }}" 
                 onclick="markAsRead({{ $notification->id }}, '{{ route('wallpaper.show', $notification->wallpaper->slug ?? '') }}')">
              <div class="d-flex align-items-center">
                <div class="notification-icon {{ $notification->type }}">
                  @if($notification->type === 'like')
                    <i class="fa-solid fa-heart"></i>
                  @elseif($notification->type === 'view')
                    <i class="fa-solid fa-eye"></i>
                  @else
                    <i class="fa-solid fa-bell"></i>
                  @endif
                </div>
                <div class="flex-grow-1">
                  <h5 style="color: #FFC300; margin-bottom: 5px;">{{ $notification->title }}</h5>
                  <p style="color: #EDEDED; margin-bottom: 5px;">{{ $notification->message }}</p>
                  <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                @if(!$notification->read)
                  <div style="width: 10px; height: 10px; background: #FFC300; border-radius: 50%;"></div>
                @endif
              </div>
            </div>
          @endforeach

          <div class="mt-4">
            {{ $notifications->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    async function markAsRead(notificationId, redirectUrl) {
      try {
        const response = await fetch(`/notifications/${notificationId}/read`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });

        if (response.ok && redirectUrl) {
          window.location.href = redirectUrl;
        }
      } catch (error) {
        console.error('Error marking notification as read:', error);
      }
    }
  </script>
</body>
</html>
