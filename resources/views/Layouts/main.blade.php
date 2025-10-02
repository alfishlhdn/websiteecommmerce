<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul')</title>
    <meta name="description"
        content="AGA IT Computer adalah toko komputer terpercaya di Singosari, Malang. Menjual laptop, PC, aksesoris, dan jasa service komputer/laptop profesional.">
    <meta name="keywords"
        content="Toko Komputer Malang, Laptop Murah Malang, Service Laptop Singosari, Rakit PC Malang, Jual SSD, Komputer Gaming, Upgrade RAM, Jasa IT Malang">
    <meta name="author" content="AGA IT COMPUTER">
    <link rel="canonical" href="https://www.agaitcomputer.com/" />

    <!-- Open Graph (Facebook, WhatsApp) -->
    <meta property="og:title" content="AGA IT COMPUTER | Toko Komputer & Service Laptop Malang">
    <meta property="og:description"
        content="Temukan produk IT terbaik & layanan service terpercaya di AGA IT Computer, Singosari - Malang.">
    <meta property="og:image" content="https://www.agaitcomputer.com/image/agaicon.jpg">
    <meta property="og:url" content="https://www.agaitcomputer.com/">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="AGA IT COMPUTER | Toko Komputer Malang">
    <meta name="twitter:description" content="Jual laptop, rakit PC, service komputer & instalasi software terpercaya.">
    <meta name="twitter:image" content="https://www.agaitcomputer.com/image/agaicon.jpg">

    <!-- Favicon -->
    <link rel="icon" href="/image/logoagaitcomputer.png" type="image/png" />
    <!-- CSS & Tailwind -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" /> --}}
    {{-- <script src="/assets/talwind.js"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">

</head>

<body class="bg-white text-black font-sans">

    @include('Layouts.header')

    @yield('content')

    @include('Layouts.footer')

    <script src="/assets/Layouts.js"></script>

</body>

</html>
