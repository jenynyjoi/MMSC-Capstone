@extends('layouts.admin_layout')
@section('title', 'Library Clearance')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- ── Page Header ── --}}
    <x-admin.page-header title="Clearance" subtitle="Student Requirements Validation">
        <div class="flex items-center gap-2 mt-1 sm:mt-0">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap font-medium">Current School Year:</span>
            <form method="GET" action="{{ route('admin.clearance.library') }}" id="sy-form">
                @foreach(request()->except('school_year') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <select name="school_year" onchange="document.getElementById('sy-form').submit()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 text-sm font-semibold text-slate-700 dark:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($allSchoolYears as $sy)
                        <option value="{{ $sy->name }}" {{ $sy->name === $schoolYear ? 'selected' : '' }}>SY {{ $sy->name }}</option>
                    @endforeach
                    @if($allSchoolYears->isEmpty())
                        <option value="{{ $schoolYear }}" selected>SY {{ $schoolYear }}</option>
                    @endif
                </select>
            </form>
        </div>
    </x-admin.page-header>

    {{-- ── Library Card ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20">
                    <iconify-icon icon="solar:library-bold" width="18" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                </div>
                <span class="text-base font-semibold text-slate-800 dark:text-white">Library</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-medium">
                <span>CURRENT SCHOOL YEAR: <span class="text-[#0d4c8f] dark:text-blue-400 font-semibold">SY {{ $schoolYear }}</span></span>
                <span class="text-slate-300 dark:text-slate-600">|</span>
                <span>AS OF: <span class="text-blue-500 font-semibold">{{ now()->format('F d, Y') }}</span></span>
            </div>
        </div>

        {{-- ── Stats Cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            @foreach([
                ['blue',   'solar:users-group-two-rounded-bold', $totalStudents, 'Total Students'],
                ['yellow', 'solar:clock-circle-bold',            $pendingCount,  'Pending'],
                ['green',  'solar:check-circle-bold',            $clearedCount,  'Cleared'],
                ['red',    'solar:danger-triangle-bold',         $overdueCount,  'With Overdue Books'],
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

        {{-- ── Filters ── --}}
        <form method="GET" action="{{ route('admin.clearance.library') }}" id="filter-form"
              class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
            <input type="hidden" name="school_year" value="{{ $schoolYear }}">

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">School Year</label>
                <div class="flex items-center justify-between rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-2 text-sm font-medium text-slate-700 dark:text-white shadow-sm">
                    <span>SY {{ $schoolYear }}</span>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="text-slate-400"></iconify-icon>
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Grade & Section</label>
                <div class="relative">
                    <select name="grade_section"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec }}" {{ request('grade_section')===$sec ?'selected':'' }}>{{ $sec }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Library Status</label>
                <div class="relative">
                    <select name="clearance_status"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="cleared" {{ request('clearance_status')==='cleared'?'selected':'' }}>Cleared</option>
                        <option value="pending" {{ request('clearance_status')==='pending'?'selected':'' }}>Pending</option>
                        <option value="overdue" {{ request('clearance_status')==='overdue'?'selected':'' }}>Overdue</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Search</label>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or ID…"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-transparent uppercase tracking-wide select-none">Actions</label>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-4 py-2 text-sm font-semibold text-white transition-colors shadow-sm flex-1 justify-center">
                        <iconify-icon icon="solar:filter-bold" width="14"></iconify-icon>
                        Apply
                    </button>
                    <a href="{{ route('admin.clearance.library', ['school_year' => $schoolYear]) }}"
                        class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm whitespace-nowrap">
                        Clear All
                    </a>
                </div>
            </div>
        </form>

        {{-- ── Table Toolbar ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                <span class="font-medium">Show</span>
                <select name="per_page" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-2 py-1 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach([5,10,25,50] as $n)
                        <option value="{{ $n }}" {{ request('per_page',10)==$n?'selected':'' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <span class="font-medium">Entries</span>
            </div>
            <p class="text-xs text-slate-400">
                Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} student(s)
            </p>
        </div>

        {{-- ── Table ── --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:1100px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 bg-slate-50/70 dark:bg-white/[0.02]">
                        <th class="px-4 py-3 w-8">
                            <input type="checkbox" id="select-all" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade & Section</th>
                        <th class="px-4 py-3 whitespace-nowrap">Cleared By</th>
                        <th class="px-4 py-3 whitespace-nowrap">Remarks</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Status</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                @forelse($students as $student)
                @php
                    $lib      = $student->libraryRecord;
                    $books    = $lib ? $lib->books : collect();
                    $libStatus = $lib?->status ?? 'pending';

                    $statusBadge = [
                        'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                        'overdue' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        'cleared' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    ];
                    $statusLabel = [
                        'pending' => 'Pending',
                        'overdue' => 'Overdue',
                        'cleared' => 'Cleared',
                    ];

                    $modalData = \Illuminate\Support\Js::from([
                        'id'   => $student->id,
                        'name' => $student->last_name . ', ' . $student->first_name,
                        'recordId' => $lib?->id,
                        'books' => $books->map(fn($b) => [
                            'id'            => $b->id,
                            'book_title'    => $b->book_title,
                            'book_id'       => $b->book_id,
                            'date_borrowed' => $b->date_borrowed?->format('Y-m-d'),
                            'due_date'      => $b->due_date?->format('Y-m-d'),
                            'date_returned' => $b->date_returned?->format('Y-m-d'),
                            'fines'         => (float)$b->fines,
                            'remarks'       => $b->remarks,
                            'librarian_name'=> $b->librarian_name,
                            'status'        => $b->status,
                        ])->values()->toArray(),
                    ]);
                @endphp
                <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors group">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="selected_students[]" value="{{ $student->id }}"
                            class="row-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </td>
                    <td class="px-4 py-3 font-mono text-xs text-slate-600 dark:text-slate-300 whitespace-nowrap">
                        {{ $student->student_id ?? '—' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0d4c8f]/10 dark:bg-blue-900/20 text-[11px] font-bold text-[#0d4c8f] dark:text-blue-400 uppercase">
                                {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                    class="text-sm font-medium text-slate-800 dark:text-slate-100 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors">
                                    {{ $student->last_name }}, {{ $student->first_name }}
                                    {{ $student->middle_name ? strtoupper(substr($student->middle_name, 0, 1)).'.' : '' }}
                                    {{ $student->suffix ?? '' }}
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                        {{ $student->section_name ? \App\Models\Section::formatName($student->grade_level ?? '—', $student->section_name, $student->strand) : ($student->grade_level ?? '—') }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300 whitespace-nowrap">
                        {{ $lib?->cleared_by ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 max-w-[140px]">
                        <span class="truncate block" title="{{ $lib?->remarks ?? '' }}">
                            {{ $lib?->remarks ? \Illuminate\Support\Str::limit($lib->remarks, 30) : '—' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold
                            {{ $statusBadge[$libStatus] ?? $statusBadge['pending'] }}">
                            {{ $statusLabel[$libStatus] ?? 'No Books Recorded' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div x-data="{ open: false }" class="relative inline-block">
                            <button @click="open = !open" @click.outside="open = false"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                                Select
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="12"
                                    :class="open ? 'rotate-180' : ''" class="transition-transform duration-200"></iconify-icon>
                            </button>
                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute right-0 z-20 mt-1 w-48 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1"
                                style="display:none">
                                <button
                                    onclick="openHistoryModal({{ $modalData }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:history-bold" width="14" class="text-slate-400"></iconify-icon>
                                    View History
                                </button>
                                <button
                                    onclick="markLibraryStatus({{ $student->id }}, 'cleared')"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:check-circle-bold" width="14" class="text-green-500"></iconify-icon>
                                    Mark Cleared
                                </button>
                                <button
                                    onclick="openNoticeModal({{ $student->id }}, {{ \Illuminate\Support\Js::from($student->last_name.', '.$student->first_name) }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:bell-bold" width="14" class="text-amber-500"></iconify-icon>
                                    Send Notice
                                </button>
                                <hr class="my-1 border-slate-100 dark:border-slate-700">
                                <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:user-id-bold" width="14" class="text-blue-500"></iconify-icon>
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                                <iconify-icon icon="solar:library-bold" width="28" class="text-slate-400"></iconify-icon>
                            </div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No students found</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Try adjusting your filters</p>
                        </div>
                    </td>
                </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        @if($students->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400">
                Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} entries
            </p>
            <div class="flex items-center gap-1">
                @if($students->onFirstPage())
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-300 dark:text-slate-600 cursor-not-allowed">
                        <iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon>
                    </span>
                @else
                    <a href="{{ $students->previousPageUrl() }}" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 transition-colors">
                        <iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon>
                    </a>
                @endif
                @foreach($students->getUrlRange(max(1,$students->currentPage()-2), min($students->lastPage(),$students->currentPage()+2)) as $page => $url)
                    @if($page === $students->currentPage())
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 text-xs transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                @if($students->hasMorePages())
                    <a href="{{ $students->nextPageUrl() }}" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 transition-colors">
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon>
                    </a>
                @else
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-300 dark:text-slate-600 cursor-not-allowed">
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon>
                    </span>
                @endif
            </div>
        </div>
        @else
        <div class="px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400">Showing {{ $students->count() }} of {{ $students->total() }} entries</p>
        </div>
        @endif

    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ── Floating Bulk Bar ── --}}
<div id="bulk-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-30 hidden pointer-events-none">
    <div class="pointer-events-auto flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-2xl px-5 py-3">
        <div class="flex items-center gap-2 pr-3 border-r border-slate-200 dark:border-dark-border">
            <iconify-icon icon="solar:users-group-two-rounded-bold" width="16" class="text-[#0d4c8f]"></iconify-icon>
            <span id="bulk-count" class="text-sm font-bold text-slate-800 dark:text-white">0</span>
            <span class="text-xs text-slate-500 dark:text-slate-400">selected</span>
        </div>
        <button onclick="bulkMarkStatus('cleared')"
            class="flex items-center gap-1.5 rounded-xl bg-green-600 hover:bg-green-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> Mark Cleared
        </button>
        <button onclick="bulkMarkStatus('overdue')"
            class="flex items-center gap-1.5 rounded-xl bg-red-600 hover:bg-red-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:danger-triangle-bold" width="14"></iconify-icon> Mark Overdue
        </button>
        <button
            class="flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:export-linear" width="14"></iconify-icon> Export
        </button>
        <button onclick="document.getElementById('bulk-bar').classList.add('hidden'); document.querySelectorAll('.row-checkbox').forEach(c=>c.checked=false); document.getElementById('select-all').checked=false;"
            class="flex items-center justify-center h-7 w-7 rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:text-slate-600 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
            <iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon>
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     ADD BOOK MODAL
══════════════════════════════════════════════════════════ --}}
<div id="add-book-modal"
     x-data="addBookModal()"
     @open-add-book.window="open($event.detail)"
     style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
      

        <form @submit.prevent="save()" class=x`"px-6 py-5 space-y-4">
            {{-- Student selector (only shown when opened from global button) --}}
            <div x-show="!studentId" style="display:none">
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Student</label>
                <select x-model="studentId" required
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Select student —</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->last_name }}, {{ $s->first_name }} ({{ $s->student_id }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Book Title <span class="text-red-500">*</span></label>
                    <input type="text" x-model="form.book_title" required placeholder="e.g. English for All Times"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Book ID / Accession No.</label>
                    <input type="text" x-model="form.book_id" placeholder="e.g. LIB-001"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Librarian Name</label>
                    <input type="text" x-model="form.librarian_name" placeholder="e.g. Mrs. Santos"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Date Borrowed <span class="text-red-500">*</span></label>
                    <input type="date" x-model="form.date_borrowed" required
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Due Date <span class="text-red-500">*</span></label>
                    <input type="date" x-model="form.due_date" required
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Fines (₱)</label>
                    <input type="number" x-model.number="form.fines" min="0" step="0.01" placeholder="0.00"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Remarks</label>
                    <input type="text" x-model="form.remarks" placeholder="Optional note…"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-1">
                <button type="button" @click="close()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    Cancel
                </button>
                <button type="submit" :disabled="saving"
                    class="rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm disabled:opacity-60 flex items-center gap-2">
                    <iconify-icon icon="solar:add-circle-bold" width="14" x-show="!saving"></iconify-icon>
                    <iconify-icon icon="solar:refresh-circle-linear" width="14" class="animate-spin" x-show="saving" style="display:none"></iconify-icon>
                    <span x-text="saving ? 'Saving…' : 'Add Book'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     HISTORY MODAL
══════════════════════════════════════════════════════════ --}}
<div id="history-modal"
     x-data="historyModal()"
     @open-history.window="open($event.detail)"
     style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
    <div class="relative w-full max-w-3xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div>
                <h3 class="text-base font-semibold text-slate-800 dark:text-white">Book Borrowing History</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="studentName"></p>
            </div>
            <button @click="close()" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>

        <div class="px-6 py-4 max-h-[60vh] overflow-y-auto">
            <template x-if="books.length === 0">
                <div class="flex flex-col items-center gap-3 py-10">
                    <iconify-icon icon="solar:library-bold" width="36" class="text-slate-300"></iconify-icon>
                    <p class="text-sm text-slate-400">No borrowed books recorded yet.</p>
                </div>
            </template>
            <template x-if="books.length > 0">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 border-b border-slate-100 dark:border-dark-border">
                            <th class="pb-2 text-left">Book Title</th>
                            <th class="pb-2 text-left">Book ID</th>
                            <th class="pb-2 text-left">Borrowed</th>
                            <th class="pb-2 text-left">Due</th>
                            <th class="pb-2 text-left">Returned</th>
                            <th class="pb-2 text-right">Fines</th>
                            <th class="pb-2 text-center">Status</th>
                            <th class="pb-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                        <template x-for="book in books" :key="book.id">
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-white/[0.02]">
                                <td class="py-2.5 pr-3 font-medium text-slate-700 dark:text-slate-300 max-w-[160px]">
                                    <span class="block truncate" x-text="book.book_title" :title="book.book_title"></span>
                                    <span class="text-[10px] text-slate-400" x-text="book.librarian_name ? 'By: ' + book.librarian_name : ''"></span>
                                </td>
                                <td class="py-2.5 pr-3 font-mono text-slate-400" x-text="book.book_id || '—'"></td>
                                <td class="py-2.5 pr-3 text-slate-500" x-text="book.date_borrowed || '—'"></td>
                                <td class="py-2.5 pr-3"
                                    :class="book.status === 'overdue' ? 'text-red-600 font-semibold' : 'text-slate-500'"
                                    x-text="book.due_date || '—'"></td>
                                <td class="py-2.5 pr-3 text-slate-500" x-text="book.date_returned || '—'"></td>
                                <td class="py-2.5 text-right"
                                    :class="book.fines > 0 ? 'text-red-600 font-semibold' : 'text-slate-400'"
                                    x-text="'₱ ' + parseFloat(book.fines || 0).toFixed(2)"></td>
                                <td class="py-2.5 text-center">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                        :class="{
                                            'bg-green-100 text-green-700': book.status === 'returned',
                                            'bg-red-100 text-red-700': book.status === 'overdue',
                                            'bg-blue-100 text-blue-700': book.status === 'borrowed',
                                        }"
                                        x-text="book.status.charAt(0).toUpperCase() + book.status.slice(1)"></span>
                                </td>
                                <td class="py-2.5 text-center">
                                    <template x-if="!book.date_returned">
                                        <button @click="markReturned(book)"
                                            class="rounded-lg bg-green-600 hover:bg-green-700 px-2.5 py-1 text-[10px] font-semibold text-white transition-colors">
                                            Mark Returned
                                        </button>
                                    </template>
                                    <template x-if="book.date_returned">
                                        <span class="text-slate-300 text-[10px]">Returned</span>
                                    </template>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </template>
        </div>

        <div class="flex justify-between items-center px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
            <div class="text-xs text-slate-500">
                <span x-text="books.length"></span> record(s) &middot;
                Total fines: <span class="font-semibold text-red-600" x-text="'₱ ' + books.reduce((s,b) => s + parseFloat(b.fines||0), 0).toFixed(2)"></span>
            </div>
            <button @click="close()"
                class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SEND NOTICE MODAL
══════════════════════════════════════════════════════════ --}}
<div id="notice-modal"
     x-data="libNoticeModal()"
     @open-lib-notice.window="open($event.detail.id, $event.detail.name)"
     style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div>
                <h3 class="text-base font-semibold text-slate-800 dark:text-white">Send Library Notice</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="studentName"></p>
            </div>
            <button @click="close()" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Notice Type</label>
                <select x-model="form.notice_type"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="overdue_reminder">Overdue Book Reminder</option>
                    <option value="fine_notice">Library Fine Notice</option>
                    <option value="return_reminder">Book Return Reminder</option>
                    <option value="clearance_notice">Clearance Notice</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Message</label>
                <textarea x-model="form.message" rows="4" placeholder="Enter notice message…"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
            <button type="button" @click="close()"
                class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                Cancel
            </button>
            <button @click="send()" :disabled="sending"
                class="rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm disabled:opacity-60 flex items-center gap-2">
                <iconify-icon icon="solar:bell-bold" width="14" x-show="!sending"></iconify-icon>
                <iconify-icon icon="solar:refresh-circle-linear" width="14" class="animate-spin" x-show="sending" style="display:none"></iconify-icon>
                <span x-text="sending ? 'Sending…' : 'Send Notice'"></span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ── Bulk bar
    const selectAll = document.getElementById('select-all');
    const bulkBar   = document.getElementById('bulk-bar');
    const bulkCount = document.getElementById('bulk-count');

    function syncBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        const total   = document.querySelectorAll('.row-checkbox').length;
        bulkCount.textContent = checked;
        bulkBar.classList.toggle('hidden', checked === 0);
        if (selectAll) {
            selectAll.checked       = checked === total && total > 0;
            selectAll.indeterminate = checked > 0 && checked < total;
        }
    }
    if (selectAll) {
        selectAll.addEventListener('change', () => {
            document.querySelectorAll('.row-checkbox').forEach(c => c.checked = selectAll.checked);
            syncBulkBar();
        });
    }
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.addEventListener('change', syncBulkBar));

    // ── Global modal openers
    window.openAddBookModal = (studentId, studentName) => {
        window.dispatchEvent(new CustomEvent('open-add-book', { detail: { studentId, studentName } }));
    };
    window.openHistoryModal = d => {
        window.dispatchEvent(new CustomEvent('open-history', { detail: d }));
    };
    window.openNoticeModal = (id, name) => {
        window.dispatchEvent(new CustomEvent('open-lib-notice', { detail: { id, name } }));
    };

    // ── Add Book Modal
    function addBookModal() {
        return {
            studentId: null,
            studentName: '',
            saving: false,
            form: { book_title: '', book_id: '', date_borrowed: '', due_date: '', fines: 0, remarks: '', librarian_name: '' },

            open(d) {
                this.studentId   = d.studentId;
                this.studentName = d.studentName || '';
                this.saving      = false;
                this.form        = { book_title: '', book_id: '', date_borrowed: '', due_date: '', fines: 0, remarks: '', librarian_name: '' };
                document.getElementById('add-book-modal').style.display = 'flex';
            },
            close() {
                document.getElementById('add-book-modal').style.display = 'none';
            },
            save() {
                if (!this.studentId) { alert('Please select a student.'); return; }
                if (!this.form.book_title || !this.form.date_borrowed || !this.form.due_date) {
                    alert('Book Title, Date Borrowed, and Due Date are required.'); return;
                }
                this.saving = true;
                fetch(`/admin/clearance/library/${this.studentId}/book`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify(this.form),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) { this.close(); location.reload(); }
                    else { alert(data.message || 'Failed to add book.'); this.saving = false; }
                })
                .catch(() => { alert('An error occurred.'); this.saving = false; });
            },
        };
    }

    // ── History Modal
    function historyModal() {
        return {
            studentId: null,
            studentName: '',
            books: [],

            open(d) {
                this.studentId   = d.id;
                this.studentName = d.name;
                this.books       = JSON.parse(JSON.stringify(d.books || []));
                document.getElementById('history-modal').style.display = 'flex';
            },
            close() {
                document.getElementById('history-modal').style.display = 'none';
            },
            markReturned(book) {
                const today = new Date().toISOString().split('T')[0];
                fetch(`/admin/clearance/library/${this.studentId}/return`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ book_id: book.id, date_returned: today }),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        book.date_returned = today;
                        book.status = 'returned';
                    } else { alert(data.message || 'Failed.'); }
                })
                .catch(() => alert('An error occurred.'));
            },
        };
    }

    // ── Notice Modal
    function libNoticeModal() {
        return {
            studentId: null,
            studentName: '',
            sending: false,
            form: { notice_type: 'overdue_reminder', message: '' },

            open(id, name) {
                this.studentId   = id;
                this.studentName = name;
                this.sending     = false;
                this.form        = { notice_type: 'overdue_reminder', message: '' };
                document.getElementById('notice-modal').style.display = 'flex';
            },
            close() {
                document.getElementById('notice-modal').style.display = 'none';
            },
            send() {
                if (!this.form.message.trim()) { alert('Please enter a message.'); return; }
                this.sending = true;
                fetch('/admin/student-records/send-notice', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({
                        student_ids: [this.studentId],
                        notice_type: this.form.notice_type,
                        subject: 'Library Notice',
                        message: this.form.message,
                        send_to: ['student', 'parent'],
                    }),
                })
                .then(r => r.json())
                .then(data => { this.close(); })
                .catch(() => { this.sending = false; });
            },
        };
    }

    // ── Mark Status
    function markLibraryStatus(id, status) {
        fetch(`/admin/clearance/library/${id}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ status }),
        })
        .then(r => r.json())
        .then(data => { if (data.success) location.reload(); })
        .catch(console.error);
    }

    function bulkMarkStatus(status) {
        const ids = [...document.querySelectorAll('.row-checkbox:checked')].map(c => c.value);
        if (!ids.length) return;
        Promise.all(ids.map(id =>
            fetch(`/admin/clearance/library/${id}/status`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ status }),
            })
        )).then(() => location.reload());
    }
</script>
@endpush

@endsection
