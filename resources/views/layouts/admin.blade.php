<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Kedanku Coffee')</title>

<link rel="icon" type="image/png" href="{{ asset('images/Logo_Kedanku.png') }}"><link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Sidebar CSS -->
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/admin.css') }}">
@stack('styles')

</head>

<body>

<div class="admin-layout">

    @include('partials.admin.sidebar')

    <main class="main-content">
        @yield('content')
    </main>

</div>

<!-- Global Scripts -->
<script src="{{ asset('plugins/js/popuphapus.js') }}"></script>
<script src="{{ asset('plugins/js/popup_confirm.js') }}"></script>

@stack('scripts')

</body>
</html>