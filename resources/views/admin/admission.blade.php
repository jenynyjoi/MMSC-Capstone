@extends('layouts.admin_layout')

@section('title', 'Admission')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4"
     x-data="{
        noticeModal: false,
        selectedIds: [],
        noticeSubject: 'Application Status Update - My Messiah School of Cavite',
        noticeType: '',
        noticeDetails: '',
        noticeSendCopy: false,
     }">

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
    @endif

    {{-- ── Page Header ── --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Admission</h1>
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">Screening and Approval</p>
        </div>
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">Current school year:</span>
            <div class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 shadow-sm">
                <span class="text-sm font-semibold text-slate-700 dark:text-white">SY 2025–2026</span>
                <button class="text-slate-400 hover:text-slate-600 transition-colors">
                    <iconify-icon icon="solar:menu-dots-bold" width="14"></iconify-icon>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- ── Card Header ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:clipboard-list-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-sm font-semibold text-slate-800 dark:text-white">Applicant</h2>
            </div>
            <a href="{{ route('online.registration') }}" target="_blank"
               class="flex items-center gap-2 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 px-4 py-2 text-xs font-semibold text-[#0d4c8f] transition-colors shadow-sm">
                <iconify-icon icon="solar:add-circle-linear" width="16"></iconify-icon>
                + Add Applicant
            </a>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 dark:border-blue-900/30 dark:bg-blue-900/10 px-4 py-3 cursor-pointer hover:shadow-sm transition-shadow"
                 onclick="window.location='{{ route('admin.admission', ['status' => 'approved']) }}'">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <iconify-icon icon="solar:user-check-bold" width="18" class="text-blue-600 dark:text-blue-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-semibold text-blue-800 dark:text-blue-200 leading-none">{{ $stats['approved'] }}</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1 leading-tight">Successfully Admitted</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-900/30 dark:bg-amber-900/10 px-4 py-3 cursor-pointer hover:shadow-sm transition-shadow"
                 onclick="window.location='{{ route('admin.admission', ['status' => 'pending']) }}'">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/30">
                    <iconify-icon icon="solar:clock-circle-bold" width="18" class="text-amber-600 dark:text-amber-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-semibold text-amber-800 dark:text-amber-200 leading-none">{{ $stats['pending'] }}</p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1 leading-tight">Pending Application</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-orange-200 bg-orange-50 dark:border-orange-900/30 dark:bg-orange-900/10 px-4 py-3 cursor-pointer hover:shadow-sm transition-shadow"
                 onclick="window.location='{{ route('admin.admission', ['status' => 'incomplete']) }}'">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/30">
                    <iconify-icon icon="solar:file-corrupted-bold" width="18" class="text-orange-600 dark:text-orange-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-semibold text-orange-800 dark:text-orange-200 leading-none">{{ $stats['incomplete'] }}</p>
                    <p class="text-xs text-orange-600 dark:text-orange-400 mt-1 leading-tight">Incomplete</p>
                </div>
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 dark:border-red-900/30 dark:bg-red-900/10 px-4 py-3 cursor-pointer hover:shadow-sm transition-shadow"
                 onclick="window.location='{{ route('admin.admission', ['status' => 'rejected']) }}'">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/30">
                    <iconify-icon icon="solar:trash-bin-trash-bold" width="18" class="text-red-600 dark:text-red-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-semibold text-red-800 dark:text-red-200 leading-none">{{ $stats['rejected'] }}</p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1 leading-tight">Rejected</p>
                </div>
            </div>
        </div>

        {{-- ── Filters ── --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 font-medium mb-3">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by
            </div>
            <form method="GET" action="{{ route('admin.admission') }}">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">School Year</label>
                        <div class="relative">
                            <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="2025-2026" {{ request('school_year','2025-2026')==='2025-2026'?'selected':'' }}>SY 2025-2026</option>
                                <option value="2024-2025" {{ request('school_year')==='2024-2025'?'selected':'' }}>SY 2024-2025</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">Application Status</label>
                        <div class="relative">
                            <select name="status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <option value="pending"    {{ request('status')==='pending'   ?'selected':'' }}>Pending</option>
                                <option value="approved"   {{ request('status')==='approved'  ?'selected':'' }}>Approved</option>
                                <option value="incomplete" {{ request('status')==='incomplete'?'selected':'' }}>Incomplete</option>
                                <option value="rejected"   {{ request('status')==='rejected'  ?'selected':'' }}>Rejected</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">Grade Level Applied</label>
                        <div class="relative">
                            <select name="level" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <option value="Elementary"         {{ request('level')==='Elementary'        ?'selected':'' }}>Elementary</option>
                                <option value="Junior High School" {{ request('level')==='Junior High School'?'selected':'' }}>Junior High School</option>
                                <option value="Senior High School" {{ request('level')==='Senior High School'?'selected':'' }}>Senior High School</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">Application Type</label>
                        <div class="relative">
                            <select name="app_type" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                <option>New</option><option>Return</option><option>Transferee</option>
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                    </button>
                    <a href="{{ route('admin.admission') }}" class="rounded-lg border border-slate-200 px-5 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        Clear All
                    </a>
                </div>
            </form>
        </div>

        {{-- ── Table Controls ── --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span>Show</span>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                    <option>10</option><option>25</option><option>50</option>
                </select>
                <span>entries</span>
            </div>
            <form method="GET" action="{{ route('admin.admission') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="level"  value="{{ request('level') }}">
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search Student, Application no.."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-60">
                </div>
            </form>
        </div>

        {{-- ── Bulk form wrapper ── --}}
        <form id="bulk-form" method="POST" action="{{ route('admin.admission.bulk-status') }}">
            @csrf
            <input type="hidden" name="status" id="bulk-status-value">
            <input type="hidden" name="action" id="bulk-action-value">

            {{-- ── Table ── --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:960px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" id="check-all"
                                    class="rounded text-blue-600 focus:ring-blue-500"
                                    @change="
                                        document.querySelectorAll('.row-check').forEach(cb => cb.checked = $event.target.checked);
                                        selectedIds = $event.target.checked
                                            ? {{ '[' . $applications->pluck('id')->implode(',') . ']' }}
                                            : [];
                                    ">
                            </th>
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
                            $sClass = [
                                'pending'      => 'bg-amber-100 text-amber-700',
                                'incomplete'   => 'bg-orange-100 text-orange-700',
                                'pre_approved' => 'bg-blue-100 text-blue-700',
                                'approved'     => 'bg-green-100 text-green-700',
                                'rejected'     => 'bg-red-100 text-red-700',
                            ][$app->application_status] ?? 'bg-slate-100 text-slate-600';
                            $appType = $app->is_transferee ? 'Transfer' : ($app->student_status === 'Old' ? 'Return' : 'New');
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="ids[]" value="{{ $app->id }}"
                                    class="row-check rounded text-blue-600 focus:ring-blue-500"
                                    x-model="selectedIds"
                                    :value="{{ $app->id }}">
                            </td>
                            <td class="px-4 py-3 text-xs font-mono text-slate-400 truncate">{{ $app->reference_number }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-300 truncate">{{ $app->first_name }} {{ $app->last_name }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $app->school_year }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 truncate">{{ $app->applied_level }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500 truncate">{{ $app->incoming_grade_level }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $appType }}</td>
                            <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">{{ $app->submitted_at?->format('n/j/y') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $sClass }}">
                                    {{ $app->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div x-data="{ open: false }" class="relative inline-block">
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
                                         class="absolute right-0 z-20 mt-1 w-44 rounded-xl border border-slate-200 bg-white dark:bg-dark-card shadow-lg py-1">

                                        {{-- View --}}
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

                                        {{-- Approve --}}
                                        <form method="POST" action="{{ route('admin.admission.status', $app->id) }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-xs text-green-600 hover:bg-green-50 transition-colors">
                                                <iconify-icon icon="solar:check-circle-linear" width="14"></iconify-icon>
                                                Approve
                                            </button>
                                        </form>

                                        {{-- Mark Incomplete --}}
                                        <form method="POST" action="{{ route('admin.admission.status', $app->id) }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="incomplete">
                                            <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-xs text-orange-500 hover:bg-orange-50 transition-colors">
                                                <iconify-icon icon="solar:danger-triangle-linear" width="14"></iconify-icon>
                                                Mark Incomplete
                                            </button>
                                        </form>

                                        {{-- Reject --}}
                                        <form method="POST" action="{{ route('admin.admission.status', $app->id) }}">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 transition-colors">
                                                <iconify-icon icon="solar:close-circle-linear" width="14"></iconify-icon>
                                                Reject
                                            </button>
                                        </form>

                                        <div class="border-t border-slate-100 my-1"></div>

                                        {{-- Send Notice (single) --}}
                                        <button type="button"
                                            @click="open = false; selectedIds = [{{ $app->id }}]; noticeModal = true"
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
        </form>

        {{-- ── Pagination ── --}}
        <div class="flex items-center justify-end px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            {{ $applications->links() }}
        </div>

    </div>

    {{-- ════════════════════════════════════════
         BULK ACTION BAR
    ════════════════════════════════════════ --}}
    <div id="bulk-bar" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-30 hidden">
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white dark:bg-dark-card shadow-2xl px-5 py-3">
            <iconify-icon icon="solar:users-group-rounded-linear" width="18" class="text-slate-400"></iconify-icon>
            <span id="bulk-count" class="text-xs font-medium text-slate-600 dark:text-slate-300 whitespace-nowrap">0 Selected</span>
            <div class="h-4 w-px bg-slate-200 dark:bg-slate-700"></div>

            {{-- Approve --}}
            <button type="button" onclick="submitBulk('approved','approve')"
                class="flex items-center gap-1.5 rounded-lg bg-green-600 hover:bg-green-700 px-4 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap">
                <iconify-icon icon="solar:check-circle-linear" width="14"></iconify-icon>
                Approve Application
            </button>

            {{-- Mark Incomplete --}}
            <button type="button" onclick="submitBulk('incomplete','mark_incomplete')"
                class="flex items-center gap-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 px-4 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap">
                <iconify-icon icon="solar:danger-triangle-linear" width="14"></iconify-icon>
                Mark Incomplete
            </button>

            {{-- Export Excel (placeholder) --}}
            <button type="button"
                class="flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-600 transition-colors whitespace-nowrap">
                <iconify-icon icon="solar:export-linear" width="14"></iconify-icon>
                Export Excel
            </button>

            {{-- Send Notice --}}
            <button type="button" onclick="openBulkNotice()"
                class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap">
                <iconify-icon icon="solar:letter-linear" width="14"></iconify-icon>
                Send Notice
            </button>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         SEND NOTICE MODAL
    ════════════════════════════════════════ --}}
    <div id="notice-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeNoticeModal()"></div>
        <div class="relative w-full max-w-lg mx-4 rounded-2xl overflow-hidden shadow-2xl">

            {{-- Header --}}
            <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
                <div>
                    <h3 class="text-white text-sm font-bold">SEND EMAIL NOTICE</h3>
                    <p id="notice-selected-label" class="text-blue-200 text-xs mt-0.5">Selected: 0 Students</p>
                </div>
                <button onclick="closeNoticeModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 transition-colors text-sm">✕</button>
            </div>

            {{-- Body --}}
            <form id="notice-form" method="POST" action="{{ route('admin.admission.send-notice') }}" class="bg-white dark:bg-dark-card px-6 py-5 space-y-4">
                @csrf
                <div id="notice-ids-container"></div>

                {{-- Subject --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Subject:</label>
                    <input type="text" name="subject" id="notice-subject"
                        value="Application Status Update - My Messiah School of Cavite"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Reason / Message Type --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Reason/Message Type:</label>
                    <div class="rounded-lg border border-slate-200 dark:border-dark-border p-3 space-y-2">
                        @foreach([
                            'Application Approved',
                            'Application Rejected',
                            'Missing Requirements',
                            'Pending Payment',
                            'Document Verification Needed',
                            'Interview Schedule',
                            'Entrance Exam Schedule',
                            'Custom Message',
                        ] as $type)
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="message_type" value="{{ $type }}"
                                class="text-blue-600 focus:ring-blue-500"
                                {{ $loop->first ? 'checked' : '' }}>
                            <span class="text-xs text-slate-600 dark:text-slate-300">{{ $type }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Details --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">
                        Reason Details <span class="font-normal normal-case text-slate-400">(if Rejected, Missing Requirements, etc.)</span>:
                    </label>
                    <textarea name="details" rows="4"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>

                {{-- Send copy --}}
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="send_copy" value="1" class="rounded text-blue-600 focus:ring-blue-500">
                    <span class="text-xs text-slate-600 dark:text-slate-300">Send copy to my email</span>
                </label>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-2">
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors uppercase tracking-wide">
                        Send Notice
                    </button>
                    <button type="button" onclick="closeNoticeModal()"
                        class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors uppercase tracking-wide">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

</div>

@push('scripts')
<script>
// ── Checkbox + Bulk Bar ────────────────────────────────
const checkAll  = document.getElementById('check-all');
const bulkBar   = document.getElementById('bulk-bar');
const bulkCount = document.getElementById('bulk-count');
let   selectedIds = [];

function updateBulkBar() {
    const checked = [...document.querySelectorAll('.row-check:checked')];
    selectedIds   = checked.map(cb => cb.value);
    bulkCount.textContent = selectedIds.length + ' Selected';
    bulkBar.classList.toggle('hidden', selectedIds.length === 0);
}

checkAll?.addEventListener('change', function () {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
    updateBulkBar();
});

document.querySelectorAll('.row-check').forEach(cb => {
    cb.addEventListener('change', updateBulkBar);
});

function submitBulk(status, action) {
    if (selectedIds.length === 0) return;
    const form = document.getElementById('bulk-form');
    document.getElementById('bulk-status-value').value = status;
    document.getElementById('bulk-action-value').value  = action;
    // Inject ids
    selectedIds.forEach(id => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'ids[]';
        inp.value = id;
        form.appendChild(inp);
    });
    form.submit();
}

// ── Notice Modal ───────────────────────────────────────
function openNoticeModal(ids) {
    selectedIds = ids;
    const modal = document.getElementById('notice-modal');
    const label = document.getElementById('notice-selected-label');
    const container = document.getElementById('notice-ids-container');

    label.textContent = 'Selected: ' + ids.length + ' Student' + (ids.length > 1 ? 's' : '');
    container.innerHTML = '';
    ids.forEach(id => {
        const inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'ids[]';
        inp.value = id;
        container.appendChild(inp);
    });

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function openBulkNotice() {
    if (selectedIds.length === 0) return;
    openNoticeModal(selectedIds);
}

function closeNoticeModal() {
    document.getElementById('notice-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Allow Alpine to call openNoticeModal for single-row Send Notice
window.openNoticeModal = openNoticeModal;
</script>
@endpush

@endsection