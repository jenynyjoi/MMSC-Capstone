<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyApp')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-gray-50">

    @include('partials.navbar')

    @include('partials.header')

    <main class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full">
        @yield('content')       {{-- ← your page content goes here --}}
    </main>

    @include('partials.footer')

</body>
</html>