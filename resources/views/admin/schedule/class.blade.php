@extends('layouts.admin_layout')
@section('title', 'Class Schedule Management')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4"
     x-data="classScheduleApp()" x-init="init()">

    <x-admin.page-header
        title="Schedule"
        subtitle="Manage class schedules, teacher assignments, and timetables"
        school-year="{{ $schoolYear }}"
    />

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- MAIN CARD                                          --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden mb-6">

        {{-- Tabs --}}
        <div class="flex border-b border-slate-100 dark:border-dark-border">
            <button @click="activeTab = 'list'"
                :class="activeTab === 'list' ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold' : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-6 py-3.5 text-sm transition-colors -mb-px flex items-center gap-2">
                <iconify-icon icon="solar:list-bold" width="15"></iconify-icon>
                Class Schedule List
            </button>
            <button @click="activeTab = 'grid'"
                :class="activeTab === 'grid' ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold' : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-6 py-3.5 text-sm transition-colors -mb-px flex items-center gap-2">
                <iconify-icon icon="solar:table-2-bold" width="15"></iconify-icon>
                Grid View
            </button>
        </div>

        {{-- Card header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-3">
                <iconify-icon icon="solar:clock-circle-bold" width="20" class="text-[#0d4c8f]"></iconify-icon>
                <div>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Class Schedule Management</h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Schedules are built from subject & teacher allocations</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button @click="openSetupModal()"
                    class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                    <iconify-icon icon="solar:settings-linear" width="14" class="text-[#0d4c8f]"></iconify-icon>
                    Manage Class Setup
                </button>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            @foreach([
                ['solar:widget-bold',       'green',  $totalSections,  'Total Sections',   'Sections this school year'],
                ['solar:chart-bold',        'orange', $totalSchedules, 'Total Schedules',  'Individual time slots assigned'],
                ['solar:check-square-bold', 'blue',   $completeSections,'Complete',        'All subjects scheduled'],
                ['solar:clock-circle-bold', 'yellow', $pendingSections, 'Pending',         'Needs schedule assignment'],
            ] as [$icon, $color, $count, $label, $sub])
            <div class="flex items-center gap-3 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 dark:border-{{ $color }}-900/30 dark:bg-{{ $color }}-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30">
                    <iconify-icon icon="{{ $icon }}" width="20" class="text-{{ $color }}-600 dark:text-{{ $color }}-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $count }}</p>
                    <p class="text-xs text-{{ $color }}-700 dark:text-{{ $color }}-400 font-semibold mt-1">{{ $label }}</p>
                    <p class="text-[10px] text-{{ $color }}-600/70 dark:text-{{ $color }}-400/60">{{ $sub }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ══════════════════════════════════════════════ --}}
        {{-- TAB 1 — LIST                                   --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'list'" x-transition.opacity>

            {{-- ── Schedule Status ── --}}
            <div class="flex items-center gap-2 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <iconify-icon icon="solar:check-circle-bold" width="16" class="text-[#0d4c8f]"></iconify-icon>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Schedule Status by Section</h3>
            </div>

            {{-- Filters --}}
            <form method="GET" action="{{ route('admin.schedule.class') }}" class="flex flex-wrap items-end gap-4 px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
                <input type="hidden" name="school_year" value="{{ $schoolYear }}">
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Grade / Section</label>
                    <input type="text" name="grade_section" value="{{ request('grade_section') }}" placeholder="e.g. Grade 7 - A"
                        class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-44">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Status</label>
                    <div class="relative">
                        <select name="sched_status" class="appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 pr-7 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-36">
                            <option value="">All</option>
                            <option value="complete" {{ request('sched_status') === 'complete' ? 'selected' : '' }}>Complete</option>
                            <option value="pending"  {{ request('sched_status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:magnifer-bold" width="13"></iconify-icon>
                    Filter
                </button>
            </form>

            {{-- Status Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse" style="min-width:700px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 bg-slate-50/70 dark:bg-white/[0.02]">
                            <th class="px-4 py-3">Grade & Section</th>
                            <th class="px-4 py-3">Level</th>
                            <th class="px-4 py-3 text-center">Total Subjects</th>
                            <th class="px-4 py-3 text-center">Scheduled</th>
                            <th class="px-4 py-3 text-center">Progress</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                        @forelse($sections as $sec)
                        @php
                            $cfg        = $sec->allocationConfig;
                            $required   = $cfg?->total_subjects_required ?? 0;
                            $allocated  = $allocCounts[$sec->id] ?? 0;
                            $scheduled  = $scheduledCounts[$sec->id] ?? 0;
                            $pct        = $allocated > 0 ? min(100, round(($scheduled / $allocated) * 100)) : 0;
                            $complete   = $allocated > 0 && $scheduled >= $allocated;
                        @endphp
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-4 py-3 font-semibold text-sm text-slate-800 dark:text-white">{{ $sec->display_name }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $sec->program_level ?? '—' }}</td>
                            <td class="px-4 py-3 text-xs text-center text-slate-600 dark:text-slate-300">
                                @if($required > 0)
                                    {{ $allocated }}/{{ $required }}
                                @else
                                    {{ $allocated ?: '—' }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-center text-slate-600 dark:text-slate-300">{{ $scheduled }}/{{ $allocated ?: '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-1.5 rounded-full bg-slate-200 dark:bg-slate-700">
                                        @if($allocated > 0)
                                            <div class="h-1.5 rounded-full {{ $complete ? 'bg-green-500' : 'bg-amber-400' }}" style="width:{{ $pct }}%"></div>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 w-8">{{ $allocated > 0 ? $pct.'%' : '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($complete)
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                        <iconify-icon icon="solar:check-circle-bold" width="11"></iconify-icon> Complete
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                        <iconify-icon icon="solar:clock-circle-bold" width="11"></iconify-icon> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button @click="goToGrid({{ $sec->id }}, '{{ $schoolYear }}')"
                                        class="inline-flex items-center gap-1 rounded-lg border border-[#0d4c8f] bg-white dark:bg-dark-card hover:bg-blue-50 dark:hover:bg-blue-900/20 px-3 py-1.5 text-xs font-semibold text-[#0d4c8f] dark:text-blue-400 transition-colors">
                                        {{ $complete ? 'View' : 'Assign' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-xs text-slate-400">No sections found for {{ $schoolYear }}.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination (sections) --}}
            @if($sections->hasPages())
            <div class="flex items-center justify-between px-6 py-3 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">Showing {{ $sections->firstItem() }}–{{ $sections->lastItem() }} of {{ $sections->total() }}</p>
                <div class="flex items-center gap-1">
                    @if($sections->onFirstPage())
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed"><iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon></span>
                    @else
                        <a href="{{ $sections->previousPageUrl() }}" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50"><iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon></a>
                    @endif
                    <span class="flex h-7 w-auto px-2 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">{{ $sections->currentPage() }}</span>
                    @if($sections->hasMorePages())
                        <a href="{{ $sections->nextPageUrl() }}" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50"><iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon></a>
                    @else
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed"><iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon></span>
                    @endif
                </div>
            </div>
            @endif

            {{-- ── Class List Schedule ── --}}
            <div class="flex items-center gap-2 px-6 py-4 border-t border-b border-slate-100 dark:border-dark-border mt-2">
                <iconify-icon icon="solar:list-bold" width="16" class="text-[#0d4c8f]"></iconify-icon>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Class List Schedule</h3>
            </div>

            {{-- Grade Level Pills --}}
            <div class="flex flex-wrap gap-2 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                @php
                    $activeGrade = request('list_grade', 'All');
                    $gradePills = ['All','K','G1','G2','G3','G4','G5','G6','G7','G8','G9','G10','G11','G12'];
                @endphp
                @foreach($gradePills as $g)
                <a href="{{ route('admin.schedule.class', array_merge(request()->except(['list_grade','list_page']), $g === 'All' ? [] : ['list_grade' => $g])) }}"
                    class="px-3 py-1.5 rounded-lg border text-xs font-semibold transition-colors {{ $activeGrade === $g ? 'bg-[#0d4c8f] text-white border-[#0d4c8f]' : 'bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 border-slate-200 dark:border-dark-border hover:border-[#0d4c8f] hover:text-[#0d4c8f]' }}">
                    {{ $g }}
                </a>
                @endforeach
            </div>

            {{-- Class List Filters --}}
            <form method="GET" action="{{ route('admin.schedule.class') }}" class="grid grid-cols-2 sm:grid-cols-4 gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
                <input type="hidden" name="school_year" value="{{ $schoolYear }}">
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Day</label>
                    <div class="relative">
                        <select name="list_day" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 pr-7 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Days</option>
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday'] as $d)
                            <option value="{{ $d }}" {{ request('list_day') === $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Section</label>
                    <div class="relative">
                        <select name="list_section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 pr-7 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Sections</option>
                            @foreach($allSections as $s)
                            <option value="{{ $s->id }}" {{ request('list_section') == $s->id ? 'selected' : '' }}>{{ $s->display_name }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Teacher</label>
                    <div class="relative">
                        <select name="list_teacher" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 pr-7 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ request('list_teacher') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors w-full justify-center">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon>
                        Apply Filter
                    </button>
                </div>
            </form>

            {{-- Class List Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse" style="min-width:860px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 bg-slate-50/70 dark:bg-white/[0.02]">
                            <th class="px-4 py-3">Day</th>
                            <th class="px-4 py-3 whitespace-nowrap">Time Start</th>
                            <th class="px-4 py-3 whitespace-nowrap">Time End</th>
                            <th class="px-4 py-3 whitespace-nowrap">Grade & Section</th>
                            <th class="px-4 py-3">Subject</th>
                            <th class="px-4 py-3">Teacher</th>
                            <th class="px-4 py-3">Room</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                        @forelse($classList as $sched)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-4 py-3 text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $sched->day_of_week }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ substr($sched->time_start, 0, 5) }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ substr($sched->time_end, 0, 5) }}</td>
                            <td class="px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300">{{ $sched->allocation?->section?->display_name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <p class="text-xs font-semibold text-slate-700 dark:text-white">{{ $sched->allocation?->subject_name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $sched->allocation?->subject_code }}</p>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $sched->allocation?->teacher?->name ?? '— No teacher' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $sched->room ?: '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <button @click="goToGrid({{ $sched->allocation?->section_id }}, '{{ $schoolYear }}')"
                                    title="View in Grid"
                                    class="flex h-7 w-7 items-center justify-center rounded-lg mx-auto bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 text-[#0d4c8f] dark:text-blue-400 transition-colors">
                                    <iconify-icon icon="solar:table-2-linear" width="14"></iconify-icon>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-xs text-slate-400">No scheduled classes found. Use the Grid View to assign class times.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($classList->hasPages())
            <div class="flex items-center justify-between px-6 py-3 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">Showing {{ $classList->firstItem() }}–{{ $classList->lastItem() }} of {{ $classList->total() }}</p>
                {{ $classList->onEachSide(1)->links() }}
            </div>
            @endif

        </div>{{-- end list tab --}}


        {{-- ══════════════════════════════════════════════ --}}
        {{-- TAB 2 — GRID VIEW                             --}}
        {{-- ══════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'grid'" x-transition.opacity>

            {{-- Grid Filters --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">School Year</label>
                    <div class="relative">
                        <select x-model="gridSchoolYear" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($schoolYears as $sy)
                            <option value="{{ $sy }}" {{ $sy === $schoolYear ? 'selected' : '' }}>{{ $sy }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Grade & Section <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <select x-model="gridSectionId" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">— Select Section —</option>
                            @foreach($allSections as $s)
                            <option value="{{ $s->id }}">{{ $s->display_name }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button @click="loadGrid()" :disabled="!gridSectionId || gridLoading"
                        class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 disabled:opacity-50 disabled:cursor-not-allowed px-5 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:magnifer-bold" width="13"></iconify-icon>
                        <span x-text="gridLoading ? 'Loading…' : 'Load Schedule'"></span>
                    </button>
                    <button @click="gridData = null; gridSectionId = ''" class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors">
                        Clear
                    </button>
                </div>
            </div>

            {{-- Grid placeholder (before load) --}}
            <div x-show="!gridData && !gridLoading" class="px-6 py-16 text-center">
                <iconify-icon icon="solar:table-2-bold" width="48" class="text-slate-200 dark:text-slate-700 mx-auto mb-3"></iconify-icon>
                <p class="text-sm font-semibold text-slate-400 dark:text-slate-500">Select a section above to view the schedule grid</p>
                <p class="text-xs text-slate-300 dark:text-slate-600 mt-1">Use the "Assign" buttons to schedule subjects in time slots</p>
            </div>

            {{-- Loading state --}}
            <div x-show="gridLoading" class="px-6 py-16 text-center">
                <div class="w-8 h-8 border-4 border-[#0d4c8f] border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                <p class="text-xs text-slate-400">Loading schedule…</p>
            </div>

            {{-- Grid content --}}
            <div x-show="gridData && !gridLoading">

                {{-- Grid section info + actions --}}
                <div class="flex items-center justify-between px-6 pt-4 pb-2 flex-wrap gap-3">
                    <div>
                        <h4 class="text-base font-bold text-slate-800 dark:text-white" x-text="gridData ? gridData.section.display_name : ''"></h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400" x-text="gridData ? 'School Year: ' + gridData.section.school_year + ' · ' + gridData.section.program_level : ''"></p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5" x-text="gridData ? 'Class hours: ' + gridData.setup.time_start + ' – ' + gridData.setup.time_end + ' · ' + gridData.setup.slot_duration + '-min slots' : ''"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="loadGrid()" class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 transition-colors">
                            <iconify-icon icon="solar:refresh-linear" width="13"></iconify-icon>
                            Refresh
                        </button>
                    </div>
                </div>

                {{-- Conflict / warning banner --}}
                <template x-if="gridConflicts.length > 0">
                    <div class="mx-6 mb-3 rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10 p-4">
                        <p class="text-xs font-bold text-amber-700 dark:text-amber-400 mb-2 flex items-center gap-1.5">
                            <iconify-icon icon="solar:danger-triangle-bold" width="14"></iconify-icon>
                            Schedule Conflict Detected
                        </p>
                        <template x-for="(conflict, i) in gridConflicts" :key="i">
                            <div class="flex items-start gap-2 mb-1.5">
                                <iconify-icon :icon="conflict.severity === 'error' ? 'solar:close-circle-bold' : 'solar:info-circle-bold'" width="13"
                                    :class="conflict.severity === 'error' ? 'text-red-500' : 'text-amber-500'" class="mt-0.5 flex-shrink-0"></iconify-icon>
                                <div>
                                    <p class="text-xs text-slate-700 dark:text-slate-300" x-text="conflict.message"></p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400" x-text="'Fix: ' + conflict.fix"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Weekly Timetable --}}
                <div class="overflow-x-auto px-6 pb-6">
                    <table class="w-full border-collapse text-xs" style="min-width:700px">
                        <thead>
                            <tr class="border border-slate-200 dark:border-dark-border">
                                <th class="border border-slate-200 dark:border-dark-border bg-[#0d4c8f] px-3 py-3 text-xs font-semibold text-white w-28 text-center">Time</th>
                                <template x-for="day in (gridData ? gridData.days : [])" :key="day">
                                    <th class="border border-slate-200 dark:border-dark-border bg-[#0d4c8f] px-3 py-3 text-xs font-semibold text-white text-center" x-text="day"></th>
                                </template>
                            </tr>
                        </thead>
                        <tbody x-ref="gridTbody">
                            {{-- Rendered by JS --}}
                        </tbody>
                    </table>
                </div>

                {{-- Auto-assign result panel --}}
                <template x-if="autoAssignResult">
                    <div class="mx-6 mt-4 rounded-xl border p-4"
                         :class="autoAssignResult.skipped.length > 0 ? 'border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10' : 'border-green-200 bg-green-50 dark:border-green-900/30 dark:bg-green-900/10'">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1">
                                <p class="text-xs font-bold mb-2"
                                   :class="autoAssignResult.skipped.length > 0 ? 'text-amber-700 dark:text-amber-400' : 'text-green-700 dark:text-green-400'"
                                   x-text="autoAssignResult.message"></p>
                                <template x-if="autoAssignResult.assigned.length > 0">
                                    <div class="mb-2">
                                        <p class="text-[10px] font-semibold text-green-700 dark:text-green-400 mb-1">Assigned:</p>
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="s in autoAssignResult.assigned" :key="s">
                                                <span class="inline-flex items-center rounded px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-[10px] font-semibold text-green-700 dark:text-green-400" x-text="s"></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="autoAssignResult.skipped.length > 0">
                                    <div>
                                        <p class="text-[10px] font-semibold text-amber-700 dark:text-amber-400 mb-1">Could not assign (no free slot found):</p>
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="s in autoAssignResult.skipped" :key="s">
                                                <span class="inline-flex items-center rounded px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-[10px] font-semibold text-amber-700 dark:text-amber-400" x-text="s"></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <button @click="autoAssignResult = null" class="w-6 h-6 flex items-center justify-center rounded text-slate-400 hover:bg-slate-200 dark:hover:bg-white/10 flex-shrink-0">
                                <iconify-icon icon="solar:close-linear" width="12"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </template>

                {{-- Unscheduled allocations --}}
                <template x-if="gridData && gridData.allocations.length > 0">
                    <div class="mx-6 mb-6 rounded-xl border border-blue-200 dark:border-blue-900/30 bg-blue-50 dark:bg-blue-900/10 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-bold text-[#0d4c8f] dark:text-blue-400 flex items-center gap-1.5">
                                <iconify-icon icon="solar:book-2-bold" width="14"></iconify-icon>
                                Allocated Subjects — click a cell in the grid to assign a schedule
                            </p>
                            <button @click="autoAssign()"
                                :disabled="isAutoAssigning"
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-[11px] font-semibold text-white bg-[#0d4c8f] hover:bg-[#0a3d73] disabled:opacity-60 disabled:cursor-not-allowed transition-colors shadow-sm">
                                <template x-if="isAutoAssigning">
                                    <iconify-icon icon="svg-spinners:ring-resize" width="12"></iconify-icon>
                                </template>
                                <template x-if="!isAutoAssigning">
                                    <iconify-icon icon="solar:magic-stick-3-bold" width="12"></iconify-icon>
                                </template>
                                <span x-text="isAutoAssigning ? 'Assigning…' : 'Auto Assign'"></span>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="alloc in gridData.allocations" :key="alloc.id">
                                <div class="inline-flex items-start gap-2 rounded-xl border px-3 py-2"
                                     :class="alloc.meetings_per_week > 0 && alloc.scheduled_count >= alloc.meetings_per_week
                                             ? 'bg-green-50 dark:bg-green-900/10 border-green-200 dark:border-green-900/30'
                                             : 'bg-white dark:bg-dark-card border-slate-200 dark:border-dark-border'">
                                    <div class="w-2 h-2 rounded-full mt-1 flex-shrink-0"
                                         :class="alloc.meetings_per_week > 0 && alloc.scheduled_count >= alloc.meetings_per_week ? 'bg-green-500' : 'bg-blue-400'"></div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200" x-text="alloc.subject_code + ' — ' + alloc.subject_name"></p>
                                        <p class="text-[10px] text-slate-400 mb-1" x-text="alloc.teacher_name"></p>
                                        <div class="flex items-center gap-1 flex-wrap">
                                            <span class="inline-flex items-center rounded px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] font-semibold text-slate-600 dark:text-slate-300"
                                                  x-text="(alloc.hours_per_meeting || '—') + 'h/mtg'"></span>
                                            <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-semibold"
                                                  :class="alloc.meetings_per_week > 0 && alloc.scheduled_count >= alloc.meetings_per_week
                                                          ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'
                                                          : 'bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400'"
                                                  x-text="alloc.scheduled_count + '/' + (alloc.meetings_per_week || '?') + ' mtg/wk'"></span>
                                            <span class="inline-flex items-center rounded px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] font-semibold text-slate-600 dark:text-slate-300"
                                                  x-text="(alloc.hours_per_week || '—') + 'h/wk'"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

            </div>
        </div>{{-- end grid tab --}}

    </div>{{-- end main card --}}

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- ASSIGN / EDIT SCHEDULE MODAL                          --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div x-show="showAssignModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div @click.outside="showAssignModal = false"
            class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-slate-200 dark:border-dark-border w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white" x-text="editScheduleId ? 'Edit Schedule' : 'Assign Schedule'"></h3>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="'Section: ' + (gridData ? gridData.section.display_name : '')"></p>
                </div>
                <button @click="showAssignModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                    <iconify-icon icon="solar:close-linear" width="16"></iconify-icon>
                </button>
            </div>

            {{-- Conflict warnings in modal --}}
            <template x-if="modalConflicts.length > 0">
                <div class="mx-6 mt-4 rounded-xl border p-3"
                    :class="modalConflicts.some(c => c.severity === 'error') ? 'border-red-200 bg-red-50 dark:border-red-900/30 dark:bg-red-900/10' : 'border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10'">
                    <p class="text-xs font-bold mb-2"
                        :class="modalConflicts.some(c => c.severity === 'error') ? 'text-red-600' : 'text-amber-700'">
                        <iconify-icon icon="solar:danger-triangle-bold" width="13" class="inline mr-1"></iconify-icon>
                        <span x-text="modalConflicts.some(c => c.severity === 'error') ? 'Conflict — cannot save' : 'Warning'"></span>
                    </p>
                    <template x-for="(c, i) in modalConflicts" :key="i">
                        <div class="mb-1.5">
                            <p class="text-xs" :class="c.severity === 'error' ? 'text-red-600' : 'text-amber-700'" x-text="c.message"></p>
                            <p class="text-[10px] text-slate-500" x-text="'→ ' + c.fix"></p>
                        </div>
                    </template>
                </div>
            </template>

            <div class="px-6 py-5 space-y-4">

                {{-- Subject / Allocation --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Subject <span class="text-red-400">*</span></label>
                    <select x-model="form.allocation_id" @change="onAllocationChange()"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Select Subject —</option>
                        <template x-for="alloc in availableAllocations" :key="alloc.id">
                            <option :value="alloc.id" x-text="alloc.subject_code + ' — ' + alloc.subject_name + ' (' + alloc.teacher_name + ')'"></option>
                        </template>
                    </select>
                    <p class="mt-1 text-[10px] text-slate-400" x-show="availableAllocations.length === 0 && !editScheduleId">
                        All subjects are fully scheduled for this section.
                    </p>
                </div>

                {{-- Subject metrics panel --}}
                <template x-if="selectedAlloc">
                    <div class="rounded-xl border border-blue-200 dark:border-blue-900/30 bg-blue-50 dark:bg-blue-900/10 px-4 py-3">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-blue-500 mb-2">Subject Schedule Requirements</p>
                        <div class="flex items-center gap-4 flex-wrap">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] text-slate-400">Hrs / Meeting</span>
                                <span class="text-sm font-bold text-slate-700 dark:text-white" x-text="(selectedAlloc.hours_per_meeting || '—') + 'h'"></span>
                            </div>
                            <div class="w-px h-8 bg-blue-200 dark:bg-blue-800"></div>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] text-slate-400">Meetings / Week</span>
                                <span class="text-sm font-bold"
                                      :class="selectedAlloc.meetings_per_week > 0 && selectedAlloc.scheduled_count >= selectedAlloc.meetings_per_week
                                              ? 'text-red-600 dark:text-red-400' : 'text-slate-700 dark:text-white'"
                                      x-text="selectedAlloc.scheduled_count + ' / ' + (selectedAlloc.meetings_per_week || '—')"></span>
                            </div>
                            <div class="w-px h-8 bg-blue-200 dark:bg-blue-800"></div>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] text-slate-400">Hrs / Week</span>
                                <span class="text-sm font-bold text-slate-700 dark:text-white" x-text="(selectedAlloc.hours_per_week || '—') + 'h'"></span>
                            </div>
                        </div>
                        <template x-if="selectedAlloc.meetings_per_week > 0 && selectedAlloc.scheduled_count >= selectedAlloc.meetings_per_week">
                            <p class="mt-2 text-xs font-semibold text-red-600 dark:text-red-400 flex items-center gap-1">
                                <iconify-icon icon="solar:danger-triangle-bold" width="13"></iconify-icon>
                                Weekly meeting limit reached — saving will be blocked.
                            </p>
                        </template>
                        <template x-if="selectedAlloc.hours_per_meeting > 0">
                            <p class="mt-1.5 text-[11px] text-blue-600 dark:text-blue-400 flex items-center gap-1">
                                <iconify-icon icon="solar:clock-circle-linear" width="12"></iconify-icon>
                                <span x-text="'End time auto-set to ' + (selectedAlloc.hours_per_meeting * 60) + ' min after start.'"></span>
                            </p>
                        </template>
                    </div>
                </template>

                {{-- Day --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Day <span class="text-red-400">*</span></label>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="day in ['Monday','Tuesday','Wednesday','Thursday','Friday']" :key="day">
                            <button type="button" @click="form.day = day"
                                :class="form.day === day ? 'bg-[#0d4c8f] text-white border-[#0d4c8f]' : 'bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 border-slate-200 dark:border-dark-border hover:border-[#0d4c8f]'"
                                class="px-3 py-1.5 rounded-lg border text-xs font-semibold transition-colors" x-text="day"></button>
                        </template>
                    </div>
                </div>

                {{-- Time --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Time Start <span class="text-red-400">*</span></label>
                        <input type="time" x-model="form.time_start" @change="onAllocationChange()"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Time End <span class="text-red-400">*</span></label>
                        <input type="time" x-model="form.time_end"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Room --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Room / Venue</label>
                    <input type="text" x-model="form.room" placeholder="e.g. Building 1, Room 201"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border gap-3">
                <div class="flex items-center gap-2">
                    <template x-if="editScheduleId">
                        <button @click="confirmDelete(editScheduleId)"
                            class="flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-4 py-2 text-xs font-semibold text-red-600 transition-colors">
                            <iconify-icon icon="solar:trash-bin-trash-linear" width="13"></iconify-icon>
                            Remove
                        </button>
                    </template>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="showAssignModal = false" class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-5 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 transition-colors hover:bg-slate-50">
                        Cancel
                    </button>
                    <button @click="saveSchedule()" :disabled="modalLoading"
                        class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 disabled:opacity-50 px-6 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:check-circle-linear" width="13"></iconify-icon>
                        <span x-text="modalLoading ? 'Saving…' : (editScheduleId ? 'Update' : 'Save Schedule')"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- DELETE CONFIRM MODAL                                  --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div x-show="showDeleteModal" x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div @click.outside="showDeleteModal = false"
            class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-slate-200 dark:border-dark-border w-full max-w-sm">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                        <iconify-icon icon="solar:trash-bin-trash-bold" width="18" class="text-red-500"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">Remove Schedule Slot</h3>
                        <p class="text-xs text-slate-400 mt-0.5">This action cannot be undone</p>
                    </div>
                </div>
                <button @click="showDeleteModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                    <iconify-icon icon="solar:close-linear" width="16"></iconify-icon>
                </button>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm text-slate-600 dark:text-slate-300">Are you sure you want to remove this schedule slot? The subject will go back to unscheduled and can be reassigned.</p>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <button @click="showDeleteModal = false"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-5 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button @click="deleteSchedule(deleteTargetId)" :disabled="modalLoading"
                    class="flex items-center gap-1.5 rounded-lg bg-red-500 hover:bg-red-600 disabled:opacity-50 px-5 py-2 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:trash-bin-trash-linear" width="13"></iconify-icon>
                    <span x-text="modalLoading ? 'Removing…' : 'Yes, Remove'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- MANAGE CLASS SETUP MODAL                              --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div x-show="showSetupModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div @click.outside="showSetupModal = false"
            class="bg-white dark:bg-dark-card rounded-2xl shadow-2xl border border-slate-200 dark:border-dark-border w-full max-w-2xl max-h-[90vh] overflow-y-auto">

            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Manage Class Setup</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Configure allowed class hours and break times per level</p>
                </div>
                <button @click="showSetupModal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                    <iconify-icon icon="solar:close-linear" width="16"></iconify-icon>
                </button>
            </div>

            {{-- Existing setups list --}}
            <div class="px-6 pt-5 pb-3">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Existing Configurations</h4>
                <div class="space-y-2" x-show="setups.length > 0">
                    <template x-for="setup in setups" :key="setup.id">
                        <div class="flex items-start justify-between gap-3 rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5 p-3">
                            <div>
                                <p class="text-xs font-bold text-slate-700 dark:text-white" x-text="setup.grade_level ? setup.grade_level + ' (' + setup.level_type + ')' : setup.level_type + ' (All Grades)'"></p>
                                <p class="text-[10px] text-slate-500 mt-0.5" x-text="setup.time_start + ' – ' + setup.time_end + ' · ' + setup.slot_duration + '-min slots'"></p>
                                <template x-if="setup.breaks && setup.breaks.length > 0">
                                    <p class="text-[10px] text-slate-400" x-text="'Breaks: ' + setup.breaks.map(b => b.label + ' ' + b.start + '–' + b.end).join(', ')"></p>
                                </template>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                <button @click="editSetup(setup)" class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-[#0d4c8f] transition-colors">
                                    <iconify-icon icon="solar:pen-linear" width="13"></iconify-icon>
                                </button>
                                <button @click="removeSetup(setup.id)" class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors">
                                    <iconify-icon icon="solar:trash-bin-trash-linear" width="13"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                <p x-show="setups.length === 0" class="text-xs text-slate-400 text-center py-4">No configurations yet. Add one below.</p>
            </div>

            {{-- Add / Edit form --}}
            <div class="px-6 pt-2 pb-5 border-t border-slate-100 dark:border-dark-border mt-2">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-4 mt-3"
                    x-text="setupForm.id ? 'Edit Configuration' : 'Add Configuration'"></h4>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Program Level <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <select x-model="setupForm.level_type" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">— Select —</option>
                                <option value="Preschool">Preschool</option>
                                <option value="Elementary">Elementary</option>
                                <option value="Junior High School">Junior High School</option>
                                <option value="Senior High School">Senior High School</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Specific Grade <span class="text-slate-300">(optional)</span></label>
                        <input type="text" x-model="setupForm.grade_level" placeholder="e.g. Grade 1, Grade 7 (blank = all)"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Class Start Time <span class="text-red-400">*</span></label>
                        <input type="time" x-model="setupForm.time_start"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Class End Time <span class="text-red-400">*</span></label>
                        <input type="time" x-model="setupForm.time_end"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Slot Duration</label>
                        <div class="flex gap-2">
                            <template x-for="dur in [30, 60, 90]">
                                <button type="button" @click="setupForm.slot_duration = dur"
                                    :class="setupForm.slot_duration == dur ? 'bg-[#0d4c8f] text-white border-[#0d4c8f]' : 'bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 border-slate-200 dark:border-dark-border hover:border-[#0d4c8f]'"
                                    class="px-4 py-1.5 rounded-lg border text-xs font-semibold transition-colors" x-text="dur + ' min'"></button>
                            </template>
                        </div>
                    </div>

                    {{-- Breaks --}}
                    <div class="col-span-2">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Break / Recess Periods</label>
                            <button type="button" @click="setupForm.breaks.push({start:'10:00',end:'10:15',label:'Recess'})"
                                class="text-[10px] font-semibold text-[#0d4c8f] hover:underline">+ Add Break</button>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(brk, i) in setupForm.breaks" :key="i">
                                <div class="flex items-center gap-2 bg-slate-50 dark:bg-white/5 rounded-lg p-2">
                                    <input type="text" x-model="brk.label" placeholder="Label (e.g. Recess)"
                                        class="flex-1 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <input type="time" x-model="brk.start"
                                        class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <span class="text-[10px] text-slate-400">to</span>
                                    <input type="time" x-model="brk.end"
                                        class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <button type="button" @click="setupForm.breaks.splice(i, 1)" class="text-red-400 hover:text-red-600 p-1">
                                        <iconify-icon icon="solar:close-circle-linear" width="14"></iconify-icon>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 mt-5">
                    <button @click="resetSetupForm()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-5 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 transition-colors hover:bg-slate-50">
                        Reset
                    </button>
                    <button @click="saveSetup()" :disabled="setupLoading"
                        class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 disabled:opacity-50 px-6 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:diskette-linear" width="13"></iconify-icon>
                        <span x-text="setupLoading ? 'Saving…' : (setupForm.id ? 'Update Setup' : 'Save Setup')"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- Toast notification --}}
<div x-data="{ show: false, msg: '', type: 'success' }"
     x-on:toast.window="show = true; msg = $event.detail.msg; type = $event.detail.type; setTimeout(() => show = false, 3500)"
     x-show="show" x-transition.opacity
     x-cloak
     :class="type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-amber-500')"
     class="fixed bottom-6 right-6 z-[100] flex items-center gap-3 text-white text-sm font-semibold px-5 py-3 rounded-xl shadow-lg">
    <iconify-icon :icon="type === 'success' ? 'solar:check-circle-bold' : 'solar:danger-triangle-bold'" width="18"></iconify-icon>
    <span x-text="msg"></span>
</div>

<style>
[x-cloak] { display: none !important; }
.grid-cell-class {
    background: #eff6ff;
    border-radius: 8px;
    padding: 6px 8px;
    text-align: left;
}
.dark .grid-cell-class {
    background: rgba(13, 76, 143, 0.15);
}
.grid-cell-break {
    background: #f1f5f9;
    text-align: center;
    color: #94a3b8;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    padding: 6px;
}
.dark .grid-cell-break { background: rgba(255,255,255,0.03); color: #64748b; }
</style>

@push('scripts')
<script>
function classScheduleApp() {
    return {
        activeTab: 'list',
        activeGrade: 'All',

        // Grid state
        gridSectionId: '',
        gridSchoolYear: '{{ $schoolYear }}',
        gridData: null,
        gridLoading: false,
        gridConflicts: [],

        // Assign modal
        showAssignModal: false,
        editScheduleId: null,
        form: { allocation_id: '', day: 'Monday', time_start: '07:00', time_end: '08:00', room: '' },
        modalLoading: false,
        modalConflicts: [],

        // Delete confirm modal
        showDeleteModal: false,
        deleteTargetId: null,

        // Auto-assign
        isAutoAssigning: false,
        autoAssignResult: null,

        // Setup modal
        showSetupModal: false,
        setups: @json($setups),
        setupForm: { id: null, level_type: '', grade_level: '', time_start: '07:00', time_end: '12:00', slot_duration: 60, breaks: [] },
        setupLoading: false,

        init() {
            // Check if URL has a preload section
            const params = new URLSearchParams(window.location.search);
            if (params.has('grid_section')) {
                this.gridSectionId = params.get('grid_section');
                this.activeTab = 'grid';
                this.$nextTick(() => this.loadGrid());
            }
        },

        // ── Navigate from list to grid ──
        goToGrid(sectionId, schoolYear) {
            this.gridSectionId = String(sectionId);
            this.gridSchoolYear = schoolYear;
            this.activeTab = 'grid';
            this.$nextTick(() => this.loadGrid());
        },

        // ── Load grid data via AJAX ──
        async loadGrid() {
            if (!this.gridSectionId) return;
            this.gridLoading = true;
            this.gridData = null;
            this.gridConflicts = [];

            try {
                const resp = await fetch(`{{ route('admin.schedule.class.grid-data') }}?section_id=${this.gridSectionId}&school_year=${encodeURIComponent(this.gridSchoolYear)}&_t=${Date.now()}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    cache: 'no-store',
                });
                const json = await resp.json();
                if (json.success) {
                    this.gridData = json;
                    await this.$nextTick();
                    this.renderGrid();
                }
            } catch (e) {
                console.error(e);
                this.dispatchToast('Failed to load grid data.', 'error');
            } finally {
                this.gridLoading = false;
            }
        },

        // ── Render the timetable grid ──
        renderGrid() {
            const tbody = this.$refs.gridTbody;
            if (!tbody || !this.gridData) return;

            const { slots, days, grid } = this.gridData;
            let html = '';

            for (const slot of slots) {
                if (slot.type === 'break') {
                    html += `<tr>
                        <td colspan="${days.length + 1}" class="grid-cell-break border border-slate-200 dark:border-dark-border py-1.5 px-3">
                            <iconify-icon icon="solar:clock-circle-linear" width="11" style="vertical-align:middle;margin-right:4px;"></iconify-icon>
                            ${slot.label} &nbsp;·&nbsp; ${this.fmt(slot.start)} – ${this.fmt(slot.end)}
                        </td>
                    </tr>`;
                    continue;
                }

                html += `<tr>
                    <td class="border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/[0.02] px-3 py-2 text-center align-middle whitespace-nowrap" style="min-width:110px">
                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-300">${this.fmt(slot.start)}</span><br>
                        <span class="text-[10px] text-slate-400">– ${this.fmt(slot.end)}</span>
                    </td>`;

                for (const day of days) {
                    const cell = grid[slot.start]?.[day];
                    if (cell) {
                        html += `<td class="border border-slate-200 dark:border-dark-border px-2 py-2 align-top" style="min-width:140px">
                            <div class="grid-cell-class">
                                <p style="font-size:11px;font-weight:700;color:#0c2340;margin:0 0 2px;" class="dark:text-white">${cell.subject_code}</p>
                                <p style="font-size:10px;color:#475569;margin:0 0 2px;">${cell.subject}</p>
                                <p style="font-size:10px;color:#94a3b8;margin:0 0 4px;">${cell.room || 'No room'} · ${cell.teacher}</p>
                                <div style="display:flex;gap:4px;">
                                    <button onclick="window.__schedApp.openEditModal(${cell.schedule_id},'${cell.allocation_id}','${day}','${cell.time_start}','${cell.time_end}','${cell.room || ''}')"
                                        style="font-size:10px;font-weight:600;color:#0d4c8f;background:#dbeafe;border:none;border-radius:5px;padding:2px 7px;cursor:pointer;">Edit</button>
                                    <button onclick="window.__schedApp.confirmDelete(${cell.schedule_id})"
                                        style="font-size:10px;font-weight:600;color:#ef4444;background:#fee2e2;border:none;border-radius:5px;padding:2px 7px;cursor:pointer;">Remove</button>
                                </div>
                            </div>
                        </td>`;
                    } else {
                        html += `<td class="border border-slate-200 dark:border-dark-border px-2 py-2 text-center align-middle" style="min-width:140px">
                            <p style="font-size:10px;font-weight:700;color:#0d4c8f;margin:0 0 4px;">TBA</p>
                            <button onclick="window.__schedApp.openAssignModal('${day}','${slot.start}','${slot.end}')"
                                style="font-size:10px;font-weight:700;color:#059669;background:#d1fae5;border:none;border-radius:5px;padding:3px 10px;cursor:pointer;display:inline-flex;align-items:center;gap:3px;">
                                + Assign
                            </button>
                        </td>`;
                    }
                }
                html += '</tr>';
            }

            tbody.innerHTML = html;
        },

        // ── Format time HH:MM → 12h ──
        fmt(t) {
            if (!t) return '';
            const [h, m] = t.split(':').map(Number);
            const ampm = h >= 12 ? 'PM' : 'AM';
            const h12  = h % 12 || 12;
            return `${h12}:${String(m).padStart(2,'0')} ${ampm}`;
        },

        // ── Computed: allocations not yet fully scheduled (for subject dropdown) ──
        get availableAllocations() {
            if (!this.gridData) return [];
            return this.gridData.allocations.filter(a => {
                // Always include the one already chosen in the form (editing case)
                if (this.editScheduleId && String(a.id) === String(this.form.allocation_id)) return true;
                return !(a.meetings_per_week > 0 && a.scheduled_count >= a.meetings_per_week);
            });
        },

        // ── Computed: currently selected allocation ──
        get selectedAlloc() {
            if (!this.form.allocation_id || !this.gridData) return null;
            return this.gridData.allocations.find(a => String(a.id) === String(this.form.allocation_id)) || null;
        },

        // ── Auto-compute time_end from hours_per_meeting ──
        onAllocationChange() {
            const alloc = this.selectedAlloc;
            if (!alloc || !alloc.hours_per_meeting || !this.form.time_start) return;
            const [h, m] = this.form.time_start.split(':').map(Number);
            const endMin = h * 60 + m + Math.round(alloc.hours_per_meeting * 60);
            this.form.time_end = `${String(Math.floor(endMin / 60)).padStart(2, '0')}:${String(endMin % 60).padStart(2, '0')}`;
        },

        // ── Open assign modal (empty cell) ──
        openAssignModal(day, timeStart, timeEnd) {
            this.editScheduleId = null;
            this.modalConflicts = [];
            this.form = {
                allocation_id: '',
                day: day,
                time_start: timeStart,
                time_end: timeEnd,
                room: '',
            };
            this.showAssignModal = true;
        },

        // ── Open edit modal (existing schedule) ──
        openEditModal(schedId, allocId, day, timeStart, timeEnd, room) {
            this.editScheduleId = schedId;
            this.modalConflicts = [];
            this.form = {
                allocation_id: String(allocId),
                day: day,
                time_start: timeStart,
                time_end: timeEnd,
                room: room,
            };
            this.showAssignModal = true;
        },

        // ── Save schedule ──
        async saveSchedule() {
            if (!this.form.allocation_id || !this.form.day || !this.form.time_start || !this.form.time_end) {
                this.dispatchToast('Please fill in all required fields.', 'error');
                return;
            }
            this.modalLoading = true;
            this.modalConflicts = [];

            const isEdit = !!this.editScheduleId;
            const url    = isEdit
                ? `{{ url('/admin/schedule/class/schedule') }}/${this.editScheduleId}`
                : '{{ route('admin.schedule.class.store') }}';

            const payload = {
                allocation_id: this.form.allocation_id,
                day_of_week:   this.form.day,
                time_start:    this.form.time_start,
                time_end:      this.form.time_end,
                room:          this.form.room,
                _token:        '{{ csrf_token() }}',
            };
            if (isEdit) payload._method = 'PUT';

            try {
                const resp = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(payload),
                });
                const json = await resp.json();
                this.modalConflicts = json.conflicts || [];

                if (json.success) {
                    this.showAssignModal = false;
                    this.dispatchToast(json.message, 'success');
                    await this.loadGrid();
                } else {
                    this.dispatchToast(json.message || 'Save failed.', 'error');
                }
            } catch (e) {
                this.dispatchToast('Network error. Try again.', 'error');
            } finally {
                this.modalLoading = false;
            }
        },

        // ── Open delete confirm modal ──
        confirmDelete(schedId) {
            this.deleteTargetId = schedId;
            this.showDeleteModal = true;
        },

        // ── Delete schedule ──
        async deleteSchedule(schedId) {
            this.modalLoading = true;
            try {
                const resp = await fetch(`{{ url('/admin/schedule/class/schedule') }}/${schedId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const json = await resp.json();
                if (json.success) {
                    this.showDeleteModal = false;
                    this.showAssignModal = false;
                    this.deleteTargetId = null;
                    this.dispatchToast(json.message, 'success');
                    await this.loadGrid();
                } else {
                    this.dispatchToast(json.message || 'Delete failed.', 'error');
                }
            } catch (e) {
                this.dispatchToast('Delete failed.', 'error');
            } finally {
                this.modalLoading = false;
            }
        },

        // ── Setup modal ──
        openSetupModal() {
            this.resetSetupForm();
            this.showSetupModal = true;
        },

        editSetup(setup) {
            this.setupForm = {
                id:            setup.id,
                level_type:    setup.level_type,
                grade_level:   setup.grade_level || '',
                time_start:    setup.time_start,
                time_end:      setup.time_end,
                slot_duration: setup.slot_duration,
                breaks:        setup.breaks ? JSON.parse(JSON.stringify(setup.breaks)) : [],
            };
        },

        resetSetupForm() {
            this.setupForm = { id: null, level_type: '', grade_level: '', time_start: '07:00', time_end: '12:00', slot_duration: 60, breaks: [] };
        },

        async saveSetup() {
            if (!this.setupForm.level_type || !this.setupForm.time_start || !this.setupForm.time_end) {
                this.dispatchToast('Program level and times are required.', 'error');
                return;
            }
            this.setupLoading = true;
            try {
                const resp = await fetch('{{ route('admin.schedule.class.setups.save') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ ...this.setupForm }),
                });
                const json = await resp.json();
                if (json.success) {
                    // Refresh setups list
                    const r2 = await fetch('{{ route('admin.schedule.class.setups') }}', { headers: { 'Accept': 'application/json' } });
                    const j2 = await r2.json();
                    this.setups = j2.setups;
                    this.resetSetupForm();
                    this.dispatchToast(json.message, 'success');
                } else {
                    this.dispatchToast(json.message || 'Save failed.', 'error');
                }
            } catch (e) {
                this.dispatchToast('Network error.', 'error');
            } finally {
                this.setupLoading = false;
            }
        },

        async removeSetup(id) {
            if (!confirm('Delete this class setup configuration?')) return;
            try {
                const resp = await fetch(`{{ url('/admin/schedule/class/setups') }}/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const json = await resp.json();
                if (json.success) {
                    this.setups = this.setups.filter(s => s.id !== id);
                    this.dispatchToast(json.message, 'success');
                }
            } catch (e) {
                this.dispatchToast('Delete failed.', 'error');
            }
        },

        // ── Auto-assign all unfinished allocations ──
        async autoAssign() {
            if (!this.gridSectionId) return;
            this.isAutoAssigning = true;
            this.autoAssignResult = null;
            try {
                const resp = await fetch('{{ route('admin.schedule.class.auto-assign') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ section_id: this.gridSectionId, school_year: this.gridSchoolYear }),
                });
                const json = await resp.json();
                if (json.success || json.assigned) {
                    this.autoAssignResult = {
                        message:  json.message || 'Auto-assign complete.',
                        assigned: json.assigned || [],
                        skipped:  json.skipped  || [],
                    };
                    await this.loadGrid();
                    if ((json.assigned || []).length > 0) {
                        this.dispatchToast(json.message, 'success');
                    }
                } else {
                    this.dispatchToast(json.message || 'Auto-assign failed.', 'error');
                }
            } catch (e) {
                this.dispatchToast('Network error during auto-assign.', 'error');
            } finally {
                this.isAutoAssigning = false;
            }
        },

        dispatchToast(msg, type = 'success') {
            window.dispatchEvent(new CustomEvent('toast', { detail: { msg, type } }));
        },
    };
}

// Expose app reference for inline onclick handlers in dynamically rendered grid
document.addEventListener('alpine:init', () => {});
document.addEventListener('alpine:initialized', () => {
    // Give grid buttons a way to call Alpine methods
    window.__schedApp = Alpine.$data(document.querySelector('[x-data="classScheduleApp()"]'));
});
</script>
@endpush

@endsection
