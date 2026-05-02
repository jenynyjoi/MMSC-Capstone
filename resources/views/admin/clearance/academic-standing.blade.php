@extends('layouts.admin_layout')

@section('title', 'Academic Standing')

@section('content')
<div class="flex-1 overflow-x-hidden lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Academic Standing"
        subtitle="View student grades and academic performance"
        school-year="{{ $activeSchoolYear }}"
    />

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:graph-new-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Academic Standing</h2>
            <span class="ml-1 text-xs text-slate-400">Student grade summary</span>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            @foreach([
                ['blue', 'solar:users-group-rounded-bold', $totalStudents ?? 28, 'Total Students'],
                ['green', 'solar:check-circle-bold', $passedCount ?? 24, 'Passed'],
                ['red', 'solar:close-circle-bold', $failedCount ?? 3, 'Failed'],
                ['yellow', 'solar:clock-circle-bold', $pendingCount ?? 1, 'Pending'],
            ] as [$color, $icon, $count, $label])
            <div class="flex items-center gap-3 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 dark:border-{{ $color }}-900/30 dark:bg-{{ $color }}-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30">
                    <iconify-icon icon="{{ $icon }}" width="20" class="text-{{ $color }}-600 dark:text-{{ $color }}-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $count }}</p>
                    <p class="text-xs text-{{ $color }}-600 dark:text-{{ $color }}-400 mt-1 leading-tight">{{ $label }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Filters --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option selected>SY 2025-2026</option>
                            <option>SY 2024-2025</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade and Section</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">All</option>
                            <option>7 - A</option>
                            <option>7 - B</option>
                            <option>8 - A</option>
                            <option>8 - B</option>
                            <option>9 - A</option>
                            <option>10 - A</option>
                            <option>11 - STEM A</option>
                            <option>12 - STEM A</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Quarter</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option>Q1</option>
                            <option>Q2</option>
                            <option>Q3</option>
                            <option selected>Q4</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Status</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">All</option>
                            <option>Passed</option>
                            <option>Failed</option>
                            <option>Pending</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                </button>
                <button class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">
                    Clear All
                </button>
            </div>
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
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                <input type="text" placeholder="Search student..." class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width: 1200px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3 text-center w-8">
                            <input type="checkbox" id="select-all-checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade & Section</th>
                        <th class="px-4 py-3">Adviser</th>
                        <th class="px-4 py-3 text-center">Q1</th>
                        <th class="px-4 py-3 text-center">Q2</th>
                        <th class="px-4 py-3 text-center">Q3</th>
                        <th class="px-4 py-3 text-center">Q4</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Final Average</th>
                        <th class="px-4 py-3">Remarks</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @php
                        $students = [
                            ['id' => '2025-001', 'name' => 'Jenny Orquiola', 'section' => '7 - A', 'adviser' => 'Mrs. Maria Reyes', 'q1' => 90, 'q2' => 90, 'q3' => 90, 'q4' => 90, 'final' => 90.00, 'remarks' => 'Passed', 'status' => 'Cleared'],
                            ['id' => '2025-002', 'name' => 'Juan Dela Cruz', 'section' => '7 - A', 'adviser' => 'Mrs. Maria Reyes', 'q1' => 85, 'q2' => 87, 'q3' => 88, 'q4' => 86, 'final' => 86.50, 'remarks' => 'Passed', 'status' => 'Cleared'],
                            ['id' => '2025-003', 'name' => 'Maria Santos', 'section' => '7 - A', 'adviser' => 'Mr. Juan Cruz', 'q1' => 75, 'q2' => 74, 'q3' => 73, 'q4' => 72, 'final' => 73.50, 'remarks' => 'Failed', 'status' => 'Pending'],
                            ['id' => '2025-004', 'name' => 'Jose Reyes', 'section' => '7 - B', 'adviser' => 'Ms. Ana Garcia', 'q1' => 80, 'q2' => 82, 'q3' => 84, 'q4' => 83, 'final' => 82.25, 'remarks' => 'Passed', 'status' => 'Cleared'],
                            ['id' => '2025-005', 'name' => 'Ana Fernandez', 'section' => '7 - B', 'adviser' => 'Ms. Ana Garcia', 'q1' => 88, 'q2' => 89, 'q3' => 90, 'q4' => 91, 'final' => 89.50, 'remarks' => 'Passed', 'status' => 'Cleared'],
                            ['id' => '2025-006', 'name' => 'Carlos Mendoza', 'section' => '8 - A', 'adviser' => 'Mr. Pedro Lopez', 'q1' => 78, 'q2' => 76, 'q3' => 75, 'q4' => 74, 'final' => 75.75, 'remarks' => 'Passed', 'status' => 'Cleared'],
                            ['id' => '2025-007', 'name' => 'Liza Cruz', 'section' => '8 - A', 'adviser' => 'Mr. Pedro Lopez', 'q1' => 70, 'q2' => 68, 'q3' => 65, 'q4' => 60, 'final' => 65.75, 'remarks' => 'Failed', 'status' => 'Pending'],
                        ];
                        
                        $remarksClass = [
                            'Passed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                            'Failed' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
                        ];
                        
                        $statusClass = [
                            'Cleared' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                            'Pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                        ];
                    @endphp

                    @foreach ($students as $index => $student)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors student-row" data-student-id="{{ $student['id'] }}">
                        <td class="px-4 py-3 text-center">
                            <input type="checkbox" class="student-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" data-id="{{ $student['id'] }}">
                        </td>
                        <td class="px-4 py-3 text-xs font-mono text-slate-500 whitespace-nowrap">{{ $student['id'] }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $student['name'] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300 whitespace-nowrap">{{ $student['section'] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $student['adviser'] }}</td>
                        
                        <td class="px-4 py-3 text-center text-sm font-semibold {{ $student['q1'] >= 75 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $student['q1'] }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-semibold {{ $student['q2'] >= 75 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $student['q2'] }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-semibold {{ $student['q3'] >= 75 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $student['q3'] }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-semibold {{ $student['q4'] >= 75 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $student['q4'] }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-slate-700 dark:text-slate-300">
                            {{ number_format($student['final'], 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $remarksClass[$student['remarks']] }}">
                                {{ $student['remarks'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass[$student['status']] }}">
                                {{ $student['status'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                                    Select
                                    <iconify-icon icon="solar:alt-arrow-down-linear" width="12"
                                        :class="open ? 'rotate-180' : ''" class="transition-transform duration-200"></iconify-icon>
                                </button>
                                <div x-show="open" @click.outside="open = false"
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    class="absolute right-0 z-20 mt-1 w-44 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1">
                                    <button class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:eye-bold" width="14" class="text-amber-500"></iconify-icon>
                                        View Details
                                    </button>
                                    <button class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:bell-bold" width="14" class="text-blue-500"></iconify-icon>
                                        Send Reminder
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400">Showing 1 to 7 of 7 entries</p>
            <div class="flex items-center gap-1">
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon>
                </button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">1</button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon>
                </button>
            </div>
        </div>

   

    <div id="bulk-actions-bar" class="fixed bottom-6 left-1/2 z-30 hidden -translate-x-1/2">
        <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-2xl dark:border-dark-border dark:bg-dark-card">
            <iconify-icon icon="solar:users-group-rounded-linear" width="18" class="text-slate-400 dark:text-slate-500"></iconify-icon>
            <span id="selected-count" class="whitespace-nowrap text-xs font-medium text-slate-600 dark:text-slate-300">0 Selected</span>
            <div class="h-4 w-px bg-slate-200 dark:bg-slate-700"></div>
            <button class="flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-emerald-700">
                <iconify-icon icon="solar:document-bold" width="13"></iconify-icon> Export Selected
            </button>
            <button class="flex items-center gap-1.5 rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-amber-700">
                <iconify-icon icon="solar:bell-bold" width="13"></iconify-icon> Send Reminder
            </button>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

</div>

{{-- View Details Modal --}}
<div id="grade-detail-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height: 90vh; overflow-y: auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10 rounded-t-2xl">
            <h3 class="text-white text-sm font-bold">STUDENT GRADE DETAILS</h3>
            <button onclick="closeModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">✕</button>
        </div>
        <div class="p-6">
            <div class="space-y-5">
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4">
                    <p class="text-xs font-bold text-slate-700 dark:text-white mb-3">Student Information</p>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div><span class="text-slate-400">Student ID:</span> <span class="font-semibold text-slate-700 dark:text-slate-300" id="modal-student-id">2025-001</span></div>
                        <div><span class="text-slate-400">Student Name:</span> <span class="font-semibold text-slate-700 dark:text-slate-300" id="modal-student-name">Jenny Orquiola</span></div>
                        <div><span class="text-slate-400">Grade & Section:</span> <span class="font-semibold text-slate-700 dark:text-slate-300" id="modal-grade-section">7 - A</span></div>
                        <div><span class="text-slate-400">Adviser:</span> <span class="font-semibold text-slate-700 dark:text-slate-300" id="modal-adviser">Mrs. Maria Reyes</span></div>
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                    <div class="bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5 border-b border-slate-200 dark:border-dark-border">
                        <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Quarterly Grades</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-800/20">
                                <tr>
                                    <th class="px-3 py-2 text-left">Subject</th>
                                    <th class="px-3 py-2 text-center">Q1</th>
                                    <th class="px-3 py-2 text-center">Q2</th>
                                    <th class="px-3 py-2 text-center">Q3</th>
                                    <th class="px-3 py-2 text-center">Q4</th>
                                    <th class="px-3 py-2 text-center">Final</th>
                                    <th class="px-3 py-2 text-center">Remarks</th>
                                </tr>
                            </thead>
                            <tbody id="modal-subjects-tbody">
                                <tr class="border-b border-slate-100 dark:border-slate-700">
                                    <td class="py-2 text-slate-700 dark:text-slate-300">Mathematics 7</td>
                                    <td class="py-2 text-center text-emerald-600">90</td>
                                    <td class="py-2 text-center text-emerald-600">90</td>
                                    <td class="py-2 text-center text-emerald-600">90</td>
                                    <td class="py-2 text-center text-emerald-600">90</td>
                                    <td class="py-2 text-center font-bold">90.00</td>
                                    <td class="py-2 text-center"><span class="inline-flex rounded-full px-2 py-0.5 text-xs bg-emerald-100 text-emerald-700">Passed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="closeModal()" class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Send Reminder Modal --}}
<div id="reminder-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeReminderModal()"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between rounded-t-2xl">
            <h3 class="text-white text-sm font-bold">SEND REMINDER</h3>
            <button onclick="closeReminderModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">✕</button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <p class="text-xs text-slate-500 mb-1">Student:</p>
                <p id="reminder-student-name" class="text-sm font-semibold text-slate-800 dark:text-white">Jenny Orquiola</p>
            </div>
            <div class="mb-4">
                <label class="text-xs font-medium text-slate-500 dark:text-slate-400 block mb-1">Message</label>
                <textarea rows="4" class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Type your reminder message here...">Dear Student, this is a reminder to check your grades and complete any pending requirements.</textarea>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button onclick="closeReminderModal()" class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50">Cancel</button>
                <button class="px-4 py-2 rounded-lg bg-[#0d4c8f] text-xs font-semibold text-white hover:bg-blue-700">Send</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Modal functions
    function closeModal() {
        document.getElementById('grade-detail-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    function closeReminderModal() {
        document.getElementById('reminder-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Bulk selection logic (mirroring Enrollment)
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox');
    const bulkActionsBar = document.getElementById('bulk-actions-bar');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateBulkActionsBar() {
        const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkActionsBar.classList.remove('hidden');
            bulkActionsBar.classList.add('flex');
            selectedCountSpan.textContent = checkedCount;
        } else {
            bulkActionsBar.classList.add('hidden');
            bulkActionsBar.classList.remove('flex');
        }
        
        // Update select all checkbox state
        if (selectAllCheckbox) {
            const totalCheckboxes = studentCheckboxes.length;
            const checkedCheckboxes = document.querySelectorAll('.student-checkbox:checked').length;
            if (checkedCheckboxes === totalCheckboxes) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }
    }

    // Add event listeners to all checkboxes
    studentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsBar);
    });

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateBulkActionsBar();
        });
    }

    // Initialize on page load
    updateBulkActionsBar();

    // View details and reminder handlers
    document.querySelectorAll('.student-row .flex.w-full.items-center.gap-2').forEach(btn => {
        if (btn.innerHTML.includes('View Details')) {
            btn.addEventListener('click', function() {
                document.getElementById('grade-detail-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }


        
        if (btn.innerHTML.includes('Send Reminder')) {
            btn.addEventListener('click', function() {
                document.getElementById('reminder-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }
    });
</script>
@endpush

@endsection
