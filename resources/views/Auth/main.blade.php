<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('judul')</title>
    {{-- <script src="/assets/talwind.js"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="image/logoagaitcomputer.png" type="image/png" />
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    @yield('content')

    <script src="/assets/auth.js"></script>


</body>

</html>
