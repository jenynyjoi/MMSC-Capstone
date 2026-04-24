@extends('layouts.admin_layout')
@section('title', 'Finance Clearance')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- ── Page Header ── --}}
    <x-admin.page-header title="Clearance" subtitle="Student Requirements Validation">
        <div class="flex items-center gap-2 mt-1 sm:mt-0" x-data="{ open: false }">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap font-medium">Current School Year:</span>
            <form method="GET" action="{{ route('admin.clearance.finance') }}" id="sy-form">
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

    {{-- ── Finance Card ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20">
                    <iconify-icon icon="solar:wallet-bold" width="18" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                </div>
                <span class="text-base font-semibold text-slate-800 dark:text-white">Finance</span>
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
                ['yellow', 'solar:clock-circle-bold',            $pendingCount,  'Pending Fees'],
                ['green',  'solar:check-circle-bold',            $clearedCount,  'Cleared Fees'],
                ['red',    'solar:danger-triangle-bold',         $overdueCount,  'Overdue Fees'],
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
        <form method="GET" action="{{ route('admin.clearance.finance') }}" id="filter-form"
              class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">

            <input type="hidden" name="school_year" value="{{ $schoolYear }}">

            {{-- School Year --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">School Year</label>
                <div class="flex items-center justify-between rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-2 text-sm font-medium text-slate-700 dark:text-white shadow-sm">
                    <span>SY {{ $schoolYear }}</span>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="text-slate-400"></iconify-icon>
                </div>
            </div>

            {{-- Student Category --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Student Category</label>
                <div class="relative">
                    <select name="student_category"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="Regular Payee"      {{ request('student_category')==='Regular Payee' ?'selected':'' }}>Regular Payee</option>
                        <option value="SHS Voucher Recipient" {{ request('student_category')==='SHS Voucher Recipient' ?'selected':'' }}>SHS Voucher Recipient</option>
                        <option value="ESC Grantee"        {{ request('student_category')==='ESC Grantee' ?'selected':'' }}>ESC Grantee</option>
                        <option value="Scholarship"        {{ request('student_category')==='Scholarship' ?'selected':'' }}>Scholarship</option>
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
                        @foreach($sections as $sec)
                            <option value="{{ $sec }}" {{ request('grade_section')===$sec ?'selected':'' }}>{{ $sec }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none rotate-90"></iconify-icon>
                </div>
            </div>

            {{-- Payment Status --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Payment Status</label>
                <div class="relative">
                    <select name="payment_status"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 pr-8 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="paid"    {{ request('payment_status')==='paid' ?'selected':'' }}>Paid</option>
                        <option value="partial" {{ request('payment_status')==='partial' ?'selected':'' }}>Partial</option>
                        <option value="unpaid"  {{ request('payment_status')==='unpaid' ?'selected':'' }}>Unpaid</option>
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

            {{-- Buttons --}}
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-transparent uppercase tracking-wide select-none">Actions</label>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-4 py-2 text-sm font-semibold text-white transition-colors shadow-sm flex-1 justify-center">
                        <iconify-icon icon="solar:filter-bold" width="14"></iconify-icon>
                        Apply
                    </button>
                    <a href="{{ route('admin.clearance.finance', ['school_year' => $schoolYear]) }}"
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
                        <a href="{{ route('admin.clearance.finance', array_merge(request()->except('program_level','page'), ['school_year'=>$schoolYear, 'program_level'=>$val])) }}"
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
            <table class="w-full text-left text-sm" style="min-width: 1100px">
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
                        <th class="px-4 py-3 whitespace-nowrap">Student Category</th>
                        <th class="px-4 py-3 whitespace-nowrap">Payment Plan</th>
                        <th class="px-4 py-3 whitespace-nowrap text-right">Tuition</th>
                        <th class="px-4 py-3 whitespace-nowrap text-right">Miscellaneous</th>
                        <th class="px-4 py-3 whitespace-nowrap text-right">Paid</th>
                        <th class="px-4 py-3 whitespace-nowrap text-right">Balance</th>
                        <th class="px-4 py-3 whitespace-nowrap">Last Payment</th>
                        <th class="px-4 py-3 whitespace-nowrap">Due Date</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Payment Status</th>
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
                    $paymentBadge = [
                        'paid'    => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'partial' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'unpaid'  => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                    ];
                @endphp

                @forelse($students as $student)
                @php
                    $fin = $student->finance;
                    $hasFinance = !is_null($fin);
                    $finCs = $hasFinance ? ($fin->finance_clearance ?? 'pending') : 'pending';
                    // Determine payment status
                    if (!$hasFinance) {
                        $payStatus = 'unpaid';
                    } elseif ($fin->balance <= 0) {
                        $payStatus = 'paid';
                    } elseif ($fin->amount_paid > 0) {
                        $payStatus = 'partial';
                    } else {
                        $payStatus = 'unpaid';
                    }
                    // Last payment date
                    $lastPaymentMonth = $hasFinance
                        ? $fin->paymentMonths->where('status', 'paid')->sortByDesc('paid_date')->first()
                        : null;
                    // Next due month
                    $nextDueMonth = $hasFinance
                        ? $fin->paymentMonths->whereIn('status', ['pending', 'overdue'])->sortBy('due_date')->first()
                        : null;
                @endphp
                <tr id="fin-row-{{ $student->id }}" class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors group">
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
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                            </div>
                            <a href="{{ route('admin.student-records.profile', $student->id) }}"
                                class="text-sm font-medium text-slate-800 dark:text-slate-100 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors whitespace-nowrap">
                                {{ $student->formatted_name }}
                            </a>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300 whitespace-nowrap">
                        {{ $student->school_year ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300 whitespace-nowrap">
                        {{ $student->section_name ? \App\Models\Section::formatName($student->grade_level ?? '—', $student->section_name, $student->strand) : ($student->grade_level ?? '—') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300 whitespace-nowrap">
                        {{ $student->student_category ?? '—' }}
                    </td>
                    {{-- Payment Plan --}}
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($hasFinance)
                            <span class="inline-flex items-center rounded-lg bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 text-[11px] font-bold text-blue-700 dark:text-blue-400">
                                Plan {{ $fin->payment_plan }}
                            </span>
                            @if($fin->monthly_months > 0)
                                <span class="ml-1 text-[11px] text-slate-400">×{{ $fin->monthly_months }}mo</span>
                            @else
                                <span class="ml-1 text-[11px] text-slate-400">cash</span>
                            @endif
                        @else
                            <button onclick="openFinanceModal({
                                studentId: {{ $student->id }},
                                referenceNumber: null,
                                gradeLevel: '{{ addslashes($student->grade_level ?? '') }}',
                                studentCategory: '{{ addslashes($student->student_category ?? 'Regular') }}',
                                schoolYear: '{{ $schoolYear }}',
                                studentName: '{{ addslashes($student->first_name.' '.$student->last_name) }}',
                                onSaved: function() { location.reload(); }
                            })" class="text-[11px] text-blue-600 hover:underline font-medium">+ Configure</button>
                        @endif
                    </td>
                    {{-- Tuition (enrollment fee) --}}
                    <td class="px-4 py-3 text-sm text-right whitespace-nowrap {{ $hasFinance ? 'text-slate-700 dark:text-slate-200' : 'text-slate-300' }}">
                        {{ $hasFinance ? '₱'.number_format($fin->enrollment_fee + ($fin->monthly_amount * $fin->monthly_months), 0) : '—' }}
                    </td>
                    {{-- Misc Fee --}}
                    <td class="px-4 py-3 text-sm text-right whitespace-nowrap {{ $hasFinance ? 'text-slate-700 dark:text-slate-200' : 'text-slate-300' }}">
                        {{ $hasFinance ? '₱'.number_format($fin->misc_fee, 0) : '—' }}
                    </td>
                    {{-- Paid --}}
                    <td class="px-4 py-3 text-sm text-right whitespace-nowrap {{ $hasFinance ? 'text-green-700 dark:text-green-400 font-semibold' : 'text-slate-300' }}">
                        {{ $hasFinance ? '₱'.number_format($fin->amount_paid, 0) : '—' }}
                    </td>
                    {{-- Balance --}}
                    <td class="px-4 py-3 text-sm text-right font-semibold whitespace-nowrap {{ $hasFinance && $fin->balance > 0 ? 'text-red-600 dark:text-red-400' : ($hasFinance ? 'text-green-700 dark:text-green-400' : 'text-slate-300') }}">
                        {{ $hasFinance ? '₱'.number_format($fin->balance, 0) : '—' }}
                    </td>
                    {{-- Last Payment --}}
                    <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                        {{ $lastPaymentMonth ? $lastPaymentMonth->month_name.' '.$lastPaymentMonth->month_year : '—' }}
                    </td>
                    {{-- Next Due --}}
                    <td class="px-4 py-3 text-xs whitespace-nowrap {{ $nextDueMonth && $nextDueMonth->status === 'overdue' ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500' }}">
                        @if($nextDueMonth)
                            {{ $nextDueMonth->month_name }}
                            @if($nextDueMonth->status === 'overdue')
                                <span class="ml-1 inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/30 px-1.5 py-0.5 text-[10px] font-bold text-red-700 dark:text-red-400">OVERDUE</span>
                            @endif
                        @else
                            {{ $hasFinance ? '—' : '—' }}
                        @endif
                    </td>
                    {{-- Payment Status --}}
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $paymentBadge[$payStatus] ?? $paymentBadge['unpaid'] }}">
                            {{ ucfirst($payStatus) }}
                        </span>
                    </td>
                    {{-- Finance Clearance Status --}}
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <span id="fin-badge-{{ $student->id }}" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $clearanceBadge[$finCs] ?? $clearanceBadge['pending'] }}">
                            {{ ucfirst($finCs) }}
                        </span>
                    </td>
                    {{-- Actions --}}
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 dark:bg-dark-card dark:hover:bg-white/5 px-3 py-1.5 text-xs font-semibold text-[#0d4c8f] dark:text-blue-400 transition-colors shadow-sm">
                                Actions
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="12" :class="open?'rotate-180':''" class="transition-transform"></iconify-icon>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 top-full mt-1 w-52 rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-lg z-50 overflow-hidden py-1"
                                style="display:none">

                                {{-- Update Fee --}}
                                @if($hasFinance && $fin->balance > 0)
                                <button @click="open=false; openUpdateFeeModal({{ $student->id }})"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-green-700 dark:text-green-400 hover:bg-slate-50 dark:hover:bg-white/5 font-semibold transition-colors">
                                    <iconify-icon icon="solar:card-bold" width="14"></iconify-icon>
                                    Update Fee (Pay Balance)
                                </button>
                                @else
                                <button @click="open=false; openUpdateFeeModal({{ $student->id }})"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:card-linear" width="14"></iconify-icon>
                                    Update Fee
                                </button>
                                @endif

                                {{-- Configure Fee --}}
                                <button @click="open=false; openFinanceModal({
                                    studentId: {{ $student->id }},
                                    referenceNumber: null,
                                    gradeLevel: '{{ addslashes($student->grade_level ?? '') }}',
                                    studentCategory: '{{ addslashes($student->student_category ?? 'Regular') }}',
                                    schoolYear: '{{ $schoolYear }}',
                                    studentName: '{{ addslashes($student->first_name.' '.$student->last_name) }}',
                                    onSaved: function() { location.reload(); }
                                })"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-blue-700 dark:text-blue-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:wallet-linear" width="14"></iconify-icon>
                                    Configure Fee
                                </button>

                                <div class="border-t border-slate-100 dark:border-dark-border my-1"></div>

                                {{-- See Details --}}
                                <button @click="open=false; openFinanceDetails({{ $student->id }})"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:document-text-linear" width="14"></iconify-icon>
                                    See Details
                                </button>

                                {{-- Generate Receipt --}}
                                <!-- <button @click="open=false; openLatestReceipt({{ $student->id }})"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:receipt-linear" width="14"></iconify-icon>
                                    Generate Receipt
                                </button> -->

                                {{-- Send Reminder --}}
                                <button @click="open=false; sendFinanceReminder({{ $student->id }}, '{{ addslashes($student->first_name.' '.$student->last_name) }}')"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:bell-linear" width="14"></iconify-icon>
                                    Send Reminder
                                </button>

                                <div class="border-t border-slate-100 dark:border-dark-border my-1"></div>

                                {{-- Clearance status --}}
                                <button @click="open=false; updateFinanceClearance({{ $student->id }}, 'cleared')"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-green-700 dark:text-green-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:check-circle-linear" width="14"></iconify-icon> Mark Cleared
                                </button>
                                <button @click="open=false; updateFinanceClearance({{ $student->id }}, 'pending')"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-amber-700 dark:text-amber-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:clock-circle-linear" width="14"></iconify-icon> Mark Pending
                                </button>
                                <button @click="open=false; updateFinanceClearance({{ $student->id }}, 'overdue')"
                                    class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-red-600 dark:text-red-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:danger-triangle-linear" width="14"></iconify-icon> Mark Overdue
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="16" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 dark:bg-white/5">
                                <iconify-icon icon="solar:wallet-linear" width="28" class="text-slate-400"></iconify-icon>
                            </div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No students found</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Try adjusting the filters or school year</p>
                        </div>
                    </td>
                </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        {{-- ── Footer: Pagination only ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-4 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">

            {{-- Pagination --}}
            <div class="flex items-center gap-3">
                <p class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                    Showing {{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }}
                    of {{ $students->total() }} students
                </p>
                <div class="flex items-center gap-1">
                    {{-- Prev --}}
                    @if($students->onFirstPage())
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-300 dark:text-slate-600 cursor-not-allowed">
                            <iconify-icon icon="solar:alt-arrow-left-linear" width="14"></iconify-icon>
                        </span>
                    @else
                        <a href="{{ $students->previousPageUrl() }}"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <iconify-icon icon="solar:alt-arrow-left-linear" width="14"></iconify-icon>
                        </a>
                    @endif

                    {{-- Pages --}}
                    @foreach($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-xs font-semibold transition-colors
                                {{ $page === $students->currentPage()
                                    ? 'bg-[#0d4c8f] text-white border border-[#0d4c8f]'
                                    : 'border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    {{-- Next --}}
                    @if($students->hasMorePages())
                        <a href="{{ $students->nextPageUrl() }}"
                            class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="14"></iconify-icon>
                        </a>
                    @else
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-300 dark:text-slate-600 cursor-not-allowed">
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="14"></iconify-icon>
                        </span>
                    @endif
                </div>
            </div>

        </div>

    </div>
    {{-- end card --}}

</div>

{{-- ── Floating Bulk Action Bar ── --}}
<div id="bulk-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-30 hidden pointer-events-none">
    <div class="pointer-events-auto flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-2xl px-5 py-3">
        <iconify-icon icon="solar:users-group-rounded-linear" width="18" class="text-slate-400 shrink-0"></iconify-icon>
        <span id="bulk-count" class="text-xs font-medium text-slate-600 dark:text-slate-300 whitespace-nowrap">0 Selected</span>
        <div class="h-4 w-px bg-slate-200 dark:bg-slate-700 shrink-0"></div>

        <button onclick="exportExcel()"
            class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/10 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 transition-colors whitespace-nowrap">
            <iconify-icon icon="solar:export-linear" width="14" class="text-green-600"></iconify-icon>
            <span class="hidden sm:inline">Export Excel</span>
        </button>

        <button onclick="generatePdf()"
            class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/10 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 transition-colors whitespace-nowrap">
            <iconify-icon icon="solar:file-text-linear" width="14" class="text-red-500"></iconify-icon>
            <span class="hidden sm:inline">Generate PDF</span>
        </button>

        <button onclick="sendReminder()"
            class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-4 py-1.5 text-xs font-semibold text-white transition-colors whitespace-nowrap">
            <iconify-icon icon="solar:bell-linear" width="14"></iconify-icon>
            <span>Send Reminder</span>
        </button>

        <div class="h-4 w-px bg-slate-200 dark:bg-slate-700 shrink-0"></div>

        <button onclick="clearSelection()"
            class="flex items-center justify-center h-7 w-7 rounded-full bg-slate-100 dark:bg-white/10 hover:bg-slate-200 dark:hover:bg-white/20 text-slate-500 dark:text-slate-400 transition-colors shrink-0"
            title="Clear selection">
            <iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon>
        </button>
    </div>
</div>

{{-- Finance Modals --}}
@include('admin.partials.finance-update-modal')
@include('admin.partials.finance-config-modal')

@endsection

@push('scripts')
<script>
let selectedIds = [];

// ── Checkbox logic ───────────────────────────────────────────
function syncBulkBar() {
    const bar   = document.getElementById('bulk-bar');
    const count = document.getElementById('bulk-count');
    if (count) count.textContent = selectedIds.length + ' Selected';
    if (bar)   bar.classList.toggle('hidden', selectedIds.length === 0);
}

document.getElementById('select-all')?.addEventListener('change', function () {
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.checked = this.checked;
        const id = parseInt(cb.value);
        if (this.checked) {
            if (!selectedIds.includes(id)) selectedIds.push(id);
        } else {
            selectedIds = selectedIds.filter(i => i !== id);
        }
    });
    syncBulkBar();
});

document.addEventListener('change', function (e) {
    if (!e.target.classList.contains('row-checkbox')) return;
    const id = parseInt(e.target.value);
    if (e.target.checked) {
        if (!selectedIds.includes(id)) selectedIds.push(id);
    } else {
        selectedIds = selectedIds.filter(i => i !== id);
        document.getElementById('select-all').checked = false;
    }
    syncBulkBar();
});

function clearSelection() {
    selectedIds = [];
    document.querySelectorAll('.row-checkbox, #select-all').forEach(cb => cb.checked = false);
    syncBulkBar();
}

// ── Update individual clearance status (AJAX) ────────────────
const financeBadgeClasses = {
    cleared: 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
    pending: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    overdue: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
};

function updateFinanceClearance(studentId, status) {
    fetch(`/admin/clearance/finance/${studentId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ status }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById(`fin-badge-${studentId}`);
            if (badge) {
                badge.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold ' + (financeBadgeClasses[status] || financeBadgeClasses.pending);
                badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            }
        } else {
            alert(data.message || 'Failed to update clearance status.');
        }
    })
    .catch(() => alert('Failed to update clearance status.'));
}

// ── Send finance reminder — opens configuration modal ─────────
function sendFinanceReminder(studentId, name) {
    if (typeof window.openFinanceReminder === 'function') {
        window.openFinanceReminder(studentId, name);
    }
}

// ── Toast ─────────────────────────────────────────────────────
function financeToast(msg, type='success') {
    const colors = { success:'bg-green-600', error:'bg-red-600', warning:'bg-amber-500' };
    const t = document.createElement('div');
    t.className = `fixed top-5 right-5 z-[9999] rounded-xl px-4 py-3 text-xs font-semibold shadow-lg text-white max-w-sm ${colors[type]||colors.success}`;
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(()=>t.remove(), 4000);
}

// Refresh a row after payment recorded (called by update modal)
window.onFinancePaymentSaved = function(finance, studentId) {
    location.reload(); // simple reload; can be optimised with AJAX row update later
};

// ── Bulk actions ─────────────────────────────────────────────
function exportExcel()  { financeToast('Export Excel — coming soon.', 'warning'); }
function generatePdf()  { financeToast('Generate PDF — coming soon.', 'warning'); }
function sendReminder() { financeToast('Use per-row Send Reminder in the Actions dropdown.', 'warning'); }
</script>
@endpush
