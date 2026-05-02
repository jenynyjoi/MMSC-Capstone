@extends('layouts.admin_layout')
@section('title', 'Student List')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Page Header --}}
    <x-admin.page-header
        title="Student List"
        subtitle="List of Enrolled Student"
        school-year="2026–2027"
    />

    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:users-group-rounded-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Student List</h2>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-4 rounded-2xl border border-blue-200 bg-blue-50 dark:border-blue-900/30 dark:bg-blue-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                    <iconify-icon icon="solar:users-group-rounded-bold" width="22" class="text-blue-600 dark:text-blue-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1.5">Total Students</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-green-200 bg-green-50 dark:border-green-900/30 dark:bg-green-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/30">
                    <iconify-icon icon="solar:user-check-bold" width="22" class="text-green-600 dark:text-green-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">{{ $stats['active'] ?? 0 }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1.5">Active Students</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                    <iconify-icon icon="solar:clipboard-check-bold" width="22" class="text-amber-600 dark:text-amber-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">{{ $stats['cleared'] ?? 0 }}</p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1.5">Cleared Students</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mb-4">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by
            </div>
            <form method="GET" action="{{ route('admin.student-records.list') }}">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                        <div class="relative">
                            <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                @foreach(\App\Models\SchoolYear::orderByDesc('start_date')->get() as $sy)
                                <option value="{{ $sy->name }}" {{ $schoolYear === $sy->name ? 'selected' : '' }}>SY {{ $sy->name }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade Level</label>
                        <div class="relative">
                            <select name="grade_level" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All Grades</option>
                                <optgroup label="Elementary">
                                    @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'] as $g)
                                    <option value="{{ $g }}" {{ ($gradeLevel ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Junior High School">
                                    @foreach(['Grade 7','Grade 8','Grade 9','Grade 10'] as $g)
                                    <option value="{{ $g }}" {{ ($gradeLevel ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Senior High School">
                                    @foreach(['Grade 11','Grade 12'] as $g)
                                    <option value="{{ $g }}" {{ ($gradeLevel ?? '') === $g ? 'selected' : '' }}>{{ $g }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Section
                            @if($gradeLevel)
                            <span class="text-[10px] text-slate-400 font-normal">({{ $gradeLevel }})</span>
                            @endif
                        </label>
                        <div class="relative">
                            <select name="section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All Sections</option>
                                @forelse($sections as $sec)
                                <option value="{{ $sec->section_name }}" {{ ($sectionFilter ?? '') === $sec->section_name ? 'selected' : '' }}>
                                    {{ $gradeLevel ? $sec->section_name : $sec->grade_level . ' – ' . $sec->section_name }}
                                </option>
                                @empty
                                <option value="" disabled>No sections found</option>
                                @endforelse
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Student Status</label>
                        <div class="relative">
                            <select name="student_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="active" {{ request('student_status','active')==='active'?'selected':'' }}>Active</option>
                                <option value="" {{ request('student_status')===''?'selected':'' }}>All</option>
                                <option value="inactive"  {{ request('student_status')==='inactive'?'selected':'' }}>Inactive</option>
                                <option value="withdrawn" {{ request('student_status')==='withdrawn'?'selected':'' }}>Withdrawn</option>
                                <option value="graduated" {{ request('student_status')==='graduated'?'selected':'' }}>Graduated</option>
                                <option value="completed" {{ request('student_status')==='completed'?'selected':'' }}>Completed</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Academic Status</label>
                        <div class="relative">
                            <select name="academic_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <option value="passed"      {{ request('academic_status')==='passed'?'selected':'' }}>Passed</option>
                                <option value="failed"      {{ request('academic_status')==='failed'?'selected':'' }}>Failed</option>
                                <option value="in_progress" {{ request('academic_status')==='in_progress'?'selected':'' }}>In Progress</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Clearance Status</label>
                        <div class="relative">
                            <select name="clearance_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <option value="cleared" {{ request('clearance_status')==='cleared'?'selected':'' }}>Cleared</option>
                                <option value="pending" {{ request('clearance_status')==='pending'?'selected':'' }}>Pending</option>
                                <option value="overdue"  {{ request('clearance_status')==='overdue'?'selected':'' }}>Overdue</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                    </button>
                    <a href="{{ route('admin.student-records.list') }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">Clear All</a>
                </div>
            </form>
        </div>

        {{-- Table Controls --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <span>Show</span>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                    <option>5</option><option selected>10</option><option>25</option><option>50</option>
                </select>
                <span>Entries</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center rounded-lg border border-slate-200 dark:border-dark-border overflow-hidden text-xs font-semibold">
                    <button onclick="setLevelFilter('all',this)" class="level-tab px-4 py-1.5 bg-[#0d4c8f] text-white transition-colors">ALL</button>
                    <button onclick="setLevelFilter('elem',this)" class="level-tab px-4 py-1.5 bg-white dark:bg-dark-card text-slate-500 hover:bg-slate-50 transition-colors border-l border-slate-200 dark:border-dark-border">ELEM</button>
                    <button onclick="setLevelFilter('jhs',this)" class="level-tab px-4 py-1.5 bg-white dark:bg-dark-card text-slate-500 hover:bg-slate-50 transition-colors border-l border-slate-200 dark:border-dark-border">JHS</button>
                    <button onclick="setLevelFilter('shs',this)" class="level-tab px-4 py-1.5 bg-white dark:bg-dark-card text-slate-500 hover:bg-slate-50 transition-colors border-l border-slate-200 dark:border-dark-border">SHS</button>
                </div>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" id="student-search" placeholder="Search Student.."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:960px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3 w-10"><input type="checkbox" id="check-all" class="rounded text-blue-600 focus:ring-blue-500 cursor-pointer"></th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade and Section</th>
                        <th class="px-4 py-3 whitespace-nowrap">Clearance</th>
                        <th class="px-4 py-3 whitespace-nowrap">Behavioral</th>
                        <th class="px-4 py-3 whitespace-nowrap">Property</th>
                        <th class="px-4 py-3 whitespace-nowrap">Student Status</th>
                        <th class="px-4 py-3 whitespace-nowrap">Academic Status</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border" id="student-table-body">
                @php
                    $clrClass = [
                        'cleared' => 'bg-green-100 text-green-700',
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'overdue' => 'bg-red-100 text-red-700',
                    ];
                    $stsClass = [
                        'active'    => 'bg-green-100 text-green-700',
                        'inactive'  => 'bg-orange-100 text-orange-700',
                        'withdrawn' => 'bg-red-100 text-red-700',
                        'graduated' => 'bg-blue-100 text-blue-700',
                        'completed' => 'bg-purple-100 text-purple-700',
                    ];
                    $acaClass = [
                        'passed'      => 'bg-green-100 text-green-700',
                        'failed'      => 'bg-red-100 text-red-700',
                        'in_progress' => 'bg-blue-100 text-blue-700',
                    ];
                    $lvl = fn($g) => match(true) {
                        in_array($g, ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6']) => 'elem',
                        in_array($g, ['Grade 7','Grade 8','Grade 9','Grade 10'])                            => 'jhs',
                        default                                                                              => 'shs',
                    };
                @endphp

                @forelse ($students ?? [] as $student)
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors student-row"
                    data-level="{{ $lvl($student->grade_level) }}"
                    data-name="{{ strtolower($student->formatted_name) }}">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                            data-student-id="{{ $student->student_id }}"
                            data-student-name="{{ $student->formatted_name }}"
                            data-grade="{{ $student->section_display_name ?? ($student->grade_level . ' - ' . ($student->section_name ?? '—')) }}"
                            class="row-check rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </td>
                    <td class="px-4 py-3 text-xs font-mono text-slate-400">{{ $student->student_id }}</td>
                    <td class="px-4 py-3 text-xs text-slate-500">{{ $student->school_year }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0d4c8f]/10 dark:bg-blue-900/20 text-[11px] font-bold text-[#0d4c8f] dark:text-blue-400">
                                {{ strtoupper(substr($student->first_name,0,1)) }}{{ strtoupper(substr($student->last_name,0,1)) }}
                            </div>
                            <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                class="text-sm font-medium text-slate-800 dark:text-slate-100 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors whitespace-nowrap">
                                {{ $student->formatted_name }}
                            </a>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500">{{ $student->section_display_name ?? ($student->grade_level . ' - ' . ($student->section_name ?? '—')) }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $clrClass[$student->clearance_status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($student->clearance_status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $clrClass[$student->behavioral_clearance] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($student->behavioral_clearance) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $clrClass[$student->property_clearance] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($student->property_clearance) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $stsClass[$student->student_status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($student->student_status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $acaClass[$student->academic_status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst(str_replace('_',' ',$student->academic_status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition-colors" title="View Profile">
                                <iconify-icon icon="solar:user-bold" width="14"></iconify-icon>
                            </a>
                            <button type="button"
                                onclick="openWithdrawModal({{ $student->id }},'{{ addslashes($student->formatted_name) }}','{{ addslashes($student->student_id) }}','{{ addslashes($student->section_display_name ?? $student->grade_level . ' - ' . ($student->section_name ?? '-')) }}','{{ ucfirst($student->student_status) }}')"
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors" title="Withdraw Student">
                                <iconify-icon icon="solar:user-minus-bold" width="14"></iconify-icon>
                            </button>
                            <button type="button"
                                onclick="openArchiveModal({{ $student->id }},'{{ addslashes($student->formatted_name) }}','{{ addslashes($student->student_id) }}','{{ $student->student_status }}','{{ $student->clearance_status ?? 'pending' }}')"
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-500 transition-colors" title="Archive Student">
                                <iconify-icon icon="solar:archive-bold" width="14"></iconify-icon>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <iconify-icon icon="solar:users-group-rounded-linear" width="32" class="text-slate-300"></iconify-icon>
                            <p class="text-sm font-medium text-slate-500">No students found.</p>
                            <p class="text-xs text-slate-400">Students appear here after section assignment in Enrollment.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($students) && $students instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400">Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ $students->total() }}</p>
            {{ $students->links() }}
        </div>
        @elseif(isset($students))
        <div class="flex items-center justify-end px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1">
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon></button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">1</button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs">2</button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs">3</button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon></button>
            </div>
        </div>
        @endif

        {{-- Bulk Action Bar --}}
        <div id="bulk-bar" class="hidden items-center justify-between px-6 py-4 border-t border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:users-group-rounded-linear" width="16" class="text-slate-500"></iconify-icon>
                <span id="bulk-count" class="text-xs font-medium text-slate-600 dark:text-slate-300">0 Selected Student</span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button onclick="openExportModal()" class="flex items-center gap-1.5 rounded-lg border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 transition-colors">
                    <iconify-icon icon="solar:export-bold" width="13" class="text-green-600"></iconify-icon> Export Excel
                </button>
                <button class="flex items-center gap-1.5 rounded-lg border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 transition-colors">
                    <iconify-icon icon="solar:file-download-bold" width="13" class="text-red-500"></iconify-icon> Download PDF
                </button>
                <button class="flex items-center gap-1.5 rounded-lg border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-700 transition-colors">
                    <iconify-icon icon="solar:printer-bold" width="13" class="text-slate-600"></iconify-icon> Print
                </button>
                <button onclick="openSendNoticeModal()" class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:letter-bold" width="14"></iconify-icon> Send Notice
                </button>
            </div>
        </div>

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ═══ WITHDRAW MODAL ═══ --}}
<div id="withdraw-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="srCloseModal('withdraw-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-red-600 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 id="withdraw-title" class="text-white text-sm font-bold">WITHDRAW STUDENT</h3>
            <button onclick="srCloseModal('withdraw-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 p-4 text-xs space-y-1.5">
                <div class="flex gap-2"><span class="text-slate-400 w-28 shrink-0">Student ID:</span><span id="w-student-id" class="font-semibold text-slate-700 dark:text-slate-300">—</span></div>
                <div class="flex gap-2"><span class="text-slate-400 w-28 shrink-0">Student Name:</span><span id="w-student-name" class="font-semibold text-slate-700 dark:text-slate-300">—</span></div>
                <div class="flex gap-2"><span class="text-slate-400 w-28 shrink-0">Grade & Section:</span><span id="w-grade-section" class="font-semibold text-slate-700 dark:text-slate-300">—</span></div>
                <div class="flex gap-2"><span class="text-slate-400 w-28 shrink-0">Current Status:</span><span id="w-current-status" class="font-semibold text-green-600">—</span></div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Withdrawal Reason: <span class="text-red-500">*</span></label>
                <div class="space-y-2 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                    @foreach(['transfer'=>'Transfer to another school','financial'=>'Financial reasons','relocation'=>'Relocation','health'=>'Health reasons','academic'=>'Academic reasons','family'=>'Personal/Family reasons','other'=>'Other (please specify)'] as $val => $label)
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="radio" name="withdraw_reason" value="{{ $val }}" class="text-red-600 focus:ring-red-500 cursor-pointer" onchange="toggleOtherReason(this.value)">
                        <span class="text-xs text-slate-600 dark:text-slate-300">{{ $label }}</span>
                    </label>
                    @endforeach
                    <textarea id="other-reason-text" rows="2" placeholder="Please specify..."
                        class="hidden w-full mt-1 rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Effective Date:</label>
                <input type="date" id="withdraw-date" value="{{ date('Y-m-d') }}"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Withdrawal Details:</label>
                <textarea id="withdraw-details" rows="3" placeholder="e.g., Student is transferring to Manila Science High School"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
            </div>
            <div class="space-y-2.5 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" id="notify-guardian" checked class="rounded border-slate-300 text-red-500 focus:ring-red-400"><span class="text-xs font-medium text-slate-600 dark:text-slate-300">Send notification to parent/guardian</span></label>
                <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" id="process-refund" class="rounded border-slate-300 text-red-500 focus:ring-red-400"><span class="text-xs font-medium text-slate-600 dark:text-slate-300">Process refund (if applicable)</span></label>
                <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" id="clear-balance" class="rounded border-slate-300 text-red-500 focus:ring-red-400"><span class="text-xs font-medium text-slate-600 dark:text-slate-300">Clear outstanding balance</span></label>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmWithdraw()" class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:user-minus-bold" width="14"></iconify-icon> CONFIRM WITHDRAWAL
                </button>
                <button type="button" onclick="srCloseModal('withdraw-modal')" class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ ARCHIVE MODAL ═══ --}}
<div id="archive-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="srCloseModal('archive-modal')"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">

        {{-- Header --}}
        <div id="archive-modal-header" class="bg-purple-600 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:archive-bold" width="18" class="text-white/80"></iconify-icon>
                <h3 id="archive-modal-title" class="text-white text-sm font-bold">ARCHIVE STUDENT</h3>
            </div>
            <button onclick="srCloseModal('archive-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        <div class="px-6 py-5 space-y-4">

            {{-- Warning banner (shown when blocked) --}}
            <div id="archive-warning-banner" class="hidden rounded-xl border border-amber-200 bg-amber-50 dark:bg-amber-900/10 px-4 py-3 flex gap-3 items-start">
                <iconify-icon icon="solar:danger-triangle-bold" width="18" class="text-amber-500 mt-0.5 shrink-0"></iconify-icon>
                <div>
                    <p class="text-xs font-bold text-amber-700 dark:text-amber-400 mb-0.5">Cannot Archive This Student</p>
                    <p class="text-xs text-amber-600 dark:text-amber-500">This student is currently <strong>Active</strong> with <strong>Pending Clearance</strong>. Resolve the clearance before archiving.</p>
                </div>
            </div>

            {{-- Info row --}}
            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 px-4 py-3 space-y-1.5">
                <div class="flex gap-2 text-xs">
                    <span class="w-28 text-slate-400 shrink-0">Student ID</span>
                    <span id="archive-student-id" class="font-mono font-medium text-slate-700 dark:text-slate-300">—</span>
                </div>
                <div class="flex gap-2 text-xs">
                    <span class="w-28 text-slate-400 shrink-0">Name</span>
                    <span id="archive-student-name" class="font-medium text-slate-700 dark:text-slate-300">—</span>
                </div>
                <div class="flex gap-2 text-xs">
                    <span class="w-28 text-slate-400 shrink-0">Status</span>
                    <span id="archive-student-status" class="font-medium text-slate-700 dark:text-slate-300">—</span>
                </div>
                <div class="flex gap-2 text-xs">
                    <span class="w-28 text-slate-400 shrink-0">Clearance</span>
                    <span id="archive-clearance-status" class="font-medium text-slate-700 dark:text-slate-300">—</span>
                </div>
            </div>

            {{-- Confirmation message (shown when allowed) --}}
            <p id="archive-confirm-text" class="text-xs text-slate-500 dark:text-slate-400">
                This will move the student to the <strong>Archives</strong>. The record will be preserved but marked as archived.
            </p>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button id="archive-confirm-btn"
                    onclick="submitArchive()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                    <iconify-icon icon="solar:archive-bold" width="14"></iconify-icon>
                    CONFIRM ARCHIVE
                </button>
                <button type="button" onclick="srCloseModal('archive-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    CANCEL
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ SEND NOTICE MODAL ═══ --}}
<div id="send-notice-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="srCloseModal('send-notice-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-white text-sm font-bold">SEND NOTICE</h3>
            <button onclick="srCloseModal('send-notice-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="text-xs text-slate-600 dark:text-slate-300"><span class="text-slate-400">Selected Students:</span> <strong id="notice-count">0</strong></div>
            <div class="rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Selected Students:</p>
                <ul id="notice-student-list" class="space-y-1 text-xs text-slate-600 dark:text-slate-300 max-h-28 overflow-y-auto list-disc list-inside"></ul>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Notice Type:</label>
                <div class="space-y-2 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                    @foreach(['general'=>'General Announcement','payment'=>'Payment Reminder','clearance'=>'Clearance Reminder','academic'=>'Academic Warning','event'=>'Event Notification','schedule'=>'Schedule Change','other'=>'Other'] as $val => $label)
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="radio" name="notice_type" value="{{ $val }}" class="text-blue-600 focus:ring-blue-500 cursor-pointer">
                        <span class="text-xs text-slate-600 dark:text-slate-300">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Subject:</label>
                <input type="text" id="notice-subject" placeholder="e.g., Clearance Requirement Reminder"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Message/Reason:</label>
                <textarea id="notice-message" rows="6" placeholder="Dear Students/Parents,&#10;&#10;..."
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Send to:</label>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="send_to[]" value="student" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"><span class="text-xs text-slate-600 dark:text-slate-300">Student</span></label>
                    <label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="send_to[]" value="parent" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"><span class="text-xs text-slate-600 dark:text-slate-300">Parent/Guardian</span></label>
                </div>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmSendNotice()" class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:letter-bold" width="14"></iconify-icon> SEND NOTICE
                </button>
                <button type="button" onclick="srCloseModal('send-notice-modal')" class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ EXPORT MODAL ═══ --}}
<div id="export-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="srCloseModal('export-modal')"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl">
        <div class="bg-green-700 px-6 py-4 flex items-center justify-between">
            <h3 class="text-white text-sm font-bold">EXPORT TO EXCEL</h3>
            <button onclick="srCloseModal('export-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="text-xs text-slate-600 dark:text-slate-300"><span class="text-slate-400">Selected Students:</span> <strong id="export-count">0</strong></div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Export Options:</label>
                <div class="space-y-2 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                    @foreach(['include_id'=>['Include Student ID',true],'include_name'=>['Include Student Name',true],'include_grade'=>['Include Grade and Section',true],'include_sy'=>['Include School Year',true],'include_enroll'=>['Include Enrollment Date',true],'include_clearance'=>['Include Clearance Status',true],'include_status'=>['Include Student Status',true],'include_academic'=>['Include Academic Status',true],'include_parent'=>['Include Parent/Guardian Contact',false],'include_address'=>['Include Address',false]] as $key => [$label,$checked])
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="export_fields[]" value="{{ $key }}" {{ $checked?'checked':'' }} class="rounded border-slate-300 text-green-600 focus:ring-green-500">
                        <span class="text-xs text-slate-600 dark:text-slate-300">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">File Name:</label>
                <input type="text" id="export-filename" value="Student_List_SY2026-2027_{{ date('Ymd') }}"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="font-semibold text-slate-500">Format:</span>
                <div class="relative">
                    <select class="appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-green-500 pr-7">
                        <option>Excel (.xlsx)</option><option>CSV (.csv)</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmExport()" class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-green-700 hover:bg-green-800 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:export-bold" width="14"></iconify-icon> EXPORT
                </button>
                <button type="button" onclick="srCloseModal('export-modal')" class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function srOpenModal(id)  { document.getElementById(id)?.classList.remove('hidden'); document.body.style.overflow='hidden'; }
function srCloseModal(id) { document.getElementById(id)?.classList.add('hidden');    document.body.style.overflow=''; }

function showToast(msg,type='success') {
    const t=document.createElement('div');
    t.className='fixed top-6 right-6 z-[100] flex items-center gap-2 rounded-xl border px-4 py-3 text-sm shadow-lg '+(type==='success'?'border-green-200 bg-green-50 text-green-700':'border-red-200 bg-red-50 text-red-700');
    t.innerHTML=`<iconify-icon icon="solar:${type==='success'?'check-circle-bold':'close-circle-bold'}" width="16"></iconify-icon> ${msg}`;
    document.body.appendChild(t);
    setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300);},3500);
}

const checkAll=document.getElementById('check-all');
const bulkBar=document.getElementById('bulk-bar');
const bulkCount=document.getElementById('bulk-count');

checkAll?.addEventListener('change',function(){
    document.querySelectorAll('.row-check').forEach(cb=>cb.checked=this.checked);
    updateBulkBar();
});
document.querySelectorAll('.row-check').forEach(cb=>{
    cb.addEventListener('change',function(){
        updateBulkBar();
        const total=document.querySelectorAll('.row-check').length;
        const checked=document.querySelectorAll('.row-check:checked').length;
        if(checkAll){checkAll.checked=checked===total;checkAll.indeterminate=checked>0&&checked<total;}
    });
});

function updateBulkBar(){
    const n=document.querySelectorAll('.row-check:checked').length;
    if(bulkCount)bulkCount.textContent=n+' Selected Student';
    if(bulkBar){bulkBar.classList.toggle('hidden',n===0);bulkBar.classList.toggle('flex',n>0);}
}

function getSelectedStudents(){
    return[...document.querySelectorAll('.row-check:checked')].map(cb=>({id:cb.dataset.studentId,name:cb.dataset.studentName,grade:cb.dataset.grade,value:cb.value}));
}

function setLevelFilter(level,btn){
    document.querySelectorAll('.level-tab').forEach(b=>{b.classList.remove('bg-[#0d4c8f]','text-white');b.classList.add('bg-white','dark:bg-dark-card','text-slate-500');});
    btn.classList.add('bg-[#0d4c8f]','text-white');btn.classList.remove('bg-white','dark:bg-dark-card','text-slate-500');
    document.querySelectorAll('.student-row').forEach(row=>{row.style.display=(level==='all'||row.dataset.level===level)?'':'none';});
}

document.getElementById('student-search')?.addEventListener('input',function(){
    const q=this.value.toLowerCase().trim();
    document.querySelectorAll('.student-row').forEach(row=>{row.style.display=(!q||row.dataset.name.includes(q))?'':'none';});
});

let withdrawStudentId=null;
function openWithdrawModal(id,name,studentId,gradeSection,status){
    withdrawStudentId=id;
    document.getElementById('withdraw-title').textContent='WITHDRAW STUDENT — '+name;
    document.getElementById('w-student-id').textContent=studentId;
    document.getElementById('w-student-name').textContent=name;
    document.getElementById('w-grade-section').textContent=gradeSection;
    document.getElementById('w-current-status').textContent=status;
    document.querySelectorAll('input[name="withdraw_reason"]').forEach(r=>r.checked=false);
    document.getElementById('other-reason-text').classList.add('hidden');
    document.getElementById('withdraw-date').value=new Date().toISOString().split('T')[0];
    document.getElementById('withdraw-details').value='';
    document.getElementById('notify-guardian').checked=true;
    document.getElementById('process-refund').checked=false;
    document.getElementById('clear-balance').checked=false;
    srOpenModal('withdraw-modal');
}
function toggleOtherReason(val){document.getElementById('other-reason-text').classList.toggle('hidden',val!=='other');}
function confirmWithdraw(){
    const reason=document.querySelector('input[name="withdraw_reason"]:checked')?.value;
    if(!reason){showToast('Please select a withdrawal reason.','error');return;}
    const otherText=reason==='other'?document.getElementById('other-reason-text').value.trim():'';
    if(reason==='other'&&!otherText){showToast('Please specify the reason.','error');return;}
    fetch('{{ route("admin.student-records.withdraw") }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({student_id:withdrawStudentId,reason,other_reason:otherText,effective_date:document.getElementById('withdraw-date').value,details:document.getElementById('withdraw-details').value,notify_guardian:document.getElementById('notify-guardian').checked,process_refund:document.getElementById('process-refund').checked,clear_balance:document.getElementById('clear-balance').checked})})
    .then(r=>r.json()).then(data=>{srCloseModal('withdraw-modal');showToast(data.message,data.success?'success':'error');if(data.success)setTimeout(()=>location.reload(),1400);})
    .catch(()=>showToast('Request failed.','error'));
}

let archiveStudentId = null;
function openArchiveModal(id, name, studentId, status, clearance) {
    archiveStudentId = id;
    document.getElementById('archive-modal-title').textContent = 'ARCHIVE STUDENT — ' + name;
    document.getElementById('archive-student-id').textContent   = studentId;
    document.getElementById('archive-student-name').textContent = name;
    document.getElementById('archive-student-status').textContent  = status.charAt(0).toUpperCase() + status.slice(1);
    document.getElementById('archive-clearance-status').textContent = clearance.charAt(0).toUpperCase() + clearance.slice(1);

    const blocked = (status === 'active' && (clearance === 'pending' || clearance === ''));
    document.getElementById('archive-warning-banner').classList.toggle('hidden', !blocked);
    document.getElementById('archive-confirm-text').classList.toggle('hidden', blocked);
    document.getElementById('archive-confirm-btn').disabled = blocked;

    // Change header color to amber when blocked
    const header = document.getElementById('archive-modal-header');
    if (blocked) {
        header.classList.remove('bg-purple-600');
        header.classList.add('bg-amber-500');
    } else {
        header.classList.remove('bg-amber-500');
        header.classList.add('bg-purple-600');
    }

    srOpenModal('archive-modal');
}
function submitArchive() {
    const btn = document.getElementById('archive-confirm-btn');
    btn.disabled = true;
    btn.textContent = 'Archiving…';
    fetch('{{ route("admin.student-records.archive") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ student_id: archiveStudentId })
    })
    .then(r => r.json())
    .then(data => {
        srCloseModal('archive-modal');
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => location.reload(), 1400);
        else { btn.disabled = false; btn.innerHTML = '<iconify-icon icon="solar:archive-bold" width="14"></iconify-icon> CONFIRM ARCHIVE'; }
    })
    .catch(() => { showToast('Request failed.', 'error'); btn.disabled = false; });
}

function openSendNoticeModal(){
    const selected=getSelectedStudents();
    document.getElementById('notice-count').textContent=selected.length;
    document.getElementById('notice-student-list').innerHTML=selected.map(s=>`<li>${s.name} (${s.id}) — ${s.grade}</li>`).join('');
    document.querySelectorAll('input[name="notice_type"]').forEach(r=>r.checked=false);
    document.getElementById('notice-subject').value='';
    document.getElementById('notice-message').value='';
    srOpenModal('send-notice-modal');
}
function confirmSendNotice(){
    const noticeType=document.querySelector('input[name="notice_type"]:checked')?.value;
    const subject=document.getElementById('notice-subject').value.trim();
    const message=document.getElementById('notice-message').value.trim();
    if(!noticeType){showToast('Please select a notice type.','error');return;}
    if(!subject){showToast('Please enter a subject.','error');return;}
    if(!message){showToast('Please enter a message.','error');return;}
    fetch('{{ route("admin.student-records.send-notice") }}',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({student_ids:getSelectedStudents().map(s=>s.value),notice_type:noticeType,subject,message,send_to:[...document.querySelectorAll('input[name="send_to[]"]:checked')].map(cb=>cb.value)})})
    .then(r=>r.json()).then(data=>{srCloseModal('send-notice-modal');showToast(data.message||'Notice sent successfully.',data.success?'success':'error');})
    .catch(()=>showToast('Request failed.','error'));
}

function openExportModal(){document.getElementById('export-count').textContent=document.querySelectorAll('.row-check:checked').length;srOpenModal('export-modal');}
function confirmExport(){
    const fields=[...document.querySelectorAll('input[name="export_fields[]"]:checked')].map(cb=>cb.value);
    const filename=document.getElementById('export-filename').value.trim()||'Student_List';
    const studentIds=getSelectedStudents().map(s=>s.value);
    const form=document.createElement('form');
    form.method='POST';form.action='{{ route("admin.student-records.export") }}';
    const add=(n,v)=>{const i=document.createElement('input');i.type='hidden';i.name=n;i.value=v;form.appendChild(i);};
    add('_token','{{ csrf_token() }}');add('filename',filename);
    studentIds.forEach(id=>add('student_ids[]',id));
    fields.forEach(f=>add('fields[]',f));
    document.body.appendChild(form);form.submit();document.body.removeChild(form);
    srCloseModal('export-modal');
}
</script>
@endpush
@endsection