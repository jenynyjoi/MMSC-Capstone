@extends('layouts.admin_layout')
@section('title', 'Student Behavioral Record')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4 pb-32">

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
        school-year="{{ $schoolYear ?? $activeSchoolYear }}"
    />

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:document-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Student Behavioral Record</h2>
            </div>
            <button onclick="brOpenModal('add-modal')"
                class="flex items-center gap-2 rounded-xl border border-blue-300 bg-blue-50 hover:bg-blue-100 dark:border-blue-700 dark:bg-blue-900/20 px-4 py-2 text-xs font-semibold text-blue-600 dark:text-blue-400 transition-colors">
                <iconify-icon icon="solar:add-circle-linear" width="15"></iconify-icon>
                Add New Record
            </button>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            @foreach([
                ['bg-slate-50 border-slate-200 text-slate-600','solar:document-bold','text-slate-600',     $stats['total']??0,    'Total Records'],
                ['bg-yellow-50 border-yellow-200 text-yellow-600','solar:hourglass-bold','text-yellow-600',$stats['pending']??0,  'Pending'],
                ['bg-green-50 border-green-200 text-green-600','solar:check-circle-bold','text-green-600', $stats['resolved']??0, 'Resolved'],
                ['bg-red-50 border-red-200 text-red-600','solar:danger-triangle-bold','text-red-600',      $stats['escalated']??0,'Escalated'],
            ] as [$card,$icon,$clr,$count,$lbl])
            <div class="flex items-center gap-3 rounded-xl border {{ $card }} px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/70 dark:bg-white/10">
                    <iconify-icon icon="{{ $icon }}" width="20" class="{{ $clr }}"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $count }}</p>
                    <p class="text-xs {{ $clr }} mt-1">{{ $lbl }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Filters --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-1 text-xs text-slate-500 font-medium mb-4">
                <iconify-icon icon="solar:filter-linear" width="14"></iconify-icon> Filter by
            </div>
            <form method="GET" action="{{ route('admin.student-records.behavioral') }}">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-3">
                    @php
                    $filters = [
                        ['school_year','School Year',['2026-2027'=>'SY 2026-2027','2025-2026'=>'SY 2025-2026','2024-2025'=>'SY 2024-2025']],
                        ['grade_section','Grade and Section',[''=>'All','Kinder'=>'Kinder','Grade 1'=>'Grade 1','Grade 2'=>'Grade 2','Grade 3'=>'Grade 3','Grade 4'=>'Grade 4','Grade 5'=>'Grade 5','Grade 6'=>'Grade 6','Grade 7'=>'Grade 7','Grade 8'=>'Grade 8','Grade 9'=>'Grade 9','Grade 10'=>'Grade 10','Grade 11'=>'Grade 11','Grade 12'=>'Grade 12']],
                        ['behavior_type','Behavior',[''=>'All','Bullying'=>'Bullying','Tardiness'=>'Tardiness','Absenteeism'=>'Absenteeism','Disrespect'=>'Disrespect','Cheating'=>'Cheating','Fighting'=>'Fighting','Vandalism'=>'Vandalism','Insubordination'=>'Insubordination','Truancy'=>'Truancy','Other'=>'Other']],
                        ['status','Status',[''=>'All','pending'=>'Pending','resolved'=>'Resolved','dismissed'=>'Dismissed','escalated'=>'Escalated']],
                    ];
                    @endphp
                    @foreach($filters as [$name,$label,$opts])
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $label }}</label>
                        <div class="relative">
                            <select name="{{ $name }}" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                @foreach($opts as $v=>$t)
                                <option value="{{ $v }}" {{ request($name, $name==='school_year'?'2026-2027':'') === (string)$v ? 'selected':'' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                    </button>
                    <a href="{{ route('admin.student-records.behavioral') }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">Clear All</a>
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
                <input type="text" id="behav-search" placeholder="Search student.."
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto" x-data="behaviorTable()">
            <table class="w-full text-left text-sm" style="min-width:1000px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" id="behav-check-all" x-model="allSelected" @change="toggleAll()"
                                class="rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                        </th>
                        <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                        <th class="px-4 py-3">Student Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade and Section</th>
                        <th class="px-4 py-3 whitespace-nowrap">Date</th>
                        <th class="px-4 py-3 whitespace-nowrap">Behavior</th>
                        <th class="px-4 py-3 whitespace-nowrap">Severity</th>
                        <th class="px-4 py-3 whitespace-nowrap">Action Taken</th>
                        <th class="px-4 py-3 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 whitespace-nowrap">Recorded By</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border" id="behav-tbody">
                @php
                    $sevClass = [
                        'Minor'    => 'bg-green-100 text-green-700',
                        'Moderate' => 'bg-yellow-100 text-yellow-700',
                        'Major'    => 'bg-orange-100 text-orange-700',
                        'Critical' => 'bg-red-100 text-red-700',
                    ];
                    $stsClass = [
                        'pending'   => 'bg-yellow-100 text-yellow-700',
                        'resolved'  => 'bg-green-100 text-green-700',
                        'dismissed' => 'bg-slate-100 text-slate-500',
                        'escalated' => 'bg-red-100 text-red-700',
                    ];
                @endphp

                @forelse($records as $rec)
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors behav-row"
                    :class="selected.includes({{ $rec->id }}) ? 'bg-blue-50/60 dark:bg-blue-900/10' : ''"
                    data-name="{{ strtolower($rec->student->full_name ?? '') }}">
                    <td class="px-4 py-3">
                        <input type="checkbox" value="{{ $rec->id }}"
                            x-model="selected" @change="updateAllSelected()"
                            class="rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                    </td>
                    <td class="px-4 py-3 text-xs font-mono text-slate-400">{{ $rec->student->student_id ?? '—' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($rec->student)
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#0d4c8f]/10 dark:bg-blue-900/20 text-[11px] font-bold text-[#0d4c8f] dark:text-blue-400">
                                {{ strtoupper(substr($rec->student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($rec->student->last_name ?? 'S', 0, 1)) }}
                            </div>
                            <a href="{{ route('admin.student-records.profile', $rec->student->id) }}"
                                class="text-sm font-medium text-slate-800 dark:text-slate-100 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors whitespace-nowrap">
                                {{ $rec->student->formatted_name }}
                            </a>
                        </div>
                        @else <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500">{{ $rec->display_name }}</td>
                    <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $rec->incident_date?->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300">{{ $rec->behavior_type }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $sevClass[$rec->severity] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $rec->severity }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500">{{ $rec->action_taken }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $stsClass[$rec->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($rec->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                        {{ $rec->recorder?->name ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div x-data="{ open: false }" class="relative inline-block">
                            <button @click="open = !open" type="button"
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

                                {{-- View Details --}}
                                <button type="button" @click="open=false"
                                    onclick="viewDetails({{ $rec->id }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:eye-bold" width="14" class="text-amber-500"></iconify-icon>View Details
                                </button>

                                {{-- Edit Record --}}
                                <button type="button" @click="open=false"
                                    onclick="editRecord({{ $rec->id }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:pen-bold" width="14" class="text-blue-500"></iconify-icon>Edit Record
                                </button>

                                {{-- Upload Document --}}
                                <button type="button" @click="open=false"
                                    onclick="uploadDoc({{ $rec->id }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:upload-bold" width="14" class="text-violet-500"></iconify-icon>Upload Document
                                </button>

                                {{-- View Documents --}}
                                <button type="button" @click="open=false"
                                    onclick="viewDocs({{ $rec->id }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:folder-bold" width="14" class="text-slate-500"></iconify-icon>View Documents
                                </button>

                                {{-- Update Status --}}
                                <button type="button" @click="open=false"
                                    onclick="updateStatus({{ $rec->id }}, '{{ $rec->status }}')"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:refresh-bold" width="14" class="text-green-500"></iconify-icon>Update Status
                                </button>

                                {{-- Print PDF --}}
                                <button type="button" @click="open=false"
                                    onclick="printRecord({{ $rec->id }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:printer-bold" width="14" class="text-slate-500"></iconify-icon>Print PDF
                                </button>

                                {{-- Send Notice --}}
                                <button type="button" @click="open=false"
                                    onclick="sendSingleNotice(
                                        {{ $rec->id }},
                                        '{{ addslashes($rec->student->full_name ?? '') }}',
                                        '{{ addslashes($rec->student->guardian_email ?? '') }}',
                                        '{{ addslashes($rec->student->personal_email ?? '') }}'
                                    )"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:letter-bold" width="14" class="text-blue-500"></iconify-icon>Send Notice
                                </button>

                                <div class="my-1 border-t border-slate-100 dark:border-dark-border"></div>

                                {{-- Delete --}}
                                <button type="button" @click="open=false"
                                    onclick="deleteRecord({{ $rec->id }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                    <iconify-icon icon="solar:trash-bin-trash-bold" width="14"></iconify-icon>Delete
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-16 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <iconify-icon icon="solar:document-linear" width="32" class="text-slate-300"></iconify-icon>
                            <p class="text-sm font-medium text-slate-500">No behavioral records found.</p>
                            <button onclick="brOpenModal('add-modal')" class="text-xs text-blue-600 hover:underline">Add the first record →</button>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Bulk Action Bar (sticky bottom, Alpine driven) --}}
            <div class="fixed bottom-0 left-0 right-0 z-30 transition-all duration-300"
                 :class="selected.length > 0 ? 'translate-y-0 opacity-100' : 'translate-y-full opacity-0 pointer-events-none'">
                <div class="mx-auto max-w-screen-xl px-4 lg:px-8 pb-4">
                    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-2xl px-6 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <iconify-icon icon="solar:document-bold" width="20" class="text-slate-400"></iconify-icon>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                <span x-text="selected.length"></span>
                                <span x-text="selected.length === 1 ? ' Selected Record' : ' Selected Records'"></span>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="bulkDownloadPdf()"
                                class="flex items-center gap-2 rounded-xl border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors">
                                <iconify-icon icon="solar:file-download-bold" width="14" class="text-red-500"></iconify-icon>
                                DOWNLOAD PDF
                            </button>
                            <button @click="bulkSendNotice()"
                                class="flex items-center gap-2 rounded-xl bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition-colors">
                                <iconify-icon icon="solar:letter-bold" width="14"></iconify-icon>
                                Send Notice
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        @if(method_exists($records, 'links'))
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400">Showing {{ $records->firstItem() ?? 0 }}–{{ $records->lastItem() ?? 0 }} of {{ $records->total() }}</p>
            {{ $records->links() }}
        </div>
        @endif

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     ADD RECORD MODAL (Stage 3)
══════════════════════════════════════════════════════════════════ --}}
<div id="add-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="brCloseModal('add-modal')"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-white text-sm font-bold">ADD NEW BEHAVIORAL RECORD</h3>
            <button onclick="brCloseModal('add-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <form id="add-form" class="px-6 py-5 space-y-5" onsubmit="submitAddRecord(event)">
            @csrf
            {{-- Student Information --}}
            <div>
                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <iconify-icon icon="solar:user-bold" width="13"></iconify-icon> Student Information
                </p>
                <div class="space-y-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">Search / Select Student <span class="text-red-500">*</span></label>
                        {{-- hidden real value --}}
                        <input type="hidden" id="add-student-select" value="">
                        <div class="relative" id="student-dropdown-wrap">
                            <div id="student-dropdown-trigger"
                                onclick="toggleStudentDropdown()"
                                class="w-full flex items-center justify-between rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm cursor-pointer select-none bg-white">
                                <span id="student-selected-label" class="text-slate-400 dark:text-slate-500">— Select Student —</span>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="text-slate-400 shrink-0"></iconify-icon>
                            </div>
                            <div id="student-dropdown-panel"
                                class="hidden absolute z-50 left-0 right-0 mt-1 rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-xl overflow-hidden">
                                <div class="p-2 border-b border-slate-100 dark:border-dark-border">
                                    <div class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800/40">
                                        <iconify-icon icon="solar:magnifer-linear" width="13" class="text-slate-400 shrink-0"></iconify-icon>
                                        <input type="text" id="student-search-input" placeholder="Search by name or ID…"
                                            oninput="filterStudentList(this.value)"
                                            class="flex-1 bg-transparent text-xs focus:outline-none text-slate-700 dark:text-white placeholder-slate-400">
                                    </div>
                                </div>
                                <ul id="student-dropdown-list" class="max-h-48 overflow-y-auto py-1 text-sm">
                                    @foreach($students as $s)
                                    <li class="student-option px-3 py-2 cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900/20 text-slate-700 dark:text-slate-300 text-xs"
                                        data-id="{{ $s->id }}"
                                        data-label="{{ $s->full_name }} ({{ $s->student_id }}) — {{ $s->grade_level }}"
                                        data-search="{{ strtolower($s->full_name . ' ' . $s->student_id . ' ' . $s->grade_level) }}"
                                        onclick="selectStudent({{ $s->id }}, '{{ addslashes($s->full_name) }} ({{ $s->student_id }}) — {{ $s->grade_level }}')">
                                        <span class="font-medium">{{ $s->full_name }}</span>
                                        <span class="text-slate-400 ml-1">{{ $s->student_id }} · {{ $s->grade_level }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                                <p id="student-no-results" class="hidden px-3 py-3 text-xs text-slate-400 text-center">No students found.</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-4 py-3 text-xs">
                        <div><p class="text-slate-400 mb-0.5">Student ID</p><p id="add-sid" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                        <div><p class="text-slate-400 mb-0.5">Student Name</p><p id="add-sname" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                        <div><p class="text-slate-400 mb-0.5">Grade & Section</p><p id="add-sgrade" class="font-semibold text-slate-700 dark:text-slate-300">—</p></div>
                    </div>
                </div>
            </div>

            {{-- Behavior Information --}}
            <div>
                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <iconify-icon icon="solar:document-bold" width="13"></iconify-icon> Behavior Information
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">Date of Incident <span class="text-red-500">*</span></label>
                        <input type="date" name="incident_date" value="{{ date('Y-m-d') }}"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    @foreach([
                        ['behavior_type','Behavior Type','required',['Bullying','Tardiness','Absenteeism','Disrespect','Cheating','Fighting','Vandalism','Insubordination','Other']],
                        ['severity','Severity Level','required',['Minor','Moderate','Major','Critical']],
                        ['action_taken','Action Taken','required',['Warning','Verbal Reprimand','Counseling','Parent Conference','Detention','Suspension','Expulsion','Other']],
                        ['referral_to','Referral To','',['None'=>'None','Guidance Office'=>'Guidance Office','Principal'=>'Principal','Discipline Office'=>'Discipline Office','Parents'=>'Parents']],
                        ['status','Status','required',['pending'=>'Pending','resolved'=>'Resolved','dismissed'=>'Dismissed','escalated'=>'Escalated']],
                    ] as [$fname,$flabel,$req,$fopts])
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">{{ $flabel }} @if($req)<span class="text-red-500">*</span>@endif</label>
                        <div class="relative">
                            <select name="{{ $fname }}" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                @if(!$req)<option value="">None</option>@endif
                                @foreach($fopts as $v=>$t)
                                @if(is_int($v))<option value="{{ $t }}">{{ $t }}</option>
                                @else<option value="{{ $v }}">{{ $t }}</option>@endif
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Details --}}
            <div>
                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <iconify-icon icon="solar:text-bold" width="13"></iconify-icon> Details
                </p>
                <div class="space-y-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">Description of Incident <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" placeholder="Describe what happened in detail..."
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">Action Details</label>
                        <textarea name="action_details" rows="2" placeholder="Describe the specific actions taken..."
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>
                </div>
            </div>

            {{-- Document Upload --}}
            <div>
                <p class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                    <iconify-icon icon="solar:paperclip-bold" width="13"></iconify-icon> Document Upload <span class="text-slate-400 font-normal text-[10px] ml-1">(Optional)</span>
                </p>
                <div class="rounded-xl border-2 border-dashed border-slate-200 dark:border-dark-border px-4 py-5 text-center">
                    <input type="file" id="add-doc-file" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" onchange="showFileName(this,'add-doc-label')">
                    <iconify-icon icon="solar:cloud-upload-bold" width="28" class="text-slate-300 mb-2 block mx-auto"></iconify-icon>
                    <p id="add-doc-label" class="text-xs text-slate-400 mb-2">PDF, DOC, DOCX, JPG, PNG · Max 5MB</p>
                    <button type="button" onclick="document.getElementById('add-doc-file').click()"
                        class="px-4 py-1.5 rounded-lg border border-slate-300 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        Choose File
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:diskette-bold" width="14"></iconify-icon> SAVE RECORD
                </button>
                <button type="button" onclick="brCloseModal('add-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     VIEW DETAILS MODAL (Stage 6)
══════════════════════════════════════════════════════════════════ --}}
<div id="view-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="brCloseModal('view-modal')"></div>
    <div class="relative w-full max-w-xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 id="view-title" class="text-white text-sm font-bold">BEHAVIORAL RECORD DETAILS</h3>
            <button onclick="brCloseModal('view-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div id="view-content" class="p-6">
            <div class="py-8 text-center"><iconify-icon icon="solar:loading-bold" width="24" class="text-slate-300 animate-spin block mx-auto mb-2"></iconify-icon><p class="text-xs text-slate-400">Loading...</p></div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     EDIT RECORD MODAL (Stage 7)
══════════════════════════════════════════════════════════════════ --}}
<div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="brCloseModal('edit-modal')"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-blue-600 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-white text-sm font-bold">EDIT BEHAVIORAL RECORD</h3>
            <button onclick="brCloseModal('edit-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <form id="edit-form" class="px-6 py-5 space-y-4" onsubmit="submitEditRecord(event)">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500">Date of Incident <span class="text-red-500">*</span></label>
                    <input type="date" id="edit-incident-date" name="incident_date"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @foreach([
                    ['edit-behavior-type','behavior_type','Behavior Type',['Bullying','Tardiness','Absenteeism','Disrespect','Cheating','Fighting','Vandalism','Insubordination','Other']],
                    ['edit-severity','severity','Severity Level',['Minor','Moderate','Major','Critical']],
                    ['edit-action-taken','action_taken','Action Taken',['Warning','Verbal Reprimand','Counseling','Parent Conference','Detention','Suspension','Expulsion','Other']],
                    ['edit-referral','referral_to','Referral To',['None','Guidance Office','Principal','Discipline Office','Parents']],
                    ['edit-status','status','Status',['pending'=>'Pending','resolved'=>'Resolved','dismissed'=>'Dismissed','escalated'=>'Escalated']],
                ] as [$eid,$ename,$elabel,$eopts])
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500">{{ $elabel }}</label>
                    <div class="relative">
                        <select id="{{ $eid }}" name="{{ $ename }}" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            @foreach($eopts as $v=>$t)
                            @if(is_int($v))<option value="{{ $t }}">{{ $t }}</option>
                            @else<option value="{{ $v }}">{{ $t }}</option>@endif
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-medium text-slate-500">Description of Incident <span class="text-red-500">*</span></label>
                <textarea id="edit-description" name="description" rows="3"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-medium text-slate-500">Action Details</label>
                <textarea id="edit-action-details" name="action_details" rows="2"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-medium text-slate-500">Resolution Notes</label>
                <textarea id="edit-resolution" name="resolution_notes" rows="2"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
            {{-- Additional doc upload in edit --}}
            <div>
                <label class="text-xs font-medium text-slate-500 mb-1.5 block">Upload Additional Document</label>
                <div class="flex items-center gap-3">
                    <input type="file" id="edit-doc-file" name="document" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" onchange="showFileName(this,'edit-doc-label')">
                    <button type="button" onclick="document.getElementById('edit-doc-file').click()"
                        class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Choose File</button>
                    <span id="edit-doc-label" class="text-xs text-slate-400">No file chosen</span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:diskette-bold" width="14"></iconify-icon> UPDATE RECORD
                </button>
                <button type="button" onclick="brCloseModal('edit-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     UPLOAD DOCUMENT MODAL (Stage 8)
══════════════════════════════════════════════════════════════════ --}}
<div id="upload-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="brCloseModal('upload-modal')"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-violet-600 px-6 py-4 flex items-center justify-between">
            <h3 class="text-white text-sm font-bold">UPLOAD DOCUMENT</h3>
            <button onclick="brCloseModal('upload-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="rounded-xl border-2 border-dashed border-slate-200 dark:border-dark-border px-4 py-6 text-center">
                <input type="file" id="upload-file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" onchange="showFileName(this,'upload-file-label')">
                <iconify-icon icon="solar:cloud-upload-bold" width="28" class="text-slate-300 mb-2 block mx-auto"></iconify-icon>
                <p id="upload-file-label" class="text-xs text-slate-400 mb-2">PDF, DOC, DOCX, JPG, PNG · Max 5MB</p>
                <button type="button" onclick="document.getElementById('upload-file').click()"
                    class="px-4 py-1.5 rounded-lg border border-slate-300 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Choose File</button>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Description <span class="text-slate-400 font-normal">(optional)</span></label>
                <input type="text" id="upload-desc" placeholder="e.g., Incident Report Letter"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500">
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmUploadDoc()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-violet-600 hover:bg-violet-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:upload-bold" width="14"></iconify-icon> UPLOAD
                </button>
                <button type="button" onclick="brCloseModal('upload-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     VIEW DOCUMENTS MODAL (Stage 9)
══════════════════════════════════════════════════════════════════ --}}
<div id="docs-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="brCloseModal('docs-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-slate-700 px-6 py-4 flex items-center justify-between">
            <h3 class="text-white text-sm font-bold">UPLOADED DOCUMENTS</h3>
            <button onclick="brCloseModal('docs-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div id="docs-list" class="px-6 py-4 space-y-2 max-h-96 overflow-y-auto min-h-[100px]">
            <div class="text-xs text-slate-400 text-center py-4">Loading...</div>
        </div>
        <div class="px-6 pb-5">
            <button type="button" onclick="brCloseModal('docs-modal')"
                class="w-full px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CLOSE</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     UPDATE STATUS MODAL (Stage 10)
══════════════════════════════════════════════════════════════════ --}}
<div id="status-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="brCloseModal('status-modal')"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-green-600 px-6 py-4 flex items-center justify-between">
            <h3 class="text-white text-sm font-bold">UPDATE STATUS</h3>
            <button onclick="brCloseModal('status-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 dark:bg-slate-800/40 px-4 py-3 text-xs">
                <span class="text-slate-400">Current Status:</span>
                <span id="status-current" class="font-semibold rounded-full px-2.5 py-0.5 bg-yellow-100 text-yellow-700">—</span>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">New Status <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="status-new" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 pr-8">
                        <option value="pending">Pending</option>
                        <option value="resolved">Resolved</option>
                        <option value="dismissed">Dismissed</option>
                        <option value="escalated">Escalated</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Resolution Notes
                    <span class="text-slate-400 font-normal">(required if Resolved or Dismissed)</span>
                </label>
                <textarea id="status-notes" rows="3" placeholder="Enter resolution details..."
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"></textarea>
            </div>
            <label class="flex items-center gap-3 cursor-pointer text-xs text-slate-600 dark:text-slate-300">
                <input type="checkbox" id="status-notify" class="rounded border-slate-300 text-green-600 focus:ring-green-500">
                Notify parent/guardian of this status change
            </label>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmUpdateStatus()"
                    class="px-6 py-2.5 rounded-lg bg-green-600 hover:bg-green-700 text-white text-xs font-bold transition-colors">UPDATE STATUS</button>
                <button type="button" onclick="brCloseModal('status-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     SEND NOTICE MODAL (Stage 13 / 14)
══════════════════════════════════════════════════════════════════ --}}
<div id="notice-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="brCloseModal('notice-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 id="notice-title" class="text-white text-sm font-bold">SEND NOTICE</h3>
            <button onclick="brCloseModal('notice-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="rounded-xl border border-slate-200 bg-slate-50 dark:bg-slate-800/40 px-4 py-3 text-xs space-y-1">
                <p id="notice-to-student" class="font-semibold text-slate-700 dark:text-slate-300">—</p>
                <p id="notice-to-parent" class="text-slate-400">Parent: —</p>
                <p id="notice-to-school" class="text-slate-400">Student: —</p>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Notice Type <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="notice-type" onchange="prefillTemplate(this.value)"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                        <option value="">— Select Type —</option>
                        <option value="Behavior Notification">Behavior Notification</option>
                        <option value="Parent Conference">Parent Conference</option>
                        <option value="Warning Letter">Warning Letter</option>
                        <option value="Suspension Notice">Suspension Notice</option>
                        <option value="Other">Other</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Subject <span class="text-red-500">*</span></label>
                <input type="text" id="notice-subject"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Message <span class="text-red-500">*</span></label>
                <textarea id="notice-message" rows="7"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>
            <div class="flex flex-col gap-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Send To:</label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 text-xs cursor-pointer text-slate-600 dark:text-slate-300">
                        <input type="checkbox" name="send_to[]" value="student" class="rounded text-blue-600 focus:ring-blue-500"> Student
                    </label>
                    <label class="flex items-center gap-2 text-xs cursor-pointer text-slate-600 dark:text-slate-300">
                        <input type="checkbox" name="send_to[]" value="parent" checked class="rounded text-blue-600 focus:ring-blue-500"> Parent/Guardian
                    </label>
                </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmSendNotice()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:letter-bold" width="14"></iconify-icon> SEND NOTICE
                </button>
                <button type="button" onclick="brCloseModal('notice-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     PRINT PREVIEW MODAL (Stage 12)
══════════════════════════════════════════════════════════════════ --}}
<div id="print-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="brCloseModal('print-modal')"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" style="max-height:90vh;overflow-y:auto">
        <div class="bg-slate-700 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-white text-sm font-bold">PRINT PREVIEW</h3>
            <button onclick="brCloseModal('print-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div id="print-content" class="p-6">
            <div class="py-8 text-center text-xs text-slate-400">Loading preview...</div>
        </div>
        <div class="px-6 pb-5 flex items-center justify-between border-t border-slate-100">
            <button type="button" onclick="window.print()"
                class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold transition-colors mt-4">
                <iconify-icon icon="solar:printer-bold" width="14"></iconify-icon> PRINT / DOWNLOAD PDF
            </button>
            <button type="button" onclick="brCloseModal('print-modal')"
                class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors mt-4">CLOSE</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF      = '{{ csrf_token() }}';
const BASE_URL  = '{{ url("/admin/student-records/behavioral") }}';
const DOC_BASE  = '{{ url("/admin/student-records/behavioral/document") }}';
const SY        = '{{ $schoolYear ?? "2026-2027" }}';

let _recordId   = null;  // active record for modals
let _noticeIds  = [];    // record ids for notice

// ── Alpine component ──────────────────────────────────────
function behaviorTable() {
    return {
        selected: [],
        allSelected: false,
        rowIds: @json($records->pluck('id')->toArray()),
        toggleAll()        { this.selected = this.allSelected ? [...this.rowIds] : []; },
        updateAllSelected(){ this.allSelected = this.selected.length === this.rowIds.length; },
        bulkDownloadPdf()  { showToast('Generating PDF for selected records...'); },
        bulkSendNotice()   {
            _noticeIds = this.selected;
            document.getElementById('notice-title').textContent = 'SEND NOTICE — ' + this.selected.length + ' Record(s)';
            document.getElementById('notice-to-student').textContent = this.selected.length + ' selected records';
            document.getElementById('notice-to-parent').textContent  = 'Parent: notices will be sent to each';
            document.getElementById('notice-to-school').textContent  = 'Student: (if selected)';
            resetNoticeForm();
            brOpenModal('notice-modal');
        },
    };
}

// ── Modal helpers ─────────────────────────────────────────
function brOpenModal(id)  { document.getElementById(id)?.classList.remove('hidden'); document.body.style.overflow='hidden'; }
function brCloseModal(id) { document.getElementById(id)?.classList.add('hidden');    document.body.style.overflow=''; }

function showToast(msg, type='success') {
    const t = document.createElement('div');
    t.className = 'fixed top-6 right-6 z-[100] flex items-center gap-2 rounded-xl border px-4 py-3 text-sm shadow-xl '
        + (type==='success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700');
    t.innerHTML = `<iconify-icon icon="solar:${type==='success'?'check-circle-bold':'close-circle-bold'}" width="16"></iconify-icon> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .3s'; setTimeout(()=>t.remove(),300); }, 3500);
}

function showFileName(input, labelId) {
    document.getElementById(labelId).textContent = input.files[0]?.name ?? 'No file chosen';
}

// ── Search ────────────────────────────────────────────────
document.getElementById('behav-search')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.behav-row').forEach(row =>
        row.style.display = (!q || (row.dataset.name||'').includes(q)) ? '' : 'none'
    );
});

// ── Stage 3: Load student info in Add modal ───────────────
// ── Student searchable dropdown ───────────────────────────
function toggleStudentDropdown() {
    const panel = document.getElementById('student-dropdown-panel');
    const isHidden = panel.classList.contains('hidden');
    panel.classList.toggle('hidden', !isHidden);
    if (isHidden) {
        document.getElementById('student-search-input').focus();
        filterStudentList('');
    }
}
function filterStudentList(q) {
    const term = q.toLowerCase().trim();
    const items = document.querySelectorAll('#student-dropdown-list .student-option');
    let visible = 0;
    items.forEach(li => {
        const match = !term || li.dataset.search.includes(term);
        li.classList.toggle('hidden', !match);
        if (match) visible++;
    });
    document.getElementById('student-no-results').classList.toggle('hidden', visible > 0);
}
function selectStudent(id, label) {
    document.getElementById('add-student-select').value = id;
    document.getElementById('student-selected-label').textContent = label;
    document.getElementById('student-selected-label').classList.remove('text-slate-400','dark:text-slate-500');
    document.getElementById('student-selected-label').classList.add('text-slate-700','dark:text-slate-300');
    document.getElementById('student-dropdown-panel').classList.add('hidden');
    document.getElementById('student-search-input').value = '';
    loadStudentInfo(id);
}
// Close dropdown on outside click
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('student-dropdown-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('student-dropdown-panel')?.classList.add('hidden');
    }
});

function loadStudentInfo(id) {
    ['add-sid','add-sname','add-sgrade'].forEach(el => document.getElementById(el).textContent = '—');
    if (!id) return;
    fetch(`{{ route('admin.student-records.behavioral.student-info') }}?student_id=${id}&school_year=${SY}`)
        .then(r => r.json())
        .then(d => {
            document.getElementById('add-sid').textContent   = d.student_id   ?? '—';
            document.getElementById('add-sname').textContent = d.full_name    ?? '—';
            document.getElementById('add-sgrade').textContent= d.grade        ?? '—';
        }).catch(() => {});
}

// ── Stage 3: Submit Add Record ────────────────────────────
function submitAddRecord(e) {
    e.preventDefault();
    const studentId = document.getElementById('add-student-select').value;
    if (!studentId) { showToast('Please select a student.','error'); return; }

    const form = document.getElementById('add-form');
    const fd   = new FormData(form);
    fd.append('student_id', studentId);
    const docFile = document.getElementById('add-doc-file').files[0];
    if (docFile) fd.set('document', docFile);

    fetch(BASE_URL, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF}, body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) { brCloseModal('add-modal'); showToast(data.message); setTimeout(()=>location.reload(),1400); }
            else showToast(data.message||'Failed to save.','error');
        }).catch(() => showToast('Request failed.','error'));
}

// ── Stage 6: View Details ─────────────────────────────────
function viewDetails(id) {
    _recordId = id;
    document.getElementById('view-content').innerHTML = '<div class="py-8 text-center"><iconify-icon icon="solar:loading-bold" width="24" class="text-slate-300 animate-spin block mx-auto mb-2"></iconify-icon><p class="text-xs text-slate-400">Loading...</p></div>';
    brOpenModal('view-modal');

    fetch(`${BASE_URL}/${id}`)
        .then(r => r.json())
        .then(({record:r}) => {
            document.getElementById('view-title').textContent = 'RECORD DETAILS — ' + r.student_name;

            const sev  = {'Minor':'bg-green-100 text-green-700','Moderate':'bg-yellow-100 text-yellow-700','Major':'bg-orange-100 text-orange-700','Critical':'bg-red-100 text-red-700'};
            const sts  = {'pending':'bg-yellow-100 text-yellow-700','resolved':'bg-green-100 text-green-700','dismissed':'bg-slate-100 text-slate-500','escalated':'bg-red-100 text-red-700'};
            const docs = r.documents.length
                ? r.documents.map(d=>`<div class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-2.5 text-xs">
                        <div><p class="font-semibold text-slate-700">${d.file_name}</p><p class="text-slate-400 mt-0.5">${d.file_size} · ${d.created_at}${d.description?' · '+d.description:''}</p></div>
                        <a href="${d.download_url}" class="text-blue-600 hover:underline font-medium text-xs">Download</a>
                    </div>`).join('')
                : '<p class="text-xs text-slate-400 text-center py-2">No documents uploaded.</p>';

            document.getElementById('view-content').innerHTML = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4 rounded-xl border border-slate-200 dark:border-dark-border p-4 text-xs">
                    <div><p class="text-slate-400 mb-0.5">Student ID</p><p class="font-semibold text-slate-700 dark:text-slate-300">${r.student_id}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Student Name</p><p class="font-semibold text-slate-700 dark:text-slate-300">${r.student_name}</p></div>
                    <div><p class="text-slate-400 mb-0.5">Grade & Section</p><p class="font-semibold text-slate-700 dark:text-slate-300">${r.grade}</p></div>
                    <div><p class="text-slate-400 mb-0.5">School Year</p><p class="font-semibold text-slate-700 dark:text-slate-300">SY ${r.school_year}</p></div>
                </div>
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4 space-y-2.5 text-xs">
                    <div class="flex gap-3"><span class="text-slate-400 w-32 shrink-0">Incident Date:</span><span class="font-medium text-slate-700 dark:text-slate-300">${r.incident_date}</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-32 shrink-0">Behavior Type:</span><span class="font-medium text-slate-700 dark:text-slate-300">${r.behavior_type}</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-32 shrink-0">Severity:</span><span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${sev[r.severity]||''}">${r.severity}</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-32 shrink-0">Action Taken:</span><span class="font-medium text-slate-700 dark:text-slate-300">${r.action_taken}</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-32 shrink-0">Referral To:</span><span class="font-medium text-slate-700 dark:text-slate-300">${r.referral_to}</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-32 shrink-0">Status:</span><span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${sts[r.status]||''}">${r.status.charAt(0).toUpperCase()+r.status.slice(1)}</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-32 shrink-0">Parent Notified:</span><span class="font-medium text-slate-700 dark:text-slate-300">${r.parent_notified}</span></div>
                    ${r.action_details?`<div><p class="text-slate-400 mb-1">Action Details:</p><p class="bg-slate-50 dark:bg-slate-800 rounded-lg px-3 py-2 text-slate-600 dark:text-slate-300">${r.action_details}</p></div>`:''}
                    <div><p class="text-slate-400 mb-1">Description:</p><p class="bg-slate-50 dark:bg-slate-800 rounded-lg px-3 py-2.5 text-slate-600 dark:text-slate-300 leading-relaxed">${r.description}</p></div>
                    ${r.resolution_notes?`<div><p class="text-slate-400 mb-1">Resolution Notes:</p><p class="bg-slate-50 dark:bg-slate-800 rounded-lg px-3 py-2 text-slate-600 dark:text-slate-300">${r.resolution_notes}</p></div>`:''}
                </div>
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4">
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">Documents (${r.documents.length})</p>
                    <div class="space-y-2">${docs}</div>
                </div>
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4 text-xs space-y-1.5">
                    <p class="font-semibold text-slate-600 dark:text-slate-300 mb-2">Audit Trail</p>
                    <div class="flex gap-3"><span class="text-slate-400 w-28">Recorded By:</span><span class="font-medium text-slate-700 dark:text-slate-300">${r.recorded_by}</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-28">Created:</span><span class="font-medium text-slate-700 dark:text-slate-300">${r.created_at}</span></div>
                    <div class="flex gap-3"><span class="text-slate-400 w-28">Last Updated:</span><span class="font-medium text-slate-700 dark:text-slate-300">${r.updated_at}</span></div>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                    <button onclick="brCloseModal('view-modal'); printRecord(${r.id})"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold transition-colors">
                        <iconify-icon icon="solar:printer-bold" width="14"></iconify-icon> PRINT PDF
                    </button>
                    <button onclick="brCloseModal('view-modal')"
                        class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CLOSE</button>
                </div>
            </div>`;
        }).catch(() => {
            document.getElementById('view-content').innerHTML = '<p class="text-xs text-red-500 text-center py-8">Failed to load record.</p>';
        });
}

// ── Stage 7: Edit Record ──────────────────────────────────
function editRecord(id) {
    _recordId = id;
    fetch(`${BASE_URL}/${id}`)
        .then(r => r.json())
        .then(({record:r}) => {
            document.getElementById('edit-incident-date').value = r.incident_date_raw ?? '';
            setSelectVal('edit-behavior-type', r.behavior_type);
            setSelectVal('edit-severity',      r.severity);
            setSelectVal('edit-action-taken',  r.action_taken);
            setSelectVal('edit-referral',      r.referral_to === '—' ? 'None' : r.referral_to);
            setSelectVal('edit-status',        r.status);
            document.getElementById('edit-description').value    = r.description       ?? '';
            document.getElementById('edit-action-details').value = r.action_details    ?? '';
            document.getElementById('edit-resolution').value     = r.resolution_notes  ?? '';
            document.getElementById('edit-doc-label').textContent = 'No file chosen';
            brOpenModal('edit-modal');
        }).catch(() => showToast('Failed to load record.','error'));
}

function setSelectVal(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    [...el.options].forEach(o => { o.selected = (o.value === val || o.text === val); });
}

function submitEditRecord(e) {
    e.preventDefault();
    const fd = new FormData(document.getElementById('edit-form'));
    fd.append('_method','PUT');
    const docFile = document.getElementById('edit-doc-file').files[0];
    if (docFile) fd.set('document', docFile);

    fetch(`${BASE_URL}/${_recordId}`, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF}, body:fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) { brCloseModal('edit-modal'); showToast(data.message); setTimeout(()=>location.reload(),1400); }
            else showToast(data.message||'Update failed.','error');
        }).catch(() => showToast('Request failed.','error'));
}

// ── Stage 8: Upload Document ──────────────────────────────
function uploadDoc(id) {
    _recordId = id;
    document.getElementById('upload-file').value   = '';
    document.getElementById('upload-file-label').textContent = 'PDF, DOC, DOCX, JPG, PNG · Max 5MB';
    document.getElementById('upload-desc').value   = '';
    brOpenModal('upload-modal');
}

function confirmUploadDoc() {
    const file = document.getElementById('upload-file').files[0];
    if (!file) { showToast('Please choose a file.','error'); return; }
    const fd = new FormData();
    fd.append('document',    file);
    fd.append('description', document.getElementById('upload-desc').value);

    fetch(`${BASE_URL}/${_recordId}/upload`, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF}, body:fd })
        .then(r => r.json())
        .then(data => { brCloseModal('upload-modal'); showToast(data.message, data.success?'success':'error'); })
        .catch(() => showToast('Upload failed.','error'));
}

// ── Stage 9: View Documents ───────────────────────────────
function viewDocs(id) {
    _recordId = id;
    document.getElementById('docs-list').innerHTML = '<div class="text-xs text-slate-400 text-center py-4">Loading...</div>';
    brOpenModal('docs-modal');

    fetch(`${BASE_URL}/${id}`)
        .then(r => r.json())
        .then(({record}) => {
            const list = document.getElementById('docs-list');
            if (!record.documents.length) {
                list.innerHTML = '<p class="text-xs text-slate-400 text-center py-6">No documents uploaded for this record.</p>';
                return;
            }
            list.innerHTML = record.documents.map(d => `
                <div class="flex items-center justify-between rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                    <div>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">${d.file_name}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">${d.file_size} · ${d.created_at}${d.description?' · '+d.description:''}</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="${d.download_url}" class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition-colors" title="Download">
                            <iconify-icon icon="solar:download-bold" width="13"></iconify-icon>
                        </a>
                        <button onclick="deleteDoc(${d.id})" class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-500 transition-colors" title="Delete">
                            <iconify-icon icon="solar:trash-bin-trash-bold" width="13"></iconify-icon>
                        </button>
                    </div>
                </div>`).join('');
        });
}

function deleteDoc(docId) {
    if (!confirm('Delete this document permanently?')) return;
    fetch(`${DOC_BASE}/${docId}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} })
        .then(r => r.json())
        .then(data => { showToast(data.message, data.success?'success':'error'); if (data.success) viewDocs(_recordId); });
}

// ── Stage 10: Update Status ───────────────────────────────
function updateStatus(id, current) {
    _recordId = id;
    const el  = document.getElementById('status-current');
    el.textContent  = current.charAt(0).toUpperCase() + current.slice(1);
    el.className = 'font-semibold rounded-full px-2.5 py-0.5 '
        + ({'pending':'bg-yellow-100 text-yellow-700','resolved':'bg-green-100 text-green-700','dismissed':'bg-slate-100 text-slate-500','escalated':'bg-red-100 text-red-700'}[current] || '');
    document.getElementById('status-new').value   = current;
    document.getElementById('status-notes').value = '';
    document.getElementById('status-notify').checked = false;
    brOpenModal('status-modal');
}

function confirmUpdateStatus() {
    const status = document.getElementById('status-new').value;
    const notes  = document.getElementById('status-notes').value.trim();
    const notify = document.getElementById('status-notify').checked;

    if (['resolved','dismissed'].includes(status) && !notes) {
        showToast('Resolution notes are required for Resolved or Dismissed.','error'); return;
    }
    fetch(`${BASE_URL}/${_recordId}/status`, {
        method:'PATCH', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({status, resolution_notes:notes, notify_parent:notify}),
    }).then(r=>r.json()).then(data => {
        brCloseModal('status-modal');
        showToast(data.message, data.success?'success':'error');
        if (data.success) setTimeout(()=>location.reload(),1400);
    }).catch(()=>showToast('Request failed.','error'));
}

// ── Stage 12: Print PDF ───────────────────────────────────
function printRecord(id) {
    document.getElementById('print-content').innerHTML = '<div class="py-8 text-center text-xs text-slate-400">Loading preview...</div>';
    brOpenModal('print-modal');

    fetch(`${BASE_URL}/${id}`)
        .then(r=>r.json())
        .then(({record:r}) => {
            document.getElementById('print-content').innerHTML = `
            <div class="space-y-4 print:block" id="print-area">
                <div class="text-center border-b-2 border-slate-800 pb-4 mb-4">
                    <p class="text-lg font-bold text-slate-800">MY MESSIAH SCHOOL OF CAVITE</p>
                    <p class="text-xs text-slate-500">Dasmariñas, Cavite</p>
                    <p class="text-sm font-bold text-slate-700 mt-2">STUDENT BEHAVIORAL RECORD</p>
                    <p class="text-xs text-slate-500">School Year ${r.school_year}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 text-xs border border-slate-200 rounded-lg p-4">
                    <div><strong>Student ID:</strong> ${r.student_id}</div>
                    <div><strong>Student Name:</strong> ${r.student_name}</div>
                    <div><strong>Grade & Section:</strong> ${r.grade}</div>
                    <div><strong>School Year:</strong> SY ${r.school_year}</div>
                </div>
                <div class="text-xs border border-slate-200 rounded-lg p-4 space-y-2">
                    <p class="font-bold text-slate-700 uppercase text-[10px] tracking-wide">Incident Information</p>
                    <div><strong>Date:</strong> ${r.incident_date}</div>
                    <div><strong>Behavior Type:</strong> ${r.behavior_type}</div>
                    <div><strong>Severity:</strong> ${r.severity}</div>
                    <div><strong>Action Taken:</strong> ${r.action_taken}</div>
                    <div><strong>Referral To:</strong> ${r.referral_to}</div>
                    <div><strong>Status:</strong> ${r.status.charAt(0).toUpperCase()+r.status.slice(1)}</div>
                </div>
                <div class="text-xs border border-slate-200 rounded-lg p-4 space-y-2">
                    <p class="font-bold text-slate-700 uppercase text-[10px] tracking-wide">Description of Incident</p>
                    <p class="leading-relaxed text-slate-700">${r.description}</p>
                    ${r.action_details?`<p><strong>Action Details:</strong> ${r.action_details}</p>`:''}
                    ${r.resolution_notes?`<p><strong>Resolution Notes:</strong> ${r.resolution_notes}</p>`:''}
                </div>
                <div class="text-xs border border-slate-200 rounded-lg p-4 mt-4">
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div><div class="border-t border-slate-700 mt-8 pt-1 text-center"><p class="font-semibold">Recorded By</p><p>${r.recorded_by}</p></div></div>
                        <div><div class="border-t border-slate-700 mt-8 pt-1 text-center"><p class="font-semibold">Date</p><p>${r.created_at}</p></div></div>
                    </div>
                </div>
            </div>`;
        });
}

// ── Stage 13: Send Notice (single) ────────────────────────
function sendSingleNotice(id, name, parentEmail, studentEmail) {
    _noticeIds = [id];
    document.getElementById('notice-title').textContent       = 'SEND NOTICE';
    document.getElementById('notice-to-student').textContent  = name;
    document.getElementById('notice-to-parent').textContent   = 'Parent: ' + (parentEmail  || '— no email on file —');
    document.getElementById('notice-to-school').textContent   = 'Student: ' + (studentEmail || '— no email on file —');
    resetNoticeForm();
    brOpenModal('notice-modal');
}

function resetNoticeForm() {
    document.getElementById('notice-type').value    = '';
    document.getElementById('notice-subject').value = '';
    document.getElementById('notice-message').value = '';
}

const TEMPLATES = {
    'Behavior Notification': {
        subject: 'Behavioral Incident Notification',
        message: `Dear Parent/Guardian,\n\nWe are writing to inform you of a behavioral incident involving your child at My Messiah School of Cavite. A formal record has been filed regarding this matter.\n\nWe encourage you to discuss this with your child and contact the school for any concerns.\n\nRespectfully,\nSchool Administration`,
    },
    'Parent Conference': {
        subject: 'Request for Parent Conference',
        message: `Dear Parent/Guardian,\n\nWe respectfully request your presence at a parent conference to discuss your child's behavior at school.\n\nPlease coordinate with the school office to schedule a convenient time.\n\nRespectfully,\nSchool Administration`,
    },
    'Warning Letter': {
        subject: 'Formal Warning Notice',
        message: `Dear Parent/Guardian,\n\nThis letter serves as a formal warning regarding your child's conduct. Continued behavioral violations may result in further disciplinary action.\n\nWe urge your cooperation in addressing this matter promptly.\n\nRespectfully,\nSchool Administration`,
    },
    'Suspension Notice': {
        subject: 'Suspension Notice',
        message: `Dear Parent/Guardian,\n\nWe regret to inform you that your child has been suspended from school due to a serious violation of school rules and regulations.\n\nPlease report to the Principal's Office to discuss the terms of reinstatement before your child returns.\n\nRespectfully,\nSchool Administration`,
    },
};

function prefillTemplate(type) {
    const t = TEMPLATES[type];
    if (t) {
        document.getElementById('notice-subject').value = t.subject;
        document.getElementById('notice-message').value = t.message;
    }
}

function confirmSendNotice() {
    const type    = document.getElementById('notice-type').value;
    const subject = document.getElementById('notice-subject').value.trim();
    const message = document.getElementById('notice-message').value.trim();
    const sendTo  = [...document.querySelectorAll('input[name="send_to[]"]:checked')].map(cb=>cb.value);

    if (!type)         { showToast('Please select a notice type.','error'); return; }
    if (!subject)      { showToast('Please enter a subject.','error'); return; }
    if (!message)      { showToast('Please enter a message.','error'); return; }
    if (!sendTo.length){ showToast('Please select at least one recipient.','error'); return; }

    fetch('{{ route("admin.student-records.behavioral.send-notice") }}', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({record_ids:_noticeIds, notice_type:type, subject, message, send_to:sendTo}),
    }).then(r=>r.json()).then(data => {
        brCloseModal('notice-modal');
        showToast(data.message, data.success?'success':'error');
    }).catch(()=>showToast('Request failed.','error'));
}

// ── Stage 5: Delete Record ────────────────────────────────
function deleteRecord(id) {
    if (!confirm('Permanently delete this behavioral record? This cannot be undone.')) return;
    fetch(`${BASE_URL}/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} })
        .then(r=>r.json())
        .then(data => { showToast(data.message, data.success?'success':'error'); if (data.success) setTimeout(()=>location.reload(),1400); })
        .catch(()=>showToast('Request failed.','error'));
}
</script>
@endpush
@endsection