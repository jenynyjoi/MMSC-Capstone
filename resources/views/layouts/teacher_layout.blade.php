<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teacher – @yield('title', 'Portal')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Poppins', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
        .teacher-sidebar-transition { transition: transform 0.3s ease; }
    </style>

    @stack('styles')
</head>

<body class="bg-slate-50 dark:bg-dark-bg antialiased">

<div class="flex h-screen overflow-hidden">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" onclick="closeTeacherSidebar()"
        class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden"></div>

    @include('teacher.partials.teacher_sidebar')

    <!-- Right side -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

        <!-- Topbar -->
        <header class="shrink-0 sticky top-0 z-30 flex h-16 items-center justify-between
                       border-b border-white/10 px-4 lg:px-6
                       bg-[#0d4c8f] dark:bg-[#091e42]">

            <div class="flex items-center gap-3">
                <button onclick="openTeacherSidebar()"
                    class="lg:hidden flex h-9 w-9 items-center justify-center rounded-lg text-white hover:bg-white/10">
                    <iconify-icon icon="solar:hamburger-menu-linear" width="20"></iconify-icon>
                </button>
                <div>
                    <h1 class="text-sm font-bold text-white leading-tight">@yield('page_title', 'Teacher Portal')</h1>
                    <p class="text-[11px] text-white/60 leading-tight">@yield('page_subtitle', '')</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-medium text-white">
                    <iconify-icon icon="solar:calendar-linear" width="12"></iconify-icon>
                    S.Y. {{ \App\Models\SchoolYear::activeName() }}
                </span>

                <button class="relative flex h-9 w-9 items-center justify-center rounded-lg text-white hover:bg-white/10 transition-colors">
                    <iconify-icon icon="solar:bell-linear" width="18"></iconify-icon>
                    <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-400"></span>
                </button>

                <button onclick="toggleTeacherDark()"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-white hover:bg-white/10 transition-colors">
                    <iconify-icon icon="solar:moon-stars-linear" width="18"></iconify-icon>
                </button>

                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                        alt="Avatar" class="h-9 w-9 rounded-full object-cover border-2 border-white/30 shrink-0">
                @else
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 text-white text-sm font-bold shrink-0 border-2 border-white/30">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-dark-bg">
            @yield('content')
        </main>

    </div>
</div>

<script>
function openTeacherSidebar() {
    document.getElementById('teacher-sidebar').classList.remove('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.remove('hidden');
}
function closeTeacherSidebar() {
    document.getElementById('teacher-sidebar').classList.add('-translate-x-full');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}
function toggleTeacherDark() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('teacher_theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
}
// Restore saved preference
(function() {
    const saved = localStorage.getItem('teacher_theme');
    if (saved === 'dark') document.documentElement.classList.add('dark');
    else if (saved === 'light') document.documentElement.classList.remove('dark');
})();
</script>

@stack('scripts')
</body>
</html>
