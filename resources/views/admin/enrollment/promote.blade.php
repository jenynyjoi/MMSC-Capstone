@extends('layouts.admin_layout')

@section('title', 'Promote Student')

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
        title="Enrollment"
        subtitle="Section Assignment and Student Promotion"
        school-year="{{ $activeSchoolYear ?? '2025-2026' }}"
    />

    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:upload-minimalistic-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <div>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Promote Student</h2>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Promote Student to Next Grade Level / School Year</p>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
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
                    <iconify-icon icon="solar:check-circle-bold" width="22" class="text-green-600 dark:text-green-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">{{ $stats['cleared'] ?? 0 }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1.5">Cleared</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-yellow-200 bg-yellow-50 dark:border-yellow-900/30 dark:bg-yellow-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-yellow-100 dark:bg-yellow-900/30">
                    <iconify-icon icon="solar:clock-circle-bold" width="22" class="text-yellow-600 dark:text-yellow-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1.5">Pending Clearance</p>
                </div>
            </div>
            <div class="flex items-center gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 dark:border-emerald-900/30 dark:bg-emerald-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30">
                    <iconify-icon icon="solar:upload-minimalistic-bold" width="22" class="text-emerald-600 dark:text-emerald-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">{{ $stats['promoted'] ?? 0 }}</p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1.5">Already Promoted</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mb-4">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by
            </div>
            <form method="GET" action="{{ route('admin.enrollment.promote') ?? '#' }}">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">

                    {{-- School Year --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year (current)</label>
                        <div class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-card px-3 py-2 text-xs text-slate-500 dark:text-slate-400 cursor-not-allowed">
                            {{ $activeSchoolYear ?? '2025 - 2026' }}
                        </div>
                    </div>

                    {{-- Grade and Section --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade and Section</label>
                        <div class="relative">
                            <select name="grade_section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <optgroup label="Elementary">
                                    @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'] as $g)
                                    <option value="{{ $g }}" {{ request('grade_section')===$g?'selected':'' }}>{{ $g }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Junior High School">
                                    @foreach(['Grade 7','Grade 8','Grade 9','Grade 10'] as $g)
                                    <option value="{{ $g }}" {{ request('grade_section')===$g?'selected':'' }}>{{ $g }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Senior High School">
                                    <option value="Grade 11" {{ request('grade_section')==='Grade 11'?'selected':'' }}>Grade 11</option>
                                    <option value="Grade 12" {{ request('grade_section')==='Grade 12'?'selected':'' }}>Grade 12</option>
                                </optgroup>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">

                    {{-- Final Results --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Final Results</label>
                        <div class="relative">
                            <select name="final_results" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <option value="passed"   {{ request('final_results')==='passed'?'selected':'' }}>Passed</option>
                                <option value="failed"   {{ request('final_results')==='failed'?'selected':'' }}>Failed</option>
                                <option value="retained" {{ request('final_results')==='retained'?'selected':'' }}>Retained</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Clearance Status --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Clearance Status</label>
                        <div class="relative">
                            <select name="clearance_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <option value="cleared" {{ request('clearance_status')==='cleared'?'selected':'' }}>Cleared</option>
                                <option value="pending" {{ request('clearance_status')==='pending'?'selected':'' }}>Pending</option>
                                <option value="overdue" {{ request('clearance_status')==='overdue'?'selected':'' }}>Overdue</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                    </button>
                    <a href="{{ route('admin.enrollment.promote') ?? '#' }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">Clear All</a>
                </div>
            </form>
        </div>

        {{-- Table Controls --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <span>Show</span>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                    <option>10</option><option>25</option><option>50</option>
                </select>
                <span>Entries</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center rounded-lg border border-slate-200 dark:border-dark-border overflow-hidden text-xs font-semibold">
                    <button onclick="setLevelFilter('all',this)" class="level-tab px-4 py-1.5 bg-[#0d4c8f] text-white transition-colors">ALL</button>
                    <button onclick="setLevelFilter('elem',this)" class="level-tab px-4 py-1.5 bg-white dark:bg-dark-card text-slate-500 hover:bg-slate-50 transition-colors border-l border-slate-200 dark:border-dark-border">ELEM</button>
                    <button onclick="setLevelFilter('jhs',this)"  class="level-tab px-4 py-1.5 bg-white dark:bg-dark-card text-slate-500 hover:bg-slate-50 transition-colors border-l border-slate-200 dark:border-dark-border">JHS</button>
                    <button onclick="setLevelFilter('shs',this)"  class="level-tab px-4 py-1.5 bg-white dark:bg-dark-card text-slate-500 hover:bg-slate-50 transition-colors border-l border-slate-200 dark:border-dark-border">SHS</button>
                </div>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" id="promote-search" placeholder="Search Student.."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:860px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" id="check-all"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Current Grade Level</th>
                        <th class="px-4 py-3 whitespace-nowrap">Final Results</th>
                        <th class="px-4 py-3 whitespace-nowrap">Clearance Status</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border" id="promote-table-body">

                @php

                    $clrClass = [
                        'cleared' => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                        'overdue' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                    ];

                    $resClass = [
                        'Passed'   => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                        'Failed'   => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                        'Retained' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/20 dark:text-orange-400',
                    ];

                    $lvl = fn($g) => match(true) {
                        in_array($g, ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6']) => 'elem',
                        in_array($g, ['Grade 7','Grade 8','Grade 9','Grade 10']) => 'jhs',
                        default => 'shs',
                    };
                @endphp

                @forelse($students as $student)
                @php $cleared = ($student->clearance_status ?? 'pending') === 'cleared'; @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors student-row"
                    data-level="{{ $lvl($student->grade_level) }}"
                    data-name="{{ strtolower($student->formatted_name) }}">

                    <td class="px-4 py-3">
                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                            data-student-id="{{ $student->student_id }}"
                            data-student-name="{{ $student->formatted_name }}"
                            data-grade="{{ $student->grade_level }}"
                            data-cleared="{{ $cleared ? '1' : '0' }}"
                            class="row-check rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </td>

                    <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500 whitespace-nowrap">{{ $student->student_id }}</td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0d4c8f]/10 dark:bg-blue-900/20 text-[11px] font-bold text-[#0d4c8f] dark:text-blue-400">
                                {{ strtoupper(substr($student->formatted_name, 0, 1)) }}{{ strtoupper(substr(strrchr($student->formatted_name,' ') ?: ' ', 1, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ $student->formatted_name }}</span>
                        </div>
                    </td>

                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $student->grade_level }}</td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $resClass[$student->final_results] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $student->final_results }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $clrClass[$student->clearance_status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($student->clearance_status) }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            {{-- View --}}
                            <a href="{{ route('admin.student-records.profile', $student->id) ?? '#' }}"
                                title="View Profile"
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:hover:bg-amber-900/40 dark:text-amber-400 transition-colors">
                                <iconify-icon icon="solar:user-bold" width="14"></iconify-icon>
                            </a>
                            {{-- Promote --}}
                            <button type="button"
                                title="{{ $cleared ? 'Promote Student' : 'Clearance required before promoting' }}"
                                onclick="{{ $cleared ? 'openPromoteModal('.$student->id.',\''.addslashes($student->formatted_name).'\',\''.addslashes($student->student_id).'\',\''.addslashes($student->grade_level).'\')' : '' }}"
                                {{ !$cleared ? 'disabled' : '' }}
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1 text-xs font-semibold text-white transition-colors
                                    {{ $cleared
                                        ? 'bg-green-600 hover:bg-green-700'
                                        : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed opacity-60' }}">
                                <iconify-icon icon="solar:upload-minimalistic-bold" width="13"></iconify-icon>
                                Promote
                            </button>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <iconify-icon icon="solar:users-group-rounded-linear" width="32" class="text-slate-300"></iconify-icon>
                            <p class="text-sm font-medium text-slate-500">No students found.</p>
                            <p class="text-xs text-slate-400">Adjust the filters and try again.</p>
                        </div>
                    </td>
                </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(isset($students) && is_object($students) && method_exists($students,'links'))
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400">Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ $students->total() }}</p>
            {{ $students->links() }}
        </div>
        @else
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400 dark:text-slate-500">Showing 1 to 4 of 4 entries</p>
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
        <div id="bulk-bar" class="hidden items-center justify-between px-6 py-4 border-t border-slate-200 dark:border-dark-border bg-green-50 dark:bg-green-900/10">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:users-group-rounded-linear" width="16" class="text-green-600 dark:text-green-400"></iconify-icon>
                <span id="bulk-count" class="text-xs font-medium text-green-700 dark:text-green-300">0 Selected Student</span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button id="bulk-promote-btn"
                    onclick="openBulkPromoteModal()"
                    class="flex items-center gap-2 rounded-lg bg-green-600 hover:bg-green-700 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                    <iconify-icon icon="solar:upload-minimalistic-bold" width="14"></iconify-icon>
                    Promote Selected
                </button>
            </div>
        </div>

    </div>
    {{-- end main card --}}

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

</div>

{{-- ═══ PROMOTE MODAL ═══ --}}
<div id="promote-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePromoteModal('promote-modal')"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-green-600 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:upload-minimalistic-bold" width="18" class="text-white/80"></iconify-icon>
                <h3 id="promote-modal-title" class="text-white text-sm font-bold">PROMOTE STUDENT</h3>
            </div>
            <button onclick="closePromoteModal('promote-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/40 px-4 py-3 space-y-1.5 text-xs">
                <div class="flex gap-2"><span class="w-28 text-slate-400 shrink-0">Student ID</span><span id="p-student-id" class="font-mono font-semibold text-slate-700 dark:text-slate-300">—</span></div>
                <div class="flex gap-2"><span class="w-28 text-slate-400 shrink-0">Name</span><span id="p-student-name" class="font-semibold text-slate-700 dark:text-slate-300">—</span></div>
                <div class="flex gap-2"><span class="w-28 text-slate-400 shrink-0">Current Grade</span><span id="p-grade" class="font-semibold text-slate-700 dark:text-slate-300">—</span></div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Promote to Grade Level: <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="p-next-grade" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 pr-8">
                        <option value="">— Select next grade level —</option>
                        @foreach(['Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12','Graduated'] as $g)
                        <option value="{{ $g }}">{{ $g }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">School Year: <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="p-next-sy" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 pr-8">
                        @foreach($nextSchoolYears ?? [] as $nsy)
                        <option value="{{ $nsy->name ?? '' }}">SY {{ $nsy->name ?? '' }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>

            <p class="text-xs text-slate-400 dark:text-slate-500">
                The student's record will be updated to the next grade level. Their current records will be preserved.
            </p>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmPromote()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:upload-minimalistic-bold" width="14"></iconify-icon>
                    CONFIRM PROMOTE
                </button>
                <button type="button" onclick="closePromoteModal('promote-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ BULK PROMOTE MODAL ═══ --}}
<div id="bulk-promote-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePromoteModal('bulk-promote-modal')"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-green-600 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:users-group-rounded-bold" width="18" class="text-white/80"></iconify-icon>
                <h3 class="text-white text-sm font-bold">BULK PROMOTE STUDENTS</h3>
            </div>
            <button onclick="closePromoteModal('bulk-promote-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="text-xs text-slate-600 dark:text-slate-300">
                <span class="text-slate-400">Selected Students:</span> <strong id="bulk-modal-count">0</strong>
            </div>
            <div class="rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3 max-h-32 overflow-y-auto">
                <ul id="bulk-student-list" class="space-y-1 text-xs text-slate-600 dark:text-slate-300 list-disc list-inside"></ul>
            </div>

            <div class="rounded-xl border border-amber-200 bg-amber-50 dark:bg-amber-900/10 px-4 py-3 text-xs text-amber-700 dark:text-amber-400 flex gap-2">
                <iconify-icon icon="solar:danger-triangle-bold" width="14" class="shrink-0 mt-0.5"></iconify-icon>
                Only students with <strong>Cleared</strong> clearance status will be promoted. Others will be skipped.
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Promote to School Year: <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="bulk-next-sy" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 pr-8">
                        @foreach($nextSchoolYears ?? [] as $nsy)
                        <option value="{{ $nsy->name ?? '' }}">SY {{ $nsy->name ?? '' }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmBulkPromote()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:upload-minimalistic-bold" width="14"></iconify-icon>
                    PROMOTE SELECTED
                </button>
                <button type="button" onclick="closePromoteModal('bulk-promote-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'fixed top-6 right-6 z-[200] flex items-center gap-2 rounded-xl border px-4 py-3 text-sm shadow-xl '
        + (type === 'success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700');
    t.innerHTML = `<iconify-icon icon="solar:${type==='success'?'check-circle-bold':'close-circle-bold'}" width="16"></iconify-icon> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .3s'; setTimeout(()=>t.remove(),300); }, 3500);
}

// ── Checkboxes & bulk bar ────────────────────────────────
const checkAll = document.getElementById('check-all');
const bulkBar  = document.getElementById('bulk-bar');
const bulkCount= document.getElementById('bulk-count');

checkAll?.addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
    updateBulkBar();
});

document.querySelectorAll('.row-check').forEach(cb => {
    cb.addEventListener('change', function() {
        updateBulkBar();
        const total   = document.querySelectorAll('.row-check').length;
        const checked = document.querySelectorAll('.row-check:checked').length;
        if (checkAll) { checkAll.checked = checked === total; checkAll.indeterminate = checked > 0 && checked < total; }
    });
});

function updateBulkBar() {
    const n = document.querySelectorAll('.row-check:checked').length;
    if (bulkCount) bulkCount.textContent = n + (n === 1 ? ' Selected Student' : ' Selected Students');
    if (bulkBar) { bulkBar.classList.toggle('hidden', n === 0); bulkBar.classList.toggle('flex', n > 0); }
}

function getSelectedStudents() {
    return [...document.querySelectorAll('.row-check:checked')].map(cb => ({
        id:      cb.value,
        sid:     cb.dataset.studentId,
        name:    cb.dataset.studentName,
        grade:   cb.dataset.grade,
        cleared: cb.dataset.cleared === '1',
    }));
}

// ── Level filter pills ───────────────────────────────────
function setLevelFilter(level, btn) {
    document.querySelectorAll('.level-tab').forEach(b => {
        b.classList.remove('bg-[#0d4c8f]','text-white');
        b.classList.add('bg-white','dark:bg-dark-card','text-slate-500');
    });
    btn.classList.add('bg-[#0d4c8f]','text-white');
    btn.classList.remove('bg-white','dark:bg-dark-card','text-slate-500');
    document.querySelectorAll('.student-row').forEach(row =>
        row.style.display = (level === 'all' || row.dataset.level === level) ? '' : 'none'
    );
}

// ── Search ───────────────────────────────────────────────
document.getElementById('promote-search')?.addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.student-row').forEach(row =>
        row.style.display = (!q || row.dataset.name.includes(q)) ? '' : 'none'
    );
});

// ── Promote Modal (single) ───────────────────────────────
let promoteStudentId = null;

function openPromoteModal(id, name, studentId, grade) {
    promoteStudentId = id;
    document.getElementById('promote-modal-title').textContent = 'PROMOTE STUDENT — ' + name;
    document.getElementById('p-student-id').textContent   = studentId;
    document.getElementById('p-student-name').textContent = name;
    document.getElementById('p-grade').textContent        = grade;
    document.getElementById('p-next-grade').value         = '';
    openModal('promote-modal');
}

function confirmPromote() {
    const nextGrade = document.getElementById('p-next-grade').value;
    const nextSy    = document.getElementById('p-next-sy').value;
    if (!nextGrade) { showToast('Please select the next grade level.', 'error'); return; }

    fetch('{{ route("admin.enrollment.promote.single") ?? "#" }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ student_id: promoteStudentId, next_grade: nextGrade, school_year: nextSy }),
    })
    .then(r => r.json())
    .then(data => {
        closePromoteModal('promote-modal');
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => location.reload(), 1400);
    })
    .catch(() => showToast('Request failed.', 'error'));
}

// ── Bulk Promote Modal ───────────────────────────────────
function openBulkPromoteModal() {
    const selected = getSelectedStudents();
    document.getElementById('bulk-modal-count').textContent = selected.length;
    document.getElementById('bulk-student-list').innerHTML  = selected.map(s =>
        `<li>${s.name} (${s.sid}) — ${s.grade}${!s.cleared ? ' <span class="text-amber-600 font-semibold">[Pending]</span>' : ''}</li>`
    ).join('');
    openModal('bulk-promote-modal');
}

function confirmBulkPromote() {
    const nextSy   = document.getElementById('bulk-next-sy').value;
    const selected = getSelectedStudents();
    const ids      = selected.map(s => s.id);

    fetch('{{ route("admin.enrollment.promote.bulk") ?? "#" }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ student_ids: ids, school_year: nextSy }),
    })
    .then(r => r.json())
    .then(data => {
        closePromoteModal('bulk-promote-modal');
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => location.reload(), 1400);
    })
    .catch(() => showToast('Request failed.', 'error'));
}

function openModal(id)  { document.getElementById(id)?.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
function closePromoteModal(id) { document.getElementById(id)?.classList.add('hidden'); document.body.style.overflow = ''; }
</script>
@endpush

@endsection