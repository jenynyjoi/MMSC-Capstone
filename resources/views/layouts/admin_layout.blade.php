<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title')</title>

    @vite(['resources/css/app.css','resources/js/dashboard.js'])

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        .sidebar-transition {
            transition: width 0.3s ease, transform 0.3s ease;
        }
        .sidebar-icon-only {
            width: 80px !important;
        }
        .sidebar-icon-only .nav-text,
        .sidebar-icon-only #logo-text {
            display: none;
        }
        .sidebar-icon-only .px-6 {
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }
        .sidebar-icon-only nav {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        .sidebar-icon-only .group {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
    
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        .animate-slideIn {
            animation: slideIn 0.35s ease forwards;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" onclick="toggleSidebar()"
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden transition-opacity opacity-0">
        </div>

        {{-- ── Sidebar ── --}}
        @include('partials.admin.admin_sidebar')

        {{-- ── Right Side: Header + Scrollable Content ── --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- Top Navigation --}}
            <header class="shrink-0 sticky top-0 z-30 flex h-16 items-center justify-between
                           border-b border-white/10 px-4 lg:px-8
                           bg-[#0d4c8f] dark:bg-[#0b1224]">
                           

                <div class="flex items-center gap-4">

                    {{-- Mobile Hamburger --}}
                    <button onclick="toggleSidebar()"
                        class="flex items-center justify-center rounded-xl p-2 text-white
                               hover:bg-white/10 lg:hidden border border-white/20">
                        <iconify-icon icon="solar:hamburger-menu-linear" width="24"></iconify-icon>
                    </button>

                    {{-- Desktop Sidebar Collapse --}}
                    <button id="collapse-btn" onclick="toggleSidebarCollapse()"
                        class="hidden lg:flex items-center justify-center rounded-lg p-1.5
                               text-white hover:bg-white/10 transition-all">
                        <iconify-icon id="collapse-icon" icon="rivet-icons:menu" width="20" height="16" style="color:white"></iconify-icon>
                    </button>

                    {{-- Search Bar --}}
                    <div class="hidden md:flex items-center gap-2 rounded-lg bg-white/20
                                px-3 py-1.3 ring-1 ring-white/30 focus-within:ring-white/50">
                        <iconify-icon icon="solar:magnifer-linear" class="text-white/70"></iconify-icon>
                        <input type="text" placeholder="Search..."
                            class="w-64 bg-transparent text-sm text-white
                                   placeholder:text-white/60 focus:outline-none">
                        <div class="flex items-center gap-1 rounded border border-white/30 px-1.5 py-0.5">
                            <span class="text-xs text-white/60">⌘K</span>
                        </div>
                    </div>

                </div>

                <div class="flex items-center gap-3">

                    {{-- Theme Toggle --}}
                    <button id="theme-toggle"
                        class="flex h-9 w-9 items-center justify-center rounded-full
                               border border-white/20 text-white transition hover:bg-white/10">
                        <iconify-icon id="theme-icon" icon="solar:sun-2-linear" width="20"></iconify-icon>
                    </button>

                    {{-- Notifications --}}
                    <button class="relative flex h-9 w-9 items-center justify-center rounded-full
                                   border border-white/20 text-white transition hover:bg-white/10">
                        <iconify-icon icon="solar:bell-linear" width="20"></iconify-icon>
                        <span class="absolute right-0 top-0 mr-0.5 mt-0.5 h-2 w-2
                                     rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>

                    {{-- Profile Dropdown --}}
                    <div id="profile-dropdown" class="relative">
                        <button onclick="toggleProfileMenu(event)"
                            class="flex items-center gap-2 rounded-full px-2 py-1
                                   hover:bg-white/10 transition">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=010694&color=fff"
                                alt="User" class="h-8 w-8 rounded-full">
                            <span class="hidden sm:inline text-sm font-medium text-white">
                                Hello, {{ explode(' ', auth()->user()->name ?? 'Admin')[0] }}
                            </span>
                            <iconify-icon icon="solar:chevron-down-linear" width="18" class="text-white"></iconify-icon>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div id="profile-menu"
                            class="absolute right-0 top-full mt-1 w-48 rounded-lg shadow-lg
                                   bg-white dark:bg-[#0a3a6e] hidden z-50">

                            {{-- User Info --}}
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-white/20">
                                <p class="text-sm font-semibold text-slate-700 dark:text-white truncate">
                                    {{ auth()->user()->name ?? 'Admin' }}
                                </p>
                                <p class="text-xs text-slate-400 truncate">
                                    {{ auth()->user()->email ?? '' }}
                                </p>
                            </div>

                            <a href="#"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700
                                      dark:text-white hover:bg-slate-100 dark:hover:bg-white/10">
                                <iconify-icon icon="solar:user-linear" width="16"></iconify-icon>
                                Profile
                            </a>
                            <a href="{{ route('admin.settings.general') }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700
                                      dark:text-white hover:bg-slate-100 dark:hover:bg-white/10">
                                <iconify-icon icon="solar:settings-linear" width="16"></iconify-icon>
                                Settings
                            </a>

                            <div class="border-t border-slate-200 dark:border-white/20 my-1"></div>

                            {{-- ✅ Logout — POST form (required by Laravel) --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-2 px-4 py-2 text-sm
                                           text-red-500 font-medium hover:bg-slate-100
                                           dark:hover:bg-white/10 text-left">
                                    <iconify-icon icon="solar:logout-2-linear" width="16"></iconify-icon>
                                    Logout
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </header>
            {{-- end header --}}

            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>

        </div>
        {{-- end right side --}}

    </div>
    {{-- end flex h-screen --}}

    @stack('scripts')

    {{-- ── Toast Notifications ── --}}
    @if (session('success') || session('error') || session('warning'))
    <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-3">

        @if (session('success'))
        <div id="toast-success"
             class="flex items-center gap-3 rounded-xl border border-green-200 bg-white px-4 py-3 shadow-lg min-w-[280px] max-w-sm animate-slideIn">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100">
                <iconify-icon icon="solar:check-circle-bold" width="20" class="text-green-600"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-slate-800">Success</p>
                <p class="text-xs text-slate-500">{{ session('success') }}</p>
            </div>
            <button onclick="dismissToast('toast-success')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>
        @endif

        @if (session('error'))
        <div id="toast-error"
             class="flex items-center gap-3 rounded-xl border border-red-200 bg-white px-4 py-3 shadow-lg min-w-[280px] max-w-sm animate-slideIn">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100">
                <iconify-icon icon="solar:close-circle-bold" width="20" class="text-red-600"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-slate-800">Error</p>
                <p class="text-xs text-slate-500">{{ session('error') }}</p>
            </div>
            <button onclick="dismissToast('toast-error')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>
        @endif

        @if (session('warning'))
        <div id="toast-warning"
             class="flex items-center gap-3 rounded-xl border border-yellow-200 bg-white px-4 py-3 shadow-lg min-w-[280px] max-w-sm animate-slideIn">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-yellow-100">
                <iconify-icon icon="solar:danger-triangle-bold" width="20" class="text-yellow-600"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-slate-800">Warning</p>
                <p class="text-xs text-slate-500">{{ session('warning') }}</p>
            </div>
            <button onclick="dismissToast('toast-warning')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>
        @endif

    </div>
    @endif

    <script>
        // ── Profile Dropdown Toggle ──
        function toggleProfileMenu(e) {
            e.stopPropagation();
            document.getElementById('profile-menu').classList.toggle('hidden');
        }
        document.addEventListener('click', () => {
            document.getElementById('profile-menu')?.classList.add('hidden');
        });

        // ── Toast Auto-dismiss after 4 seconds ──
        document.addEventListener('DOMContentLoaded', () => {
            ['toast-success', 'toast-error', 'toast-warning'].forEach(id => {
                const el = document.getElementById(id);
                if (el) setTimeout(() => dismissToast(id), 4000);
            });
        });

        function dismissToast(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            el.style.opacity    = '0';
            el.style.transform  = 'translateX(40px)';
            setTimeout(() => el.remove(), 300);
        }
    </script>

</body>
</html>