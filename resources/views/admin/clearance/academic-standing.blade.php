@extends('layouts.admin_layout')

@section('title', 'Academic Standing')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- ── Page Header ── --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Clearance</h1>
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">Student Requirements Validation</p>
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
        <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:clipboard-check-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Academic Standing</h2>
        </div>

        {{-- ── Filters ── --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">

            {{-- 4-column filter row --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-4">

                {{-- School Year --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option>SY 2025-2026</option>
                            <option>SY 2024-2025</option>
                            <option>SY 2023-2024</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                {{-- Grade and Section --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade and Section</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">All</option>
                            <option>7 - A</option>
                            <option>7 - B</option>
                            <option>8 - A</option>
                            <option>8 - B</option>
                            <option>9 - A</option>
                            <option>10 - A</option>
                            <option>11 - A</option>
                            <option>12 - A</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                {{-- Quarter --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Quarter</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option>Q1</option>
                            <option>Q2</option>
                            <option>Q3</option>
                            <option>Q4</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                {{-- Status --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Status</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">All</option>
                            <option>Pending</option>
                            <option>Cleared</option>
                            <option>Not Cleared</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

            </div>

            {{-- Apply / Clear All --}}
            <div class="flex items-center justify-end gap-2">
                <button class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
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
            <table class="w-full text-left text-sm" style="table-layout:fixed;min-width:760px">
                <colgroup>
                    <col style="width:100px">
                    <col style="width:130px">
                    <col style="width:130px">
                    <col style="width:110px">
                    <col style="width:80px">
                    <col style="width:100px">
                    <col style="width:90px">
                    <col style="width:100px">
                    <col style="width:100px">
                </colgroup>
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3">Student ID</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3">Grade and Section</th>
                        <th class="px-4 py-3">Adviser</th>
                        <th class="px-4 py-3">Quarter</th>
                        <th class="px-4 py-3">Final Average</th>
                        <th class="px-4 py-3">Remarks</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                    @php
                        $students = [
                            ['232-555-09', 'Jenny Orquiola',  '7 - A', 'Teacher 1', 'Q1', '88', 'Passed', 'Pending'],
                            ['232-555-09', 'Jeneva Ybanez',   '7 - A', 'Teacher 1', 'Q1', '93', 'Passed', 'Cleared'],
                            ['232-555-09', 'Dianne Balaoro',  '7 - A', 'Teacher 2', 'Q1', '97', 'Passed', 'Pending'],
                            ['232-555-09', 'Hans Gayon',      '7 - A', 'Teacher 3', 'Q1', '90', 'Passed', 'Pending'],
                        ];

                        $statusClass = [
                            'Pending'     => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                            'Cleared'     => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'Not Cleared' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                        ];
                    @endphp

                    @foreach ($students as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500 truncate">{{ $row[0] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $row[1] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 truncate">{{ $row[2] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 truncate">{{ $row[3] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $row[4] }}</td>
                        <td class="px-4 py-3 text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $row[5] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $row[6] }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $statusClass[$row[7]] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row[7] }}
                            </span>
                        </td>
                        {{-- Select dropdown --}}
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open"
                                    class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f]
                                           bg-white hover:bg-blue-50 dark:border-blue-700 dark:bg-dark-card dark:hover:bg-white/5
                                           px-3 py-1 text-xs font-medium text-[#0d4c8f] dark:text-blue-400 transition-colors">
                                    Select
                                    <iconify-icon icon="solar:alt-arrow-down-linear" width="12"
                                        :class="open ? 'rotate-180' : ''"
                                        class="transition-transform duration-200"></iconify-icon>
                                </button>
                                <div x-show="open"
                                     @click.outside="open = false"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 z-10 mt-1 w-36 rounded-xl border border-slate-200
                                            dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1">
                                    <button class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:eye-linear" width="14" class="text-amber-500"></iconify-icon>
                                        View
                                    </button>
                                    <button class="flex w-full items-center gap-2 px-3 py-2 text-xs text-green-600 hover:bg-green-50 dark:hover:bg-green-900/10 transition-colors">
                                        <iconify-icon icon="solar:check-circle-linear" width="14"></iconify-icon>
                                        Clear
                                    </button>
                                    <button class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                        <iconify-icon icon="solar:close-circle-linear" width="14"></iconify-icon>
                                        Not Clear
                                    </button>
                                </div>
                            </div>
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