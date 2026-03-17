@props(['title' => config('app.name'), 'description' => 'Download free wallpapers for your desktop and mobile devices', 'image' => asset('images/default-og.jpg'), 'url' => url()->current()])

<title>{{ $title }} - {{ config('app.name') }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="wallpapers, free wallpapers, desktop wallpapers, mobile wallpapers, HD wallpapers, 4K wallpapers">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:site_name" content="{{ config('app.name') }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $url }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="{{ $image }}">

<!-- Additional SEO -->
<meta name="robots" content="index, follow">
<meta name="author" content="{{ config('app.name') }}">
<link rel="canonical" href="{{ $url }}">
