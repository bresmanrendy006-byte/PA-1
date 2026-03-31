<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>@yield('title')</title>

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

@include('admin.sidebar')

<main class="main-content">
@yield('content')
</main>

</div>

@stack('scripts')

</body>
</html>