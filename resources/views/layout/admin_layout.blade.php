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
                           bg-[#0d4c8f]">

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
                                px-3 py-1.5 ring-1 ring-white/30 focus-within:ring-white/50">
                        <iconify-icon icon="solar:magnifer-linear" class="text-white/70"></iconify-icon>
                        <input type="text" placeholder="Search analytics..."
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
                            <img src="https://ui-avatars.com/api/?name=Jenny&background=010694&color=fff"
                                alt="User" class="h-8 w-8 rounded-full">
                            <span class="hidden sm:inline text-sm font-medium text-white">Hello, Jenny</span>
                            <iconify-icon icon="solar:chevron-down-linear" width="18" class="text-white"></iconify-icon>
                        </button>

                        <div id="profile-menu"
                            class="absolute right-0 top-full mt-1 w-48 rounded-lg shadow-lg
                                   bg-white hidden z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-slate-700
                                               hover:bg-slate-100">
                                Profile
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-700
                                               hover:bg-slate-100">
                                Settings
                            </a>
                            <div class="border-t border-slate-200 my-1"></div>
                            <a href="#" class="block px-4 py-2 text-sm text-red-500 font-medium
                                               hover:bg-slate-100">
                                Logout
                            </a>
                        </div>
                    </div>

                </div>
            </header>
            {{-- end header --}}

            {{-- ✅ This is the scrollable area — only this scrolls, not the whole page --}}
            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>

        </div>
        {{-- end right side --}}

    </div>
    {{-- end flex h-screen --}}

    @stack('scripts')
</body>
</html>