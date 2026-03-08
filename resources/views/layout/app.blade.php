<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
    @include('partials.head')
<body class="font-inter bg-gray-100 text-gray-900">

    @include('partials.header')  {{-- visible navigation bar --}}
    @yield('content')
    @include('partials.footer')

</body>
</html>