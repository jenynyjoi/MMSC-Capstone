<!DOCTYPE html>
<html lang="en" x-data="{ 
    dark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches), 
    mobileMenu: false 
}" x-init="$watch('dark', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="dark ? 'dark' : ''">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'My Messiah School of Cavite')</title>

    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
 
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/welcome.css', 'resources/js/welcome.js'])

    @stack('styles')
</head>

<body>

    @include('partials.navbar')

    @yield('content')

    @include('partials.footer')

    @stack('scripts')  

</body>
</html>