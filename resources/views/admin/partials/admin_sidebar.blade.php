<!-- Sidebar -->
<aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 w-64 -translate-x-full bg-white lg:static lg:translate-x-0 flex flex-col justify-between dark:bg-[#0b1224]">

    <div class="flex flex-col h-full">

        <!-- ── Logo ── -->
        <div class="flex h-16 items-center justify-between px-6 border-b border-white/10 bg-[#0d4c8f] dark:bg-[#0b1224]">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/messiah-logo.png') }}" class="h-10 w-10 rounded-full object-cover">
                <div id="logo-text" class="flex flex-col leading-tight">
                    <span class="text-xl font-bold text-white tracking-tight font-poppins">MMSC</span>
                    <span class="text-xs text-white/70 font-medium">Admin</span>
                </div>
            </div>
        </div>

        <!-- ── Nav ── -->
        <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-4 space-y-0.5"
             x-data="sidebarNav()"
             x-on:sidebar-collapsed.window="openItem = null">

            @php
                $navItems = [
                    [
                        'label' => 'Dashboard',
                        'icon'  => 'boxicons:dashboard-filled',
                        'href'  => route('admin.dashboard'),
                        'route' => 'admin.dashboard',
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Admission',
                        'icon'  => 'material-symbols:other-admission',
                        'href'  => route('admin.admission'),
                        'route' => 'admin.admission',
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Enrollment',
                        'icon'  => 'lets-icons:user-fill-add',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'Enroll Student',  'href' => route('admin.enrollment.enroll'),  'route' => 'admin.enrollment.enroll'],
                            ['label' => 'Promote Student', 'href' => route('admin.enrollment.promote'), 'route' => 'admin.enrollment.promote'],
                        ],
                    ],
                    [
                        'label' => 'Student Records',
                        'icon'  => 'ph:folders-fill',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'Student List',       'href' => route('admin.student-records.list'),       'route' => 'admin.student-records.list'],
                            ['label' => 'Withdrawn Students', 'href' => route('admin.student-records.withdrawn'),  'route' => 'admin.student-records.withdrawn'],
                            ['label' => 'Behavioral Records', 'href' => route('admin.student-records.behavioral'), 'route' => 'admin.student-records.behavioral'],
                            ['label' => 'Student Archives',   'href' => route('admin.student-records.archives'),   'route' => 'admin.student-records.archives'],
                        ],
                    ],
                    [
                        'label' => 'Clearance',
                        'icon'  => 'tdesign:task-checked-filled',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'Academic Standing', 'href' => route('admin.clearance.academic-standing'), 'route' => 'admin.clearance.academic-standing'],
                            ['label' => 'Finance',           'href' => route('admin.clearance.finance'),           'route' => 'admin.clearance.finance'],
                            ['label' => 'Library',           'href' => route('admin.clearance.library'),           'route' => 'admin.clearance.library'],
                            ['label' => 'Records',           'href' => route('admin.clearance.records'),           'route' => 'admin.clearance.records'],
                            ['label' => 'Behavioral',        'href' => route('admin.clearance.behavioral'),        'route' => 'admin.clearance.behavioral'],
                            ['label' => 'Property',          'href' => route('admin.clearance.property'),          'route' => 'admin.clearance.property'],
                            ['label' => 'Summary',           'href' => route('admin.clearance.summary'),           'route' => 'admin.clearance.summary'],
                        ],
                    ],
                    [
                        'label' => 'Academics',
                        'icon'  => 'mdi:books',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'Attendance', 'href' => route('admin.academic.attendance'), 'route' => 'admin.academic.attendance'],
                            ['label' => 'Subjects',   'href' => route('admin.academic.subjects'),   'route' => 'admin.academic.subjects'],
                            ['label' => 'Grades',     'href' => route('admin.academic.grades'),     'route' => 'admin.academic.grades'],
                        ],
                    ],
                    [
                        'label' => 'Classes',
                        'icon'  => 'icon-park-solid:bell-ring',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'Class List',          'href' => route('admin.classes.list'),       'route' => 'admin.classes.list'],
                            ['label' => 'Class Rosters',       'href' => route('admin.classes.rosters'),    'route' => 'admin.classes.rosters'],
                            ['label' => 'Classrooms',          'href' => route('admin.classes.classrooms'), 'route' => 'admin.classes.classrooms'],
                            ['label' => 'Section Management',  'href' => route('admin.classes.sections'),   'route' => 'admin.classes.sections'],
                        ],
                    ],
                    [
                        'label' => 'Schedule',
                        'icon'  => 'uis:schedule',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'Class',   'href' => route('admin.schedule.class'),   'route' => 'admin.schedule.class'],
                            ['label' => 'Teacher', 'href' => route('admin.schedule.teacher'), 'route' => 'admin.schedule.teacher'],
                            ['label' => 'Room',    'href' => route('admin.schedule.room'),    'route' => 'admin.schedule.room'],
                        ],
                    ],
                    [
                        'label' => 'Teachers',
                        'icon'  => 'fa6-solid:user-tie',
                        'href'  => route('admin.teachers'),
                        'route' => 'admin.teachers',
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Announcements',
                        'icon'  => 'mdi:announcement',
                        'href'  => route('admin.announcements'),
                        'route' => 'admin.announcements',
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Reports',
                        'icon'  => 'mage:file-2-fill',
                        'href'  => '#',
                        'sub'   => [
                            // Student
                            ['label' => 'Student Profile',      'href' => route('admin.reports.student-profile'),    'route' => 'admin.reports.student-profile',    'group' => 'Student Reports'],
                            ['label' => 'Student List',         'href' => route('admin.reports.student-list'),       'route' => 'admin.reports.student-list'],
                            ['label' => 'Enrollment Summary',   'href' => route('admin.reports.enrollment-summary'), 'route' => 'admin.reports.enrollment-summary'],
                            ['label' => 'Graduation List',      'href' => route('admin.reports.graduation-list'),    'route' => 'admin.reports.graduation-list'],
                            ['label' => 'Report Card',          'href' => route('admin.reports.report-card'),        'route' => 'admin.reports.report-card'],
                            // Attendance
                            ['label' => 'Daily Attendance',     'href' => route('admin.reports.daily-attendance'),   'route' => 'admin.reports.daily-attendance',   'group' => 'Attendance'],
                            ['label' => 'Attendance Record',    'href' => route('admin.reports.attendance-record'),  'route' => 'admin.reports.attendance-record'],
                            // Academic
                            ['label' => 'Class Record',         'href' => route('admin.reports.class-record'),       'route' => 'admin.reports.class-record',        'group' => 'Academic'],
                            ['label' => 'Class Roster',         'href' => route('admin.reports.class-roster'),       'route' => 'admin.reports.class-roster'],
                            ['label' => 'Honor Roll',           'href' => route('admin.reports.honor-roll'),         'route' => 'admin.reports.honor-roll'],
                            // Clearance
                            ['label' => 'Clearance Summary',    'href' => route('admin.reports.clearance-summary'),  'route' => 'admin.reports.clearance-summary',   'group' => 'Clearance'],
                            ['label' => 'Records Clearance',    'href' => route('admin.reports.records-clearance'),  'route' => 'admin.reports.records-clearance'],
                            ['label' => 'Library Clearance',    'href' => route('admin.reports.library-clearance'),  'route' => 'admin.reports.library-clearance'],
                            ['label' => 'Financial Clearance',  'href' => route('admin.reports.financial-clearance'),'route' => 'admin.reports.financial-clearance'],
                            // Schedule
                            ['label' => 'Class Schedule',       'href' => route('admin.reports.class-schedule'),     'route' => 'admin.reports.class-schedule',      'group' => 'Schedule'],
                            ['label' => 'Teacher Schedule',     'href' => route('admin.reports.teacher-schedule'),   'route' => 'admin.reports.teacher-schedule'],
                            ['label' => 'Room Schedule',        'href' => route('admin.reports.room-schedule'),      'route' => 'admin.reports.room-schedule'],
                            // Teacher
                            ['label' => 'Teacher Load',         'href' => route('admin.reports.teacher-load'),       'route' => 'admin.reports.teacher-load',        'group' => 'Teacher Reports'],
                            ['label' => 'Teacher List',         'href' => route('admin.reports.teacher-list'),       'route' => 'admin.reports.teacher-list'],
                        ],
                    ],
                    [
                        'label' => 'School Calendar',
                        'icon'  => 'solar:calendar-bold',
                        'href'  => route('admin.school-calendar.index'),
                        'route' => 'admin.school-calendar.index',
                        'sub'   => [
                            ['label' => 'Calendar',              'href' => route('admin.school-calendar.index'),    'route' => 'admin.school-calendar.index'],
                            ['label' => 'School Year Config',    'href' => route('admin.school-year-config.index'), 'route' => 'admin.school-year-config.index'],
                        ],
                    ],
                    [
                        'label' => 'Settings',
                        'icon'  => 'material-symbols:settings',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'Account',          'href' => route('admin.settings.account'),         'route' => 'admin.settings.account'],
                            ['label' => 'User Management',  'href' => route('admin.settings.user-management'), 'route' => 'admin.settings.user-management'],
                            ['label' => 'General Settings', 'href' => route('admin.settings.general'),         'route' => 'admin.settings.general'],
                        ],
                    ],
                ];
            @endphp

            @foreach ($navItems as $item)

                @if (count($item['sub']) > 0)

                    @php
                        $isSubActive = false;
                        foreach ($item['sub'] as $sub) {
                            if (isset($sub['route']) && request()->routeIs($sub['route'])) {
                                $isSubActive = true;
                                break;
                            }
                        }
                    @endphp

                    <div>

                        <button
                            @click="toggle('{{ $item['label'] }}')"
                            :class="isOpen('{{ $item['label'] }}')
                                ? 'bg-blue-50 text-[#0d4c8f] dark:bg-[#0d4c8f]/10 dark:text-blue-300'
                                : 'text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300'"
                            class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5
                                   text-sm font-medium transition-all duration-200"
                            title="{{ $item['label'] }}">

                            <iconify-icon icon="{{ $item['icon'] }}" width="20" class="shrink-0"
                                :class="isOpen('{{ $item['label'] }}') ? 'text-[#0d4c8f] dark:text-blue-300' : 'text-slate-400 group-hover:text-[#0d4c8f] dark:group-hover:text-blue-300'">
                            </iconify-icon>

                            <span class="nav-text flex-1 text-left">{{ $item['label'] }}</span>

                            <iconify-icon icon="solar:alt-arrow-down-linear" width="16"
                                class="nav-text shrink-0 transition-transform duration-300"
                                :class="isOpen('{{ $item['label'] }}') ? 'rotate-180 text-[#0d4c8f] dark:text-blue-300' : 'text-slate-400 group-hover:text-[#0d4c8f] dark:group-hover:text-blue-300'">
                            </iconify-icon>
                        </button>

                        <div x-show="isOpen('{{ $item['label'] }}')"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-slate-700 space-y-0.5 py-1">

                            @php $prevGroup = null; @endphp
                            @foreach ($item['sub'] as $sub)
                                @php
                                    $subActive = isset($sub['route']) && request()->routeIs($sub['route']);
                                    $currentGroup = $sub['group'] ?? null;
                                @endphp

                                {{-- Group label divider --}}
                                @if ($currentGroup && $currentGroup !== $prevGroup)
                                    <p class="px-3 pt-3 pb-0.5 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide border-t border-slate-100 dark:border-slate-700/60 mt-1">
                                        {{ $currentGroup }}
                                    </p>
                                    @php $prevGroup = $currentGroup; @endphp
                                @endif

                                <a href="{{ $sub['href'] }}"
                                   class="flex items-center gap-2 px-3 py-2 -ml-[13px] pl-[11px] border-l-2
                                          text-xs font-medium transition-all duration-150
                                          {{ $subActive
                                              ? 'border-[#0d4c8f] text-[#0d4c8f] dark:border-blue-400 dark:text-blue-300'
                                              : 'border-transparent text-slate-500 dark:text-slate-400 hover:border-[#0d4c8f] hover:text-[#0d4c8f] dark:hover:border-blue-400 dark:hover:text-blue-300' }}">
                                    {{ $sub['label'] }}
                                </a>
                            @endforeach
                        </div>

                    </div><!-- /.dropdown-item -->

                @else

                    @php $isActive = isset($item['route']) && request()->routeIs($item['route']); @endphp
                    <a href="{{ $item['href'] }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2.5
                              text-sm font-medium transition-all duration-200
                              {{ $isActive
                                  ? 'bg-blue-50 text-[#0d4c8f] dark:bg-[#0d4c8f]/10 dark:text-blue-300'
                                  : 'text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300' }}"
                       title="{{ $item['label'] }}">

                        <iconify-icon icon="{{ $item['icon'] }}" width="20"
                            class="shrink-0 {{ $isActive ? 'text-[#0d4c8f] dark:text-blue-300' : 'text-slate-400 group-hover:text-[#0d4c8f] dark:group-hover:text-blue-300' }}">
                        </iconify-icon>

                        <span class="nav-text">{{ $item['label'] }}</span>
                    </a>

                @endif

            @endforeach

        </nav>

    </div>

</aside>

{{-- ── Alpine.js ── --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
@php
    // Determine which item is active on page load
    $activeLabel = null;
    foreach ($navItems as $item) {
        if (!empty($item['sub'])) {
            foreach ($item['sub'] as $sub) {
                if (isset($sub['route']) && request()->routeIs($sub['route'])) {
                    $activeLabel = $item['label'];
                    break 2;
                }
            }
        }
    }
@endphp

function sidebarNav() {
    return {
        openItem: @json($activeLabel),
        isOpen(label)  { return this.openItem === label; },
        toggle(label)  { this.openItem = this.openItem === label ? null : label; },
    };
}
</script>