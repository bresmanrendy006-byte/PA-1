<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kedanku Coffee')</title>

    <!-- Fonts - Google Fonts lebih stabil -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Plugins -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Custom CSS Global -->
    <link rel="stylesheet" href="{{ asset('plugins/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/css/footer.css') }}">
    
    <!-- Page Specific CSS -->
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    @include('guest.navbar')

    <!-- Main Content -->
    <main>
    
    @yield('content')
    </main>

    @include('guest.footer')
    <!-- Scripts Global -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('plugins/js/navbar.js') }}"></script>
    
    <!-- Page Specific Scripts -->
     <script src="{{ asset('plugins/js/popuphapus.js') }}"></script>
    @stack('scripts')
</body>
</html>