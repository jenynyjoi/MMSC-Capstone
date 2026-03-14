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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/welcome.css', 'resources/js/welcome.js'])
    


    @stack('styles')   {{-- ← for page-specific CSS --}}
</head>

<body>
    <!-- <body class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 antialiased"> -->


    @include('partials.navbar')   {{--  navbar --}}

    @yield('content')          {{--  page content  --}}

    @include('partials.footer')   {{-- footer --}}

    @stack('scripts')  {{-- page-specific JS --}}

</body>
</html>