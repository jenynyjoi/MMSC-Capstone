@extends('layouts.admin_layout')

@section('title', 'Student List')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- ── Page Header ── --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Student Records</h1>
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">Student Record and Information</p>
        </div>
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">Current school year:</span>
            <div class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 shadow-sm">
                <span class="text-sm font-semibold text-slate-700 dark:text-white">SY 2025–2026</span>
                <button class="text-slate-400 hover:text-slate-600 transition-colors">
                    <iconify-icon icon="solar:menu-dots-bold" width="14"></iconify-icon>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- ── Card Header ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:users-group-rounded-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Student List</h2>
            </div>
            <div class="flex items-center gap-2">
                <button class="flex items-center gap-2 rounded-xl border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                    <iconify-icon icon="solar:user-plus-bold" width="14"></iconify-icon>
                    Add Student
                </button>
                <button class="flex items-center gap-2 rounded-xl border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                    <iconify-icon icon="solar:user-minus-bold" width="14"></iconify-icon>
                    Withdraw Student
                </button>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 px-6 py-5 border-b border-slate-100 dark:border-dark-border">

            <div class="flex items-center gap-4 rounded-2xl border border-blue-200 bg-blue-50 dark:border-blue-900/30 dark:bg-blue-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                    <iconify-icon icon="solar:users-group-rounded-bold" width="22" class="text-blue-600 dark:text-blue-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">0</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1.5 leading-tight">Total Students</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-green-200 bg-green-50 dark:border-green-900/30 dark:bg-green-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/30">
                    <iconify-icon icon="solar:user-check-bold" width="22" class="text-green-600 dark:text-green-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">0</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1.5 leading-tight">Active Students</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10 px-5 py-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                    <iconify-icon icon="solar:clipboard-check-bold" width="22" class="text-amber-600 dark:text-amber-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-2xl font-semibold text-slate-800 dark:text-white leading-none">0</p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1.5 leading-tight">Cleared Students</p>
                </div>
            </div>

        </div>

        {{-- ── Filters ── --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">

            {{-- Filter by label --}}
            <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mb-4">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon>
                Filter by
            </div>

            {{-- Row 1: School Year | Grade Level | Section --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-4">

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8 bg-slate-50 dark:bg-slate-800/40">
                            <option value=""></option>
                            <option>2025-2026</option>
                            <option>2024-2025</option>
                            <option>2023-2024</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade Level</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8 bg-slate-50 dark:bg-slate-800/40">
                            <option value=""></option>
                            <option>Pre School</option>
                            <option>Kinder</option>
                            <option>Grade 1</option>
                            <option>Grade 2</option>
                            <option>Grade 3</option>
                            <option>Grade 4</option>
                            <option>Grade 5</option>
                            <option>Grade 6</option>
                            <option>Grade 7</option>
                            <option>Grade 8</option>
                            <option>Grade 9</option>
                            <option>Grade 10</option>
                            <option>Grade 11</option>
                            <option>Grade 12</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Section</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8 bg-slate-50 dark:bg-slate-800/40">
                            <option value=""></option>
                            <option>Section A</option>
                            <option>Section B</option>
                            <option>Section C</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

            </div>

            {{-- Row 2: Student Status | Academic Status | Clearance Status --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-4">

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Student Status</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8 bg-slate-50 dark:bg-slate-800/40">
                            <option value=""></option>
                            <option>Active</option>
                            <option>Inactive</option>
                            <option>Withdrawn</option>
                            <option>Graduated</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Academic Status</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8 bg-slate-50 dark:bg-slate-800/40">
                            <option value=""></option>
                            <option>Passed</option>
                            <option>Failed</option>
                            <option>In Progress</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Clearance Status</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8 bg-slate-50 dark:bg-slate-800/40">
                            <option value=""></option>
                            <option>Cleared</option>
                            <option>Pending</option>
                            <option>Not Cleared</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

            </div>

            {{-- Apply / Clear All --}}
            <div class="flex items-center justify-end gap-2">
                <button class="flex items-center gap-1.5 rounded-lg bg-green-700 hover:bg-green-800 px-5 py-2 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon>
                    Apply
                </button>
                <button class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    Clear All
                </button>
            </div>

        </div>

        {{-- ── Table Controls ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <span>Show</span>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <span>entries</span>
            </div>
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" width="14"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                <input type="text" placeholder="Search..."
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white
                           pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
            </div>
        </div>

        {{-- ── Table ── --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="table-layout:fixed;min-width:860px">
                <colgroup>
                    <col style="width:100px">
                    <col style="width:90px">
                    <col style="width:140px">
                    <col style="width:100px">
                    <col style="width:75px">
                    <col style="width:110px">
                    <col style="width:120px">
                    <col style="width:110px">
                    <col style="width:120px">
                    <col style="width:110px">
                </colgroup>
                <thead>
                    <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3">Student ID</th>
                        <th class="px-4 py-3">School Year</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3">Grade Level</th>
                        <th class="px-4 py-3">Section</th>
                        <th class="px-4 py-3">Enrolled Date</th>
                        <th class="px-4 py-3">Clearance Status</th>
                        <th class="px-4 py-3">Student Status</th>
                        <th class="px-4 py-3">Academic Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                    @php
                        $students = [
                            ['232-555-09', '2025-2026', 'Jenny Orquiola',  'Grade 2', 'A', '1/20/26', 'Cleared',  'Active',    'Passed'],
                            ['232-555-09', '2025-2026', 'Jeneva Ybanez',   'Grade 2', 'A', '1/25/26', 'Pending',  'Inactive',  'In Progress'],
                            ['232-555-09', '2025-2026', 'Dianne Balaoro',  'Grade 2', 'A', '1/26/26', 'Pending',  'Withdrawn', 'Passed'],
                            ['232-555-09', '2025-2026', 'Hans Gayon',      'Grade 2', 'A', '1/7/26',  'Cleared',  'Graduated', 'Failed'],
                        ];

                        $clearanceClass = [
                            'Cleared'     => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'Pending'     => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                            'Not Cleared' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                        ];

                        $studentStatusClass = [
                            'Active'    => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'Inactive'  => 'bg-orange-100 text-orange-700 dark:bg-orange-900/20 dark:text-orange-400',
                            'Withdrawn' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                            'Graduated' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                        ];

                        $academicClass = [
                            'Passed'      => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'Failed'      => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                            'In Progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                        ];
                    @endphp

                    @foreach ($students as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500 truncate">{{ $row[0] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 truncate">{{ $row[1] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $row[2] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 truncate">{{ $row[3] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 text-center">{{ $row[4] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap">{{ $row[5] }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $clearanceClass[$row[6]] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row[6] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $studentStatusClass[$row[7]] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row[7] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $academicClass[$row[8]] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row[8] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                      
                            {{-- After (link to profile page) --}}
                            <a href="{{ route('admin.student-records.profile', ['id' => '232-555-09']) }}"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-amber-300
                                    bg-amber-50 hover:bg-amber-100 text-amber-700
                                    dark:border-amber-700 dark:bg-amber-900/20 dark:hover:bg-amber-900/40 dark:text-amber-400
                                    px-3 py-1 text-xs font-medium transition-colors">
                                <iconify-icon icon="solar:user-bold" width="13"></iconify-icon>
                                Profile
                            </a>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        <div class="flex items-center justify-end px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1">
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon>
                </button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">1</button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 text-xs transition-colors">2</button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 text-xs transition-colors">3</button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon>
                </button>
            </div>
        </div>

    </div>
    {{-- end main card --}}

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

</div>
@endsection