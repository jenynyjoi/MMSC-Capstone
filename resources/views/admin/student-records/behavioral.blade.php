@extends('layouts.admin_layout')

@section('title', 'Student Behavioral Record')

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
                <iconify-icon icon="solar:document-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Student Behavioral Record</h2>
            </div>
            {{-- Light blue outlined Add New Record button matching screenshot 2 --}}
            <button class="flex items-center gap-2 rounded-xl border border-blue-300 bg-blue-50 hover:bg-blue-100
                           dark:border-blue-700 dark:bg-blue-900/20 dark:hover:bg-blue-900/40
                           px-4 py-2 text-xs font-semibold text-blue-600 dark:text-blue-400 transition-colors">
                <iconify-icon icon="solar:add-circle-linear" width="15"></iconify-icon>
                Add New Record
            </button>
        </div>

        {{-- ── Filters ── --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">

            <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mb-4">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon>
                Filter by
            </div>

            {{-- Row 1: School Year | Grade Level | Section --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-4">

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
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
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
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
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
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

            {{-- Row 2: Behavior | Severity | Status --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-4">

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Behavior</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value=""></option>
                            <option>Bullying</option>
                            <option>Vandalism</option>
                            <option>Cheating</option>
                            <option>Tardiness</option>
                            <option>Disrespect</option>
                            <option>Fighting</option>
                            <option>Truancy</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Severity</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value=""></option>
                            <option>Minor</option>
                            <option>Moderate</option>
                            <option>Major</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Status</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value=""></option>
                            <option>Pending</option>
                            <option>Resolved</option>
                            <option>Escalated</option>
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
                    <col style="width:150px">
                    <col style="width:100px">
                    <col style="width:75px">
                    <col style="width:100px">
                    <col style="width:100px">
                    <col style="width:90px">
                    <col style="width:100px">
                    <col style="width:100px">
                    <col style="width:110px">
                </colgroup>
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3">Student ID</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3">Grade Level</th>
                        <th class="px-4 py-3">Section</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Behavior</th>
                        <th class="px-4 py-3">Severity</th>
                        <th class="px-4 py-3">Action</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                    @php
                        $records = [
                            ['232-555-09', 'Jenny Orquiola',  'Grade 2', 'A', '2026-03-14', 'Bullying',  'Minor',    'Warning',    'Pending'],
                            ['232-555-09', 'Jeneva Ybanez',   'Grade 6', 'B', '2026-02-20', 'Tardiness', 'Minor',    'Reminder',   'Resolved'],
                            ['232-555-09', 'Dianne Balaoro',  'Grade 9', 'A', '2026-01-10', 'Fighting',  'Major',    'Suspension', 'Escalated'],
                            ['232-555-09', 'Hans Gayon',      'Kinder',  'A', '2026-03-01', 'Vandalism', 'Moderate', 'Warning',    'Pending'],
                        ];

                        $severityClass = [
                            'Minor'    => 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                            'Moderate' => 'bg-orange-50 text-orange-700 dark:bg-orange-900/20 dark:text-orange-400',
                            'Major'    => 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                        ];

                        $statusClass = [
                            'Pending'   => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                            'Resolved'  => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'Escalated' => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                        ];
                    @endphp

                    @foreach ($records as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500 truncate">{{ $row[0] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $row[1] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 truncate">{{ $row[2] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 text-center">{{ $row[3] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap">{{ $row[4] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300 truncate">{{ $row[5] }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $severityClass[$row[6]] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row[6] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300 truncate">{{ $row[7] }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $statusClass[$row[8]] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row[8] }}
                            </span>
                        </td>
                        {{-- Select dropdown action button --}}
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open"
                                    class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f]
                                           bg-white hover:bg-slate-50 dark:border-slate-600 dark:bg-dark-card dark:hover:bg-white/5
                                           px-3 py-1 text-xs font-medium text-[#0d4c8f] dark:text-slate-300 transition-colors">
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
                                    <button class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:pen-linear" width="14" class="text-blue-500"></iconify-icon>
                                        Edit
                                    </button>
                                    <button class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                        <iconify-icon icon="solar:trash-bin-trash-linear" width="14"></iconify-icon>
                                        Delete
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