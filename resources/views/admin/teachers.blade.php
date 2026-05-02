@extends('layouts.admin_layout')
@section('title', 'Teachers')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4 pb-24"
     x-data="{ activeTab: '{{ request('tab', 'teacher-list') }}' }">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Teachers"
        subtitle="Manage Teachers"
        school-year="{{ $schoolYear ?? $activeSchoolYear }}"
    />

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Tabs --}}
        <div class="flex border-b border-slate-200 dark:border-dark-border">
            <button @click="activeTab = 'teacher-list'"
                :class="activeTab === 'teacher-list' ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold' : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700'"
                class="px-6 py-4 text-sm -mb-px transition-colors whitespace-nowrap">
                Teacher List
            </button>
            <button @click="activeTab = 'teaching-load'"
                :class="activeTab === 'teaching-load' ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold' : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700'"
                class="px-6 py-4 text-sm -mb-px transition-colors whitespace-nowrap">
                Teaching Load
            </button>
            <button @click="activeTab = 'assign-teacher'"
                :class="activeTab === 'assign-teacher' ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold' : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700'"
                class="px-6 py-4 text-sm -mb-px transition-colors whitespace-nowrap">
                Assign Teacher
            </button>
        </div>

        {{-- ══ TAB 1: TEACHER LIST ══ --}}
        <div x-show="activeTab === 'teacher-list'" x-cloak x-transition.opacity>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:user-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Teacher List</h2>
                </div>
                <button onclick="resetTeacherForm(); openTeacherModal('add-teacher-modal')"
                    class="flex items-center gap-2 rounded-xl border border-blue-300 bg-blue-50 hover:bg-blue-100 dark:border-blue-700 dark:bg-blue-900/20 px-4 py-2 text-xs font-semibold text-blue-600 dark:text-blue-400 transition-colors">
                    <iconify-icon icon="solar:add-circle-linear" width="15"></iconify-icon>
                    Add Teacher
                </button>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                @foreach([
                    ['green','solar:user-bold','text-green-600',$stats['total']??0,'Total Teachers'],
                    ['blue','solar:user-check-bold','text-blue-500',$stats['active']??0,'Active Teachers'],
                    ['red','solar:user-cross-bold','text-red-400',$stats['inactive']??0,'Inactive Teachers'],
                ] as [$color,$icon,$clr,$count,$label])
                <div class="flex items-center gap-4 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 dark:bg-{{ $color }}-900/10 px-5 py-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30">
                        <iconify-icon icon="{{ $icon }}" width="22" class="{{ $clr }}"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $count }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $label }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Filters --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <form method="GET" action="{{ route('admin.teachers') }}">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-3">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Subject/Specialization</label>
                            <div class="relative">
                                <select name="specialization" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    @foreach(['Science','English','Mathematics','Filipino','MAPEH','Statistics','Social Studies','TLE'] as $s)
                                    <option value="{{ $s }}" {{ request('specialization')===$s?'selected':'' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Department</label>
                            <div class="relative">
                                <select name="department" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    @foreach(['Science','English','Mathematics','Filipino','MAPEH','Social Studies','TLE'] as $d)
                                    <option value="{{ $d }}" {{ request('department')===$d?'selected':'' }}>{{ $d }}</option>
                                    @endforeach
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade Level</label>
                            <div class="relative">
                                <select name="grade_level" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $g)
                                    <option value="{{ $g }}" {{ request('grade_level')===$g?'selected':'' }}>{{ $g }}</option>
                                    @endforeach
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Status</label>
                            <div class="relative">
                                <select name="status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All</option>
                                    <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
                                    <option value="resigned" {{ request('status')==='resigned'?'selected':'' }}>Resigned</option>
                                    <option value="on_leave" {{ request('status')==='on_leave'?'selected':'' }}>On Leave</option>
                                    <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                            <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                        </button>
                        <a href="{{ route('admin.teachers') }}" class="rounded-lg border border-slate-200 px-5 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Clear All</a>
                    </div>
                </form>
            </div>

            {{-- Table Controls --}}
            <div class="flex items-center justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Show</span>
                    <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none"><option>10</option><option>25</option></select>
                    <span>Entries</span>
                </div>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" id="teacher-search" placeholder="Search teacher.."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:1100px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3 whitespace-nowrap">Teacher ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3 whitespace-nowrap">Academic Rank</th>
                            <th class="px-4 py-3 whitespace-nowrap">Employment</th>
                            <th class="px-4 py-3">Specialization</th>
                            <th class="px-4 py-3">Department</th>
                            <th class="px-4 py-3 whitespace-nowrap">Grade Levels</th>
                            <th class="px-4 py-3 whitespace-nowrap">Advisory</th>
                            <th class="px-4 py-3 whitespace-nowrap">Load (Hrs/Wk)</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @php
                        $statusBadge = ['active'=>'bg-green-100 text-green-700','resigned'=>'bg-red-100 text-red-600','on_leave'=>'bg-yellow-100 text-yellow-700','inactive'=>'bg-slate-100 text-slate-500'];
                    @endphp
                    @forelse($teachers as $tp)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors teacher-row" data-name="{{ strtolower($tp->user?->name ?? '') }}">
                        <td class="px-4 py-3 text-xs font-mono text-slate-400 whitespace-nowrap">{{ $tp->teacher_id_code ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $tp->user?->name }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $tp->academic_rank ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $tp->employment_status ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ implode(', ', $tp->specializations ?? []) ?: '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $tp->department ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">{{ implode(', ', $tp->grade_levels ?? []) ?: '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $tp->advisory_class ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs whitespace-nowrap">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $tp->current_weekly ?? 0 }}</span>
                            <span class="text-slate-400"> / {{ $tp->max_weekly ?? 0 }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge[$tp->status] ?? 'bg-slate-100 text-slate-500' }}">
                                {{ ucfirst(str_replace('_', ' ', $tp->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open=!open" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                                    Select <iconify-icon icon="solar:alt-arrow-down-linear" width="12" :class="open?'rotate-180':''" class="transition-transform duration-200"></iconify-icon>
                                </button>
                                <div x-show="open" @click.outside="open=false"
                                     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute right-0 z-20 mt-1 w-36 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1">
                                    <button type="button" @click="open=false" onclick="viewTeacher({{ $tp->id }})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:eye-bold" width="14" class="text-amber-500"></iconify-icon> View
                                    </button>
                                    <button type="button" @click="open=false" onclick="editTeacher({{ $tp->id }})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:pen-bold" width="14" class="text-blue-500"></iconify-icon> Edit
                                    </button>
                                    <button type="button" @click="open=false" onclick="deleteTeacher({{ $tp->id }}, '{{ addslashes($tp->user?->name ?? '') }}')"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                        <iconify-icon icon="solar:trash-bin-trash-bold" width="14"></iconify-icon> Deactivate
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-12 text-center text-xs text-slate-400">
                            No teachers found.
                            <button onclick="resetTeacherForm(); openTeacherModal('add-teacher-modal')" class="text-blue-600 hover:underline">Add the first teacher →</button>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">Showing {{ $teachers->firstItem() ?? 0 }}–{{ $teachers->lastItem() ?? 0 }} of {{ $teachers->total() }}</p>
                {{ $teachers->links() }}
            </div>
        </div>

        {{-- ══ TAB 2: TEACHING LOAD ══ --}}
        <div x-show="activeTab === 'teaching-load'" x-cloak x-transition.opacity>

            <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <iconify-icon icon="solar:chart-2-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Teacher Load</h2>
                <span class="ml-2 text-xs text-slate-400">Auto-calculated from subject allocations</span>
            </div>

            {{-- Load Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                @foreach([
                    ['green','solar:user-bold','text-green-600',$loadStats['total']??0,'Total Teachers'],
                    ['blue','solar:chart-minimalistic-bold','text-blue-500',$loadStats['underloaded']??0,'Underloaded'],
                    ['amber','solar:check-circle-bold','text-amber-600',$loadStats['loaded']??0,'Loaded'],
                    ['red','solar:danger-triangle-bold','text-red-500',$loadStats['overloaded']??0,'Overloaded'],
                ] as [$color,$icon,$clr,$count,$label])
                <div class="flex items-center gap-4 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 dark:bg-{{ $color }}-900/10 px-5 py-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30">
                        <iconify-icon icon="{{ $icon }}" width="20" class="{{ $clr }}"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $count }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $label }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Load Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:860px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3">Teacher</th>
                            <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                            <th class="px-4 py-3 whitespace-nowrap text-center">Max Hrs/Wk</th>
                            <th class="px-4 py-3 whitespace-nowrap text-center">Current Hrs/Wk</th>
                            <th class="px-4 py-3 whitespace-nowrap text-center">Remaining</th>
                            <th class="px-4 py-3">Load Progress</th>
                            <th class="px-4 py-3 whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @php
                        $loadBadge = ['underloaded'=>'bg-blue-100 text-blue-700','loaded'=>'bg-green-100 text-green-700','overloaded'=>'bg-red-100 text-red-700'];
                        $loadBarColor = ['underloaded'=>'bg-blue-500','loaded'=>'bg-green-500','overloaded'=>'bg-red-500'];
                    @endphp
                    @forelse($loads as $l)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300">
                            {{ $l->teacher?->name ?? 'Teacher #'.$l->teacher_id }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $l->school_year }}</td>
                        <td class="px-4 py-3 text-xs text-center font-semibold text-slate-700 dark:text-slate-300">{{ $l->max_weekly_hours }}</td>
                        <td class="px-4 py-3 text-xs text-center font-semibold text-slate-700 dark:text-slate-300">{{ $l->current_weekly_hours }}</td>
                        <td class="px-4 py-3 text-xs text-center font-semibold {{ $l->remaining <= 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $l->remaining }}
                        </td>
                        <td class="px-4 py-3 w-40">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                    <div class="h-full rounded-full {{ $loadBarColor[$l->load_status] ?? 'bg-slate-300' }} transition-all duration-500"
                                         style="width:{{ min(100, $l->pct) }}%"></div>
                                </div>
                                <span class="text-[10px] text-slate-400 w-8 text-right">{{ $l->pct }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $loadBadge[$l->load_status] ?? 'bg-slate-100 text-slate-500' }}">
                                {{ ucfirst($l->load_status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-xs text-slate-400">
                            No teacher load records. Records are auto-created when subjects are assigned to teachers in Subject Allocation.
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">Showing {{ $loads->firstItem() ?? 0 }}–{{ $loads->lastItem() ?? 0 }} of {{ $loads->total() }}</p>
                {{ $loads->links() }}
            </div>
        </div>

        {{-- ══ TAB 3: ASSIGN TEACHER ══ --}}
        <div x-show="activeTab === 'assign-teacher'" x-cloak x-transition.opacity
             x-data="teacherAssignApp()" x-init="init()" id="assign-teacher-section">

            {{-- Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-green-50 dark:bg-green-900/20 shrink-0">
                        <iconify-icon icon="solar:user-check-rounded-bold" width="18" class="text-green-600 dark:text-green-400"></iconify-icon>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800 dark:text-white">Assign Teacher</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Assign teachers to subjects per section</p>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <form method="GET" action="{{ route('admin.teachers') }}#assign-teacher-section">
                    <input type="hidden" name="tab" value="assign-teacher">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Grade Level</label>
                            <div class="relative">
                                <select name="alloc_grade" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="">All Grades</option>
                                    @foreach($allocGradeLevels as $gl)
                                    <option value="{{ $gl }}" {{ request('alloc_grade')===$gl?'selected':'' }}>{{ $gl }}</option>
                                    @endforeach
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Search</label>
                            <div class="relative">
                                <input type="text" name="alloc_search" value="{{ request('alloc_search') }}"
                                    placeholder="Section name or grade…"
                                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 pl-8 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder:text-slate-400">
                                <iconify-icon icon="solar:magnifer-linear" width="13" class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex items-end">
                            <div class="flex items-center gap-2 w-full">
                                <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                                    <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                                </button>
                                <a href="{{ route('admin.teachers') }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Clear</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:700px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-white/[0.02]">
                            <th class="px-5 py-3.5 w-10"></th>
                            <th class="px-5 py-3.5 whitespace-nowrap">School Year</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Grade and Section</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Program Level</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Teacher Assignment Progress</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($allocations as $alloc)
                    @php
                        $allocd  = $allocCounts[$alloc->id]  ?? 0;
                        $taught  = $teacherCounts[$alloc->id] ?? 0;
                        $tpct    = $allocd > 0 ? min(100, round(($taught / $allocd) * 100)) : 0;
                        $tStatus = $allocd === 0 ? 'none' : ($taught >= $allocd ? 'complete' : ($taught > 0 ? 'partial' : 'pending'));
                    @endphp

                    {{-- Parent row --}}
                    <tr class="border-b border-slate-100 dark:border-dark-border hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors cursor-pointer select-none"
                        @click="toggleRow({{ $alloc->id }}, '{{ $schoolYear }}')">
                        <td class="px-5 py-4 text-center">
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="14"
                                class="text-slate-400 transition-transform duration-200"
                                :style="expandedRow === {{ $alloc->id }} ? 'transform:rotate(90deg)' : ''"></iconify-icon>
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $schoolYear }}</td>
                        <td class="px-5 py-4">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $alloc->display_name }}</span>
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $alloc->program_level ?? '—' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden" style="min-width:90px">
                                    @if($allocd > 0)
                                    <div class="h-full rounded-full transition-all {{ $tStatus==='complete' ? 'bg-green-500' : 'bg-blue-500' }}" style="width:{{ $tpct }}%"></div>
                                    @endif
                                </div>
                                <span class="text-xs font-medium whitespace-nowrap
                                    {{ $tStatus==='complete' ? 'text-green-600' : ($tStatus==='partial' ? 'text-blue-600' : 'text-slate-400') }}">
                                    {{ $taught }}/{{ $allocd }} teachers assigned
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                {{ $tStatus==='complete' ? 'bg-green-100 text-green-700' : ($tStatus==='partial' ? 'bg-blue-100 text-blue-700' : ($tStatus==='pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500')) }}">
                                {{ $tStatus==='complete' ? 'Complete' : ($tStatus==='partial' ? 'Partial' : ($tStatus==='pending' ? 'Pending' : 'No Subjects')) }}
                            </span>
                        </td>
                    </tr>

                    {{-- Expanded subjects row --}}
                    <tr x-show="expandedRow === {{ $alloc->id }}" style="display:none"
                        class="border-b border-slate-200 dark:border-dark-border bg-blue-50/30 dark:bg-blue-900/5">
                        <td colspan="6" class="p-0">
                            <template x-if="loadingRow === {{ $alloc->id }}">
                                <div class="flex items-center justify-center gap-2 py-8 text-xs text-slate-400">
                                    <iconify-icon icon="svg-spinners:ring-resize" width="16"></iconify-icon>
                                    Loading subjects…
                                </div>
                            </template>
                            <template x-if="subjectsMap[{{ $alloc->id }}] && loadingRow !== {{ $alloc->id }}">
                                <div class="px-6 py-5">
                                    <template x-if="subjectsMap[{{ $alloc->id }}].length === 0">
                                        <p class="text-xs text-slate-400 text-center py-6">No subjects allocated to this section yet.</p>
                                    </template>
                                    <template x-if="subjectsMap[{{ $alloc->id }}].length > 0">
                                        <table class="w-full text-xs rounded-lg overflow-hidden" style="min-width:580px">
                                            <thead>
                                                <tr class="bg-slate-100 dark:bg-white/[0.04] text-slate-500 dark:text-slate-400 font-semibold">
                                                    <th class="px-4 py-3 text-left">Subject Code</th>
                                                    <th class="px-4 py-3 text-left">Subject Name</th>
                                                    <th class="px-4 py-3 text-center">Hrs / Week</th>
                                                    <th class="px-4 py-3 text-left">Assigned Teacher</th>
                                                    <th class="px-4 py-3 text-center w-36">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-white/[0.04] bg-white dark:bg-dark-card">
                                                <template x-for="subj in subjectsMap[{{ $alloc->id }}]" :key="subj.id">
                                                    <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors">
                                                        <td class="px-4 py-3 font-mono font-semibold text-slate-700 dark:text-slate-200" x-text="subj.subject_code"></td>
                                                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300" x-text="subj.subject_name"></td>
                                                        <td class="px-4 py-3 text-center text-slate-500" x-text="subj.hours_per_week || '—'"></td>
                                                        <td class="px-4 py-3">
                                                            <template x-if="!editingTeacher[subj.id]">
                                                                <span :class="subj.teacher_name ? 'text-slate-700 dark:text-slate-200 font-medium' : 'text-slate-400 italic'"
                                                                      x-text="subj.teacher_name || 'Not assigned'"></span>
                                                            </template>
                                                            <template x-if="editingTeacher[subj.id]">
                                                                <div class="space-y-1.5 min-w-[220px]">
                                                                    <div class="flex items-center gap-1.5 text-[10px]">
                                                                        <template x-if="editingTeacher[subj.id].teacher_id">
                                                                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 px-2 py-0.5 font-semibold">
                                                                                <iconify-icon icon="solar:user-check-rounded-bold" width="10"></iconify-icon>
                                                                                <span x-text="selectedTeacherName(subj.id)"></span>
                                                                            </span>
                                                                        </template>
                                                                        <template x-if="!editingTeacher[subj.id].teacher_id">
                                                                            <span class="text-slate-400 italic">No teacher selected</span>
                                                                        </template>
                                                                    </div>
                                                                    <div class="relative">
                                                                        <input type="text"
                                                                            :value="teacherSearch[subj.id] || ''"
                                                                            @input="teacherSearch = {...teacherSearch, [subj.id]: $event.target.value}"
                                                                            @click.stop
                                                                            placeholder="Search teacher…"
                                                                            class="w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 pl-6 pr-2.5 py-1.5 text-[10px] focus:outline-none focus:ring-2 focus:ring-blue-400 placeholder:text-slate-400">
                                                                        <iconify-icon icon="solar:magnifer-linear" width="10" class="absolute left-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                                                                    </div>
                                                                    <div class="rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 overflow-hidden" style="max-height:176px;overflow-y:auto">
                                                                        <template x-if="loadingTeachers === subj.id">
                                                                            <div class="flex items-center justify-center gap-1.5 py-4 text-[10px] text-slate-400">
                                                                                <iconify-icon icon="svg-spinners:ring-resize" width="12"></iconify-icon> Loading…
                                                                            </div>
                                                                        </template>
                                                                        <template x-if="loadingTeachers !== subj.id">
                                                                            <div>
                                                                                <div @click.stop="selectTeacher(subj.id, '', null)"
                                                                                    class="flex items-center gap-1.5 px-3 py-2 cursor-pointer text-[10px] border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors"
                                                                                    :class="!editingTeacher[subj.id].teacher_id ? 'bg-slate-50 dark:bg-white/5 font-semibold text-slate-600 dark:text-slate-300' : 'text-slate-400'">
                                                                                    <iconify-icon icon="solar:user-cross-rounded-linear" width="11"></iconify-icon>
                                                                                    Unassign teacher
                                                                                </div>
                                                                                <template x-if="filteredTeachers(subj.id).length === 0 && !teacherSearch[subj.id]">
                                                                                    <p class="px-3 py-3 text-[10px] text-slate-400 text-center">No available teachers for this subject.</p>
                                                                                </template>
                                                                                <template x-if="filteredTeachers(subj.id).length === 0 && teacherSearch[subj.id]">
                                                                                    <p class="px-3 py-3 text-[10px] text-slate-400 text-center">No results match your search.</p>
                                                                                </template>
                                                                                <template x-for="t in filteredTeachers(subj.id)" :key="t.id">
                                                                                    <div @click.stop="selectTeacher(subj.id, String(t.id), t.name)"
                                                                                        class="flex items-center justify-between gap-2 px-3 py-2 cursor-pointer text-[10px] hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors border-b border-slate-50 dark:border-slate-700/50 last:border-0"
                                                                                        :class="editingTeacher[subj.id].teacher_id === String(t.id) ? 'bg-blue-50 dark:bg-blue-900/20' : ''">
                                                                                        <div class="min-w-0">
                                                                                            <p class="font-medium text-slate-700 dark:text-slate-200 truncate" x-text="t.name"></p>
                                                                                            <div class="flex items-center gap-1 mt-0.5 flex-wrap">
                                                                                                <span class="text-slate-400" x-text="t.available_hours + 'h free'"></span>
                                                                                                <template x-if="t.has_warnings">
                                                                                                    <span class="inline-flex items-center gap-0.5 text-amber-600 dark:text-amber-400">
                                                                                                        <iconify-icon icon="solar:danger-triangle-linear" width="9"></iconify-icon>
                                                                                                        <span x-text="t.warnings[0]" class="truncate" style="max-width:140px"></span>
                                                                                                    </span>
                                                                                                </template>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span class="shrink-0 rounded-full px-1.5 py-0.5 font-semibold text-[9px]"
                                                                                            :class="t.status === 'available' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400'"
                                                                                            x-text="t.status === 'available' ? 'Available' : 'Near Full'"></span>
                                                                                    </div>
                                                                                </template>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </td>
                                                        <td class="px-4 py-3 text-center align-top">
                                                            <template x-if="!editingTeacher[subj.id]">
                                                                <button @click.stop="startEdit(subj, {{ $alloc->id }}, '{{ $schoolYear }}')"
                                                                    class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-[10px] font-semibold transition-colors"
                                                                    :class="subj.teacher_name ? 'border border-slate-300 text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-white/5' : 'bg-[#0d4c8f] text-white hover:bg-blue-700'">
                                                                    <iconify-icon :icon="subj.teacher_name ? 'solar:pen-linear' : 'solar:user-plus-rounded-linear'" width="11"></iconify-icon>
                                                                    <span x-text="subj.teacher_name ? 'Change' : 'Assign'"></span>
                                                                </button>
                                                            </template>
                                                            <template x-if="editingTeacher[subj.id]">
                                                                <div class="flex flex-col items-center gap-1.5 pt-7">
                                                                    <button @click.stop="saveTeacher(subj, {{ $alloc->id }})"
                                                                        :disabled="savingId === subj.id"
                                                                        class="inline-flex items-center gap-1 rounded-lg bg-green-600 hover:bg-green-700 disabled:opacity-60 px-2.5 py-1 text-[10px] font-semibold text-white transition-colors w-full justify-center">
                                                                        <iconify-icon x-show="savingId !== subj.id" icon="solar:check-circle-linear" width="11"></iconify-icon>
                                                                        <iconify-icon x-show="savingId === subj.id" icon="svg-spinners:ring-resize" width="11"></iconify-icon>
                                                                        Save
                                                                    </button>
                                                                    <button @click.stop="cancelEdit(subj.id)"
                                                                        class="inline-flex items-center gap-1 rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-white/5 px-2.5 py-1 text-[10px] font-medium text-slate-600 dark:text-slate-300 transition-colors w-full justify-center">
                                                                        Cancel
                                                                    </button>
                                                                </div>
                                                            </template>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </template>
                                </div>
                            </template>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-xs text-slate-400">
                            No sections found for SY {{ $schoolYear }}. Create sections in Section Management first.
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400">Showing {{ $allocations->firstItem() ?? 0 }}–{{ $allocations->lastItem() ?? 0 }} of {{ $allocations->total() }}</p>
                {{ $allocations->links() }}
            </div>

        </div>{{-- END ASSIGN TEACHER TAB --}}

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══ ADD / EDIT TEACHER MODAL ══ --}}
<div id="add-teacher-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTeacherModal('add-teacher-modal')"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 id="teacher-modal-title" class="text-white text-sm font-bold">ADD TEACHER</h3>
            <button onclick="closeTeacherModal('add-teacher-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div id="teacher-errors" class="mx-6 mt-4 hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700 space-y-0.5"></div>
        <form id="teacher-form" class="px-6 py-5 space-y-4">
            <input type="hidden" id="teacher-id" value="">

            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Personal Information</p>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">First Name <span class="text-red-500">*</span></label>
                    <input type="text" id="t-first-name" placeholder="e.g. Maria"
                        oninput="syncInstitutionalEmail()"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" id="t-last-name" placeholder="e.g. Santos"
                        oninput="syncInstitutionalEmail()"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Middle Name</label>
                    <input type="text" id="t-middle-name" placeholder="e.g. Cruz (optional)"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Contact Number</label>
                    <input type="text" id="t-contact" placeholder="e.g. 09XX-XXX-XXXX"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Account Information</p>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">School Email (auto-generated)</label>
                    <input type="email" id="t-inst-email" placeholder="Auto-generated from name" readonly
                        class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-400 text-slate-500 px-3 py-2.5 text-sm cursor-default focus:outline-none">
                    <p class="text-[10px] text-slate-400">Format: firstname.lastname@mmsc.edu.ph</p>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Personal Email <span class="text-slate-400">(for credentials)</span></label>
                    <input type="email" id="t-personal-email" placeholder="e.g. maria.santos@gmail.com"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-[10px] text-slate-400">Portal credentials will be sent here.</p>
                </div>
            </div>

            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Professional Information</p>
            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['t-rank','Academic Rank',['Teacher I','Teacher II','Teacher III','Master Teacher I','Master Teacher II','Head Teacher']],
                    ['t-employment','Employment Status',['Full Time','Part Time']],
                    ['t-dept','Department',['Science','English','Mathematics','Filipino','MAPEH','Social Studies','TLE','Values Education']],
                ] as [$eid,$elabel,$eopts])
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ $elabel }}</label>
                    <div class="relative">
                        <select id="{{ $eid }}" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— Select —</option>
                            @foreach($eopts as $opt)<option value="{{ $opt }}">{{ $opt }}</option>@endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                @endforeach

                {{-- Class Advisory — sections without adviser --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Class Advisory</label>
                    <div class="relative">
                        <select id="t-advisory-section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— No Advisory —</option>
                            @foreach($unassignedSections as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->display_name }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                    <p class="text-[10px] text-slate-400">Only sections without an assigned adviser are shown.</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Status</label>
                    <div class="relative">
                        <select id="t-status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="resigned">Resigned</option>
                            <option value="on_leave">On Leave</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Max Weekly Hours</label>
                    <input type="number" id="t-max-hours" min="1" max="80" value="40"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Specializations --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 block mb-2">Subject Specializations</label>
                <div class="grid grid-cols-3 gap-2 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                    @foreach(['Science','English','Mathematics','Filipino','MAPEH','Social Studies','TLE','Values Education','Statistics','Physics','Chemistry','Biology','History'] as $subj)
                    <label class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" value="{{ $subj }}" class="t-spec rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        {{ $subj }}
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Grade Levels --}}
            <div>
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 block mb-2">Assigned Grade Levels</label>
                <div class="grid grid-cols-4 gap-2 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                    @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $gl)
                    <label class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" value="{{ $gl }}" class="t-grade rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        {{ $gl }}
                    </label>
                    @endforeach
                </div>
            </div>

            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Availability</p>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Weekly Days Available</label>
                    <input type="number" id="t-days" min="1" max="7" value="5"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div></div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Available From</label>
                    <input type="time" id="t-avail-from" value="07:00"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Available To</label>
                    <input type="time" id="t-avail-to" value="17:00"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Lunch Start</label>
                    <input type="time" id="t-lunch-start" value="12:00"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Lunch End</label>
                    <input type="time" id="t-lunch-end" value="13:00"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="submitTeacher()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:diskette-bold" width="14"></iconify-icon>
                    <span id="teacher-btn-label">SAVE TEACHER</span>
                </button>
                <button type="button" onclick="closeTeacherModal('add-teacher-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ VIEW TEACHER MODAL ══ --}}
<div id="view-teacher-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTeacherModal('view-teacher-modal')"></div>
    <div class="relative w-full max-w-xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 id="view-teacher-title" class="text-white text-sm font-bold">TEACHER PROFILE</h3>
            <button onclick="closeTeacherModal('view-teacher-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div id="view-teacher-content" class="p-6">
            <div class="py-8 text-center text-xs text-slate-400">Loading...</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';
const BASE = '{{ url("/admin/teachers") }}';

function openTeacherModal(id)  { document.getElementById(id)?.classList.remove('hidden'); document.body.style.overflow='hidden'; }
function closeTeacherModal(id) { document.getElementById(id)?.classList.add('hidden');    document.body.style.overflow=''; }

function showToast(msg, type='success') {
    const t = document.createElement('div');
    t.className = 'fixed top-6 right-6 z-[200] flex items-center gap-2 rounded-xl border px-4 py-3 text-sm shadow-xl '
        + (type==='success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700');
    t.innerHTML = `<iconify-icon icon="solar:${type==='success'?'check-circle-bold':'close-circle-bold'}" width="16"></iconify-icon> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .3s'; setTimeout(()=>t.remove(),300); }, 3500);
}

function setSelect(id, val) {
    const el = document.getElementById(id);
    if (el) [...el.options].forEach(o => o.selected = (o.value===val || o.text===val));
}

function syncInstitutionalEmail() {
    const first = (document.getElementById('t-first-name').value || '').trim().toLowerCase().replace(/[^a-z]/g, '');
    const last  = (document.getElementById('t-last-name').value  || '').trim().toLowerCase().replace(/[^a-z]/g, '');
    const field = document.getElementById('t-inst-email');
    field.value = (first && last) ? `${first}.${last}@mmsc.edu.ph` : '';
}

function resetTeacherForm() {
    document.getElementById('teacher-id').value        = '';
    document.getElementById('t-first-name').value      = '';
    document.getElementById('t-last-name').value       = '';
    document.getElementById('t-middle-name').value     = '';
    document.getElementById('t-contact').value         = '';
    document.getElementById('t-inst-email').value      = '';
    document.getElementById('t-personal-email').value  = '';
    document.getElementById('t-inst-email').readOnly   = true;
    setSelect('t-advisory-section', '');
    document.getElementById('t-max-hours').value   = '40';
    document.getElementById('t-days').value        = '5';
    document.getElementById('t-avail-from').value  = '07:00';
    document.getElementById('t-avail-to').value    = '17:00';
    document.getElementById('t-lunch-start').value = '12:00';
    document.getElementById('t-lunch-end').value   = '13:00';
    setSelect('t-rank', '');
    setSelect('t-employment', '');
    setSelect('t-dept', '');
    setSelect('t-status', 'active');
    document.querySelectorAll('.t-spec').forEach(cb => cb.checked = false);
    document.querySelectorAll('.t-grade').forEach(cb => cb.checked = false);
    document.getElementById('teacher-modal-title').textContent = 'ADD TEACHER';
    document.getElementById('teacher-btn-label').textContent   = 'SAVE TEACHER';
    const errBox = document.getElementById('teacher-errors');
    if (errBox) { errBox.classList.add('hidden'); errBox.innerHTML = ''; }
}

// Search
document.getElementById('teacher-search')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.teacher-row').forEach(row =>
        row.style.display = (!q || (row.dataset.name||'').includes(q)) ? '' : 'none'
    );
});

function submitTeacher() {
    const id  = document.getElementById('teacher-id').value;
    const specializations = [...document.querySelectorAll('.t-spec:checked')].map(cb => cb.value);
    const grade_levels    = [...document.querySelectorAll('.t-grade:checked')].map(cb => cb.value);

    const payload = {
        first_name:             document.getElementById('t-first-name').value.trim(),
        last_name:              document.getElementById('t-last-name').value.trim(),
        middle_name:            document.getElementById('t-middle-name').value.trim() || null,
        contact_number:         document.getElementById('t-contact').value.trim() || null,
        personal_email:         document.getElementById('t-personal-email').value.trim() || null,
        academic_rank:          document.getElementById('t-rank').value,
        employment_status:      document.getElementById('t-employment').value,
        status:                 document.getElementById('t-status').value || 'active',
        department:             document.getElementById('t-dept').value,
        advisory_section_id:    document.getElementById('t-advisory-section').value || null,
        max_weekly_hours:       document.getElementById('t-max-hours').value,
        weekly_days_available:  document.getElementById('t-days').value,
        available_from:         document.getElementById('t-avail-from').value,
        available_to:           document.getElementById('t-avail-to').value,
        lunch_start:            document.getElementById('t-lunch-start').value,
        lunch_end:              document.getElementById('t-lunch-end').value,
        specializations,
        grade_levels,
    };

    if (!payload.first_name) { showToast('First name is required.','error'); return; }
    if (!payload.last_name)  { showToast('Last name is required.','error'); return; }

    const url    = id ? `${BASE}/${id}` : BASE;
    const method = id ? 'PUT' : 'POST';
    const errBox = document.getElementById('teacher-errors');
    errBox.classList.add('hidden'); errBox.innerHTML = '';

    fetch(url, { method, headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(payload) })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (ok && data.success) {
                closeTeacherModal('add-teacher-modal');
                showToast(data.message);
                setTimeout(() => location.reload(), 1400);
            } else if (data.errors) {
                const msgs = Object.values(data.errors).flat();
                errBox.innerHTML = msgs.map(m => `<p>• ${m}</p>`).join('');
                errBox.classList.remove('hidden');
            } else {
                showToast(data.message || 'Error saving teacher.', 'error');
            }
        }).catch(() => showToast('Request failed. Check your connection.', 'error'));
}

function viewTeacher(id) {
    document.getElementById('view-teacher-content').innerHTML = '<div class="py-8 text-center text-xs text-slate-400">Loading...</div>';
    openTeacherModal('view-teacher-modal');

    fetch(`${BASE}/${id}`)
        .then(r=>r.json())
        .then(({teacher:t}) => {
            document.getElementById('view-teacher-title').textContent = 'TEACHER — ' + t.name;
            const pct = t.max_weekly_hours > 0 ? Math.round((t.current_weekly_hours / t.max_weekly_hours)*100) : 0;
            const barColor = pct >= 100 ? 'bg-red-500' : pct >= 60 ? 'bg-green-500' : 'bg-blue-500';

            document.getElementById('view-teacher-content').innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4 rounded-xl border border-slate-200 dark:border-dark-border p-4 text-xs">
                    <div><p class="text-slate-400 mb-0.5">Teacher ID</p><p class="font-mono font-semibold text-slate-700 dark:text-slate-300">${t.teacher_id_code||'—'}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Full Name</p><p class="font-semibold text-slate-700 dark:text-slate-300">${t.name}</p></div>
                    <div><p class="text-slate-400 mb-0.5">School Email</p><p class="font-semibold text-slate-700 dark:text-slate-300">${t.email}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Personal Email</p><p class="font-semibold text-slate-700 dark:text-slate-300">${t.personal_email||'—'}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Contact No.</p><p class="font-semibold text-slate-700 dark:text-slate-300">${t.contact_number||'—'}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Academic Rank</p><p class="font-semibold text-slate-700 dark:text-slate-300">${t.academic_rank||'—'}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Employment</p><p class="font-semibold text-slate-700 dark:text-slate-300">${t.employment_status||'—'}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Department</p><p class="font-semibold text-slate-700 dark:text-slate-300">${t.department||'—'}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Class Advisory</p><p class="font-semibold text-slate-700 dark:text-slate-300">${t.advisory_class||'—'}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Status</p><p class="font-semibold capitalize text-slate-700 dark:text-slate-300">${(t.status||'').replace('_',' ')}</p></div>
                </div>
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4 text-xs space-y-2">
                    <p class="font-semibold text-slate-600 dark:text-slate-300 mb-2">Specializations</p>
                    <p class="text-slate-700 dark:text-slate-300">${(t.specializations||[]).join(', ')||'—'}</p>
                    <p class="font-semibold text-slate-600 dark:text-slate-300 mt-2 mb-1">Grade Levels</p>
                    <p class="text-slate-700 dark:text-slate-300">${(t.grade_levels||[]).join(', ')||'—'}</p>
                </div>
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4 text-xs space-y-2">
                    <p class="font-semibold text-slate-600 dark:text-slate-300 mb-2">Teaching Load</p>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex-1 h-2.5 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                            <div class="h-full rounded-full ${barColor}" style="width:${Math.min(100,pct)}%"></div>
                        </div>
                        <span class="text-xs font-semibold">${t.current_weekly_hours} / ${t.max_weekly_hours} hrs (${pct}%)</span>
                    </div>
                    <div class="flex gap-6 flex-wrap">
                        <div><span class="text-slate-400">Days/Wk: </span><span class="font-semibold text-slate-700 dark:text-slate-300">${t.weekly_days_available||5}</span></div>
                        <div><span class="text-slate-400">Available: </span><span class="font-semibold text-slate-700 dark:text-slate-300">${t.available_from||'—'} – ${t.available_to||'—'}</span></div>
                        <div><span class="text-slate-400">Lunch: </span><span class="font-semibold text-slate-700 dark:text-slate-300">${t.lunch_start||'—'} – ${t.lunch_end||'—'}</span></div>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                    <button onclick="closeTeacherModal('view-teacher-modal'); editTeacher(${t.id})"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                        <iconify-icon icon="solar:pen-bold" width="14"></iconify-icon> EDIT
                    </button>
                    <button onclick="closeTeacherModal('view-teacher-modal')"
                        class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CLOSE</button>
                </div>
            </div>`;
        }).catch(()=>showToast('Failed to load teacher.','error'));
}

function editTeacher(id) {
    fetch(`${BASE}/${id}`)
        .then(r=>r.json())
        .then(({teacher:t}) => {
            document.getElementById('teacher-id').value        = t.id;
            // Split name fields — fall back to parsing full name if new fields not yet set
            document.getElementById('t-first-name').value      = t.first_name || (t.name ? t.name.split(' ')[0] : '');
            document.getElementById('t-last-name').value       = t.last_name  || (t.name ? t.name.split(' ').slice(1).join(' ') : '');
            document.getElementById('t-middle-name').value     = t.middle_name || '';
            document.getElementById('t-contact').value         = t.contact_number || '';
            document.getElementById('t-personal-email').value  = t.personal_email || '';
            // Institutional email is fixed (already assigned) — show read-only
            document.getElementById('t-inst-email').value      = t.email || '';
            document.getElementById('t-inst-email').readOnly   = true;
            // Populate advisory section dropdown — ensure current section is present even if already assigned
            const advisorySel = document.getElementById('t-advisory-section');
            if (t.advisory_section_id) {
                const exists = [...advisorySel.options].some(o => o.value == t.advisory_section_id);
                if (!exists) {
                    const opt = new Option(t.advisory_class || ('Section ' + t.advisory_section_id), t.advisory_section_id);
                    advisorySel.appendChild(opt);
                }
            }
            setSelect('t-advisory-section', t.advisory_section_id ? String(t.advisory_section_id) : '');
            document.getElementById('t-max-hours').value    = t.max_weekly_hours ?? 40;
            document.getElementById('t-days').value         = t.weekly_days_available ?? 5;
            document.getElementById('t-avail-from').value   = t.available_from ?? '07:00';
            document.getElementById('t-avail-to').value     = t.available_to   ?? '17:00';
            document.getElementById('t-lunch-start').value  = t.lunch_start    ?? '12:00';
            document.getElementById('t-lunch-end').value    = t.lunch_end      ?? '13:00';
            setSelect('t-rank',       t.academic_rank       ?? '');
            setSelect('t-employment', t.employment_status   ?? '');
            setSelect('t-status',     t.status              ?? 'active');
            setSelect('t-dept',       t.department          ?? '');
            document.querySelectorAll('.t-spec').forEach(cb => cb.checked = (t.specializations||[]).includes(cb.value));
            document.querySelectorAll('.t-grade').forEach(cb => cb.checked = (t.grade_levels||[]).includes(cb.value));
            document.getElementById('teacher-modal-title').textContent = 'EDIT TEACHER — ' + t.name;
            document.getElementById('teacher-btn-label').textContent   = 'UPDATE TEACHER';
            const errBox = document.getElementById('teacher-errors');
            if (errBox) { errBox.classList.add('hidden'); errBox.innerHTML = ''; }
            openTeacherModal('add-teacher-modal');
        }).catch(()=>showToast('Failed to load teacher.','error'));
}

function deleteTeacher(id, name) {
    if (!confirm(`Deactivate teacher "${name}"?`)) return;
    fetch(`${BASE}/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} })
        .then(r=>r.json())
        .then(data => { showToast(data.message, data.success?'success':'error'); if (data.success) setTimeout(()=>location.reload(),1400); })
        .catch(()=>showToast('Request failed.','error'));
}

function teacherAssignApp() {
    return {
        expandedRow:      null,
        subjectsMap:      {},
        loadingRow:       null,
        editingTeacher:   {},
        teachersBySubject:{},
        loadingTeachers:  null,
        teacherSearch:    {},
        savingId:         null,

        async init() {},

        async toggleRow(sectionId, schoolYear) {
            if (this.expandedRow === sectionId) { this.expandedRow = null; return; }
            this.expandedRow = sectionId;
            if (!this.subjectsMap[sectionId]) await this.loadSubjects(sectionId, schoolYear);
        },

        async loadSubjects(sectionId, schoolYear) {
            this.loadingRow = sectionId;
            try {
                const r = await fetch(
                    `{{ route('admin.academic.section-subjects-teachers') }}?section_id=${sectionId}&school_year=${encodeURIComponent(schoolYear)}&_t=${Date.now()}`,
                    { headers: { 'Accept': 'application/json' }, cache: 'no-store' }
                );
                const j = await r.json();
                if (j.success) this.subjectsMap = { ...this.subjectsMap, [sectionId]: j.subjects };
            } catch (e) { console.error(e); }
            finally { this.loadingRow = null; }
        },

        async startEdit(subj, sectionId, schoolYear) {
            this.editingTeacher = { ...this.editingTeacher, [subj.id]: { teacher_id: subj.teacher_id ? String(subj.teacher_id) : '', teacher_name: subj.teacher_name || null } };
            this.teacherSearch  = { ...this.teacherSearch, [subj.id]: '' };
            if (!this.teachersBySubject[subj.id]) await this.loadTeachersForSubject(subj, sectionId, schoolYear);
        },

        async loadTeachersForSubject(subj, sectionId, schoolYear) {
            this.loadingTeachers = subj.id;
            try {
                const url = `{{ route('admin.academic.allocation.teachers-for-section') }}?section_id=${sectionId}&subject_id=${subj.subject_id || ''}&school_year=${encodeURIComponent(schoolYear)}&_t=${Date.now()}`;
                const r   = await fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' });
                const j   = await r.json();
                if (j.success) {
                    const currentId = subj.teacher_id ? String(subj.teacher_id) : null;
                    const filtered  = j.teachers.filter(t => (t.status !== 'full' && !t.conflict) || String(t.id) === currentId);
                    this.teachersBySubject = { ...this.teachersBySubject, [subj.id]: filtered };
                }
            } catch (e) { console.error(e); }
            finally { this.loadingTeachers = null; }
        },

        filteredTeachers(subjId) {
            const teachers = this.teachersBySubject[subjId] || [];
            const q = (this.teacherSearch[subjId] || '').toLowerCase().trim();
            return q ? teachers.filter(t => t.name.toLowerCase().includes(q)) : teachers;
        },

        selectedTeacherName(subjId) {
            const tid = this.editingTeacher[subjId]?.teacher_id;
            if (!tid) return '';
            const t = (this.teachersBySubject[subjId] || []).find(t => String(t.id) === String(tid));
            return t ? t.name : (this.editingTeacher[subjId]?.teacher_name || '');
        },

        selectTeacher(subjId, teacherId, teacherName) {
            this.editingTeacher = { ...this.editingTeacher, [subjId]: { ...this.editingTeacher[subjId], teacher_id: teacherId, teacher_name: teacherName } };
        },

        cancelEdit(subjId) {
            const ec = { ...this.editingTeacher }; delete ec[subjId]; this.editingTeacher = ec;
            const sc = { ...this.teacherSearch };   delete sc[subjId]; this.teacherSearch  = sc;
        },

        async saveTeacher(subj, sectionId) {
            const editing = this.editingTeacher[subj.id];
            if (!editing) return;
            this.savingId = subj.id;
            try {
                const r = await fetch('{{ route('admin.academic.assign-teacher') }}', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body:    JSON.stringify({ allocation_id: subj.id, teacher_id: editing.teacher_id || null }),
                });
                const j = await r.json();
                if (j.success) {
                    const tName = editing.teacher_name || null;
                    const subjects = (this.subjectsMap[sectionId] || []).map(s =>
                        s.id === subj.id ? { ...s, teacher_id: editing.teacher_id || null, teacher_name: tName } : s
                    );
                    this.subjectsMap = { ...this.subjectsMap, [sectionId]: subjects };
                    this.cancelEdit(subj.id);
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: j.message, type: 'success' } }));
                } else {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: j.message || 'Failed.', type: 'error' } }));
                }
            } catch (e) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Network error.', type: 'error' } }));
            } finally { this.savingId = null; }
        },
    };
}
</script>
@endpush
@endsection