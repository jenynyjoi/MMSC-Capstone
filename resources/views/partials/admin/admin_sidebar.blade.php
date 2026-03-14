<!-- Sidebar -->
<aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 w-64 -translate-x-full bg-white lg:static lg:translate-x-0 flex flex-col justify-between dark:bg-[#0b1224]">

    <div class="flex flex-col h-full">

        <!-- ── Logo ── -->
        <div class="flex h-16 items-center justify-between px-6 border-b border-white/10 bg-[#0d4c8f] dark:bg-[#0b1224]">
            <div class="flex items-center gap-2">
                <img src="{{ asset('img/download.jpg') }}" class="h-10 w-10 rounded-full object-cover">
                <div id="logo-text" class="flex flex-col leading-tight">
                    <span class="text-xl font-bold text-white tracking-tight font-poppins">MMSC</span>
                    <span class="text-xs text-white/70 font-medium">Admin</span>
                </div>
            </div>
        </div>

        <!-- ── Nav ── -->
        <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-4 space-y-0.5">

            @php
                $navItems = [
                    [
                        'label' => 'Dashboard',
                        'icon'  => 'boxicons:dashboard-filled',
                        'href'  => route('admin.dashboard'),
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Admission',
                        'icon'  => 'material-symbols:other-admission',
                        'href'  => route('admin.admission'),
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Enrollment',
                        'icon'  => 'lets-icons:user-fill-add',
                        'href'  => route('admin.enrollment'),
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Student Records',
                        'icon'  => 'ph:folders-fill',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'All Records',   'href' => route('admin.student-records.index')],
                            ['label' => 'Documents',     'href' => route('admin.student-records.documents')],
                        ],
                    ],
                    [
                        'label' => 'Clearance',
                        'icon'  => 'tdesign:task-checked-filled',
                        'href'  => route('admin.clearance'),
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Academics',
                        'icon'  => 'mdi:books',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'Subjects',      'href' => route('admin.academics.subjects')],
                            ['label' => 'Curriculum',    'href' => route('admin.academics.curriculum')],
                        ],
                    ],
                    [
                        'label' => 'Classes',
                        'icon'  => 'icon-park-solid:bell-ring',
                        'href'  => route('admin.classes'),
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Schedule',
                        'icon'  => 'uis:schedule',
                        'href'  => route('admin.schedule'),
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Teachers',
                        'icon'  => 'fa6-solid:user-tie',
                        'href'  => route('admin.teachers'),
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Announcements',
                        'icon'  => 'mdi:announcement',
                        'href'  => route('admin.announcements'),
                        'sub'   => [],
                    ],
                    [
                        'label' => 'Reports',
                        'icon'  => 'mage:file-2-fill',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'Summary',       'href' => route('admin.reports.summary')],
                            ['label' => 'Analytics',     'href' => route('admin.reports.analytics')],
                        ],
                    ],
                    [
                        'label' => 'Settings',
                        'icon'  => 'material-symbols:settings',
                        'href'  => '#',
                        'sub'   => [
                            ['label' => 'General',       'href' => route('admin.settings.general')],
                            ['label' => 'Preferences',   'href' => route('admin.settings.preferences')],
                        ],
                    ],
                ];
            @endphp

            @foreach ($navItems as $item)

                @if (count($item['sub']) > 0)

                    {{-- ── Check if any sub-route is currently active ── --}}
                    @php
                        $isSubActive = false;
                        foreach ($item['sub'] as $sub) {
                            if (request()->url() === $sub['href']) {
                                $isSubActive = true;
                                break;
                            }
                        }
                    @endphp

                    {{-- ── Nav Item WITH Dropdown ──
                         open starts true only if a sub-page is currently active
                         NO mouseenter/mouseleave — click only
                    ── --}}
                    <div x-data="{ open: {{ $isSubActive ? 'true' : 'false' }} }">

                        {{-- Parent Button --}}
                        <button
                            @click="open = !open"
                            :class="open
                                ? 'bg-blue-50 dark:bg-slate-800 text-[#0d4c8f] dark:text-white'
                                : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white'"
                            class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5
                                   text-sm font-medium transition-all duration-200"
                            title="{{ $item['label'] }}">

                            <iconify-icon
                                icon="{{ $item['icon'] }}"
                                width="20"
                                class="shrink-0"
                                :class="open ? 'text-[#0d4c8f] dark:text-blue-400' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white'">
                            </iconify-icon>

                            <span class="nav-text flex-1 text-left">{{ $item['label'] }}</span>

                            {{-- Chevron rotates on open --}}
                            <iconify-icon
                                icon="solar:alt-arrow-down-linear"
                                width="16"
                                class="nav-text shrink-0 transition-transform duration-300 text-slate-400"
                                :class="open ? 'rotate-180 text-[#0d4c8f]' : ''">
                            </iconify-icon>
                        </button>

                        {{-- ── Sub Items ── --}}
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="mt-0.5 ml-5 pl-4 border-l-2 border-slate-200 dark:border-slate-700 space-y-0.5"
                        >
                            @foreach ($item['sub'] as $sub)
                                <a href="{{ $sub['href'] }}"
                                   class="flex items-center gap-2 rounded-lg px-3 py-2
                                          text-xs font-medium
                                          transition-all duration-150
                                          {{ request()->url() === $sub['href']
                                              ? 'text-[#0d4c8f] bg-blue-50 dark:bg-slate-800 dark:text-white'
                                              : 'text-slate-500 dark:text-slate-400 hover:text-[#0d4c8f] dark:hover:text-white hover:bg-blue-50 dark:hover:bg-slate-800' }}">
                                    {{ $sub['label'] }}
                                </a>
                            @endforeach
                        </div>

                    </div>

                @else

                    {{-- ── Nav Item WITHOUT Dropdown ── --}}
                    <a href="{{ $item['href'] }}"
                       class="group flex items-center gap-3 rounded-lg px-3 py-2.5
                              text-sm font-medium transition-all duration-200
                              {{ request()->url() === $item['href']
                                  ? 'bg-[#0d4c8f] text-white'
                                  : 'text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}"
                       title="{{ $item['label'] }}">

                        <iconify-icon
                            icon="{{ $item['icon'] }}"
                            width="20"
                            class="shrink-0 {{ request()->url() === $item['href'] ? 'text-white' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white' }}">
                        </iconify-icon>

                        <span class="nav-text">{{ $item['label'] }}</span>
                    </a>

                @endif

            @endforeach

        </nav>

    </div>

</aside>

{{-- ── Alpine.js (required for dropdowns) ── --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>