@extends('layout.admin_layout')

@section('title', 'Enrollment')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Enrollment</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage student enrollments for the current school year.</p>
        </div>
        <button class="flex items-center gap-2 rounded-xl bg-[#0d4c8f] px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
            <iconify-icon icon="solar:add-circle-linear" width="18"></iconify-icon>
            Enroll Student
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 mb-6">
        @foreach ([
            ['Pre School',   '45',  'bg-yellow-50', 'text-yellow-600'],
            ['Elementary',   '120', 'bg-green-50',  'text-green-600'],
            ['Junior High',  '98',  'bg-blue-50',   'text-blue-600'],
            ['Senior High',  '76',  'bg-purple-50', 'text-purple-600'],
        ] as $stat)
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
            <p class="text-xs text-slate-400 mb-1">{{ $stat[0] }}</p>
            <h3 class="text-3xl font-bold {{ $stat[2] }} {{ $stat[3] }} -mx-5 -mb-5 mt-2 px-5 py-3 rounded-b-xl">{{ $stat[1] }}</h3>
        </div>
        @endforeach
    </div>

    <!-- Table -->
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Enrolled Students</h3>
            <input type="text" placeholder="Search..." class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-dark-border dark:bg-dark-card dark:text-white">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-medium text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-3">Student</th>
                        <th class="px-6 py-3">Level</th>
                        <th class="px-6 py-3">Section</th>
                        <th class="px-6 py-3">School Year</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @foreach ([
                        ['Ana Gonzales',  'Grade 8',  'Section A', '2025-2026', 'Active'],
                        ['Ben Torres',    'Grade 11', 'Section B', '2025-2026', 'Active'],
                        ['Clara Mendoza', 'Grade 3',  'Section C', '2025-2026', 'Active'],
                    ] as $row)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-6 py-3 font-medium text-slate-700 dark:text-slate-300">{{ $row[0] }}</td>
                        <td class="px-6 py-3 text-slate-500 dark:text-slate-400">{{ $row[1] }}</td>
                        <td class="px-6 py-3 text-slate-500 dark:text-slate-400">{{ $row[2] }}</td>
                        <td class="px-6 py-3 text-slate-500 dark:text-slate-400">{{ $row[3] }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">{{ $row[4] }}</span>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <button class="text-slate-400 hover:text-[#0d4c8f] transition-colors">
                                <iconify-icon icon="solar:eye-linear" width="18"></iconify-icon>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <p class="mt-8 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection