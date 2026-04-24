@extends('layouts.admin_layout')

@section('title', 'Student Archives')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4 pb-24">

    {{-- ── Page Header ── --}}
    <x-admin.page-header
        title="Student Records"
        subtitle="Student Record and Information"
        school-year="{{ $activeSchoolYear }}"
        :show-menu="true"
    />

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

            <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mb-4">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon>
                Filter by
            </div>

            {{-- 3-column filter grid --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-4">

                {{-- School Year --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
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
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
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

                {{-- Grade and Section --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade and Section</label>
                    <div class="relative">
                        <select class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border
                                       bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300
                                       px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">All</option>
                            <option>Kinder - A</option>
                            <option>Kinder - B</option>
                            <option>Grade 1 - A</option>
                            <option>Grade 1 - B</option>
                            <option>Grade 2 - A</option>
                            <option>Grade 2 - B</option>
                            <option>Grade 3 - A</option>
                            <option>Grade 3 - B</option>
                            <option>Grade 4 - A</option>
                            <option>Grade 4 - B</option>
                            <option>Grade 5 - A</option>
                            <option>Grade 5 - B</option>
                            <option>Grade 6 - A</option>
                            <option>Grade 6 - B</option>
                            <option>Grade 7 - A</option>
                            <option>Grade 7 - B</option>
                            <option>Grade 8 - A</option>
                            <option>Grade 8 - B</option>
                            <option>Grade 9 - A</option>
                            <option>Grade 9 - B</option>
                            <option>Grade 10 - A</option>
                            <option>Grade 10 - B</option>
                            <option>Grade 11 - A</option>
                            <option>Grade 11 - B</option>
                            <option>Grade 12 - A</option>
                            <option>Grade 12 - B</option>
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
                <span>Entries</span>
            </div>
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" width="14"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                <input type="text" placeholder="Search.."
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white
                           pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
            </div>
        </div>

        {{-- ── Table ── --}}
        <div class="overflow-x-auto" x-data="archiveTable()">
            <table class="w-full text-left text-sm border-collapse" style="min-width:1000px">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        {{-- Select All --}}
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox"
                                x-model="allSelected"
                                @change="toggleAll()"
                                class="h-4 w-4 rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade and Section</th>
                        <th class="px-4 py-3 whitespace-nowrap">Exit date</th>
                        <th class="px-4 py-3 whitespace-nowrap">Student Status</th>
                        <th class="px-4 py-3 whitespace-nowrap">Clearance Status</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap w-24">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                    @php
                        $archives = [
                            ['id'=>1, 'student_id'=>'232-555-09', 'sy'=>'2022-2023', 'name'=>'Jenny Orquiola',  'grade'=>'Grade 2', 'section'=>'A', 'exit_date'=>'11/4/2022', 'status'=>'Graduated',  'clearance'=>'Cleared'],
                            ['id'=>2, 'student_id'=>'232-555-09', 'sy'=>'2022-2023', 'name'=>'Jeneva Ybanez',   'grade'=>'Grade 2', 'section'=>'A', 'exit_date'=>'11/4/2022', 'status'=>'Withdrawn',  'clearance'=>'Cleared'],
                            ['id'=>3, 'student_id'=>'232-555-09', 'sy'=>'2022-2023', 'name'=>'Dianne Balaoro',  'grade'=>'Grade 2', 'section'=>'A', 'exit_date'=>'11/4/2022', 'status'=>'Completed',  'clearance'=>'Cleared'],
                            ['id'=>4, 'student_id'=>'232-555-09', 'sy'=>'2022-2023', 'name'=>'Hans Gayon',      'grade'=>'Grade 2', 'section'=>'A', 'exit_date'=>'11/4/2022', 'status'=>'Graduated',  'clearance'=>'Cleared'],
                        ];

                        $studentStatusClass = [
                            'Graduated'   => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                            'Withdrawn'   => 'bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400',
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
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors"
                        :class="selected.includes({{ $row['id'] }}) ? 'bg-blue-50/60 dark:bg-blue-900/10' : ''">

                        <td class="px-4 py-3">
                            <input type="checkbox"
                                value="{{ $row['id'] }}"
                                x-model="selected"
                                @change="updateAllSelected()"
                                class="h-4 w-4 rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                        </td>
                        <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500">{{ $row['student_id'] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $row['sy'] }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @php $nameParts = explode(' ', $row['name']); @endphp
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0d4c8f]/10 dark:bg-blue-900/20 text-[11px] font-bold text-[#0d4c8f] dark:text-blue-400">
                                    {{ strtoupper(substr($nameParts[0] ?? 'S', 0, 1)) }}{{ strtoupper(substr($nameParts[1] ?? 'S', 0, 1)) }}
                                </div>
                                <a href="{{ route('admin.student-records.profile', $row['id']) }}"
                                    class="text-sm font-medium text-slate-800 dark:text-slate-100 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors whitespace-nowrap">
                                    {{ $row['name'] }}
                                </a>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $row['grade'] }} - {{ $row['section'] }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $row['exit_date'] }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $studentStatusClass[$row['status']] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row['status'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $clearanceClass[$row['clearance']] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $row['clearance'] }}
                            </span>
                        </td>
                        {{-- Two action icon buttons: View + Download --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <button title="View"
                                    class="flex h-7 w-7 items-center justify-center rounded-lg
                                           bg-amber-50 hover:bg-amber-100 text-amber-600
                                           dark:bg-amber-900/20 dark:hover:bg-amber-900/40 dark:text-amber-400
                                           transition-colors">
                                    <iconify-icon icon="solar:eye-bold" width="14"></iconify-icon>
                                </button>
                                <button title="Download"
                                    class="flex h-7 w-7 items-center justify-center rounded-lg
                                           bg-blue-50 hover:bg-blue-100 text-blue-600
                                           dark:bg-blue-900/20 dark:hover:bg-blue-900/40 dark:text-blue-400
                                           transition-colors">
                                    <iconify-icon icon="solar:download-bold" width="14"></iconify-icon>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{-- ── Bulk Action Bar ── --}}
            <div class="fixed bottom-0 left-0 right-0 z-30 transition-all duration-300"
                 :class="selected.length > 0 ? 'translate-y-0 opacity-100' : 'translate-y-full opacity-0 pointer-events-none'">
                <div class="mx-auto max-w-screen-xl px-4 lg:px-8 pb-4">
                    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-2xl px-6 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="solar:users-group-rounded-bold" width="20" class="text-slate-400"></iconify-icon>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                <span x-text="selected.length"></span>
                                <span x-text="selected.length === 1 ? 'Selected Student' : 'Selected Students'"></span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="flex items-center gap-2 rounded-xl border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors">
                                <iconify-icon icon="solar:file-download-bold" width="14" class="text-green-600"></iconify-icon>
                                EXPORT EXCEL
                            </button>
                            <button class="flex items-center gap-2 rounded-xl border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors">
                                <iconify-icon icon="solar:document-bold" width="14" class="text-slate-500"></iconify-icon>
                                DOWNLOAD PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

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

@push('scripts')
<script>
function archiveTable() {
    return {
        selected: [],
        allSelected: false,
        rowIds: @json(array_column($archives, 'id')),

        toggleAll() {
            this.selected = this.allSelected ? [...this.rowIds] : [];
        },
        updateAllSelected() {
            this.allSelected = this.selected.length === this.rowIds.length;
        }
    }
}
</script>
@endpush

@endsection