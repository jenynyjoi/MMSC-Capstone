<!-- resource/views/admin/admission.blade.php -->
@extends('layouts.admin_layout')
@section('title', 'Admission')
@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif
    @if(session('warning'))
    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 flex items-center gap-2">
        <iconify-icon icon="solar:danger-triangle-bold" width="16" class="text-amber-600"></iconify-icon>
        {{ session('warning') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Page Header --}}
    
    

    <x-admin.page-header
        title="Admission"
        subtitle="Screening and Approval"
        school-year="{{ $activeSchoolYear }}"
    />

    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:clipboard-list-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-sm font-semibold text-slate-800 dark:text-white">Applicant</h2>
            </div>
            <a href="{{ route('online.registration') }}" target="_blank"
               class="flex items-center gap-2 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 px-4 py-2 text-xs font-semibold text-[#0d4c8f] transition-colors shadow-sm">
                <iconify-icon icon="solar:add-circle-linear" width="16"></iconify-icon>
                Add Applicant
            </a>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 dark:border-blue-900/30 dark:bg-blue-900/10 px-4 py-3 cursor-pointer hover:shadow-sm transition-shadow" onclick="window.location='{{ route('admin.admission', ['status' => 'approved']) }}'">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30"><iconify-icon icon="solar:user-check-bold" width="18" class="text-blue-600 dark:text-blue-400"></iconify-icon></div>
                <div><p class="text-xl font-semibold text-blue-800 dark:text-blue-200 leading-none">{{ $stats['approved'] }}</p><p class="text-xs text-blue-600 dark:text-blue-400 mt-1 leading-tight">Successfully Admitted</p></div>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10 px-4 py-3 cursor-pointer hover:shadow-sm transition-shadow" onclick="window.location='{{ route('admin.admission', ['status' => 'pending']) }}'">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30"><iconify-icon icon="solar:clock-circle-bold" width="18" class="text-amber-600 dark:text-amber-400"></iconify-icon></div>
                <div><p class="text-xl font-semibold text-amber-800 dark:text-amber-200 leading-none">{{ $stats['pending'] }}</p><p class="text-xs text-amber-600 dark:text-amber-400 mt-1 leading-tight">Pending Application</p></div>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-orange-200 bg-orange-50 dark:border-orange-900/30 dark:bg-orange-900/10 px-4 py-3 cursor-pointer hover:shadow-sm transition-shadow" onclick="window.location='{{ route('admin.admission', ['status' => 'incomplete']) }}'">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/30"><iconify-icon icon="solar:file-corrupted-bold" width="18" class="text-orange-600 dark:text-orange-400"></iconify-icon></div>
                <div><p class="text-xl font-semibold text-orange-800 dark:text-orange-200 leading-none">{{ $stats['incomplete'] }}</p><p class="text-xs text-orange-600 dark:text-orange-400 mt-1 leading-tight">Incomplete</p></div>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 dark:border-red-900/30 dark:bg-red-900/10 px-4 py-3 cursor-pointer hover:shadow-sm transition-shadow" onclick="window.location='{{ route('admin.admission', ['status' => 'rejected']) }}'">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/30"><iconify-icon icon="solar:trash-bin-trash-bold" width="18" class="text-red-600 dark:text-red-400"></iconify-icon></div>
                <div><p class="text-xl font-semibold text-red-800 dark:text-red-200 leading-none">{{ $stats['rejected'] }}</p><p class="text-xs text-red-600 dark:text-red-400 mt-1 leading-tight">Rejected</p></div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1 text-xs text-slate-500 font-medium mb-3"><iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by</div>
            <form method="GET" action="{{ route('admin.admission') }}">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-3">
                    @foreach([
                        ['school_year','School Year',        [['2025-2026','SY 2025-2026'],['2024-2025','SY 2024-2025']]],
                        ['status',     'Application Status', [['','All'],['pending','Pending'],['approved','Approved'],['incomplete','Incomplete'],['rejected','Rejected']]],
                        ['level',      'Grade Level Applied',[['','All'],['Elementary','Elementary'],['Junior High School','Junior High School'],['Senior High School','Senior High School']]],
                        ['app_type',   'Application Type',   [['','All'],['New','New'],['Return','Return'],['Transferee','Transferee']]],
                    ] as [$name,$labelText,$options])
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">{{ $labelText }}</label>
                        <div class="relative">
                            <select name="{{ $name }}" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                @foreach($options as [$val,$display])
                                <option value="{{ $val }}" {{ request($name, $name==='school_year'?'2025-2026':'') === $val ? 'selected' : '' }}>{{ $display }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors"><iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply</button>
                    <a href="{{ route('admin.admission') }}" class="rounded-lg border border-slate-200 px-5 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Clear All</a>
                </div>
            </form>
        </div>

        {{-- Table Controls --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span>Show</span>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none"><option>10</option><option>25</option><option>50</option></select>
                <span>entries</span>
            </div>
            <form method="GET" action="{{ route('admin.admission') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="level"  value="{{ request('level') }}">
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Student, Application no.."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-60">
                </div>
            </form>
        </div>

        {{-- TABLE — zero nested forms; row actions use rowAction() fetch --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:960px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3 w-10"><input type="checkbox" id="check-all" class="rounded text-blue-600 focus:ring-blue-500"></th>
                        <th class="px-4 py-3">Applicant ID</th>
                        <th class="px-4 py-3">Applicant Name</th>
                        <th class="px-4 py-3">School Year</th>
                        <th class="px-4 py-3">Level Applied For</th>
                        <th class="px-4 py-3">Grade / Program</th>
                        <th class="px-4 py-3">Application Type</th>
                        <th class="px-4 py-3">Date Applied</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @forelse ($applications as $app)
                    @php
                        $sBadge = ['pending'=>'bg-amber-100 text-amber-700','incomplete'=>'bg-orange-100 text-orange-700','pre_approved'=>'bg-blue-100 text-blue-700','approved'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700'][$app->application_status] ?? 'bg-slate-100 text-slate-600';
                        $appType = $app->is_transferee ? 'Transfer' : ($app->student_status === 'Old' ? 'Return' : 'New');
                        $isApproved = $app->application_status === 'approved';
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors" id="row-{{ $app->id }}"
                        data-status="{{ $app->application_status }}"
                        data-name="{{ $app->first_name }} {{ $app->last_name }}">
                        <td class="px-4 py-3"><input type="checkbox" value="{{ $app->id }}" class="row-check rounded text-blue-600 focus:ring-blue-500"></td>
                        <td class="px-4 py-3 text-xs font-mono text-slate-400 truncate">{{ $app->reference_number }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $app->first_name }} {{ $app->last_name }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $app->school_year }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 truncate">{{ $app->applied_level }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500 truncate">{{ $app->incoming_grade_level }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $appType }}</td>
                        <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">{{ $app->submitted_at?->format('n/j/y') }}</td>
                        <td class="px-4 py-3">
                            <span class="row-badge inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $sBadge }}">
                                {{ $app->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open" type="button"
                                    class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 px-3 py-1 text-xs font-medium text-[#0d4c8f] transition-colors">
                                    Actions
                                    <iconify-icon icon="solar:alt-arrow-down-linear" width="12" :class="open?'rotate-180':''" class="transition-transform duration-200"></iconify-icon>
                                </button>
                                <div x-show="open" @click.outside="open = false"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute right-0 z-20 mt-1 w-48 rounded-xl border border-slate-200 bg-white dark:bg-dark-card shadow-lg py-1">

                                    {{-- View Application --}}
                                    <a href="{{ route('admin.admission.show', $app->id) }}"
                                       class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 hover:bg-slate-50 transition-colors">
                                        <iconify-icon icon="solar:eye-linear" width="14" class="text-amber-500"></iconify-icon>
                                        View Application
                                    </a>

                                    {{-- Download PDF --}}
                                    <a href="{{ route('admin.admission.pdf', $app->id) }}"
                                       class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 hover:bg-slate-50 transition-colors">
                                        <iconify-icon icon="solar:file-download-linear" width="14" class="text-blue-500"></iconify-icon>
                                        Download PDF
                                    </a>

                                    <div class="border-t border-slate-100 my-1"></div>

                                    {{-- Approve (hidden if already approved) --}}
                                    @if(!$isApproved)
                                    <button type="button" id="approve-btn-{{ $app->id }}"
                                        @click="open=false; openFinanceModal({
                                            referenceNumber: '{{ addslashes($app->reference_number) }}',
                                            studentId: null,
                                            gradeLevel: '{{ addslashes($app->incoming_grade_level ?? $app->applied_level) }}',
                                            studentCategory: '{{ addslashes($app->student_category ?? 'Regular') }}',
                                            schoolYear: '{{ $app->school_year }}',
                                            studentName: '{{ addslashes($app->first_name.' '.$app->last_name) }}',
                                            onSaved: function() {
                                                rowAction({{ $app->id }},'approved','{{ addslashes($app->first_name.' '.$app->last_name) }}','{{ $app->application_status }}');
                                            }
                                        })"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-green-600 hover:bg-green-50 transition-colors">
                                        <iconify-icon icon="solar:check-circle-linear" width="14"></iconify-icon>
                                        Approve Application
                                    </button>
                                    @else
                                    <div id="approve-btn-{{ $app->id }}" class="flex items-center gap-2 px-3 py-2 text-xs text-green-600 opacity-60 cursor-not-allowed">
                                        <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                                        Already Approved
                                    </div>
                                    @endif

                                    {{-- Mark Incomplete --}}
                                    <button type="button" @click="open=false; rowAction({{ $app->id }},'incomplete','{{ addslashes($app->first_name.' '.$app->last_name) }}','{{ $app->application_status }}')"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-orange-500 hover:bg-orange-50 transition-colors">
                                        <iconify-icon icon="solar:danger-triangle-linear" width="14"></iconify-icon>
                                        Mark Incomplete
                                        @if($isApproved) <span class="ml-auto text-[10px] bg-amber-100 text-amber-700 px-1.5 rounded-full">⚠</span> @endif
                                    </button>

                                    {{-- Reject --}}
                                    <button type="button" @click="open=false; rowAction({{ $app->id }},'rejected','{{ addslashes($app->first_name.' '.$app->last_name) }}','{{ $app->application_status }}')"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 transition-colors">
                                        <iconify-icon icon="solar:close-circle-linear" width="14"></iconify-icon>
                                        Reject Application
                                        @if($isApproved) <span class="ml-auto text-[10px] bg-amber-100 text-amber-700 px-1.5 rounded-full">⚠</span> @endif
                                    </button>

                                    <div class="border-t border-slate-100 my-1"></div>

                                    {{-- Send Notice --}}
                                    <button type="button" @click="open=false; openNoticeModal([{{ $app->id }}])"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-[#0d4c8f] hover:bg-blue-50 transition-colors">
                                        <iconify-icon icon="solar:letter-linear" width="14"></iconify-icon>
                                        Send Notice
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-14 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <iconify-icon icon="solar:inbox-linear" width="32" class="text-slate-300"></iconify-icon>
                                <p class="text-xs text-slate-400">No applications found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-end px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            {{ $applications->links() }}
        </div>
    </div>

    {{-- Hidden forms (outside table) --}}
    <form id="bulk-form" method="POST" action="{{ route('admin.admission.bulk-status') }}" class="hidden">
        @csrf
        <input type="hidden" name="status" id="bulk-status-value">
        <input type="hidden" name="action" id="bulk-action-value">
    </form>
    <form id="export-form" method="POST" action="{{ route('admin.admission.export') }}" class="hidden">
        @csrf
    </form>

    {{-- Bulk Action Bar --}}
    <div id="bulk-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-30 hidden">
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white dark:bg-dark-card shadow-2xl px-5 py-3">
            <iconify-icon icon="solar:users-group-rounded-linear" width="18" class="text-slate-400"></iconify-icon>
            <span id="bulk-count" class="text-xs font-medium text-slate-600 dark:text-slate-300 whitespace-nowrap">0 Selected</span>
            <div class="h-4 w-px bg-slate-200 dark:bg-slate-700"></div>
            <button type="button" onclick="submitBulk('approved','approve')" class="flex items-center gap-1.5 rounded-lg bg-green-600 hover:bg-green-700 px-4 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap"><iconify-icon icon="solar:check-circle-linear" width="14"></iconify-icon> Approve</button>
            <button type="button" onclick="submitBulk('incomplete','mark_incomplete')" class="flex items-center gap-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 px-4 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap"><iconify-icon icon="solar:danger-triangle-linear" width="14"></iconify-icon> Mark Incomplete</button>
            <button type="button" onclick="submitBulk('rejected','reject')" class="flex items-center gap-1.5 rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap"><iconify-icon icon="solar:close-circle-linear" width="14"></iconify-icon> Reject</button>
            <button type="button" onclick="submitExport()" class="flex items-center gap-1.5 rounded-lg border border-emerald-400 bg-white hover:bg-emerald-50 px-4 py-2 text-xs font-semibold text-emerald-700 transition-colors whitespace-nowrap"><iconify-icon icon="solar:file-download-linear" width="14"></iconify-icon> Export Excel</button>
            <button type="button" onclick="openBulkNotice()" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap"><iconify-icon icon="solar:letter-linear" width="14"></iconify-icon> Send Notice</button>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         APPROVAL LOCK WARNING MODAL
    ════════════════════════════════════════ --}}
    <div id="lock-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
            {{-- Header --}}
            <div class="bg-amber-500 px-6 py-4 flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-600">
                    <iconify-icon icon="solar:lock-bold" width="18" class="text-white"></iconify-icon>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Approval Lock Warning</h3>
                    <p class="text-xs text-amber-100 mt-0.5">This application is already approved</p>
                </div>
            </div>
            {{-- Body --}}
            <div class="px-6 py-5">
                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 mb-4">
                    <p class="text-xs font-bold text-amber-800 mb-1">⚠ Approved Status is Final</p>
                    <p class="text-xs text-amber-700 leading-relaxed">
                        This application has already been <strong>approved</strong> and the student's enrollment record is active in the system.
                    </p>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-3">
                    Changing the status to <strong id="lock-new-status-label" class="text-red-600"></strong> will only flag this <em>application record</em> for tracking purposes.
                </p>
                <ul class="text-xs text-slate-500 space-y-1.5 mb-5">
                    <li class="flex items-start gap-2"><iconify-icon icon="solar:check-circle-bold" class="text-green-500 mt-0.5 flex-shrink-0" width="13"></iconify-icon> The student's approved enrollment remains <strong>active</strong></li>
                    <li class="flex items-start gap-2"><iconify-icon icon="solar:check-circle-bold" class="text-green-500 mt-0.5 flex-shrink-0" width="13"></iconify-icon> Portal access credentials are NOT revoked</li>
                    <li class="flex items-start gap-2"><iconify-icon icon="solar:info-circle-bold" class="text-blue-500 mt-0.5 flex-shrink-0" width="13"></iconify-icon> This action will be logged in the audit trail</li>
                </ul>
                <p class="text-xs text-slate-500 mb-5">Student: <strong id="lock-student-name" class="text-slate-700 dark:text-slate-300"></strong></p>
                <div class="flex gap-2">
                    <button type="button" onclick="closeLockModal()"
                        class="flex-1 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Cancel — Keep Approved
                    </button>
                    <button type="button" onclick="confirmLockOverride()"
                        class="flex-1 rounded-xl bg-amber-500 hover:bg-amber-600 px-4 py-2.5 text-xs font-semibold text-white transition-colors">
                        Proceed Anyway
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Notice Modal --}}
    <div id="notice-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeNoticeModal()"></div>
        <div class="relative w-full max-w-lg mx-4 rounded-2xl overflow-hidden shadow-2xl">
            <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-white text-sm font-bold">SEND EMAIL NOTICE</h3>
                    <p id="notice-selected-label" class="text-blue-200 text-xs mt-0.5">Selected: 0 Students</p>
                </div>
                <button onclick="closeNoticeModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors">✕</button>
            </div>
            <form id="notice-form" method="POST" action="{{ route('admin.admission.send-notice') }}" class="bg-white dark:bg-dark-card px-6 py-5 space-y-4">
                @csrf
                <div id="notice-ids-container"></div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Subject:</label>
                    <input type="text" name="subject" id="notice-subject" value="Application Status Update - My Messiah School of Cavite"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Reason/Message Type:</label>
                    <div class="rounded-lg border border-slate-200 dark:border-dark-border p-3 space-y-2">
                        @foreach(['Application Approved','Application Rejected','Missing Requirements','Pending Payment','Document Verification Needed','Interview Schedule','Entrance Exam Schedule','Custom Message'] as $type)
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="message_type" value="{{ $type }}" class="text-blue-600 focus:ring-blue-500" {{ $loop->first ? 'checked' : '' }}>
                            <span class="text-xs text-slate-600 dark:text-slate-300">{{ $type }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Reason Details <span class="font-normal normal-case text-slate-400">(optional)</span>:</label>
                    <textarea name="details" rows="4" class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="send_copy" value="1" class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="text-xs text-slate-600 dark:text-slate-300">Send copy to my email</span>
                </label>
                <div class="flex items-center justify-between pt-2">
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors uppercase tracking-wide">Send Notice</button>
                    <button type="button" onclick="closeNoticeModal()" class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors uppercase tracking-wide">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- Finance Modals --}}
@include('admin.partials.finance-update-modal')
@include('admin.partials.finance-config-modal')

@push('scripts')
<script>
const CSRF = '{{ csrf_token() }}';
let selectedIds = [];

// ── Checkboxes ─────────────────────────────────────────────
document.getElementById('check-all')?.addEventListener('change', function () {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
    updateBulkBar();
});
document.querySelectorAll('.row-check').forEach(cb => cb.addEventListener('change', updateBulkBar));

function updateBulkBar() {
    selectedIds = [...document.querySelectorAll('.row-check:checked')].map(cb => cb.value);
    document.getElementById('bulk-count').textContent = selectedIds.length + ' Selected';
    document.getElementById('bulk-bar').classList.toggle('hidden', selectedIds.length === 0);
}

// ══════════════════════════════════════════════════════════
// ROW-LEVEL ACTION — fetch PUT with approval lock support
// ══════════════════════════════════════════════════════════
const badgeMap = {
    approved:   { text: 'Approved',   cls: 'bg-green-100 text-green-700'  },
    incomplete: { text: 'Incomplete', cls: 'bg-orange-100 text-orange-700' },
    rejected:   { text: 'Rejected',   cls: 'bg-red-100 text-red-700'      },
    pending:    { text: 'Pending',    cls: 'bg-amber-100 text-amber-700'  },
};

// State for lock-modal confirm
let _lockPending = null;

function rowAction(id, status, name, currentStatus, lockConfirmed = false) {
    fetch(`/admin/admission/${id}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status, lock_confirmed: lockConfirmed }),
    })
    .then(r => r.json())
    .then(data => {
        // ── Approval lock: server requires confirmation ──
        if (data.lock_warning) {
            openLockModal(id, status, name, currentStatus);
            return;
        }

        if (data.success) {
            // Update badge in the row
            const badge = document.querySelector(`#row-${id} .row-badge`);
            if (badge && badgeMap[status]) {
                badge.className = 'row-badge inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ' + badgeMap[status].cls;
                badge.textContent = badgeMap[status].text;
            }
            // Update row's data-status for next action
            const row = document.getElementById('row-' + id);
            if (row) row.dataset.status = status;

            // Sync the dropdown approve button when approved
            if (status === 'approved') {
                const approveBtn = document.getElementById(`approve-btn-${id}`);
                if (approveBtn) {
                    approveBtn.outerHTML = `<div id="approve-btn-${id}" class="flex items-center gap-2 px-3 py-2 text-xs text-green-600 opacity-60 cursor-not-allowed">
                        <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                        Already Approved
                    </div>`;
                }
            }

            const toastType = data.pipeline_error ? 'warning' : (data.is_downgrade ? 'warning' : 'success');
            toast(data.message ?? 'Status updated.', toastType);
        } else {
            toast(data.message ?? 'Something went wrong.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        toast('Request failed. Please try again.', 'error');
    });
}

// ══════════════════════════════════════════════════════════
// APPROVAL LOCK MODAL
// ══════════════════════════════════════════════════════════
function openLockModal(id, status, name, currentStatus) {
    _lockPending = { id, status, name, currentStatus };
    document.getElementById('lock-student-name').textContent = name;
    document.getElementById('lock-new-status-label').textContent =
        status === 'rejected' ? 'Rejected' : 'Incomplete';
    document.getElementById('lock-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLockModal() {
    document.getElementById('lock-modal').classList.add('hidden');
    document.body.style.overflow = '';
    _lockPending = null;
}

function confirmLockOverride() {
    if (!_lockPending) return;
    const { id, status, name, currentStatus } = _lockPending;
    closeLockModal();
    // Re-call with lock_confirmed = true
    rowAction(id, status, name, currentStatus, true);
}

// ── Bulk — POST form ───────────────────────────────────────
function submitBulk(status, action) {
    if (!selectedIds.length) return;
    const f = document.getElementById('bulk-form');
    document.getElementById('bulk-status-value').value = status;
    document.getElementById('bulk-action-value').value  = action;
    f.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
    selectedIds.forEach(id => {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = 'ids[]'; i.value = id;
        f.appendChild(i);
    });
    f.submit();
}

// ── Export xlsx ────────────────────────────────────────────
function submitExport() {
    if (!selectedIds.length) return;
    const f = document.getElementById('export-form');
    f.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
    selectedIds.forEach(id => {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = 'ids[]'; i.value = id;
        f.appendChild(i);
    });
    f.submit();
}

// ── Notice Modal ───────────────────────────────────────────
function openNoticeModal(ids) {
    selectedIds = ids.map(String);
    document.getElementById('notice-selected-label').textContent =
        'Selected: ' + ids.length + ' Student' + (ids.length !== 1 ? 's' : '');
    const c = document.getElementById('notice-ids-container');
    c.innerHTML = '';
    ids.forEach(id => {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = 'ids[]'; i.value = id;
        c.appendChild(i);
    });
    document.getElementById('notice-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function openBulkNotice() { if (selectedIds.length) openNoticeModal(selectedIds); }
function closeNoticeModal() {
    document.getElementById('notice-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// ── Toast ──────────────────────────────────────────────────
function toast(msg, type) {
    const colors = {
        success: 'bg-green-600 text-white',
        warning: 'bg-amber-500 text-white',
        error:   'bg-red-600 text-white',
    };
    const t = document.createElement('div');
    t.className = 'fixed top-5 right-5 z-[999] rounded-xl px-4 py-3 text-xs font-semibold shadow-lg max-w-sm ' + (colors[type] ?? colors.success);
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 4000);
}

// Global exposure for Alpine
window.rowAction       = rowAction;
window.openNoticeModal = openNoticeModal;
window.openBulkNotice  = openBulkNotice;
window.closeNoticeModal = closeNoticeModal;
window.submitBulk      = submitBulk;
window.submitExport    = submitExport;
window.closeLockModal  = closeLockModal;
window.confirmLockOverride = confirmLockOverride;
</script>
@endpush
@endsection