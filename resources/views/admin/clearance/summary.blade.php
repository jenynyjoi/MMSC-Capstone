@extends('layouts.admin_layout')
@section('title', 'Clearance Summary')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- ── Page Header ── --}}
    <x-admin.page-header title="Clearance" subtitle="Student Requirements Validation">
        <div class="flex items-center gap-2 mt-1 sm:mt-0">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap font-medium">Current School Year:</span>
            <form method="GET" action="{{ route('admin.clearance.summary') }}" id="sy-form">
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

    {{-- ── Main Card ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20">
                    <iconify-icon icon="solar:checklist-bold" width="18" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                </div>
                <span class="text-base font-semibold text-slate-800 dark:text-white">Clearance Summary</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 font-medium">
                <span>CURRENT SCHOOL YEAR: <span class="text-[#0d4c8f] dark:text-blue-400 font-semibold">SY {{ $schoolYear }}</span></span>
                <span class="text-slate-300 dark:text-slate-600">|</span>
                <span>AS OF: <span class="text-blue-500 font-semibold">{{ now()->format('F d, Y') }}</span></span>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            @foreach([
                ['blue',   'solar:users-group-two-rounded-bold', $totalStudents, 'Total Students'],
                ['yellow', 'solar:clock-square-bold',            $pendingCount,  'Pending Clearance'],
                ['green',  'solar:check-square-bold',            $clearedCount,  'Cleared'],
                ['red',    'solar:close-square-bold',            $overdueCount,  'Overdue'],
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

        {{-- ── Progress Bars ── --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-5">
                @php
                    $bars = [
                        ['Finance Cleared',   $progress['finance'],   'solar:wallet-bold',            '#8b5cf6', 'bg-violet-500'],
                        ['Library Cleared',   $progress['library'],   'solar:library-bold',           '#3b82f6', 'bg-blue-500'],
                        ['Records Cleared',   $progress['records'],   'solar:folder-with-files-bold', '#0d4c8f', 'bg-[#0d4c8f]'],
                        ['Behavioral Cleared',$progress['behavioral'],'solar:user-check-bold',        '#10b981', 'bg-emerald-500'],
                        ['Property Cleared',  $progress['property'],  'solar:box-bold',               '#ec4899', 'bg-pink-500'],
                        ['Academic Cleared',  $progress['academic'],  'solar:diploma-bold',           '#f59e0b', 'bg-amber-500'],
                    ];
                @endphp
                @foreach($bars as [$label, $pct, $icon, $hex, $barClass])
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="flex items-center gap-1.5">
                            <iconify-icon icon="{{ $icon }}" width="13" style="color:{{ $hex }}"></iconify-icon>
                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $label }}</span>
                        </div>
                        <span class="text-sm font-bold text-slate-800 dark:text-white">{{ $pct }}%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div class="h-2 rounded-full {{ $barClass }} transition-all duration-500" style="width:{{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── Filters ── --}}
        <form method="GET" action="{{ route('admin.clearance.summary') }}" id="filter-form"
              class="px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01] space-y-3">
            <input type="hidden" name="school_year" value="{{ $schoolYear }}">

            {{-- Row 1 --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                {{-- School Year --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">School Year</label>
                    <div class="flex items-center justify-between rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-2 text-sm font-medium text-slate-700 dark:text-white shadow-sm">
                        <span>SY {{ $schoolYear }}</span>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="text-slate-400"></iconify-icon>
                    </div>
                </div>
                {{-- Grade & Section --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Grade and Section</label>
                    <div class="relative">
                        <select name="grade_section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All</option>
                            @foreach($sections as $sec)
                                <option value="{{ $sec }}" {{ request('grade_section')===$sec?'selected':'' }}>{{ $sec }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                    </div>
                </div>
                {{-- Overall Status --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Overall Status</label>
                    <div class="relative">
                        <select name="overall_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="cleared" {{ request('overall_status')==='cleared'?'selected':'' }}>Cleared</option>
                            <option value="pending" {{ request('overall_status')==='pending'?'selected':'' }}>Pending</option>
                            <option value="overdue" {{ request('overall_status')==='overdue'?'selected':'' }}>Overdue</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                    </div>
                </div>
                {{-- Finance Status --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Finance Status</label>
                    <div class="relative">
                        <select name="finance_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="cleared" {{ request('finance_status')==='cleared'?'selected':'' }}>Cleared</option>
                            <option value="pending" {{ request('finance_status')==='pending'?'selected':'' }}>Pending</option>
                            <option value="overdue" {{ request('finance_status')==='overdue'?'selected':'' }}>Overdue</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                    </div>
                </div>
                {{-- Library Status --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Library Status</label>
                    <div class="relative">
                        <select name="library_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="cleared"   {{ request('library_status')==='cleared'?'selected':'' }}>Cleared</option>
                            <option value="pending"   {{ request('library_status')==='pending'?'selected':'' }}>Pending</option>
                            <option value="overdue"   {{ request('library_status')==='overdue'?'selected':'' }}>Overdue</option>
                            <option value="no_record" {{ request('library_status')==='no_record'?'selected':'' }}>No Books</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                    </div>
                </div>
                {{-- Records Status --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Records Status</label>
                    <div class="relative">
                        <select name="records_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="cleared" {{ request('records_status')==='cleared'?'selected':'' }}>Cleared</option>
                            <option value="pending" {{ request('records_status')==='pending'?'selected':'' }}>Pending</option>
                            <option value="missing" {{ request('records_status')==='missing'?'selected':'' }}>Missing</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                    </div>
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 gap-3">
                {{-- Behavioral --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Behavioral Status</label>
                    <div class="relative">
                        <select name="behavioral_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="cleared" {{ request('behavioral_status')==='cleared'?'selected':'' }}>Cleared</option>
                            <option value="pending" {{ request('behavioral_status')==='pending'?'selected':'' }}>Pending</option>
                            <option value="overdue" {{ request('behavioral_status')==='overdue'?'selected':'' }}>Overdue</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                    </div>
                </div>
                {{-- Property --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Property Status</label>
                    <div class="relative">
                        <select name="property_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All</option>
                            <option value="cleared"      {{ request('property_status')==='cleared'?'selected':'' }}>Cleared</option>
                            <option value="issued"       {{ request('property_status')==='issued'?'selected':'' }}>Issued</option>
                            <option value="for_issuance" {{ request('property_status')==='for_issuance'?'selected':'' }}>For Issuance</option>
                            <option value="overdue"      {{ request('property_status')==='overdue'?'selected':'' }}>Overdue</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                    </div>
                </div>
                {{-- Academic (placeholder) --}}
                <div class="flex flex-col gap-1">
                    <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Academic Status</label>
                    <div class="flex items-center rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50/70 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-400 italic">
                        Not configured
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
                        <a href="{{ route('admin.clearance.summary', ['school_year' => $schoolYear]) }}"
                            class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-3 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm whitespace-nowrap">
                            Clear All
                        </a>
                    </div>
                </div>
            </div>
        </form>

        {{-- ── Table Toolbar ── --}}
        @php $activeLevel = request('program_level', ''); @endphp
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300 shrink-0">
                <span class="font-medium">Show</span>
                <select name="per_page" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-2 py-1 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach([5,10,25,50] as $n)
                        <option value="{{ $n }}" {{ request('per_page',10)==$n?'selected':'' }}>{{ $n }}</option>
                    @endforeach
                </select>
                <span class="font-medium">Entries</span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex rounded-lg overflow-hidden border border-slate-200 dark:border-dark-border text-xs font-semibold shrink-0">
                    @foreach(['' => 'ALL', 'Elementary' => 'ELEM', 'Junior High School' => 'JHS', 'Senior High School' => 'SHS'] as $val => $label)
                        <a href="{{ route('admin.clearance.summary', array_merge(request()->except('program_level','page'), ['school_year'=>$schoolYear,'program_level'=>$val])) }}"
                            class="px-4 py-2 transition-colors {{ $activeLevel===$val ? 'bg-[#0d4c8f] text-white' : 'bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5' }} {{ !$loop->first ? 'border-l border-slate-200 dark:border-dark-border' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
                {{-- Search beside level tabs --}}
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" name="search" form="filter-form" value="{{ request('search') }}" placeholder="Search name or ID…"
                        onkeydown="if(event.key==='Enter'){document.getElementById('filter-form').submit()}"
                        class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 pl-9 pr-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-52">
                </div>
            </div>
        </div>

        {{-- ── Table ── --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:1200px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 bg-slate-50/70 dark:bg-white/[0.02]">
                        <th class="px-4 py-3 w-8">
                            <input type="checkbox" id="select-all" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade & Section</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Finance</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Library</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Academic</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Records</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Property</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Behavioral</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Overall Clearance</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">

                @php
                    // Plain colored text helper for category columns
                    $statusText = function(string $status): string {
                        $colors = [
                            'cleared'      => 'text-green-600 dark:text-green-400',
                            'pending'      => 'text-yellow-600 dark:text-yellow-400',
                            'overdue'      => 'text-red-600 dark:text-red-400',
                            'missing'      => 'text-red-600 dark:text-red-400',
                            'no_record'    => 'text-slate-400 dark:text-slate-500',
                            'for_issuance' => 'text-slate-500 dark:text-slate-400',
                            'issued'       => 'text-blue-600 dark:text-blue-400',
                            'null'         => 'text-slate-300 dark:text-slate-600',
                        ];
                        $labels = [
                            'cleared'      => 'Cleared',
                            'pending'      => 'Pending',
                            'overdue'      => 'Overdue',
                            'missing'      => 'Missing',
                            'no_record'    => 'No Books',
                            'for_issuance' => 'For Issuance',
                            'issued'       => 'Issued',
                            'null'         => '—',
                        ];
                        $cls = $colors[$status] ?? $colors['null'];
                        $txt = $labels[$status] ?? ucfirst($status);
                        return '<span class="text-xs font-semibold '.$cls.'">'.$txt.'</span>';
                    };
                @endphp

                @forelse($students as $student)
                @php
                    $detailData = \Illuminate\Support\Js::from([
                        'id'   => $student->id,
                        'name' => $student->last_name.', '.$student->first_name.' '.($student->middle_name ? strtoupper(substr($student->middle_name,0,1)).'.' : '').($student->suffix ? ' '.$student->suffix : ''),
                        'studentId' => $student->student_id,
                        'grade' => $student->section_name ? \App\Models\Section::formatName($student->grade_level ?? '—', $student->section_name, $student->strand) : ($student->grade_level ?? '—'),
                        'clearances' => [
                            ['key'=>'finance',   'label'=>'Finance',   'icon'=>'solar:wallet-bold',            'status'=>$student->finance_status,    'route'=>route('admin.clearance.finance')],
                            ['key'=>'library',   'label'=>'Library',   'icon'=>'solar:library-bold',           'status'=>$student->library_status,    'route'=>route('admin.clearance.library')],
                            ['key'=>'records',   'label'=>'Records',   'icon'=>'solar:folder-with-files-bold', 'status'=>$student->records_status,    'route'=>route('admin.clearance.records')],
                            ['key'=>'behavioral','label'=>'Behavioral','icon'=>'solar:user-check-bold',        'status'=>$student->behavioral_status, 'route'=>route('admin.clearance.behavioral')],
                            ['key'=>'property',  'label'=>'Property',  'icon'=>'solar:box-bold',               'status'=>$student->property_status,   'route'=>route('admin.clearance.property')],
                            ['key'=>'academic',  'label'=>'Academic',  'icon'=>'solar:diploma-bold',           'status'=>'null',                      'route'=>'#'],
                        ],
                    ]);
                @endphp
                <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors">
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
                                {{ strtoupper(substr($student->first_name??'S',0,1)) }}{{ strtoupper(substr($student->last_name??'S',0,1)) }}
                            </div>
                            <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                class="text-sm font-medium text-slate-800 dark:text-slate-100 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors">
                                {{ $student->last_name }}, {{ $student->first_name }}
                                {{ $student->middle_name ? strtoupper(substr($student->middle_name,0,1)).'.' : '' }}
                                {{ $student->suffix ?? '' }}
                            </a>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                        {{ $student->section_name ? \App\Models\Section::formatName($student->grade_level ?? '—', $student->section_name, $student->strand) : ($student->grade_level ?? '—') }}
                    </td>
                    <td class="px-4 py-3 text-center">{!! $statusText($student->finance_status) !!}</td>
                    <td class="px-4 py-3 text-center">{!! $statusText($student->library_status) !!}</td>
                    <td class="px-4 py-3 text-center">{!! $statusText('null') !!}</td>
                    <td class="px-4 py-3 text-center">{!! $statusText($student->records_status) !!}</td>
                    <td class="px-4 py-3 text-center">{!! $statusText($student->property_status) !!}</td>
                    <td class="px-4 py-3 text-center">{!! $statusText($student->behavioral_status) !!}</td>
                    <td class="px-4 py-3 text-center">
                        @php $ov = $student->overall_status; @endphp
                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold
                            {{ $ov==='cleared' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                             : ($ov==='overdue' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                             : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400') }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $ov==='cleared'?'bg-green-500':($ov==='overdue'?'bg-red-500':'bg-yellow-500') }}"></span>
                            {{ $ov==='cleared'?'Cleared':($ov==='overdue'?'Overdue':'Pending') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div x-data="{ open: false }" class="relative inline-block">
                            <button @click="open = !open" @click.outside="open = false"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                                Select
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="12"
                                    :class="open?'rotate-180':''" class="transition-transform duration-200"></iconify-icon>
                            </button>
                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute right-0 z-20 mt-1 w-52 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1"
                                style="display:none">
                                <button
                                    onclick="openDetailsModal({{ $detailData }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:list-check-bold" width="14" class="text-[#0d4c8f]"></iconify-icon>
                                    See Details
                                </button>
                                <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:user-id-bold" width="14" class="text-blue-500"></iconify-icon>
                                    View Profile
                                </a>
                                <button
                                    onclick="openSummaryNotice({{ $student->id }}, {{ \Illuminate\Support\Js::from($student->last_name.', '.$student->first_name) }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:bell-bold" width="14" class="text-amber-500"></iconify-icon>
                                    Send Notice
                                </button>
                                <hr class="my-1 border-slate-100 dark:border-slate-700">
                                <button
                                    onclick="confirmMarkAllCleared({{ $student->id }}, {{ \Illuminate\Support\Js::from($student->last_name.', '.$student->first_name) }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-green-700 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/10 transition-colors">
                                    <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                                    Mark All Cleared
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                                <iconify-icon icon="solar:checklist-bold" width="28" class="text-slate-400"></iconify-icon>
                            </div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No students found</p>
                            <p class="text-xs text-slate-400">Try adjusting your filters</p>
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
            <p class="text-xs text-slate-400">Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} entries</p>
            <div class="flex items-center gap-1">
                @if($students->onFirstPage())
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-300 cursor-not-allowed"><iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon></span>
                @else
                    <a href="{{ $students->previousPageUrl() }}" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon></a>
                @endif
                @foreach($students->getUrlRange(max(1,$students->currentPage()-2), min($students->lastPage(),$students->currentPage()+2)) as $page => $url)
                    @if($page===$students->currentPage())
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 text-xs transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                @if($students->hasMorePages())
                    <a href="{{ $students->nextPageUrl() }}" class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon></a>
                @else
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-300 cursor-not-allowed"><iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon></span>
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
            <span class="text-xs text-slate-500">selected</span>
        </div>
        <button onclick="bulkMarkAllCleared()"
            class="flex items-center gap-1.5 rounded-xl bg-green-600 hover:bg-green-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> Mark All Cleared
        </button>
        <button class="flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:export-linear" width="14"></iconify-icon> Export Excel
        </button>
        <button class="flex items-center gap-1.5 rounded-xl bg-red-600 hover:bg-red-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:file-text-linear" width="14"></iconify-icon> Generate PDF
        </button>
        <button class="flex items-center gap-1.5 rounded-xl bg-[#0d4c8f] hover:bg-blue-800 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:bell-bold" width="14"></iconify-icon> Send Reminder
        </button>
        <button onclick="document.getElementById('bulk-bar').classList.add('hidden'); document.querySelectorAll('.row-checkbox').forEach(c=>c.checked=false); document.getElementById('select-all').checked=false;"
            class="flex items-center justify-center h-7 w-7 rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:text-slate-600 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
            <iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon>
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SEE DETAILS MODAL
══════════════════════════════════════════════════════════ --}}
<div id="details-modal"
     x-data="detailsModal()"
     @open-details.window="open($event.detail)"
     style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
    <div class="relative w-full max-w-xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div>
                <h3 class="text-base font-semibold text-slate-800 dark:text-white">Clearance Details</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    <span x-text="data.name"></span> &middot; <span x-text="data.studentId" class="font-mono"></span>
                </p>
            </div>
            <button @click="close()" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>

        <div class="px-6 py-4 space-y-2 max-h-[60vh] overflow-y-auto">
            <template x-for="c in data.clearances" :key="c.key">
                <div class="flex items-center justify-between py-3 border-b border-slate-50 dark:border-dark-border last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800">
                            <iconify-icon :icon="c.icon" width="18" class="text-slate-500 dark:text-slate-400"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200" x-text="c.label"></p>
                            <p class="text-[11px] text-slate-400 mt-0.5" x-text="c.key === 'academic' ? 'Not yet configured' : ''"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold"
                            :class="{
                                'bg-green-100 text-green-700': c.status === 'cleared',
                                'bg-yellow-100 text-yellow-700': c.status === 'pending' || c.status === 'no_record' || c.status === 'for_issuance' || c.status === 'issued',
                                'bg-red-100 text-red-700': c.status === 'overdue' || c.status === 'missing',
                                'bg-slate-100 text-slate-400': c.status === 'null',
                            }"
                            x-text="statusLabel(c.status)">
                        </span>
                        <template x-if="c.key !== 'academic'">
                            <a :href="c.route" target="_blank"
                                class="flex items-center gap-1 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-2.5 py-1 text-[11px] font-medium text-slate-600 dark:text-slate-300 transition-colors">
                                <iconify-icon icon="solar:arrow-right-up-linear" width="11"></iconify-icon>
                                View
                            </a>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
            <a :href="'/admin/student-records/profile/' + data.id"
                class="flex items-center gap-1.5 text-xs text-[#0d4c8f] dark:text-blue-400 font-medium hover:underline">
                <iconify-icon icon="solar:user-id-bold" width="13"></iconify-icon>
                View Full Profile
            </a>
            <div class="flex gap-2">
                <button @click="close()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    Close
                </button>
                <button @click="close(); confirmMarkAllCleared(data.id, data.name)"
                    class="rounded-lg bg-green-600 hover:bg-green-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                    Mark All Cleared
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     CONFIRM MARK ALL CLEARED MODAL
══════════════════════════════════════════════════════════ --}}
<div id="confirm-modal"
     x-data="confirmModal()"
     @open-confirm.window="open($event.detail.id, $event.detail.name)"
     style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="px-6 pt-6 pb-4 text-center">
            <div class="flex h-14 w-14 mx-auto items-center justify-center rounded-2xl bg-green-100 dark:bg-green-900/30 mb-4">
                <iconify-icon icon="solar:check-circle-bold" width="28" class="text-green-600 dark:text-green-400"></iconify-icon>
            </div>
            <h3 class="text-base font-semibold text-slate-800 dark:text-white">Mark All Cleared?</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                This will mark <span class="font-semibold text-slate-700 dark:text-slate-200" x-text="studentName"></span> as cleared across all clearance categories (Finance, Library, Records, Property, Behavioral).
            </p>
            <p class="text-xs text-amber-600 dark:text-amber-400 mt-2 font-medium">This action cannot be automatically undone. Please proceed with caution.</p>
        </div>
        <div class="flex gap-2 px-6 pb-6">
            <button @click="close()"
                class="flex-1 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                Cancel
            </button>
            <button @click="confirm()" :disabled="processing"
                class="flex-1 rounded-lg bg-green-600 hover:bg-green-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors shadow-sm disabled:opacity-60 flex items-center justify-center gap-2">
                <iconify-icon icon="solar:refresh-circle-linear" width="14" class="animate-spin" x-show="processing" style="display:none"></iconify-icon>
                <span x-text="processing ? 'Processing…' : 'Yes, Mark Cleared'"></span>
            </button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SEND NOTICE MODAL
══════════════════════════════════════════════════════════ --}}
<div id="notice-modal"
     x-data="summaryNoticeModal()"
     @open-summary-notice.window="open($event.detail.id, $event.detail.name)"
     style="display:none"
     class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close()"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div>
                <h3 class="text-base font-semibold text-slate-800 dark:text-white">Send Clearance Notice</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="studentName"></p>
            </div>
            <button @click="close()" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Notice Type</label>
                <select x-model="form.notice_type" class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="clearance_reminder">General Clearance Reminder</option>
                    <option value="overdue_notice">Overdue Clearance Notice</option>
                    <option value="final_clearance">Final Clearance Notice</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Send To</label>
                <div class="flex gap-3">
                    <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="form.send_student" class="rounded border-slate-300 text-blue-600"> Student
                    </label>
                    <label class="flex items-center gap-1.5 text-xs text-slate-600 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="form.send_parent" class="rounded border-slate-300 text-blue-600"> Parent/Guardian
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Message</label>
                <textarea x-model="form.message" rows="4" placeholder="Enter notice message…"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
            <button @click="close()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Cancel</button>
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
    // ── Bulk bar ─────────────────────────────────────────────
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

    // ── Global openers ────────────────────────────────────────
    window.openDetailsModal = d => window.dispatchEvent(new CustomEvent('open-details', { detail: d }));
    window.confirmMarkAllCleared = (id, name) => window.dispatchEvent(new CustomEvent('open-confirm', { detail: { id, name } }));
    window.openSummaryNotice = (id, name) => window.dispatchEvent(new CustomEvent('open-summary-notice', { detail: { id, name } }));

    // ── Details Modal ─────────────────────────────────────────
    function detailsModal() {
        return {
            data: { id: null, name: '', studentId: '', grade: '', clearances: [] },
            open(d) {
                this.data = d;
                document.getElementById('details-modal').style.display = 'flex';
            },
            close() { document.getElementById('details-modal').style.display = 'none'; },
            statusLabel(s) {
                const m = { cleared:'Cleared', pending:'Pending', overdue:'Overdue', missing:'Missing',
                            no_record:'No Books', for_issuance:'For Issuance', issued:'Issued', null:'—' };
                return m[s] ?? s;
            },
        };
    }

    // ── Confirm Mark All Cleared ──────────────────────────────
    function confirmModal() {
        return {
            studentId: null, studentName: '', processing: false,
            open(id, name) {
                this.studentId = id; this.studentName = name; this.processing = false;
                document.getElementById('confirm-modal').style.display = 'flex';
            },
            close() { document.getElementById('confirm-modal').style.display = 'none'; },
            confirm() {
                this.processing = true;
                fetch(`/admin/clearance/summary/${this.studentId}/mark-all`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({}),
                })
                .then(r => r.json())
                .then(data => { this.close(); if (data.success) location.reload(); })
                .catch(() => { this.processing = false; });
            },
        };
    }

    // ── Send Notice Modal ─────────────────────────────────────
    function summaryNoticeModal() {
        return {
            studentId: null, studentName: '', sending: false,
            form: { notice_type: 'clearance_reminder', message: '', send_student: true, send_parent: true },
            open(id, name) {
                this.studentId = id; this.studentName = name; this.sending = false;
                this.form = { notice_type: 'clearance_reminder', message: '', send_student: true, send_parent: true };
                document.getElementById('notice-modal').style.display = 'flex';
            },
            close() { document.getElementById('notice-modal').style.display = 'none'; },
            send() {
                if (!this.form.message.trim()) { alert('Please enter a message.'); return; }
                this.sending = true;
                const sendTo = [];
                if (this.form.send_student) sendTo.push('student');
                if (this.form.send_parent)  sendTo.push('parent');
                fetch('/admin/student-records/send-notice', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ student_ids: [this.studentId], notice_type: this.form.notice_type, subject: 'Clearance Notice', message: this.form.message, send_to: sendTo }),
                })
                .then(() => this.close())
                .catch(() => { this.sending = false; });
            },
        };
    }

    // ── Bulk Mark All Cleared ─────────────────────────────────
    function bulkMarkAllCleared() {
        const ids = [...document.querySelectorAll('.row-checkbox:checked')].map(c => c.value);
        if (!ids.length) return;
        if (!confirm(`Mark all ${ids.length} selected student(s) as fully cleared? This cannot be automatically undone.`)) return;
        Promise.all(ids.map(id =>
            fetch(`/admin/clearance/summary/${id}/mark-all`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({}),
            })
        )).then(() => location.reload());
    }
</script>
@endpush

@endsection
