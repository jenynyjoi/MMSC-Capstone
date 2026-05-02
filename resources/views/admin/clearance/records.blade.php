@extends('layouts.admin_layout')
@section('title', 'Records Clearance')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4 pb-28">

    {{-- ── Page Header ── --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Clearance</h1>
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">Student Requirements Validation</p>
        </div>
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap font-medium">Current School Year:</span>
            <form method="GET" action="{{ route('admin.clearance.records') }}" id="sy-form">
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
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center justify-center h-8 w-8 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors shadow-sm">
                    <iconify-icon icon="solar:menu-dots-bold" width="16"></iconify-icon>
                </button>
                <div x-show="open" x-transition
                    class="absolute right-0 top-full mt-1 w-44 rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-lg z-50 overflow-hidden"
                    style="display:none">
                    <button class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:export-linear" width="14"></iconify-icon> Export Excel
                    </button>
                    <button class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:file-text-linear" width="14"></iconify-icon> Generate PDF
                    </button>
                    <button class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:bell-linear" width="14"></iconify-icon> Send Reminder
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Records Card ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- ── Card Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20">
                    <iconify-icon icon="solar:folder-with-files-bold" width="18" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                </div>
                <span class="text-base font-semibold text-slate-800 dark:text-white">Records</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-medium">
                <span>CURRENT SCHOOL YEAR:
                    <span class="text-[#0d4c8f] dark:text-blue-400 font-semibold">SY {{ $schoolYear }}</span>
                </span>
                <span class="text-slate-300 dark:text-slate-600">|</span>
                <span>AS OF: <span class="text-orange-500 font-semibold">{{ now()->format('F d, Y') }}</span></span>
            </div>
        </div>

        {{-- ── Stats Cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">

            {{-- Total Students --}}
            <div class="flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 dark:border-blue-900/30 dark:bg-blue-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                    <iconify-icon icon="solar:users-group-two-rounded-bold" width="20" class="text-blue-600 dark:text-blue-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $totalStudents ?? 0 }}</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1 leading-tight">Total Students</p>
                </div>
            </div>

            {{-- Pending --}}
            <div class="flex items-center gap-3 rounded-xl border border-yellow-200 bg-yellow-50 dark:border-yellow-900/30 dark:bg-yellow-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-yellow-100 dark:bg-yellow-900/30">
                    <iconify-icon icon="solar:clock-circle-bold" width="20" class="text-yellow-600 dark:text-yellow-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $pendingCount ?? 0 }}</p>
                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1 leading-tight">Pending</p>
                </div>
            </div>

            {{-- Cleared --}}
            <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 dark:border-green-900/30 dark:bg-green-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-100 dark:bg-green-900/30">
                    <iconify-icon icon="solar:check-circle-bold" width="20" class="text-green-600 dark:text-green-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $clearedCount ?? 0 }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1 leading-tight">Cleared</p>
                </div>
            </div>

            {{-- Missing Requirements --}}
            <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 dark:border-red-900/30 dark:bg-red-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 dark:bg-red-900/30">
                    <iconify-icon icon="solar:danger-triangle-bold" width="20" class="text-red-600 dark:text-red-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $missingCount ?? 0 }}</p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1 leading-tight">Missing Requirements</p>
                </div>
            </div>

        </div>

        {{-- ── Filters ── --}}
        <form method="GET" action="{{ route('admin.clearance.records') }}" id="filter-form"
              class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">

            <input type="hidden" name="school_year" value="{{ $schoolYear }}">

            {{-- School Year display --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">School Year</label>
                <div class="flex items-center justify-between rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-2 text-sm font-medium text-slate-700 dark:text-white shadow-sm">
                    <span>SY {{ $schoolYear }}</span>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="text-slate-400"></iconify-icon>
                </div>
            </div>

            {{-- Records Status --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Records Status</label>
                <div class="relative">
                    <select name="status"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="cleared" {{ request('status')==='cleared'?'selected':'' }}>Cleared</option>
                        <option value="pending" {{ request('status')==='pending'?'selected':'' }}>Pending</option>
                        <option value="missing" {{ request('status')==='missing'?'selected':'' }}>Missing</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                </div>
            </div>

            {{-- Grade and Section --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Grade and Section</label>
                <div class="relative">
                    <select name="grade_section"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        @foreach($sections ?? [] as $sec)
                            <option value="{{ $sec }}" {{ request('grade_section')===$sec?'selected':'' }}>{{ $sec }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                </div>
            </div>

            {{-- Search --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Search</label>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or ID…"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- Apply / Clear --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-transparent uppercase tracking-wide select-none">Actions</label>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-4 py-2 text-sm font-semibold text-white transition-colors shadow-sm flex-1 justify-center">
                        <iconify-icon icon="solar:filter-bold" width="14"></iconify-icon>
                        Apply
                    </button>
                    <a href="{{ route('admin.clearance.records', ['school_year' => $schoolYear]) }}"
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
                Showing {{ $allStudents->count() }} of {{ $totalStudents }} student(s)
            </p>
        </div>

        {{-- ── Table ── --}}
        <div class="overflow-x-auto" x-data="recordsTable()">
            <table class="w-full text-left text-sm border-collapse" style="min-width:1020px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 bg-slate-50/70 dark:bg-white/[0.02]">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" x-model="allSelected" @change="toggleAll()"
                                class="rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">NSO / PSA</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Form 137/SF9</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Good Moral</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Medical</th>
                        <th class="px-4 py-3 whitespace-nowrap">Cleared By</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Status</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                    @php
                        $statusBadge = [
                            'cleared' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                            'missing' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        ];
                        $docBadge = [
                            'approved'     => ['label' => 'Approved',  'class' => 'text-green-600 dark:text-green-400 font-semibold'],
                            'pending'      => ['label' => 'Pending',   'class' => 'text-amber-600 dark:text-amber-400'],
                            'not_uploaded' => ['label' => 'Missing',   'class' => 'text-red-500 dark:text-red-400 font-semibold'],
                        ];
                    @endphp

                    @forelse($allStudents as $student)
                    @php
                        $app = $student->application;
                        $docs = [
                            'psa'         => $app?->psa_status         ?? 'not_uploaded',
                            'report_card' => $app?->report_card_status ?? 'not_uploaded',
                            'good_moral'  => $app?->good_moral_status  ?? 'not_uploaded',
                            'medical'     => $app?->medical_status     ?? 'not_uploaded',
                        ];
                    @endphp
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors"
                        :class="selected.includes({{ $student->id }}) ? 'bg-blue-50/50 dark:bg-blue-900/10' : ''">
                        <td class="px-4 py-3">
                            <input type="checkbox" value="{{ $student->id }}"
                                x-model="selected" @change="updateAllSelected()"
                                class="rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            {{ $student->student_id }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0d4c8f]/10 dark:bg-blue-900/20 text-[11px] font-bold text-[#0d4c8f] dark:text-blue-400 uppercase">
                                    {{ strtoupper(substr($student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? 'S', 0, 1)) }}
                                </div>
                                <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                    class="text-sm font-medium text-slate-800 dark:text-slate-100 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors">
                                    {{ $student->last_name }}, {{ $student->first_name }}
                                    {{ $student->middle_name ? strtoupper(substr($student->middle_name, 0, 1)).'.' : '' }}
                                    {{ $student->suffix ?? '' }}
                                </a>
                            </div>
                        </td>

                        {{-- Document status columns --}}
                        @foreach($docs as $docKey => $docStatus)
                        @php $badge = $docBadge[$docStatus] ?? $docBadge['not_uploaded']; @endphp
                        <td class="px-4 py-3 text-xs text-center whitespace-nowrap">
                            <span class="{{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        @endforeach

                        <td class="px-4 py-3 text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap">—</td>

                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $statusBadge[$student->records_status] ?? $statusBadge['missing'] }}">
                                {{ ucfirst($student->records_status) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open" @click.outside="open = false"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 transition-colors shadow-sm">
                                    Select
                                    <iconify-icon icon="solar:alt-arrow-down-linear" width="12"
                                        :class="open ? 'rotate-180' : ''"
                                        class="transition-transform duration-200"></iconify-icon>
                                </button>
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 top-full mt-1 w-56 rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-lg z-50 overflow-hidden">

                                    {{-- View Documents → profile Records tab --}}
                                    <a href="{{ route('admin.student-records.profile', $student->id) }}?tab=records"
                                       class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:folder-with-files-linear" width="14"></iconify-icon> View Documents
                                    </a>

                                    @if($app)
                                    @php
                                        $modalData = [
                                            'appId'       => $app->id,
                                            'studentName' => $student->first_name . ' ' . $student->last_name . ' · ' . $student->student_id,
                                            'docs' => [
                                                'psa'         => ['status' => $app->psa_status ?? 'not_uploaded',         'submitted' => (bool)$app->psa_submitted,         'path' => $app->psa_path         ? Storage::url($app->psa_path)         : ''],
                                                'report_card' => ['status' => $app->report_card_status ?? 'not_uploaded', 'submitted' => (bool)$app->report_card_submitted, 'path' => $app->report_card_path ? Storage::url($app->report_card_path) : ''],
                                                'good_moral'  => ['status' => $app->good_moral_status ?? 'not_uploaded',  'submitted' => (bool)$app->good_moral_submitted,  'path' => $app->good_moral_path  ? Storage::url($app->good_moral_path)  : ''],
                                                'medical'     => ['status' => $app->medical_status ?? 'not_uploaded',     'submitted' => (bool)$app->medical_submitted,     'path' => $app->medical_path     ? Storage::url($app->medical_path)     : ''],
                                            ],
                                        ];
                                    @endphp

                                    {{-- Upload Missing Docs → doc modal --}}
                                    <button @click="open=false; openDocModal({{ Js::from($modalData) }})"
                                       class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-[#0d4c8f] dark:text-blue-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:upload-linear" width="14"></iconify-icon> Upload Missing Docs
                                    </button>

                                    {{-- Mark Cleared --}}
                                    @php $studentFullName = $student->first_name . ' ' . $student->last_name; @endphp
                                    <button @click="open=false; markRecordsCleared({{ $app->id }}, {{ Js::from($studentFullName) }})"
                                       class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-green-700 dark:text-green-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> Mark Cleared
                                    </button>

                                    {{-- Send Notice --}}
                                    <button @click="open=false; openNoticeModal({{ $student->id }}, {{ Js::from($studentFullName) }})"
                                       class="flex w-full items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:letter-linear" width="14"></iconify-icon> Send Notice
                                    </button>
                                    @endif

                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-10 text-center text-sm text-slate-400">
                            No students found for the selected filters.
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>

            {{-- ── Floating Bulk Action Bar ── --}}
            <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-30 transition-all duration-300"
                 :class="selected.length > 0 ? 'translate-y-0 opacity-100' : 'translate-y-full opacity-0 pointer-events-none'">
                <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-2xl px-5 py-3">
                    <iconify-icon icon="solar:users-group-rounded-linear" width="18" class="text-slate-400 shrink-0"></iconify-icon>
                    <span class="text-xs font-medium text-slate-600 dark:text-slate-300 whitespace-nowrap">
                        <span x-text="selected.length"></span> Selected
                    </span>
                    <div class="h-4 w-px bg-slate-200 dark:bg-slate-700 shrink-0"></div>

                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/10 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 transition-colors whitespace-nowrap">
                        <iconify-icon icon="solar:export-linear" width="14" class="text-green-600"></iconify-icon>
                        EXPORT EXCEL
                    </button>

                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/10 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 transition-colors whitespace-nowrap">
                        <iconify-icon icon="solar:file-text-linear" width="14" class="text-red-500"></iconify-icon>
                        GENERATE PDF
                    </button>

                    <button class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-4 py-1.5 text-xs font-semibold text-white transition-colors whitespace-nowrap">
                        <iconify-icon icon="solar:letter-bold" width="14"></iconify-icon>
                        Send Reminder
                    </button>

                    <div class="h-4 w-px bg-slate-200 dark:bg-slate-700 shrink-0"></div>
                    <button @click="selected = []; allSelected = false"
                        class="flex items-center justify-center h-7 w-7 rounded-full bg-slate-100 dark:bg-white/10 hover:bg-slate-200 dark:hover:bg-white/20 text-slate-500 dark:text-slate-400 transition-colors shrink-0">
                        <iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon>
                    </button>
                </div>
            </div>

        </div>

        {{-- ── Pagination ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
            <p class="text-xs text-slate-500 dark:text-slate-400">Showing {{ $allStudents->count() }} of {{ $totalStudents }} student(s)</p>
            <div class="flex items-center gap-1">
                <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-300 dark:text-slate-600 cursor-not-allowed">
                    <iconify-icon icon="solar:alt-arrow-left-linear" width="14"></iconify-icon>
                </button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">1</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 hover:bg-slate-50 text-xs">2</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 hover:bg-slate-50 text-xs">3</button>
                <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14"></iconify-icon>
                </button>
            </div>
        </div>

    </div>
    {{-- end card --}}

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══ Send Notice Modal ══ --}}
<div id="notice-modal"
     x-data="noticeModal()"
     @open-notice-modal.window="open($event.detail.id, $event.detail.name)"
     style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl flex flex-col">

        <div class="bg-slate-800 px-6 py-4 flex items-center justify-between rounded-t-2xl shrink-0">
            <div>
                <h3 class="text-white text-sm font-bold">SEND NOTICE</h3>
                <p class="text-slate-400 text-xs mt-0.5" x-text="studentName"></p>
            </div>
            <button @click="close()"
                class="flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 text-sm">✕</button>
        </div>

        <div class="p-6 space-y-4">
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Notice Type</label>
                <select x-model="form.notice_type"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800 text-sm px-3 py-2 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="records_reminder">Records Reminder</option>
                    <option value="missing_documents">Missing Documents</option>
                    <option value="clearance_notice">Clearance Notice</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Subject</label>
                <input type="text" x-model="form.subject"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800 text-sm px-3 py-2 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Message</label>
                <textarea x-model="form.message" rows="4"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800 text-sm px-3 py-2 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
            <div class="flex gap-4 text-xs">
                <label class="flex items-center gap-1.5 cursor-pointer text-slate-600 dark:text-slate-400">
                    <input type="checkbox" x-model="form.send_student" class="rounded border-slate-300"> Student
                </label>
                <label class="flex items-center gap-1.5 cursor-pointer text-slate-600 dark:text-slate-400">
                    <input type="checkbox" x-model="form.send_parent" class="rounded border-slate-300"> Parent / Guardian
                </label>
            </div>
        </div>

        <div class="px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-dark-border shrink-0">
            <button @click="close()"
                class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">
                Cancel
            </button>
            <button @click="send()" :disabled="sending"
                class="rounded-lg bg-[#0d4c8f] hover:bg-blue-800 disabled:opacity-60 px-5 py-2 text-xs font-semibold text-white transition-colors"
                x-text="sending ? 'Sending...' : 'Send Notice'">
            </button>
        </div>
    </div>
</div>

{{-- ══ Document Management Modal ══ --}}
<div id="doc-modal"
     x-data="docModal()"
     @open-doc-modal.window="open($event.detail)"
     style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl flex flex-col" style="max-height:90vh">

            {{-- Header --}}
            <div class="bg-slate-800 px-6 py-4 flex items-center justify-between rounded-t-2xl shrink-0">
                <div>
                    <h3 class="text-white text-sm font-bold">DOCUMENT VERIFICATION</h3>
                    <p class="text-slate-400 text-xs mt-0.5" x-text="studentName"></p>
                </div>
                <button @click="close()"
                    class="flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 text-sm">✕</button>
            </div>

            {{-- Body --}}
            <div class="overflow-y-auto flex-1 p-6 space-y-3">

                {{-- PSA --}}
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                            NSO / PSA Birth Certificate <span class="text-red-500">*</span>
                        </span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold"
                            :class="statusBadge(docs.psa.status)" x-text="statusLabel(docs.psa.status)"></span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <template x-if="docs.psa.path">
                            <a :href="docs.psa.path" target="_blank"
                                class="flex items-center gap-1 text-xs text-[#0d4c8f] hover:underline">
                                <iconify-icon icon="solar:eye-linear" width="13"></iconify-icon> View file
                            </a>
                        </template>
                        <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" x-model="docs.psa.submitted" class="rounded border-slate-300"> Physically submitted
                        </label>
                        <select x-model="docs.psa.status"
                            class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800 text-xs px-2 py-1.5 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="not_uploaded">Not Uploaded</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                        <label class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 cursor-pointer border border-dashed border-slate-300 dark:border-slate-600 rounded-lg px-3 py-1.5 hover:bg-slate-50 dark:hover:bg-white/5">
                            <iconify-icon icon="solar:upload-linear" width="13"></iconify-icon>
                            Upload file
                            <input type="file" x-ref="psa_file" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        </label>
                    </div>
                </div>

                {{-- Report Card --}}
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                            Form 137 / Report Card <span class="text-red-500">*</span>
                        </span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold"
                            :class="statusBadge(docs.report_card.status)" x-text="statusLabel(docs.report_card.status)"></span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <template x-if="docs.report_card.path">
                            <a :href="docs.report_card.path" target="_blank"
                                class="flex items-center gap-1 text-xs text-[#0d4c8f] hover:underline">
                                <iconify-icon icon="solar:eye-linear" width="13"></iconify-icon> View file
                            </a>
                        </template>
                        <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" x-model="docs.report_card.submitted" class="rounded border-slate-300"> Physically submitted
                        </label>
                        <select x-model="docs.report_card.status"
                            class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800 text-xs px-2 py-1.5 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="not_uploaded">Not Uploaded</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                        <label class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 cursor-pointer border border-dashed border-slate-300 dark:border-slate-600 rounded-lg px-3 py-1.5 hover:bg-slate-50 dark:hover:bg-white/5">
                            <iconify-icon icon="solar:upload-linear" width="13"></iconify-icon>
                            Upload file
                            <input type="file" x-ref="report_card_file" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        </label>
                    </div>
                </div>

                {{-- Good Moral --}}
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                            Good Moral Certificate <span class="text-red-500">*</span>
                        </span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold"
                            :class="statusBadge(docs.good_moral.status)" x-text="statusLabel(docs.good_moral.status)"></span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <template x-if="docs.good_moral.path">
                            <a :href="docs.good_moral.path" target="_blank"
                                class="flex items-center gap-1 text-xs text-[#0d4c8f] hover:underline">
                                <iconify-icon icon="solar:eye-linear" width="13"></iconify-icon> View file
                            </a>
                        </template>
                        <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" x-model="docs.good_moral.submitted" class="rounded border-slate-300"> Physically submitted
                        </label>
                        <select x-model="docs.good_moral.status"
                            class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800 text-xs px-2 py-1.5 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="not_uploaded">Not Uploaded</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                        <label class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 cursor-pointer border border-dashed border-slate-300 dark:border-slate-600 rounded-lg px-3 py-1.5 hover:bg-slate-50 dark:hover:bg-white/5">
                            <iconify-icon icon="solar:upload-linear" width="13"></iconify-icon>
                            Upload file
                            <input type="file" x-ref="good_moral_file" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        </label>
                    </div>
                </div>

                {{-- Medical (optional) --}}
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                            Medical Certificate <span class="text-slate-400 font-normal">(optional)</span>
                        </span>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold"
                            :class="statusBadge(docs.medical.status)" x-text="statusLabel(docs.medical.status)"></span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <template x-if="docs.medical.path">
                            <a :href="docs.medical.path" target="_blank"
                                class="flex items-center gap-1 text-xs text-[#0d4c8f] hover:underline">
                                <iconify-icon icon="solar:eye-linear" width="13"></iconify-icon> View file
                            </a>
                        </template>
                        <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" x-model="docs.medical.submitted" class="rounded border-slate-300"> Physically submitted
                        </label>
                        <select x-model="docs.medical.status"
                            class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800 text-xs px-2 py-1.5 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="not_uploaded">Not Uploaded</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                        <label class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 cursor-pointer border border-dashed border-slate-300 dark:border-slate-600 rounded-lg px-3 py-1.5 hover:bg-slate-50 dark:hover:bg-white/5">
                            <iconify-icon icon="solar:upload-linear" width="13"></iconify-icon>
                            Upload file
                            <input type="file" x-ref="medical_file" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                        </label>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-dark-border shrink-0">
                <button @click="close()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button @click="save()" :disabled="saving"
                    class="rounded-lg bg-[#0d4c8f] hover:bg-blue-800 disabled:opacity-60 px-5 py-2 text-xs font-semibold text-white transition-colors"
                    x-text="saving ? 'Saving...' : 'Save Changes'">
                </button>
            </div>

    </div>
</div>

@push('scripts')
<script>
function recordsTable() {
    return {
        selected: [],
        allSelected: false,
        rowIds: @json($allStudents->pluck('id')),

        toggleAll() {
            this.selected = this.allSelected ? [...this.rowIds] : [];
        },
        updateAllSelected() {
            this.allSelected = this.selected.length === this.rowIds.length;
        }
    }
}

// ── Notice Modal ─────────────────────────────────────
window.openNoticeModal = (id, name) => {
    window.dispatchEvent(new CustomEvent('open-notice-modal', { detail: { id, name } }));
};

function noticeModal() {
    return {
        sending: false,
        studentId: null,
        studentName: '',
        form: {
            notice_type:  'records_reminder',
            subject:      'Records Clearance Reminder',
            message:      'Dear Student/Guardian,\n\nThis is a reminder to submit your missing documents for records clearance. Please visit the school at your earliest convenience.\n\nThank you.',
            send_student: true,
            send_parent:  true,
        },

        open(id, name) {
            this.studentId   = id;
            this.studentName = name;
            this.sending     = false;
            document.getElementById('notice-modal').style.display = 'flex';
        },

        close() {
            document.getElementById('notice-modal').style.display = 'none';
        },

        async send() {
            this.sending = true;
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const sendTo = [];
            if (this.form.send_student) sendTo.push('student');
            if (this.form.send_parent)  sendTo.push('parent');

            try {
                const res  = await fetch('/admin/student-records/send-notice', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify({
                        student_ids: [this.studentId],
                        notice_type: this.form.notice_type,
                        subject:     this.form.subject,
                        message:     this.form.message,
                        send_to:     sendTo,
                    }),
                });
                const json = await res.json();
                if (json.success) {
                    this.close();
                    alert(json.message);
                } else {
                    alert(json.message || 'Failed to send.');
                }
            } catch (e) { alert('An error occurred.'); }
            this.sending = false;
        }
    }
}

// ── Mark Records Cleared ─────────────────────────────
async function markRecordsCleared(appId, studentName) {
    if (!confirm(`Mark all required documents as cleared for ${studentName}?\n\nThis will set PSA, Report Card, and Good Moral to Approved.`)) return;

    const token = document.querySelector('meta[name="csrf-token"]').content;
    const fd    = new FormData();
    fd.append('_token',               token);
    fd.append('psa_status',           'approved');
    fd.append('psa_submitted',        '1');
    fd.append('report_card_status',   'approved');
    fd.append('report_card_submitted','1');
    fd.append('good_moral_status',    'approved');
    fd.append('good_moral_submitted', '1');

    try {
        const res  = await fetch(`/admin/admission/${appId}/documents`, {
            method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: fd,
        });
        const json = await res.json();
        if (json.success) window.location.reload();
        else alert(json.message || 'Failed.');
    } catch (e) { alert('An error occurred.'); }
}

window.openDocModal = d => {
    window.dispatchEvent(new CustomEvent('open-doc-modal', { detail: d }));
};

function docModal() {
    return {
        appId: null,
        studentName: '',
        saving: false,
        docs: {
            psa:         { status: 'not_uploaded', submitted: false, path: '' },
            report_card: { status: 'not_uploaded', submitted: false, path: '' },
            good_moral:  { status: 'not_uploaded', submitted: false, path: '' },
            medical:     { status: 'not_uploaded', submitted: false, path: '' },
        },

        open(d) {
            this.appId       = d.appId;
            this.studentName = d.studentName;
            this.docs        = JSON.parse(JSON.stringify(d.docs));
            this.saving      = false;
            document.getElementById('doc-modal').style.display = 'flex';
            // Clear stale file inputs from previous open
            this.$nextTick(() => {
                ['psa_file', 'report_card_file', 'good_moral_file', 'medical_file'].forEach(ref => {
                    if (this.$refs[ref]) this.$refs[ref].value = '';
                });
            });
        },

        close() {
            document.getElementById('doc-modal').style.display = 'none';
        },

        statusLabel(s) {
            return { not_uploaded: 'Not Uploaded', pending: 'Pending', approved: 'Approved' }[s] || s;
        },
        statusBadge(s) {
            return {
                not_uploaded: 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                pending:      'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                approved:     'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            }[s] || 'bg-slate-100 text-slate-500';
        },

        async save() {
            this.saving = true;
            const fd = new FormData();
            const token = document.querySelector('meta[name="csrf-token"]').content;
            fd.append('_token', token);

            const keys = ['psa', 'report_card', 'good_moral', 'medical'];
            for (const k of keys) {
                fd.append(`${k}_status`, this.docs[k].status);
                if (this.docs[k].submitted) fd.append(`${k}_submitted`, '1');
                const refKey = k === 'report_card' ? 'report_card_file'
                             : k === 'good_moral'  ? 'good_moral_file'
                             : k + '_file';
                const fileInput = this.$refs[refKey];
                if (fileInput && fileInput.files[0]) {
                    fd.append(`${k}_file`, fileInput.files[0]);
                }
            }

            try {
                const res  = await fetch(`/admin/admission/${this.appId}/documents`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token },
                    body: fd,
                });
                const json = await res.json();
                if (json.success) {
                    this.close();
                    window.location.reload();
                } else {
                    alert(json.message || 'Failed to save.');
                }
            } catch (e) {
                console.error(e);
                alert('An error occurred. Please try again.');
            }
            this.saving = false;
        }
    }
}
</script>
@endpush

@endsection