@extends('layouts.admin_layout')
@section('title', 'Withdrawn Students')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <x-admin.page-header
        title="Student Records"
        subtitle="Student Record and Information"
        school-year="2026–2027"
    />
    </div>

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:logout-3-bold" width="20" class="text-red-500"></iconify-icon>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Withdrawn Students</h2>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mb-4">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by
            </div>
            <form method="GET" action="{{ route('admin.student-records.withdrawn') }}">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                        <div class="relative">
                            <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="2026-2027" {{ request('school_year','2026-2027')==='2026-2027'?'selected':'' }}>SY 2026-2027</option>
                                <option value="2025-2026" {{ request('school_year')==='2025-2026'?'selected':'' }}>SY 2025-2026</option>
                                <option value="2024-2025" {{ request('school_year')==='2024-2025'?'selected':'' }}>SY 2024-2025</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade and Section</label>
                        <div class="relative">
                            <select name="grade_section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <optgroup label="Elementary">
                                    @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'] as $g)
                                    <option value="{{ $g }}" {{ request('grade_section')===$g?'selected':'' }}>{{ $g }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Junior High School">
                                    @foreach(['Grade 7','Grade 8','Grade 9','Grade 10'] as $g)
                                    <option value="{{ $g }}" {{ request('grade_section')===$g?'selected':'' }}>{{ $g }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Senior High School">
                                    <option value="Grade 11" {{ request('grade_section')==='Grade 11'?'selected':'' }}>Grade 11</option>
                                    <option value="Grade 12" {{ request('grade_section')==='Grade 12'?'selected':'' }}>Grade 12</option>
                                </optgroup>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                    </button>
                    <a href="{{ route('admin.student-records.withdrawn') }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">Clear All</a>
                </div>
            </form>
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
                <input type="text" placeholder="Search student.."
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:960px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade and Section</th>
                        <th class="px-4 py-3 whitespace-nowrap">Withdrawal Date</th>
                        <th class="px-4 py-3 whitespace-nowrap">Withdrawal Reason</th>
                        <th class="px-4 py-3 whitespace-nowrap">Clearance Status</th>
                        <th class="px-4 py-3 whitespace-nowrap">Student Status</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                @php
                    $rows = $students ?? collect([
                        (object)[
                            'id'=>1,'student_id'=>'2025-001','school_year'=>'2025-2026','full_name'=>'Jenny Orquiola',
                            'grade_level'=>'Grade 7','section_name'=>'A','withdrawn_at'=>now(),
                            'withdrawal_reason'=>'Transfer to another school',
                            'withdrawal_details'=>'Student transferred to Manila Science High School due to family relocation. All requirements and clearances have been completed.',
                            'clearance_status'=>'cleared','student_status'=>'withdrawn',
                            'guardian_email'=>'conrado@email.com','guardian_name'=>'Conrado Orquiola Jr.',
                            'finance_status'=>'cleared','library_status'=>'cleared','records_status'=>'cleared','academic_standing'=>'cleared',
                            'total_assessment'=>45000,'amount_paid'=>42000,'outstanding_balance'=>3000,
                            'refund_processed'=>true,'refund_amount'=>0,
                            'withdrawn_by_name'=>'Maria Santos (Registrar)','notification_sent'=>true,
                        ],
                        (object)[
                            'id'=>2,'student_id'=>'2025-002','school_year'=>'2025-2026','full_name'=>'Anna Cruz',
                            'grade_level'=>'Grade 7','section_name'=>'A','withdrawn_at'=>now(),
                            'withdrawal_reason'=>'Financial reasons',
                            'withdrawal_details'=>'Family unable to continue payment for the school year.',
                            'clearance_status'=>'pending','student_status'=>'withdrawn',
                            'guardian_email'=>'cruz@email.com','guardian_name'=>'Pedro Cruz',
                            'finance_status'=>'pending','library_status'=>'cleared','records_status'=>'cleared','academic_standing'=>'cleared',
                            'total_assessment'=>45000,'amount_paid'=>20000,'outstanding_balance'=>25000,
                            'refund_processed'=>false,'refund_amount'=>0,
                            'withdrawn_by_name'=>'Maria Santos (Registrar)','notification_sent'=>true,
                        ],
                    ]);
                    $clrClass = ['cleared'=>'bg-green-100 text-green-700','pending'=>'bg-yellow-100 text-yellow-700','overdue'=>'bg-red-100 text-red-700'];
                @endphp

                @forelse($rows as $row)
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <td class="px-4 py-3 text-xs font-mono text-slate-400 dark:text-slate-500">{{ $row->student_id }}</td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $row->school_year }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0d4c8f]/10 dark:bg-blue-900/20 text-[11px] font-bold text-[#0d4c8f] dark:text-blue-400">
                                {{ strtoupper(substr($row->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($row->last_name ?? 'S', 0, 1)) }}
                            </div>
                            <a href="{{ route('admin.student-records.profile', $row->id) }}"
                                class="text-sm font-medium text-slate-800 dark:text-slate-100 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors whitespace-nowrap">
                                {{ $row->formatted_name }}
                            </a>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                        {{ $row->section_display_name ?? $row->grade_level }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                        {{ isset($row->withdrawn_at) ? \Carbon\Carbon::parse($row->withdrawn_at)->format('n/j/y') : '—' }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400 max-w-[140px] truncate" title="{{ $row->withdrawal_reason ?? '' }}">
                        {{ $row->withdrawal_reason ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $clrClass[$row->clearance_status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($row->clearance_status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400">
                            Withdrawn
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="relative inline-block" x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 px-3 py-1 text-xs font-medium text-[#0d4c8f] transition-colors">
                                Select
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="12"
                                    :class="open ? 'rotate-180' : ''" class="transition-transform duration-200"></iconify-icon>
                            </button>
                            <div x-show="open" @click.outside="open = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 z-20 mt-1 w-40 rounded-xl border border-slate-200 bg-white dark:bg-dark-card shadow-lg py-1">

                                {{-- Send Notice --}}
                                <button type="button"
                                    onclick="openSendNotice({{ json_encode(['id'=>$row->id,'name'=>$row->full_name,'email'=>$row->guardian_email??'']) }})"
                                    @click="open=false"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:letter-bold" width="14" class="text-blue-500"></iconify-icon>
                                    Send Notice
                                </button>

                                {{-- Profile --}}
                                <a href="{{ route('admin.student-records.profile', $row->id) }}"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:user-bold" width="14" class="text-amber-500"></iconify-icon>
                                    Profile
                                </a>

                                {{-- View Details --}}
                                <button type="button"
                                    onclick="openViewDetails({{ json_encode([
                                        'id'                 => $row->id,
                                        'student_id'         => $row->student_id,
                                        'name'               => $row->full_name,
                                        'grade'              => $row->section_display_name ?? $row->grade_level,
                                        'school_year'        => $row->school_year,
                                        'withdrawn_at'       => isset($row->withdrawn_at) ? \Carbon\Carbon::parse($row->withdrawn_at)->format('F d, Y \a\t g:i A') : '—',
                                        'reason'             => $row->withdrawal_reason ?? '—',
                                        'details'            => $row->withdrawal_details ?? '—',
                                        'clearance'          => $row->clearance_status ?? 'pending',
                                        'finance'            => $row->finance_status ?? 'pending',
                                        'library'            => $row->library_status ?? 'pending',
                                        'records'            => $row->records_status ?? 'pending',
                                        'academic'           => $row->academic_standing ?? 'pending',
                                        'total_assessment'   => number_format($row->total_assessment ?? 0, 2),
                                        'amount_paid'        => number_format($row->amount_paid ?? 0, 2),
                                        'outstanding_balance'=> number_format($row->outstanding_balance ?? 0, 2),
                                        'refund_processed'   => ($row->refund_processed ?? false) ? 'Yes' : 'No',
                                        'refund_amount'      => number_format($row->refund_amount ?? 0, 2),
                                        'withdrawn_by'       => $row->withdrawn_by_name ?? '—',
                                        'notification_sent'  => ($row->notification_sent ?? false) ? 'Yes, to ' . ($row->guardian_email ?? '—') : 'No',
                                    ]) }})"
                                    @click="open=false"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:document-text-bold" width="14" class="text-green-500"></iconify-icon>
                                    View Details
                                </button>

                                {{-- Print PDF --}}
                                <button type="button" @click="open=false"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:printer-bold" width="14" class="text-slate-500"></iconify-icon>
                                    Print PDF
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <iconify-icon icon="solar:logout-3-linear" width="32" class="text-slate-300"></iconify-icon>
                            <p class="text-sm font-medium text-slate-500">No withdrawn students found.</p>
                            <p class="text-xs text-slate-400">Withdrawn students will appear here after withdrawal is processed.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-end px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1">
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon>
                </button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">1</button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 text-xs">2</button>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon>
                </button>
            </div>
        </div>

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ═══ VIEW DETAILS MODAL ═══ --}}
<div id="view-details-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeWdModal('view-details-modal')"></div>
    <div class="relative w-full max-w-xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" style="max-height:90vh;overflow-y:auto">

        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 id="wd-modal-title" class="text-white text-sm font-bold">WITHDRAWAL DETAILS</h3>
            <button onclick="closeWdModal('view-details-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        <div class="p-6 space-y-5">

            {{-- Student Information --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5 border-b border-slate-200 dark:border-dark-border">
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide text-center">Student Information</p>
                </div>
                <div class="px-4 py-4 grid grid-cols-2 gap-4 text-xs">
                    <div><p class="text-slate-400 mb-0.5">Student ID</p><p id="wd-sid" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                    <div><p class="text-slate-400 mb-0.5">Student Name</p><p id="wd-name" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                    <div><p class="text-slate-400 mb-0.5">Grade & Section</p><p id="wd-grade" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                    <div><p class="text-slate-400 mb-0.5">School Year</p><p id="wd-sy" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                </div>
            </div>

            {{-- Withdrawal Details --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5 border-b border-slate-200 dark:border-dark-border">
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide text-center">Withdrawal Details</p>
                </div>
                <div class="px-4 py-4 space-y-3 text-xs">
                    <div class="flex gap-3"><span class="text-slate-400 w-36 shrink-0">Withdrawal Date:</span><span id="wd-date" class="font-medium text-slate-700 dark:text-slate-300">—</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-36 shrink-0">Withdrawal Reason:</span><span id="wd-reason" class="font-medium text-slate-700 dark:text-slate-300">—</span></div>
                    <div>
                        <p class="text-slate-400 mb-1.5">Withdrawal Details:</p>
                        <div id="wd-details-text" class="rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-dark-border px-3 py-2.5 text-slate-600 dark:text-slate-300 leading-relaxed min-h-[60px]">—</div>
                    </div>
                </div>
            </div>

            {{-- Clearance Status --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5 border-b border-slate-200 dark:border-dark-border">
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide text-center">Clearance Status</p>
                </div>
                <div class="px-4 py-4 space-y-3">
                    <div class="grid grid-cols-4 gap-2">
                        @foreach(['finance'=>'Finance','library'=>'Library','records'=>'Records','academic'=>'Academic'] as $key => $label)
                        <div class="flex flex-col items-center gap-1.5 rounded-xl border border-slate-200 dark:border-dark-border px-2 py-3 text-center">
                            <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">{{ $label }}</p>
                            <div id="clr-{{ $key }}" class="flex items-center gap-1 text-xs font-semibold text-slate-400">
                                <span class="w-2 h-2 rounded-full bg-slate-300 shrink-0"></span> —
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-2 pt-1 border-t border-slate-100 dark:border-dark-border">
                        <span class="text-xs font-semibold text-slate-500">Overall Status:</span>
                        <span id="clr-overall" class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold bg-slate-100 text-slate-500">
                            <span class="w-2 h-2 rounded-full bg-slate-300 shrink-0"></span> —
                        </span>
                    </div>
                </div>
            </div>

            {{-- Financial Summary --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5 border-b border-slate-200 dark:border-dark-border">
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide text-center">Financial Summary</p>
                </div>
                <div class="px-4 py-4 space-y-2 text-xs">
                    <div class="flex justify-between"><span class="text-slate-400">Total Assessment:</span><span id="fin-total" class="font-semibold text-slate-700 dark:text-slate-300">—</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Amount Paid:</span><span id="fin-paid" class="font-semibold text-slate-700 dark:text-slate-300">—</span></div>
                    <div class="flex justify-between pt-1.5 border-t border-slate-100 dark:border-dark-border">
                        <span class="text-slate-400">Outstanding Balance:</span>
                        <span id="fin-balance" class="font-bold text-red-600 dark:text-red-400">—</span>
                    </div>
                    <div class="flex justify-between pt-1"><span class="text-slate-400">Refund Processed:</span><span id="fin-refund" class="font-semibold text-slate-700 dark:text-slate-300">—</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Refund Amount:</span><span id="fin-refund-amount" class="font-semibold text-slate-700 dark:text-slate-300">—</span></div>
                </div>
            </div>

            {{-- Audit Information --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                <div class="bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5 border-b border-slate-200 dark:border-dark-border">
                    <p class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide text-center">Audit Information</p>
                </div>
                <div class="px-4 py-4 space-y-2 text-xs">
                    <div class="flex gap-3"><span class="text-slate-400 w-36 shrink-0">Withdrawn By:</span><span id="audit-by" class="font-medium text-slate-700 dark:text-slate-300">—</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-36 shrink-0">Withdrawn At:</span><span id="audit-at" class="font-medium text-slate-700 dark:text-slate-300">—</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-36 shrink-0">Notification Sent:</span><span id="audit-notif" class="font-medium text-slate-700 dark:text-slate-300">—</span></div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="window.print()"
                    class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:printer-bold" width="14"></iconify-icon> PRINT DETAILS
                </button>
                <button type="button" onclick="closeWdModal('view-details-modal')"
                    class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    CLOSE
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══ SEND NOTICE MODAL ═══ --}}
<div id="send-notice-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeWdModal('send-notice-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-white text-sm font-bold">SEND NOTICE</h3>
            <button onclick="closeWdModal('send-notice-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-4 py-3 text-xs">
                <p class="text-slate-400 mb-0.5">Sending notice to:</p>
                <p id="sn-student-name" class="font-semibold text-slate-700 dark:text-slate-300">—</p>
                <p id="sn-student-email" class="text-slate-400 mt-0.5">—</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Notice Type: <span class="text-red-500">*</span></label>
                <div class="space-y-2 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                    @foreach(['general'=>'General Notice','clearance'=>'Clearance Reminder','financial'=>'Financial Notice','other'=>'Other'] as $v => $l)
                    <label class="flex items-center gap-2.5 cursor-pointer text-xs text-slate-600 dark:text-slate-300">
                        <input type="radio" name="wd_notice_type" value="{{ $v }}" class="text-blue-600 focus:ring-blue-500 cursor-pointer"> {{ $l }}
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Subject: <span class="text-red-500">*</span></label>
                <input type="text" id="sn-subject" placeholder="e.g., Clearance Reminder"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Message: <span class="text-red-500">*</span></label>
                <textarea id="sn-message" rows="5" placeholder="Dear Parent/Guardian,&#10;&#10;..."
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmSendNotice()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:letter-bold" width="14"></iconify-icon> SEND NOTICE
                </button>
                <button type="button" onclick="closeWdModal('send-notice-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function closeWdModal(id) { document.getElementById(id)?.classList.add('hidden');    document.body.style.overflow = ''; }
function openWdModal(id)  { document.getElementById(id)?.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = 'fixed top-6 right-6 z-[100] flex items-center gap-2 rounded-xl border px-4 py-3 text-sm shadow-lg '
        + (type === 'success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700');
    t.innerHTML = `<iconify-icon icon="solar:${type==='success'?'check-circle-bold':'close-circle-bold'}" width="16"></iconify-icon> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3500);
}

// ── View Details ─────────────────────────────────
function openViewDetails(data) {
    document.getElementById('wd-modal-title').textContent = 'WITHDRAWAL DETAILS — ' + data.name;

    // Student info
    document.getElementById('wd-sid').textContent   = data.student_id;
    document.getElementById('wd-name').textContent  = data.name;
    document.getElementById('wd-grade').textContent = data.grade;
    document.getElementById('wd-sy').textContent    = 'SY ' + data.school_year;

    // Withdrawal
    document.getElementById('wd-date').textContent         = data.withdrawn_at;
    document.getElementById('wd-reason').textContent       = data.reason;
    document.getElementById('wd-details-text').textContent = data.details;

    // Clearance components
    ['finance','library','records','academic'].forEach(key => {
        const el      = document.getElementById('clr-' + key);
        const val     = data[key] ?? 'pending';
        const cleared = val === 'cleared';
        el.innerHTML  = `<span class="w-2 h-2 rounded-full shrink-0 ${cleared ? 'bg-green-500' : 'bg-yellow-400'}"></span>
            <span class="${cleared ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400'}">${cleared ? 'Cleared' : 'Pending'}</span>`;
    });

    // Overall
    const cleared   = (data.clearance ?? 'pending') === 'cleared';
    const overallEl = document.getElementById('clr-overall');
    overallEl.className = 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold '
        + (cleared ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700');
    overallEl.innerHTML = `<span class="w-2 h-2 rounded-full shrink-0 ${cleared ? 'bg-green-500' : 'bg-yellow-400'}"></span> ${cleared ? 'CLEARED' : 'PENDING'}`;

    // Financial
    document.getElementById('fin-total').textContent         = '₱ ' + data.total_assessment;
    document.getElementById('fin-paid').textContent          = '₱ ' + data.amount_paid;
    document.getElementById('fin-balance').textContent       = '₱ ' + data.outstanding_balance;
    document.getElementById('fin-refund').textContent        = data.refund_processed;
    document.getElementById('fin-refund-amount').textContent = '₱ ' + data.refund_amount;

    // Audit
    document.getElementById('audit-by').textContent    = data.withdrawn_by;
    document.getElementById('audit-at').textContent    = data.withdrawn_at;
    document.getElementById('audit-notif').textContent = data.notification_sent;

    openWdModal('view-details-modal');
}

// ── Send Notice ──────────────────────────────────
let _snId = null;
function openSendNotice(data) {
    _snId = data.id;
    document.getElementById('sn-student-name').textContent  = data.name;
    document.getElementById('sn-student-email').textContent = data.email || '— no email on file —';
    document.getElementById('sn-subject').value  = '';
    document.getElementById('sn-message').value  = '';
    document.querySelectorAll('input[name="wd_notice_type"]').forEach(r => r.checked = false);
    openWdModal('send-notice-modal');
}

function confirmSendNotice() {
    const type    = document.querySelector('input[name="wd_notice_type"]:checked')?.value;
    const subject = document.getElementById('sn-subject').value.trim();
    const message = document.getElementById('sn-message').value.trim();
    if (!type)    { showToast('Please select a notice type.', 'error'); return; }
    if (!subject) { showToast('Please enter a subject.', 'error'); return; }
    if (!message) { showToast('Please enter a message.', 'error'); return; }

    fetch('{{ route("admin.student-records.send-notice") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ student_ids: [_snId], notice_type: type, subject, message, send_to: ['parent'] }),
    })
    .then(r => r.json())
    .then(data => {
        closeWdModal('send-notice-modal');
        showToast(data.message || 'Notice sent.', data.success ? 'success' : 'error');
    })
    .catch(() => showToast('Request failed.', 'error'));
}
</script>
@endpush
@endsection