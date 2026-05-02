<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - @yield('title')</title>

    @vite(['resources/css/app.css','resources/js/app.js','resources/js/dashboard.js'])

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
        @include('admin.partials.admin_sidebar')

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
                    <div class="hidden md:block relative" id="global-search-wrap"
                         x-data="globalSearch()" @keydown.window.escape="close()">

                        {{-- Input --}}
                        <div class="flex items-center gap-2 rounded-lg bg-white/20 px-3 py-1
                                    ring-1 ring-white/30 focus-within:ring-white/60 transition-all"
                             :class="open ? 'ring-white/60' : ''">
                            <iconify-icon icon="solar:magnifer-linear" class="text-white/70 shrink-0"></iconify-icon>
                            <input id="global-search-input"
                                   type="text"
                                   placeholder="Search students, teachers, pages…"
                                   x-model="query"
                                   @input.debounce.250ms="fetch()"
                                   @focus="if(query.length) open = true; else showDefaults()"
                                   @keydown.arrow-down.prevent="moveDown()"
                                   @keydown.arrow-up.prevent="moveUp()"
                                   @keydown.enter.prevent="selectActive()"
                                   autocomplete="off"
                                   class="w-64 bg-transparent text-sm text-white placeholder:text-white/60 focus:outline-none">
                            <kbd class="hidden lg:flex items-center gap-0.5 rounded border border-white/30 px-1.5 py-0.5 text-[10px] text-white/50 font-mono select-none">⌘K</kbd>
                        </div>

                        {{-- Dropdown --}}
                        <div x-show="open" @click.outside="close()"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-1"
                             class="absolute left-0 top-full mt-2 w-[420px] rounded-xl border border-slate-200
                                    bg-white dark:bg-[#0d1a2e] dark:border-slate-700
                                    shadow-2xl overflow-hidden z-[9999]"
                             style="display:none">

                            {{-- Loading --}}
                            <div x-show="loading" class="flex items-center gap-2 px-4 py-3 text-xs text-slate-400">
                                <iconify-icon icon="svg-spinners:ring-resize" width="14"></iconify-icon>
                                Searching…
                            </div>

                            {{-- No results --}}
                            <div x-show="!loading && empty && query.length > 0"
                                 class="px-4 py-6 text-center text-sm text-slate-400 dark:text-slate-500">
                                <iconify-icon icon="solar:magnifer-linear" width="24" class="mx-auto mb-2 opacity-30"></iconify-icon>
                                No results for "<span x-text="query" class="font-medium text-slate-500"></span>"
                            </div>

                            {{-- Results --}}
                            <div x-show="!loading && !empty" class="divide-y divide-slate-100 dark:divide-slate-700/60 max-h-[420px] overflow-y-auto">

                                {{-- Students --}}
                                <template x-if="results.students && results.students.length">
                                    <div>
                                        <p class="px-4 pt-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Students</p>
                                        <template x-for="(item, i) in results.students" :key="'s'+i">
                                            <a :href="item.href"
                                               :data-idx="flatIndex('student', i)"
                                               :class="activeIdx === flatIndex('student', i) ? 'bg-blue-50 dark:bg-blue-900/20' : 'hover:bg-slate-50 dark:hover:bg-white/5'"
                                               class="flex items-center gap-3 px-4 py-2.5 cursor-pointer transition-colors">
                                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0d4c8f]/10 dark:bg-blue-900/30">
                                                    <iconify-icon icon="solar:user-bold" width="14" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate" x-text="item.name"></p>
                                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate" x-text="item.meta"></p>
                                                </div>
                                                <iconify-icon icon="solar:arrow-right-linear" width="13" class="text-slate-300 dark:text-slate-600 shrink-0"></iconify-icon>
                                            </a>
                                        </template>
                                    </div>
                                </template>

                                {{-- Teachers --}}
                                <template x-if="results.teachers && results.teachers.length">
                                    <div>
                                        <p class="px-4 pt-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Teachers</p>
                                        <template x-for="(item, i) in results.teachers" :key="'t'+i">
                                            <a :href="item.href"
                                               :data-idx="flatIndex('teacher', i)"
                                               :class="activeIdx === flatIndex('teacher', i) ? 'bg-blue-50 dark:bg-blue-900/20' : 'hover:bg-slate-50 dark:hover:bg-white/5'"
                                               class="flex items-center gap-3 px-4 py-2.5 cursor-pointer transition-colors">
                                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-900/30">
                                                    <iconify-icon icon="solar:user-id-bold" width="14" class="text-emerald-600 dark:text-emerald-400"></iconify-icon>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate" x-text="item.name"></p>
                                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate" x-text="item.meta"></p>
                                                </div>
                                                <iconify-icon icon="solar:arrow-right-linear" width="13" class="text-slate-300 dark:text-slate-600 shrink-0"></iconify-icon>
                                            </a>
                                        </template>
                                    </div>
                                </template>

                                {{-- Pages --}}
                                <template x-if="results.pages && results.pages.length">
                                    <div>
                                        <p class="px-4 pt-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Pages</p>
                                        <template x-for="(item, i) in results.pages" :key="'p'+i">
                                            <a :href="item.href"
                                               :data-idx="flatIndex('page', i)"
                                               :class="activeIdx === flatIndex('page', i) ? 'bg-blue-50 dark:bg-blue-900/20' : 'hover:bg-slate-50 dark:hover:bg-white/5'"
                                               class="flex items-center gap-3 px-4 py-2.5 cursor-pointer transition-colors">
                                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-violet-50 dark:bg-violet-900/30">
                                                    <iconify-icon icon="solar:widget-4-bold" width="14" class="text-violet-500 dark:text-violet-400"></iconify-icon>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100" x-text="item.label"></p>
                                                    <p class="text-[11px] text-slate-400 dark:text-slate-500" x-text="item.parent ?? 'Main Menu'"></p>
                                                </div>
                                                <iconify-icon icon="solar:arrow-right-linear" width="13" class="text-slate-300 dark:text-slate-600 shrink-0"></iconify-icon>
                                            </a>
                                        </template>
                                    </div>
                                </template>

                            </div>

                            {{-- Footer hint --}}
                            <div x-show="!loading && !empty"
                                 class="flex items-center gap-3 border-t border-slate-100 dark:border-slate-700/60 bg-slate-50 dark:bg-white/[0.03] px-4 py-2">
                                <span class="text-[10px] text-slate-400 flex items-center gap-1">
                                    <kbd class="rounded border border-slate-200 dark:border-slate-700 px-1 py-0.5 font-mono">↑↓</kbd> navigate
                                </span>
                                <span class="text-[10px] text-slate-400 flex items-center gap-1">
                                    <kbd class="rounded border border-slate-200 dark:border-slate-700 px-1 py-0.5 font-mono">↵</kbd> open
                                </span>
                                <span class="text-[10px] text-slate-400 flex items-center gap-1">
                                    <kbd class="rounded border border-slate-200 dark:border-slate-700 px-1 py-0.5 font-mono">Esc</kbd> close
                                </span>
                            </div>

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
                            class="flex items-center gap-2 rounded-full px-2 py-1 hover:bg-white/10 transition">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                    alt="User" class="h-8 w-8 rounded-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=010694&color=fff"
                                    alt="User" class="h-8 w-8 rounded-full">
                            @endif
                            <span class="hidden sm:flex items-center text-sm font-medium text-white gap-1">
                                Hello, {{ explode(' ', auth()->user()->name ?? 'Admin')[0] }}
                                <iconify-icon 
                                    icon="ri:arrow-drop-down-line" 
                                    width="20" 
                                    height="20" 
                                    class="text-white">
                                </iconify-icon>     
                            </span>
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

                            <a href="{{ route('admin.settings.account') }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700
                                      dark:text-white hover:bg-slate-100 dark:hover:bg-white/10">
                                <iconify-icon icon="solar:user-linear" width="16"></iconify-icon>
                                Profile
                            </a>
                            <a href="{{ route('admin.settings.account') }}"
                               class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700
                                      dark:text-white hover:bg-slate-100 dark:hover:bg-white/10">
                                <iconify-icon icon="solar:settings-linear" width="16"></iconify-icon>
                                Settings
                            </a>

                            <div class="border-t border-slate-200 dark:border-white/20 my-1"></div>

                            {{--  Logout — POST form (required by Laravel) --}}
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
        // ── Global Search ──────────────────────────────────────────────
        function globalSearch() {
            return {
                query:     '',
                open:      false,
                loading:   false,
                results:   { students: [], teachers: [], pages: [] },
                activeIdx: -1,
                _timer:    null,

                get empty() {
                    return !this.results.students?.length &&
                           !this.results.teachers?.length &&
                           !this.results.pages?.length;
                },

                // Total flat list length for keyboard nav
                get total() {
                    return (this.results.students?.length ?? 0) +
                           (this.results.teachers?.length ?? 0) +
                           (this.results.pages?.length ?? 0);
                },

                // Convert section + index → flat index
                flatIndex(section, i) {
                    const sl = this.results.students?.length ?? 0;
                    const tl = this.results.teachers?.length ?? 0;
                    if (section === 'student') return i;
                    if (section === 'teacher') return sl + i;
                    if (section === 'page')    return sl + tl + i;
                    return -1;
                },

                // Get href of currently active item
                activeHref() {
                    const sl = this.results.students?.length ?? 0;
                    const tl = this.results.teachers?.length ?? 0;
                    if (this.activeIdx < sl)
                        return this.results.students[this.activeIdx]?.href;
                    if (this.activeIdx < sl + tl)
                        return this.results.teachers[this.activeIdx - sl]?.href;
                    return this.results.pages[this.activeIdx - sl - tl]?.href;
                },

                async fetch() {
                    if (this.query.trim().length === 0) {
                        this.results = { students: [], teachers: [], pages: [] };
                        this.open = false;
                        return;
                    }
                    this.loading = true;
                    this.open    = true;
                    this.activeIdx = -1;
                    try {
                        const res = await fetch(
                            `/admin/search?q=${encodeURIComponent(this.query.trim())}`,
                            { headers: { 'X-Requested-With': 'XMLHttpRequest' } }
                        );
                        this.results = await res.json();
                    } catch (e) {
                        this.results = { students: [], teachers: [], pages: [] };
                    } finally {
                        this.loading = false;
                    }
                },

                showDefaults() {
                    // on focus with empty query, show nothing — could show recent here later
                    this.open = false;
                },

                close() {
                    this.open = false;
                    this.activeIdx = -1;
                },

                moveDown() {
                    if (!this.open) return;
                    this.activeIdx = this.activeIdx < this.total - 1 ? this.activeIdx + 1 : 0;
                },

                moveUp() {
                    if (!this.open) return;
                    this.activeIdx = this.activeIdx > 0 ? this.activeIdx - 1 : this.total - 1;
                },

                selectActive() {
                    if (this.activeIdx >= 0) {
                        const href = this.activeHref();
                        if (href) window.location.href = href;
                    }
                },
            };
        }

        // ⌘K / Ctrl+K shortcut — focuses the search input
        document.addEventListener('keydown', e => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('global-search-input')?.focus();
            }
        });

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