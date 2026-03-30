@extends('layouts.admin_layout')


@section('title', 'Student Archives')

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
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 mb-1">
                <iconify-icon icon="solar:users-group-rounded-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Student Archives</h2>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 ml-7">This section contains student records older than three years</p>
        </div>

        {{-- ── Filters ── --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">

            {{-- Filter by label --}}
            <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mb-4">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon>
                Filter by
            </div>

            {{-- Filter Row: School Year | Status | Apply | Clear All --}}
            <div class="flex flex-wrap items-end gap-4 mb-0">

                {{-- School Year --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                    <div class="relative">
                        <select class="appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8 w-48">
                            <option value="">Select School Year</option>
                            <option>2022-2023</option>
                            <option>2021-2022</option>
                            <option>2020-2021</option>
                            <option>2019-2020</option>
                            <option>2018-2019</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                {{-- Status --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Status:</label>
                    <div class="relative">
                        <select class="appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8 w-36">
                            <option value="">All</option>
                            <option>Graduated</option>
                            <option>Withdrawn</option>
                            <option>Completed</option>
                            <option>Transferred</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                {{-- Apply / Clear All --}}
                <button class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors mb-0.5">
                    <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon>
                    Apply
                </button>
                <button class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors mb-0.5">
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
            <table class="w-full text-left text-sm" style="table-layout:fixed;min-width:780px">
                <colgroup>
                    <col style="width:100px">
                    <col style="width:100px">
                    <col style="width:150px">
                    <col style="width:110px">
                    <col style="width:80px">
                    <col style="width:100px">
                    <col style="width:120px">
                    <col style="width:120px">
                    <col style="width:70px">
                </colgroup>
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3">Student ID</th>
                        <th class="px-4 py-3">School Year</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3">Grade Level</th>
                        <th class="px-4 py-3">Section</th>
                        <th class="px-4 py-3">Exit date</th>
                        <th class="px-4 py-3">Student Status</th>
                        <th class="px-4 py-3">Clearance Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                    @php
                        $archives = [
                            ['232-555-09', '2022-2023', 'Jenny Orquiola',  '11/4/2022', 'A', '11/4/2022', 'Graduated', 'Cleared'],
                            ['232-555-09', '2022-2023', 'Jeneva Ybanez',   '11/4/2022', 'A', '11/4/2022', 'Withdrawn', 'Cleared'],
                            ['232-555-09', '2022-2023', 'Dianne Balaoro',  '11/4/2022', 'A', '11/4/2022', 'Completed', 'Cleared'],
                            ['232-555-09', '2022-2023', 'Hans Gayon',      '11/4/2022', 'A', '11/4/2022', 'Graduated', 'Cleared'],
                        ];

                        $studentStatusClass = [
                            'Graduated'   => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'Withdrawn'   => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                            'Completed'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                            'Transferred' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/20 dark:text-purple-400',
                        ];

                        $clearanceClass = [
                            'Cleared'     => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'Pending'     => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                            'Not Cleared' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                        ];
                    @endphp

                    @foreach ($archives as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500 truncate">{{ $row[0] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 truncate">{{ $row[1] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $row[2] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 truncate">{{ $row[3] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 text-center">{{ $row[4] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap">{{ $row[5] }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $studentStatusClass[$row[6]] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row[6] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $clearanceClass[$row[7]] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row[7] }}
                            </span>
                        </td>
                        {{-- Eye button only ── --}}
                        <td class="px-4 py-3 text-center">
                            <button title="View"
                                class="flex h-7 w-7 items-center justify-center rounded-lg mx-auto
                                       bg-amber-50 hover:bg-amber-100 text-amber-600
                                       dark:bg-amber-900/20 dark:hover:bg-amber-900/40 dark:text-amber-400
                                       transition-colors">
                                <iconify-icon icon="solar:eye-bold" width="14"></iconify-icon>
                            </button>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400 dark:text-slate-500">Showing 1 to 4 of 4 entries</p>
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