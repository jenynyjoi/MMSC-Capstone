@extends('layouts.admin_layout')
@section('title', 'Property Clearance')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- ── Page Header ── --}}
    <x-admin.page-header title="Clearance" subtitle="Student Requirements Validation">
        <div class="flex items-center gap-2 mt-1 sm:mt-0" x-data="{ open: false }">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap font-medium">Current School Year:</span>
            <form method="GET" action="{{ route('admin.clearance.property') }}" id="sy-form">
                @foreach(request()->except('school_year') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <select name="school_year" onchange="document.getElementById('sy-form').submit()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 text-sm font-semibold text-slate-700 dark:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($allSchoolYears as $sy)
                        <option value="{{ $sy->name }}" {{ $sy->name === $schoolYear ? 'selected' : '' }}>
                            SY {{ $sy->name }}
                        </option>
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
                    <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:export-linear" width="14"></iconify-icon> Export Excel
                    </a>
                    <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:file-text-linear" width="14"></iconify-icon> Generate PDF
                    </a>
                    <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:bell-linear" width="14"></iconify-icon> Send Reminder
                    </a>
                </div>
            </div>
        </div>
    </x-admin.page-header>

    {{-- ── Property Card ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-50 dark:bg-orange-900/20">
                    <iconify-icon icon="solar:box-bold" width="18" class="text-orange-600 dark:text-orange-400"></iconify-icon>
                </div>
                <span class="text-base font-semibold text-slate-800 dark:text-white">Property</span>
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
            @foreach([
                ['blue',   'solar:users-group-two-rounded-bold', $totalStudents, 'Total Students'],
                ['yellow', 'solar:clock-circle-bold',            $pendingCount,  'Pending'],
                ['green',  'solar:check-circle-bold',            $clearedCount,  'Cleared'],
                ['red',    'solar:danger-triangle-bold',         $overdueCount,  'Overdue'],
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
        <form method="GET" action="{{ route('admin.clearance.property') }}" id="filter-form"
              class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">

            <input type="hidden" name="school_year" value="{{ $schoolYear }}">

            {{-- School Year --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">School Year</label>
                <div class="flex items-center justify-between rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-2 text-sm font-medium text-slate-700 dark:text-white shadow-sm">
                    <span>SY {{ $schoolYear }}</span>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="text-slate-400"></iconify-icon>
                </div>
            </div>

            {{-- Grade and Section --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Grade and Section</label>
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

            {{-- Clearance Status --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Clearance Status</label>
                <div class="relative">
                    <select name="clearance_status"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="cleared" {{ request('clearance_status')==='cleared' ?'selected':'' }}>Cleared</option>
                        <option value="pending" {{ request('clearance_status')==='pending' ?'selected':'' }}>Pending</option>
                        <option value="overdue" {{ request('clearance_status')==='overdue' ?'selected':'' }}>Overdue</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                </div>
            </div>

            {{-- Item Type --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Item Type</label>
                <div class="relative">
                    <select name="item_type"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="ID Card"    {{ request('item_type')==='ID Card' ?'selected':'' }}>ID Card</option>
                        <option value="Library Card" {{ request('item_type')==='Library Card' ?'selected':'' }}>Library Card</option>
                        <option value="Locker Key" {{ request('item_type')==='Locker Key' ?'selected':'' }}>Locker Key</option>
                        <option value="Textbooks"  {{ request('item_type')==='Textbooks' ?'selected':'' }}>Textbooks</option>
                        <option value="Equipment"  {{ request('item_type')==='Equipment' ?'selected':'' }}>Equipment</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-transparent uppercase tracking-wide select-none">Actions</label>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-4 py-2 text-sm font-semibold text-white transition-colors shadow-sm flex-1 justify-center">
                        <iconify-icon icon="solar:filter-bold" width="14"></iconify-icon>
                        Apply
                    </button>
                    <a href="{{ route('admin.clearance.property', ['school_year' => $schoolYear]) }}"
                        class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm whitespace-nowrap">
                        Clear All
                    </a>
                </div>
            </div>

        </form>

        {{-- ── Table Toolbar ── --}}
        @php
            $activeLevel = request('program_level', '');
            $levels = ['' => 'ALL', 'Elementary' => 'ELEM', 'Junior High School' => 'JHS', 'Senior High School' => 'SHS'];
        @endphp
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">

            {{-- Show entries --}}
            <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                <span class="font-medium">Show</span>
                <select name="per_page" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-2 py-1 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach([5,10,25,50] as $n)
                        <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <span class="font-medium">Entries</span>
            </div>

            {{-- Level tabs + Search --}}
            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex rounded-lg overflow-hidden border border-slate-200 dark:border-dark-border text-xs font-semibold">
                    @foreach($levels as $val => $label)
                        <a href="{{ route('admin.clearance.property', array_merge(request()->except('program_level','page'), ['school_year'=>$schoolYear, 'program_level'=>$val])) }}"
                            class="px-4 py-2 transition-colors
                                {{ $activeLevel === $val
                                    ? 'bg-[#0d4c8f] text-white'
                                    : 'bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5' }}
                                {{ !$loop->first ? 'border-l border-slate-200 dark:border-dark-border' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="15"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" name="search" form="filter-form"
                        value="{{ request('search') }}"
                        placeholder="Search..."
                        class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card pl-9 pr-3 py-2 text-sm text-slate-700 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 w-44">
                </div>
            </div>

        </div>

        {{-- ── Table ── --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width: 1000px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 bg-slate-50/70 dark:bg-white/[0.02]">
                        <th class="px-4 py-3 w-8">
                            <input type="checkbox" id="select-all"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade & Section</th>
                        <th class="px-4 py-3 whitespace-nowrap">Assigned Items</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Status</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                @php
                    $clearanceBadge = [
                        'cleared' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                        'overdue' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                    ];
                @endphp

                @forelse($students as $student)
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
                        {{ $student->school_year }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300 whitespace-nowrap">
                        {{ $student->section_name ? \App\Models\Section::formatName($student->grade_level ?? '—', $student->section_name, $student->strand) : ($student->grade_level ?? '—') }}
                    </td>
                    @php
                        $propRecord = $student->propertyRecord;
                        $propItems  = $propRecord ? $propRecord->items : collect();
                        $propStatus = $propRecord?->status ?? 'pending';

                        // Build modal items: use existing record items or default list
                        $modalItems = $propItems->isNotEmpty()
                            ? $propItems->map(fn($i) => [
                                'item_name'       => $i->item_name,
                                'lost'            => (bool)$i->returned,  // returned col repurposed as lost
                                'damaged'         => (bool)$i->damaged,
                                'replacement_fee' => (float)$i->replacement_fee,
                              ])->values()->toArray()
                            : array_map(fn($n) => [
                                'item_name'       => $n,
                                'lost'            => false,
                                'damaged'         => false,
                                'replacement_fee' => 0,
                              ], \App\Models\StudentPropertyRecord::defaultItems());

                        $lostCount    = $propItems->where('returned', true)->count();
                        $damagedCount = $propItems->where('damaged', true)->count();
                    @endphp

                    {{-- Assigned Items --}}
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                        @if($propItems->isNotEmpty())
                        <div class="flex flex-col gap-0.5">
                            @foreach($propItems as $item)
                            <span class="inline-flex items-center gap-1">
                                @if($item->returned)
                                    <iconify-icon icon="solar:close-circle-bold" width="10" class="text-red-500"></iconify-icon>
                                    <span class="line-through text-red-400">{{ $item->item_name }}</span>
                                    <span class="text-[10px] text-red-500 font-semibold">Lost</span>
                                @elseif($item->damaged)
                                    <iconify-icon icon="solar:danger-triangle-bold" width="10" class="text-amber-500"></iconify-icon>
                                    <span class="text-amber-600">{{ $item->item_name }}</span>
                                    <span class="text-[10px] text-amber-500 font-semibold">Damaged</span>
                                @else
                                    <iconify-icon icon="solar:check-circle-bold" width="10" class="text-blue-500"></iconify-icon>
                                    {{ $item->item_name }}
                                @endif
                            </span>
                            @endforeach
                        </div>
                        @else
                        <span class="text-slate-400 italic text-[11px]">No items on record</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    @php
                        $propBadge = [
                            'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            'cleared' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'overdue' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                        ];
                        $propLabel = [
                            'pending' => 'Pending',
                            'cleared' => 'Cleared',
                            'overdue' => 'Overdue',
                        ];
                    @endphp
                    <td class="px-4 py-3 text-center">
                        <div class="flex flex-col items-center gap-1">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                {{ $propBadge[$propStatus] ?? $propBadge['pending'] }}">
                                {{ $propLabel[$propStatus] ?? 'Pending' }}
                            </span>
                            @if($lostCount || $damagedCount)
                            <span class="text-[10px] text-red-400">
                                {{ $lostCount ? "{$lostCount} lost" : '' }}{{ ($lostCount && $damagedCount) ? ', ' : '' }}{{ $damagedCount ? "{$damagedCount} damaged" : '' }}
                            </span>
                            @endif
                        </div>
                    </td>

                    {{-- Actions --}}
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
                                    onclick="openIssueModal({{ \Illuminate\Support\Js::from([
                                        'id'   => $student->id,
                                        'name' => $student->last_name . ', ' . $student->first_name,
                                        'items'=> $modalItems,
                                    ]) }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:pen-bold" width="14" class="text-blue-500"></iconify-icon>
                                    Edit Property Items
                                </button>
                                <button
                                    onclick="updatePropertyClearance({{ $student->id }}, 'cleared')"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:check-circle-bold" width="14" class="text-green-500"></iconify-icon>
                                    Mark Cleared
                                </button>
                                <button
                                    onclick="updatePropertyClearance({{ $student->id }}, 'pending')"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:clock-circle-bold" width="14" class="text-amber-500"></iconify-icon>
                                    Mark Pending
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
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                                <iconify-icon icon="solar:box-bold" width="28" class="text-slate-400"></iconify-icon>
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
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400">
                Showing {{ $students->count() }} of {{ $students->total() }} entries
            </p>
        </div>
        @endif

    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

</div>

{{-- ── Floating Bulk Action Bar ── --}}
<div id="bulk-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-30 hidden pointer-events-none">
    <div class="pointer-events-auto flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-2xl px-5 py-3">
        <div class="flex items-center gap-2 pr-3 border-r border-slate-200 dark:border-dark-border">
            <iconify-icon icon="solar:users-group-two-rounded-bold" width="16" class="text-[#0d4c8f]"></iconify-icon>
            <span id="bulk-count" class="text-sm font-bold text-slate-800 dark:text-white">0</span>
            <span class="text-xs text-slate-500 dark:text-slate-400">selected</span>
        </div>
        <button onclick="bulkUpdateStatus('cleared')"
            class="flex items-center gap-1.5 rounded-xl bg-green-600 hover:bg-green-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
            Mark Cleared
        </button>
        <button onclick="bulkUpdateStatus('pending')"
            class="flex items-center gap-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:clock-circle-bold" width="14"></iconify-icon>
            Mark Pending
        </button>
        <button onclick="bulkUpdateStatus('overdue')"
            class="flex items-center gap-1.5 rounded-xl bg-red-600 hover:bg-red-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:danger-triangle-bold" width="14"></iconify-icon>
            Mark Overdue
        </button>
        <button
            class="flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:export-linear" width="14"></iconify-icon>
            Export
        </button>
        <button onclick="document.getElementById('bulk-bar').classList.add('hidden'); document.querySelectorAll('.row-checkbox').forEach(c=>c.checked=false); document.getElementById('select-all').checked=false;"
            class="flex items-center justify-center h-7 w-7 rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:text-slate-600 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
            <iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon>
        </button>
    </div>
</div>

{{-- ── Edit Property Items Modal ── --}}
<div id="issue-modal"
     x-data="issueModal()"
     @open-issue-modal.window="open($event.detail)"
     style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div>
                <h3 class="text-base font-semibold text-slate-800 dark:text-white">Edit Property Items</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="studentName"></p>
            </div>
            <button @click="close()" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>

        {{-- Info note --}}
        <div class="mx-6 mt-4 flex items-start gap-2 rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-800 dark:bg-amber-900/10 px-3 py-2.5">
            <iconify-icon icon="solar:info-circle-bold" width="14" class="text-amber-500 shrink-0 mt-0.5"></iconify-icon>
            <p class="text-xs text-amber-700 dark:text-amber-400">All items are pre-assigned. Mark any that are <strong>lost</strong> or <strong>damaged</strong> — this will automatically set the student's status to Overdue.</p>
        </div>

        {{-- Items Table --}}
        <div class="px-6 py-4 max-h-[50vh] overflow-y-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 border-b border-slate-100 dark:border-dark-border">
                        <th class="py-2 text-left">Item</th>
                        <th class="py-2 text-center w-16">Lost</th>
                        <th class="py-2 text-center w-20">Damaged</th>
                        <th class="py-2 text-right w-28">Rep. Fee (₱)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-dark-border">
                    <template x-for="(item, idx) in items" :key="idx">
                        <tr :class="(item.lost || item.damaged) ? 'bg-red-50/50 dark:bg-red-900/10' : 'hover:bg-slate-50/60 dark:hover:bg-white/[0.02]'">
                            <td class="py-2.5 pr-2 font-medium text-slate-700 dark:text-slate-300"
                                :class="item.lost ? 'line-through text-red-400' : ''"
                                x-text="item.item_name"></td>
                            <td class="py-2.5 text-center">
                                <input type="checkbox" x-model="item.lost"
                                    @change="if(item.lost){ item.damaged = false; }"
                                    class="rounded border-slate-300 text-red-600 focus:ring-red-500 cursor-pointer">
                            </td>
                            <td class="py-2.5 text-center">
                                <input type="checkbox" x-model="item.damaged"
                                    @change="if(item.damaged){ item.lost = false; }"
                                    class="rounded border-slate-300 text-amber-500 focus:ring-amber-500 cursor-pointer">
                            </td>
                            <td class="py-2.5 text-right">
                                <input type="number" x-model.number="item.replacement_fee"
                                    :disabled="!item.lost && !item.damaged"
                                    min="0" step="0.01" placeholder="0.00"
                                    class="w-24 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-2 py-1 text-xs text-right text-slate-700 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:opacity-40 disabled:cursor-not-allowed">
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
            <div class="text-xs text-slate-500 dark:text-slate-400">
                <span class="text-red-600 font-semibold" x-show="items.filter(i=>i.lost).length > 0">
                    <span x-text="items.filter(i=>i.lost).length"></span> lost
                </span>
                <span x-show="items.filter(i=>i.lost).length > 0 && items.filter(i=>i.damaged).length > 0"> · </span>
                <span class="text-amber-600 font-semibold" x-show="items.filter(i=>i.damaged).length > 0">
                    <span x-text="items.filter(i=>i.damaged).length"></span> damaged
                </span>
                <span x-show="items.filter(i=>i.lost).length === 0 && items.filter(i=>i.damaged).length === 0"
                      class="text-green-600 font-semibold">All items in good condition</span>
            </div>
            <div class="flex gap-2">
                <button @click="close()" type="button"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    Cancel
                </button>
                <button @click="save()" type="button"
                    :disabled="saving"
                    class="rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm disabled:opacity-60 flex items-center gap-2">
                    <iconify-icon icon="solar:floppy-disk-bold" width="14" x-show="!saving"></iconify-icon>
                    <iconify-icon icon="solar:refresh-circle-linear" width="14" class="animate-spin" x-show="saving" style="display:none"></iconify-icon>
                    <span x-text="saving ? 'Saving…' : 'Save Changes'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const selectAll    = document.getElementById('select-all');
    const bulkBar      = document.getElementById('bulk-bar');
    const bulkCount    = document.getElementById('bulk-count');

    function syncBulkBar() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        const total   = document.querySelectorAll('.row-checkbox').length;
        bulkCount.textContent = checked;
        if (checked > 0) {
            bulkBar.classList.remove('hidden');
        } else {
            bulkBar.classList.add('hidden');
        }
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

    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.addEventListener('change', syncBulkBar);
    });

    window.openIssueModal = d => {
        window.dispatchEvent(new CustomEvent('open-issue-modal', { detail: d }));
    };

    function issueModal() {
        return {
            studentId:   null,
            studentName: '',
            items:       [],
            saving:      false,

            open(d) {
                this.studentId   = d.id;
                this.studentName = d.name;
                this.items       = JSON.parse(JSON.stringify(d.items));
                this.saving      = false;
                document.getElementById('issue-modal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            },
            close() {
                document.getElementById('issue-modal').style.display = 'none';
                document.body.style.overflow = '';
            },
            save() {
                this.saving = true;
                fetch(`/admin/clearance/property/${this.studentId}/issue`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ items: this.items }),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        this.close();
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to save.');
                        this.saving = false;
                    }
                })
                .catch(() => {
                    alert('An error occurred. Please try again.');
                    this.saving = false;
                });
            },
        };
    }

    function updatePropertyClearance(id, status) {
        fetch(`/admin/clearance/property/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ status }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
        })
        .catch(console.error);
    }

    function bulkUpdateStatus(status) {
        const ids = [...document.querySelectorAll('.row-checkbox:checked')].map(c => c.value);
        if (!ids.length) return;
        Promise.all(ids.map(id =>
            fetch(`/admin/clearance/property/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status }),
            })
        )).then(() => location.reload());
    }
</script>
@endpush

@endsection
