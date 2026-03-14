@extends('layouts.admin')

@section('title', 'Admission')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- ── Page Header ── --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Admission</h1>
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">Screening and Approval</p>
        </div>
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <span class="text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">Current School Year:</span>
            <div class="flex items-center gap-1 rounded-lg border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card px-3 py-1.5 shadow-sm">
                <span class="text-sm font-semibold text-slate-700 dark:text-white">SY 2025-2026</span>
                <button class="ml-1 text-slate-400 hover:text-slate-600 transition-colors">
                    <iconify-icon icon="solar:menu-dots-bold" width="16"></iconify-icon>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card">

        {{-- Card Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between px-6 pt-6 pb-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:clipboard-list-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white underline underline-offset-2">Applicant</h2>
            </div>
            <button class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition-colors shadow-sm">
                <iconify-icon icon="solar:add-circle-linear" width="18"></iconify-icon>
                Add Applicant
            </button>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-2 gap-3 px-6 py-5 lg:grid-cols-4">

            {{-- Successfully Admitted --}}
            <div class="flex items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 dark:border-blue-900/30 dark:bg-blue-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <iconify-icon icon="solar:user-check-bold" width="20" class="text-blue-600 dark:text-blue-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">0</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight">Successfully Admitted</p>
                </div>
            </div>

            {{-- Pending Application --}}
            <div class="flex items-center gap-3 rounded-xl border border-orange-100 bg-orange-50 dark:border-orange-900/30 dark:bg-orange-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/30">
                    <iconify-icon icon="solar:clock-circle-bold" width="20" class="text-orange-500 dark:text-orange-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">0</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight">Pending Application</p>
                </div>
            </div>

            {{-- Incomplete --}}
            <div class="flex items-center gap-3 rounded-xl border border-yellow-100 bg-yellow-50 dark:border-yellow-900/30 dark:bg-yellow-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-yellow-100 dark:bg-yellow-900/30">
                    <iconify-icon icon="solar:file-corrupted-bold" width="20" class="text-yellow-500 dark:text-yellow-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">0</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight">Incomplete</p>
                </div>
            </div>

            {{-- Rejected --}}
            <div class="flex items-center gap-3 rounded-xl border border-red-100 bg-red-50 dark:border-red-900/30 dark:bg-red-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/30">
                    <iconify-icon icon="solar:trash-bin-trash-bold" width="20" class="text-red-500 dark:text-red-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">0</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-tight">Rejected</p>
                </div>
            </div>

        </div>

        {{-- ── Filters Row ── --}}
        <div class="px-6 pb-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-3">
                <div class="flex items-center gap-1 text-sm text-slate-500 dark:text-slate-400">
                    <iconify-icon icon="solar:filter-linear" width="16"></iconify-icon>
                    <span class="font-medium">Filter by</span>
                </div>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="16"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input type="text" placeholder="Search..."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white
                               pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-52">
                </div>
            </div>

            {{-- Filter Dropdowns --}}
            <div class="flex flex-wrap items-center gap-2">
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300
                               px-3 py-1.5 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Status</option>
                    <option>Pending</option>
                    <option>Complete</option>
                    <option>Incomplete</option>
                    <option>Rejected</option>
                </select>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300
                               px-3 py-1.5 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Date</option>
                    <option>Newest</option>
                    <option>Oldest</option>
                </select>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300
                               px-3 py-1.5 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Grade</option>
                    <option>Pre School</option>
                    <option>Elementary</option>
                    <option>Junior High</option>
                    <option>Senior High</option>
                </select>
                <button class="flex items-center gap-1.5 rounded-lg bg-green-600 hover:bg-green-700 px-4 py-1.5 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:filter-bold" width="14"></iconify-icon>
                    Apply
                </button>
                <button class="rounded-lg border border-slate-200 dark:border-dark-border px-4 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    Clear All
                </button>
            </div>
        </div>

        {{-- ── Table ── --}}
        <div class="overflow-x-auto border-t border-slate-100 dark:border-dark-border">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-white/5">
                        <th class="px-6 py-3">Applicant ID</th>
                        <th class="px-6 py-3">Applicant Name</th>
                        <th class="px-6 py-3">Level Applied For</th>
                        <th class="px-6 py-3">Grade / Program</th>
                        <th class="px-6 py-3">Application Type</th>
                        <th class="px-6 py-3">Date Applied</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @php
                        $applicants = [
                            ['232-555-09', 'Jenny Orquiola',  'Senior High', 'STEM (12)', 'New',      '1/20/26', 'Pending'],
                            ['232-555-09', 'Jeneva Ybanez',   'Elementary',  'Grade 6',   'Return',   '1/25/26', 'Incomplete'],
                            ['232-555-09', 'Dianne Balaoro',  'Junior High', 'Grade 9',   'Transfer', '1/26/26', 'Complete'],
                            ['232-555-09', 'Hans Gayon',      'Pre School',  'Kinder',    'New',      '1/7/26',  'Rejected'],
                        ];

                        $statusColors = [
                            'Pending'    => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400',
                            'Incomplete' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/20 dark:text-orange-400',
                            'Complete'   => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'Rejected'   => 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400',
                        ];
                    @endphp

                    @foreach ($applicants as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-3 text-xs text-slate-500 dark:text-slate-400 font-mono">{{ $row[0] }}</td>
                        <td class="px-6 py-3 font-medium text-slate-700 dark:text-slate-300 text-sm">{{ $row[1] }}</td>
                        <td class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $row[2] }}</td>
                        <td class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $row[3] }}</td>
                        <td class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $row[4] }}</td>
                        <td class="px-6 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $row[5] }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $statusColors[$row[6]] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row[6] }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center justify-center gap-2">
                                {{-- View --}}
                                <button class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition-colors">
                                    <iconify-icon icon="solar:eye-bold" width="14"></iconify-icon>
                                </button>
                                {{-- Edit --}}
                                <button class="flex h-7 w-7 items-center justify-center rounded-lg bg-green-500 hover:bg-green-600 text-white transition-colors">
                                    <iconify-icon icon="solar:pen-bold" width="14"></iconify-icon>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <span>Show</span>
                <select class="rounded border border-slate-200 dark:border-dark-border dark:bg-dark-card px-2 py-1 text-xs focus:outline-none">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <span>Entries</span>
            </div>
            <div class="flex items-center gap-1">
                <button class="flex h-7 w-7 items-center justify-center rounded border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-left-linear" width="14"></iconify-icon>
                </button>
                <button class="flex h-7 w-7 items-center justify-center rounded bg-[#0d4c8f] text-white text-xs font-semibold">1</button>
                <button class="flex h-7 w-7 items-center justify-center rounded border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 text-xs transition-colors">2</button>
                <button class="flex h-7 w-7 items-center justify-center rounded border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 text-xs transition-colors">3</button>
                <button class="flex h-7 w-7 items-center justify-center rounded border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14"></iconify-icon>
                </button>
            </div>
        </div>

    </div>
    {{-- end main card --}}

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

</div>
@endsection