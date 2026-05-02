@php
$r = request()->route()?->getName() ?? '';
$navLink = function(string $route, bool $exact = false) use ($r): string {
    $active = $exact ? $r === $route : str_starts_with($r, $route);
    return $active
        ? 'flex items-center gap-3 rounded-lg px-3 py-2.5 bg-blue-50 text-[#0d4c8f] dark:bg-[#0d4c8f]/15 dark:text-blue-300 text-sm font-semibold'
        : 'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all';
};
$subLink = function(string $route) use ($r): string {
    $active = str_starts_with($r, $route);
    return $active
        ? 'block px-3 py-2 text-xs font-semibold text-[#0d4c8f] dark:text-blue-300 -ml-[13px] pl-[11px] border-l-2 border-[#0d4c8f]'
        : 'block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors';
};
$classesOpen   = str_starts_with($r, 'teacher.classes');
$reportsOpen   = str_starts_with($r, 'teacher.reports');
$settingsOpen  = str_starts_with($r, 'teacher.settings');
@endphp

<aside id="teacher-sidebar"
    class="teacher-sidebar-transition fixed inset-y-0 left-0 z-50 w-64 -translate-x-full lg:static lg:translate-x-0
           flex flex-col bg-white dark:bg-dark-card border-r border-slate-100 dark:border-dark-border">

    {{-- Logo --}}
    <div class="flex h-16 items-center gap-2 px-5 bg-[#0d4c8f] dark:bg-[#091e42] shrink-0">
        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 shrink-0">
            <iconify-icon icon="solar:diploma-verified-bold" width="22" class="text-white"></iconify-icon>
        </div>
        <div class="flex flex-col leading-tight">
            <span class="text-base font-bold text-white tracking-tight">MMSC</span>
            <span class="text-[11px] text-white/70 font-medium">Teacher Portal</span>
        </div>
        <button onclick="closeTeacherSidebar()" class="ml-auto lg:hidden text-white/70 hover:text-white">
            <iconify-icon icon="solar:close-linear" width="18"></iconify-icon>
        </button>
    </div>

    {{-- Teacher Info --}}
    <div class="flex items-center gap-3 px-4 py-3.5 border-b border-slate-100 dark:border-dark-border">
        @if(auth()->user()->profile_photo)
            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                class="h-9 w-9 rounded-full object-cover shrink-0">
        @else
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 shrink-0">
                <iconify-icon icon="solar:user-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
            </div>
        @endif
        <div class="flex flex-col leading-tight overflow-hidden">
            <span class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</span>
            <span class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Teacher</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-3 space-y-0.5">

        {{-- Dashboard --}}
        <a href="{{ route('teacher.dashboard') }}" class="{{ $navLink('teacher.dashboard', true) }}">
            <iconify-icon icon="boxicons:dashboard-filled" width="18" class="shrink-0"></iconify-icon>
            Dashboard
        </a>

        {{-- My Classes --}}
        <div x-data="{ open: {{ $classesOpen ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                       {{ $classesOpen ? 'text-[#0d4c8f] dark:text-blue-300' : 'text-slate-600 dark:text-slate-300' }}
                       hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
                <iconify-icon icon="solar:book-2-bold" width="18" class="shrink-0"></iconify-icon>
                <span class="flex-1 text-left">My Classes</span>
                <iconify-icon icon="solar:alt-arrow-down-linear" width="14"
                    :class="open ? 'rotate-180' : ''"
                    class="transition-transform text-slate-400"></iconify-icon>
            </button>
            <div x-show="open" x-cloak class="mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-dark-border space-y-0.5 py-1">
                <a href="{{ route('teacher.classes.list') }}" class="{{ $subLink('teacher.classes.list') }}">Class List</a>
                <a href="{{ route('teacher.classes.roster') }}" class="{{ $subLink('teacher.classes.roster') }}">Class Rosters</a>
            </div>
        </div>

        {{-- Attendance --}}
        <a href="{{ route('teacher.attendance') }}" class="{{ $navLink('teacher.attendance', true) }}">
            <iconify-icon icon="solar:user-check-rounded-bold" width="20" class="shrink-0"></iconify-icon>
            Attendance
        </a>

        {{-- Grades --}}
        <a href="{{ route('teacher.grades') }}" class="{{ $navLink('teacher.grades', true) }}">
            <iconify-icon icon="solar:medal-ribbons-star-bold" width="18" class="shrink-0"></iconify-icon>
            Grades
        </a>

        {{-- Schedule --}}
        <a href="{{ route('teacher.schedule') }}" class="{{ $navLink('teacher.schedule', true) }}">
            <iconify-icon icon="uis:schedule" width="18" class="shrink-0"></iconify-icon>
            Schedule
        </a>

        {{-- My Assigned Subjects --}}
        <a href="{{ route('teacher.my-subjects') }}" class="{{ $navLink('teacher.my-subjects', true) }}">
            <iconify-icon icon="solar:notebook-bold" width="18" class="shrink-0"></iconify-icon>
            My Assigned Subjects
        </a>

        {{-- Announcements --}}
        <a href="{{ route('teacher.announcements') }}" class="{{ $navLink('teacher.announcements', true) }}">
            <iconify-icon icon="mdi:announcement" width="18" class="shrink-0"></iconify-icon>
            Announcements
        </a>

        {{-- Reports --}}
        <div x-data="{ open: {{ $reportsOpen ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                       text-slate-600 dark:text-slate-300
                       hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
                <iconify-icon icon="mage:file-2-fill" width="18" class="shrink-0"></iconify-icon>
                <span class="flex-1 text-left">Reports</span>
                <iconify-icon icon="solar:alt-arrow-down-linear" width="14"
                    :class="open ? 'rotate-180' : ''"
                    class="transition-transform text-slate-400"></iconify-icon>
            </button>
            <div x-show="open" x-cloak class="mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-dark-border space-y-0.5 py-1">
                <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Attendance Record</a>
                <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Class Record</a>
                <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Grade Sheet</a>
                <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Teacher Schedule</a>
            </div>
        </div>

        {{-- Settings --}}
        <div x-data="{ open: {{ $settingsOpen ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                       text-slate-600 dark:text-slate-300
                       hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
                <iconify-icon icon="material-symbols:settings" width="18" class="shrink-0"></iconify-icon>
                <span class="flex-1 text-left">Settings</span>
                <iconify-icon icon="solar:alt-arrow-down-linear" width="14"
                    :class="open ? 'rotate-180' : ''"
                    class="transition-transform text-slate-400"></iconify-icon>
            </button>
            <div x-show="open" x-cloak class="mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-dark-border space-y-0.5 py-1">
                <a href="{{ route('teacher.settings.account') }}" class="{{ $subLink('teacher.settings.account') }}">My Account</a>
            </div>
        </div>

    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-slate-100 dark:border-dark-border">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium
                       text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all">
                <iconify-icon icon="solar:logout-2-bold" width="18" class="shrink-0"></iconify-icon>
                Logout
            </button>
        </form>
    </div>

</aside>
