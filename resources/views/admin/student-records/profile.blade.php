@extends('layouts.admin_layout')
@section('title', 'Student Profile — {{ $student->full_name ?? "Student" }}')

@section('content')
@php
    // $enrollment is passed from controller (latestEnrollment)
    $gradeSection = $enrollment?->section_name
        ? \App\Models\Section::formatName($student->grade_level ?? '—', $enrollment->section_name, $enrollment->strand)
        : ($student->grade_level ?? '—');

    // ── Profile completion ──────────────────────────────────
    $profileFields = [
        'lrn'              => ['label' => 'LRN',                'type' => 'text'],
        'date_of_birth'    => ['label' => 'Date of Birth',      'type' => 'date'],
        'place_of_birth'   => ['label' => 'Place of Birth',     'type' => 'text'],
        'nationality'      => ['label' => 'Nationality',        'type' => 'text'],
        'mother_tongue'    => ['label' => 'Mother Tongue',      'type' => 'text'],
        'religion'         => ['label' => 'Religion',           'type' => 'text'],
        'mobile_number'    => ['label' => 'Contact Number',     'type' => 'text'],
        'personal_email'   => ['label' => 'Personal Email',     'type' => 'email'],
        'home_address'     => ['label' => 'House Address',      'type' => 'text'],
        'city'             => ['label' => 'City / Municipality','type' => 'text'],
        'province'         => ['label' => 'Province',           'type' => 'text'],
        'zip_code'         => ['label' => 'ZIP Code',           'type' => 'text'],
        'father_name'      => ['label' => "Father's Name",      'type' => 'text'],
        'father_contact'   => ['label' => "Father's Contact",   'type' => 'text'],
        'mother_maiden_name'=> ['label' => "Mother's Maiden Name",'type' => 'text'],
        'mother_contact'   => ['label' => "Mother's Contact",   'type' => 'text'],
        'guardian_name'       => ['label' => 'Guardian Name',         'type' => 'text'],
        'guardian_relationship'=> ['label' => 'Guardian Relationship','type' => 'text'],
        'guardian_contact'    => ['label' => 'Guardian Contact',    'type' => 'text'],
        'guardian_email'      => ['label' => 'Guardian Email',      'type' => 'email'],
        'guardian_address'    => ['label' => 'Guardian Address',    'type' => 'text'],
        'guardian_occupation' => ['label' => 'Guardian Occupation', 'type' => 'text'],
    ];
    $totalFields   = count($profileFields);
    $missingFields = array_filter($profileFields, fn($_, $key) => empty($student->$key), ARRAY_FILTER_USE_BOTH);
    $missingCount  = count($missingFields);
    $filledCount   = $totalFields - $missingCount;
    $isComplete    = $missingCount === 0;

    // ── Status badge classes ────────────────────────────────
    $clrBadge = match($student->clearance_status ?? 'pending') {
        'cleared' => 'bg-green-100 text-green-700',
        'overdue' => 'bg-red-100 text-red-700',
        default   => 'bg-yellow-100 text-yellow-700',
    };
    $stsBadge = match($student->student_status ?? 'active') {
        'active'    => 'bg-green-100 text-green-700',
        'inactive'  => 'bg-orange-100 text-orange-700',
        'withdrawn' => 'bg-red-100 text-red-700',
        'graduated' => 'bg-blue-100 text-blue-700',
        'completed' => 'bg-purple-100 text-purple-700',
        default     => 'bg-slate-100 text-slate-600',
    };
    $acaBadge = match($student->academic_status ?? 'in_progress') {
        'passed'      => 'bg-green-100 text-green-700',
        'failed'      => 'bg-red-100 text-red-700',
        default       => 'bg-blue-100 text-blue-700',
    };
@endphp
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Student Records"
        subtitle="Student Record and Information"
        school-year="{{ $student->school_year ?? $activeSchoolYear }}"
    />

    {{-- Back --}}
    <div class="mb-4">
        <a href="{{ route('admin.student-records.list') }}"
           class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 transition-colors">
            <iconify-icon icon="solar:alt-arrow-left-linear" width="14"></iconify-icon>
            Back to Student List
        </a>
    </div>

    {{-- Two-column layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-6 items-start">

        {{-- ═══ LEFT PANEL ═══ --}}
        <div class="flex flex-col gap-4">

            {{-- Avatar + Name + Action Buttons --}}
            <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-6 flex flex-col items-center gap-4">

                <div class="relative mt-1">
                    <div class="flex h-28 w-28 items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden ring-4 ring-slate-100 dark:ring-slate-700">
                        <iconify-icon icon="solar:user-bold" width="64" class="text-slate-400 dark:text-slate-500 mt-4"></iconify-icon>
                    </div>
                    <button class="absolute bottom-1 right-1 flex h-7 w-7 items-center justify-center rounded-full bg-slate-600 text-white shadow hover:bg-slate-700 transition-colors">
                        <iconify-icon icon="solar:pen-bold" width="13"></iconify-icon>
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm font-bold text-slate-800 dark:text-white uppercase">{{ $student->full_name }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $student->school_email ?? $student->personal_email ?? '—' }}</p>
                </div>

                <div class="w-full flex flex-col gap-2 pt-1">
                    <button class="flex items-center justify-center gap-2 w-full rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 dark:border-red-900/30 dark:bg-red-900/10 px-4 py-2.5 text-xs font-semibold text-red-600 dark:text-red-400 transition-colors">
                        <iconify-icon icon="solar:forbidden-bold" width="14"></iconify-icon>
                        Deactivate Account
                    </button>
                    <button class="flex items-center justify-center gap-2 w-full rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors">
                        <iconify-icon icon="solar:key-bold" width="14" class="text-slate-500"></iconify-icon>
                        Reset Password
                    </button>
                    <button class="flex items-center justify-center gap-2 w-full rounded-xl bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2.5 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:letter-bold" width="14"></iconify-icon>
                        Send Email Notice
                    </button>
                </div>
            </div>

            {{-- Current Academic Information --}}
            <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-5">
                <div class="flex items-center gap-2 mb-3">
                    <iconify-icon icon="solar:clipboard-list-bold" width="15" class="text-slate-500 dark:text-slate-400"></iconify-icon>
                    <h3 class="text-xs font-bold text-slate-700 dark:text-white">Current Academic Information</h3>
                </div>

                {{-- Student ID emphasized --}}
                <div class="mb-3 rounded-xl bg-[#0d4c8f]/5 dark:bg-blue-900/20 border border-[#0d4c8f]/20 px-3 py-2.5 flex items-center justify-between">
                    <span class="text-[10px] font-semibold uppercase tracking-widest text-[#0d4c8f]/60 dark:text-blue-300/60">Student ID</span>
                    <span class="text-sm font-bold font-mono text-[#0d4c8f] dark:text-blue-300 tracking-wide">{{ $student->student_id }}</span>
                </div>

                <div class="flex flex-col">
                    @foreach([
                        ['School Year',      $student->school_year ?? '—'],
                        ['Grade and Section', $gradeSection],
                        ['Enrolled Date',    $student->enrollment_date?->format('m/d/y') ?? ($student->enrolled_at?->format('m/d/y') ?? '—')],
                        ['Student Status',   ucfirst($student->student_status ?? 'active')],
                        ['Academic Status',  ucfirst(str_replace('_',' ', $student->academic_status ?? 'in_progress'))],
                    ] as $row)
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-dark-border last:border-0">
                        <span class="text-xs text-slate-400 dark:text-slate-500">{{ $row[0] }}</span>
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $row[1] }}</span>
                    </div>
                    @endforeach
                </div>

                <button onclick="openWithdrawModal()"
                    class="mt-4 flex items-center justify-center gap-2 w-full rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 dark:border-red-900/30 dark:bg-red-900/10 px-4 py-2.5 text-xs font-semibold text-red-600 dark:text-red-400 transition-colors">
                    <iconify-icon icon="solar:logout-3-bold" width="14"></iconify-icon>
                    Withdraw Student
                </button>
            </div>

        </div>

        {{-- ═══ RIGHT: Tabs ═══ --}}
        <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden"
             x-data="{ tab: (new URLSearchParams(location.search)).get('tab') || 'profile' }">

            {{-- Tab Bar --}}
            <div class="flex border-b border-slate-200 dark:border-dark-border">
                @foreach([['profile','Profile'],['academics','Academics'],['grades','Grades'],['records','Records']] as $t)
                <button @click="tab = '{{ $t[0] }}'"
                    :class="tab === '{{ $t[0] }}' ? 'border-b-2 border-[#0d4c8f] text-[#0d4c8f]' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 border-b-2 border-transparent'"
                    class="px-6 py-3.5 text-xs font-semibold transition-all whitespace-nowrap">
                    {{ $t[1] }}
                </button>
                @endforeach
            </div>

            {{-- ══ PROFILE TAB ══ --}}
            <div x-show="tab === 'profile'" x-cloak class="p-6">

                {{-- Profile completion bar --}}
                <div class="flex items-center justify-between gap-3 mb-5 flex-wrap">
                    <div class="flex items-center gap-3">
                        @if($isComplete)
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 text-green-700 px-2.5 py-0.5 text-xs font-semibold">
                            <iconify-icon icon="solar:check-circle-bold" width="12"></iconify-icon> Profile Complete
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 px-2.5 py-0.5 text-xs font-semibold">
                            <iconify-icon icon="solar:danger-triangle-bold" width="12"></iconify-icon>
                            Missing details: {{ $missingCount }}/{{ $totalFields }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="openCompleteProfileModal()"
                            class="flex items-center gap-1.5 rounded-lg {{ $isComplete ? 'border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 text-slate-600 dark:text-slate-300' : 'border border-[#0d4c8f] bg-white hover:bg-blue-50 text-[#0d4c8f]' }} px-3 py-1.5 text-xs font-semibold transition-colors">
                            <iconify-icon icon="solar:add-circle-bold" width="13"></iconify-icon>
                            Complete Profile Details
                        </button>
                      
                    </div>
                </div>

                {{-- Student Information --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <iconify-icon icon="solar:user-bold" width="15" class="text-slate-500"></iconify-icon>
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Student Information</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-x-8 gap-y-5">

                        <div><p class="text-xs text-slate-400 mb-0.5">First Name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->first_name ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">LRN</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->lrn ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">City / Municipality</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->city ?? '—' }}</p></div>

                        <div><p class="text-xs text-slate-400 mb-0.5">Last Name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->last_name ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Mother Tongue</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->mother_tongue ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Province</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->province ?? '—' }}</p></div>

                        <div><p class="text-xs text-slate-400 mb-0.5">Middle Name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->middle_name ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Nationality</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->nationality ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">ZIP Code</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->zip_code ?? '—' }}</p></div>

                        <div><p class="text-xs text-slate-400 mb-0.5">Suffix</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->suffix ?? 'N/A' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Religion</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->religion ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Contact Number</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->mobile_number ?? '—' }}</p></div>

                        <div><p class="text-xs text-slate-400 mb-0.5">Gender</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->gender ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Civil Status</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->civil_status ?? 'Single' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Email</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate">{{ $student->personal_email ?? '—' }}</p></div>

                        <div><p class="text-xs text-slate-400 mb-0.5">Date of Birth</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('F d, Y') : '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">House Address</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->home_address ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Place of Birth</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->place_of_birth ?? '—' }}</p></div>

                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-dark-border mb-6"></div>

                {{-- Parent / Guardian Information --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <iconify-icon icon="solar:users-group-rounded-bold" width="15" class="text-slate-500"></iconify-icon>
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Parent/Guardian Information</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-x-8 gap-y-5">

                        <div><p class="text-xs text-slate-400 mb-0.5">Fathers Name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->father_name ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Fathers Contact</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->father_contact ?? '—' }}</p></div>
                        <div></div>

                        <div><p class="text-xs text-slate-400 mb-0.5">Mothers Maiden Name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->mother_maiden_name ?? $student->mother_name ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Mothers Contact</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->mother_contact ?? '—' }}</p></div>
                        <div></div>

                        <div class="col-span-3 border-t border-slate-100 dark:border-dark-border"></div>

                        <div><p class="text-xs text-slate-400 mb-0.5">Guardian Name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->guardian_name ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Guardians Contact</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->guardian_contact ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Address</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->guardian_address ?? '—' }}</p></div>

                        <div><p class="text-xs text-slate-400 mb-0.5">Relationship</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->guardian_relationship ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Occupation</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->guardian_occupation ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Guardian Email</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate">{{ $student->guardian_email ?? '—' }}</p></div>

                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-dark-border mb-6"></div>

                {{-- Academic Information --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <iconify-icon icon="solar:diploma-bold" width="15" class="text-slate-500"></iconify-icon>
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Academic Information</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-x-8 gap-y-5">

                        <div><p class="text-xs text-slate-400 mb-0.5">Admission Type</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->admission_type ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Enrolled Date</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->enrollment_date?->format('m/d/Y') ?? ($student->enrolled_at?->format('m/d/Y') ?? '—') }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Student Type</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ ucfirst($enrollment?->student_type ?? 'regular') }}</p></div>

                        <div><p class="text-xs text-slate-400 mb-0.5">Track</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $enrollment?->track ?? $student->track ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Strand/Path</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $enrollment?->strand ?? $student->strand ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Student Category</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $student->student_category ?? '—' }}</p></div>

                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Clearance Status</p>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $clrBadge }}">
                                {{ ucfirst($student->clearance_status ?? 'pending') }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Academic Status</p>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $acaBadge }}">
                                {{ ucfirst(str_replace('_',' ', $student->academic_status ?? 'in_progress')) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Student Status</p>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $stsBadge }}">
                                {{ ucfirst($student->student_status ?? 'active') }}
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-dark-border">
                    <p class="text-xs text-slate-400">
                        Last Edited:
                        {{ $student->updated_at ? $student->updated_at->format('F d, Y') : '—' }}
                    </p>
                    <div class="flex items-center gap-2">
                        <button onclick="openCompleteProfileModal()"
                            class="flex items-center gap-2 rounded-xl bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                            <iconify-icon icon="solar:add-circle-bold" width="13"></iconify-icon>
                            Complete Profile Details
                            @if($missingCount > 0)
                            <span class="ml-1 inline-flex items-center justify-center rounded-full bg-white/20 text-white text-[10px] font-bold px-1.5 py-0.5 min-w-[18px]">{{ $missingCount }}</span>
                            @endif
                        </button>
                        <button onclick="openEditDetailsModal()"
                            class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                            <iconify-icon icon="solar:pen-bold" width="13"></iconify-icon>
                            Edit Details
                        </button>
                        <button class="flex items-center gap-2 rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                            <iconify-icon icon="solar:printer-bold" width="13"></iconify-icon>
                            Print Record
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══ ACADEMICS TAB ══ --}}
            <div x-show="tab === 'academics'" x-cloak class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    @foreach([
                        ['School Year', $student->school_year ?? '—'],
                        ['Section', $enrollment?->section_name ?? '—'],
                        ['Grade', $student->grade_level ?? '—'],
                        ['Class Adviser', $enrollment?->section?->homeroom_adviser_name ?? '—'],
                    ] as $r)
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 dark:text-slate-400 w-28 shrink-0">{{ $r[0] }}:</span>
                        <span class="flex-1 border-b border-slate-300 dark:border-slate-600 text-xs font-medium text-slate-700 dark:text-slate-300 pb-0.5 text-center">{{ $r[1] }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="rounded-xl border border-slate-200 dark:border-dark-border px-5 py-8 text-center text-xs text-slate-400">
                    <iconify-icon icon="solar:calendar-search-bold" width="28" class="text-slate-300 mb-2 block mx-auto"></iconify-icon>
                    Class schedule will appear here once subjects are assigned.
                </div>
            </div>

            {{-- ══ GRADES TAB ══ --}}
            <div x-show="tab === 'grades'" x-cloak class="p-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">REPORT CARD</h3>
                <div class="rounded-xl border border-slate-200 dark:border-dark-border px-5 py-8 text-center text-xs text-slate-400">
                    <iconify-icon icon="solar:diploma-linear" width="28" class="text-slate-300 mb-2 block mx-auto"></iconify-icon>
                    Grades will appear here once teachers submit them.
                </div>
            </div>

            {{-- ══ RECORDS TAB ══ --}}
            <div x-show="tab === 'records'" x-cloak class="p-6" x-data="{ open: 'requirements' }">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Records</h3>

                {{-- Admission Requirements --}}
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 mb-3 overflow-hidden">
                    <button @click="open = open === 'requirements' ? '' : 'requirements'"
                        class="w-full flex items-center justify-between px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <span>Records Office — Admission Requirements</span>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="14" :class="open === 'requirements' ? 'rotate-180' : ''" class="transition-transform duration-200 text-slate-400"></iconify-icon>
                    </button>
                    <div x-show="open === 'requirements'" x-transition>
                        <div class="px-4 py-4 border-t border-slate-100 dark:border-slate-700"
                             x-data="profileDocs({{ $application ? $application->id : 'null' }})">
                            @if($application)
                            @php
                                $docList = [
                                    ['key' => 'psa',         'label' => 'NSO / PSA Birth Certificate', 'required' => true,  'status' => $application->psa_status ?? 'not_uploaded',        'submitted' => (bool)$application->psa_submitted,        'path' => $application->psa_path ?? null],
                                    ['key' => 'report_card', 'label' => 'Form 137 / Report Card',      'required' => true,  'status' => $application->report_card_status ?? 'not_uploaded', 'submitted' => (bool)$application->report_card_submitted, 'path' => $application->report_card_path ?? null],
                                    ['key' => 'good_moral',  'label' => 'Good Moral Certificate',      'required' => true,  'status' => $application->good_moral_status ?? 'not_uploaded',  'submitted' => (bool)$application->good_moral_submitted,  'path' => $application->good_moral_path ?? null],
                                    ['key' => 'medical',     'label' => 'Medical Certificate',         'required' => false, 'status' => $application->medical_status ?? 'not_uploaded',     'submitted' => (bool)$application->medical_submitted,     'path' => $application->medical_path ?? null],
                                ];
                                $badgeCls = [
                                    'approved'     => ['label' => 'Approved',     'class' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'],
                                    'pending'      => ['label' => 'Pending',      'class' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'],
                                    'not_uploaded' => ['label' => 'Not Uploaded', 'class' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'],
                                ];
                            @endphp
                            <div class="space-y-2">
                                @foreach($docList as $doc)
                                @php
                                    $hasFile    = !empty($doc['path']);
                                    $isPhysical = !$hasFile && $doc['submitted'];
                                    $isEmpty    = !$hasFile && !$doc['submitted'];
                                    $badge      = $badgeCls[$doc['status']] ?? $badgeCls['not_uploaded'];
                                @endphp
                                <div class="rounded-lg border border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-white/[0.02] px-3 py-3">

                                    {{-- Row: label + status badge + actions --}}
                                    <div class="flex items-center justify-between flex-wrap gap-2">

                                        {{-- Left: icon + name + badge --}}
                                        <div class="flex items-center gap-2 min-w-0 flex-wrap">
                                            <iconify-icon icon="solar:document-{{ $hasFile ? 'bold' : 'linear' }}" width="14"
                                                class="{{ $hasFile ? 'text-[#0d4c8f]' : 'text-slate-400' }} shrink-0"></iconify-icon>
                                            <span class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $doc['label'] }}</span>
                                            @if(!$doc['required'])
                                            <span class="text-[10px] text-slate-400">(optional)</span>
                                            @endif
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $badge['class'] }}">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>

                                        {{-- Right: context-aware actions --}}
                                        <div class="flex items-center gap-2 shrink-0 flex-wrap">

                                            @if($hasFile)
                                            {{-- ── STATE 1: File uploaded ── --}}
                                            <a href="{{ Storage::url($doc['path']) }}" target="_blank"
                                               class="flex items-center gap-1 text-[10px] font-medium text-[#0d4c8f] dark:text-blue-400 hover:underline border border-blue-200 dark:border-blue-800 rounded px-2 py-1">
                                                <iconify-icon icon="solar:eye-linear" width="11"></iconify-icon> View
                                            </a>
                                            <label class="flex items-center gap-1 text-[10px] text-slate-500 dark:text-slate-400 cursor-pointer border border-dashed border-slate-300 dark:border-slate-600 rounded px-2 py-1 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                                                <iconify-icon icon="solar:refresh-linear" width="11"></iconify-icon> Upload New
                                                <input type="file" class="hidden" data-key="{{ $doc['key'] }}"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    @change="handleFileSelected($event, '{{ $doc['key'] }}')">
                                            </label>

                                            @elseif($isPhysical)
                                            {{-- ── STATE 2: Physically submitted, no digital copy ── --}}
                                            <span class="flex items-center gap-1 text-[10px] text-amber-600 dark:text-amber-400">
                                                <iconify-icon icon="solar:danger-triangle-linear" width="11"></iconify-icon> No digital copy
                                            </span>
                                            <label class="flex items-center gap-1 text-[10px] text-slate-500 dark:text-slate-400 cursor-pointer border border-dashed border-slate-300 dark:border-slate-600 rounded px-2 py-1 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                                                <iconify-icon icon="solar:upload-linear" width="11"></iconify-icon> Upload
                                                <input type="file" class="hidden" data-key="{{ $doc['key'] }}"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    @change="handleFileSelected($event, '{{ $doc['key'] }}')">
                                            </label>
                                            @if($doc['status'] !== 'approved')
                                            <button @click="quickApprove('{{ $doc['key'] }}')"
                                                class="flex items-center gap-1 text-[10px] font-semibold text-green-700 dark:text-green-400 border border-green-300 dark:border-green-700 rounded px-2 py-1 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                                                <iconify-icon icon="solar:check-circle-linear" width="11"></iconify-icon> Approve
                                            </button>
                                            @endif

                                            @else
                                            {{-- ── STATE 3: Nothing uploaded at all ── --}}
                                            <label class="flex items-center gap-1.5 text-[10px] text-slate-600 dark:text-slate-400 cursor-pointer">
                                                <input type="checkbox" data-key="{{ $doc['key'] }}"
                                                    class="rounded border-slate-300 text-[#0d4c8f]"
                                                    @change="markSubmitted($event, '{{ $doc['key'] }}')">
                                                Mark as Submitted
                                            </label>
                                            <label class="flex items-center gap-1 text-[10px] text-slate-500 dark:text-slate-400 cursor-pointer border border-dashed border-slate-300 dark:border-slate-600 rounded px-2 py-1 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                                                <iconify-icon icon="solar:upload-linear" width="11"></iconify-icon> Upload Document
                                                <input type="file" class="hidden" data-key="{{ $doc['key'] }}"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    @change="handleFileSelected($event, '{{ $doc['key'] }}')">
                                            </label>
                                            @if($doc['status'] !== 'approved')
                                            <button @click="quickApprove('{{ $doc['key'] }}')"
                                                class="flex items-center gap-1 text-[10px] font-semibold text-green-700 dark:text-green-400 border border-green-300 dark:border-green-700 rounded px-2 py-1 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                                                <iconify-icon icon="solar:check-circle-linear" width="11"></iconify-icon> Approve
                                            </button>
                                            @endif
                                            @endif

                                        </div>
                                    </div>

                                    {{-- Sub-row notices --}}
                                    @if($isPhysical && $doc['status'] === 'approved')
                                    <p class="mt-1.5 text-[10px] text-amber-600 dark:text-amber-400">
                                        <iconify-icon icon="solar:info-circle-linear" width="11"></iconify-icon>
                                        Cleared but no digital copy on file. Consider uploading for full records.
                                    </p>
                                    @endif
                                    <p x-show="pendingFiles['{{ $doc['key'] }}']"
                                       x-text="'Ready to upload: ' + (pendingFiles['{{ $doc['key'] }}'] || '')"
                                       class="mt-1.5 text-[10px] text-blue-600 dark:text-blue-400 font-medium"></p>

                                </div>
                                @endforeach
                            </div>

                            {{-- Save Changes bar --}}
                            <div x-show="hasPending" class="mt-3 flex items-center justify-between rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 px-4 py-2.5">
                                <span class="text-xs text-blue-700 dark:text-blue-300 font-medium">You have unsaved document changes.</span>
                                <button @click="saveAll()" :disabled="saving"
                                    class="rounded-lg bg-[#0d4c8f] hover:bg-blue-800 disabled:opacity-60 px-4 py-1.5 text-xs font-semibold text-white transition-colors"
                                    x-text="saving ? 'Saving...' : 'Save Changes'">
                                </button>
                            </div>

                            @else
                            <p class="text-xs text-slate-400">No submitted requirements on file.</p>
                            @endif
                        </div>
                    </div>
                </div>

                @foreach(['fees'=>'Fees Collected — Finance','library'=>'Library','behavioral'=>'Behavioral Record'] as $key => $label)
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 mb-3 overflow-hidden">
                    <button @click="open = open === '{{ $key }}' ? '' : '{{ $key }}'"
                        class="w-full flex items-center justify-between px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <span>{{ $label }}</span>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="14" :class="open === '{{ $key }}' ? 'rotate-180' : ''" class="transition-transform duration-200 text-slate-400"></iconify-icon>
                    </button>
                    <div x-show="open === '{{ $key }}'" x-transition>
                        <div class="px-4 py-4 text-xs text-slate-400 border-t border-slate-100 dark:border-slate-700">No records available.</div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>{{-- end right --}}
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══ EDIT DETAILS MODAL ══ --}}
<div id="edit-details-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditDetailsModal()"></div>
    <div class="relative w-full max-w-3xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl flex flex-col" style="max-height:90vh">

        <div class="bg-slate-800 px-6 py-4 flex items-center justify-between rounded-t-2xl shrink-0">
            <div>
                <h3 class="text-white text-sm font-bold">EDIT PROFILE DETAILS</h3>
                <p class="text-slate-400 text-xs mt-0.5">{{ $student->full_name }} · {{ $student->student_id }}</p>
            </div>
            <button onclick="closeEditDetailsModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 text-sm">✕</button>
        </div>

        <div class="overflow-y-auto flex-1 px-6 py-5 space-y-6">

            {{-- Student Information --}}
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Student Information</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach([
                        ['first_name',    'First Name',          'text',  true],
                        ['middle_name',   'Middle Name',         'text',  false],
                        ['last_name',     'Last Name',           'text',  true],
                        ['suffix',        'Suffix',              'text',  false],
                        ['gender',        'Gender',              'select',true,  ['Male','Female']],
                        ['date_of_birth', 'Date of Birth',       'date',  false],
                        ['place_of_birth','Place of Birth',      'text',  false],
                        ['nationality',   'Nationality',         'text',  false],
                        ['mother_tongue', 'Mother Tongue',       'text',  false],
                        ['religion',      'Religion',            'text',  false],
                        ['lrn',           'LRN',                 'text',  false],
                        ['mobile_number', 'Contact Number',      'text',  false],
                        ['personal_email','Personal Email',      'email', false],
                        ['home_address',  'House Address',       'text',  false],
                        ['city',          'City / Municipality', 'text',  false],
                        ['province',      'Province',            'text',  false],
                        ['zip_code',      'ZIP Code',            'text',  false],
                    ] as $f)
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">
                            {{ $f[1] }}@if($f[3])<span class="text-red-500 ml-0.5">*</span>@endif
                        </label>
                        @if($f[2] === 'select')
                        <select name="{{ $f[0] }}" id="ed-{{ $f[0] }}"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($f[4] as $opt)
                            <option value="{{ $opt }}" {{ ($student->{$f[0]} ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @else
                        <input type="{{ $f[2] }}" name="{{ $f[0] }}" id="ed-{{ $f[0] }}"
                            value="{{ $f[2] === 'date' ? ($student->{$f[0]} ? \Carbon\Carbon::parse($student->{$f[0]})->format('Y-m-d') : '') : ($student->{$f[0]} ?? '') }}"
                            placeholder="{{ $f[1] }}"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-dark-border"></div>

            {{-- Parent / Guardian Information --}}
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-3">Parent / Guardian Information</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach([
                        ['father_name',          "Father's Name",         'text',  false],
                        ['father_contact',        "Father's Contact",      'text',  false],
                        ['mother_maiden_name',    "Mother's Maiden Name",  'text',  false],
                        ['mother_contact',        "Mother's Contact",      'text',  false],
                        ['guardian_name',         'Guardian Name',         'text',  false],
                        ['guardian_relationship', 'Relationship',          'text',  false],
                        ['guardian_contact',      'Guardian Contact',      'text',  false],
                        ['guardian_email',        'Guardian Email',        'email', false],
                        ['guardian_address',      'Guardian Address',      'text',  false],
                        ['guardian_occupation',   'Guardian Occupation',   'text',  false],
                    ] as $f)
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $f[1] }}</label>
                        <input type="{{ $f[2] }}" name="{{ $f[0] }}" id="ed-{{ $f[0] }}"
                            value="{{ $student->{$f[0]} ?? '' }}"
                            placeholder="{{ $f[1] }}"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="px-6 py-4 border-t border-slate-100 dark:border-dark-border shrink-0 flex items-center justify-between">
            <button type="button" onclick="submitEditDetails()" id="ed-submit-btn"
                class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-colors">
                <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                SAVE CHANGES
            </button>
            <button type="button" onclick="closeEditDetailsModal()"
                class="px-6 py-2.5 rounded-lg border border-slate-200 dark:border-dark-border text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                CANCEL
            </button>
        </div>
    </div>
</div>

{{-- ══ COMPLETE PROFILE MODAL ══ --}}
<div id="complete-profile-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCompleteProfileModal()"></div>
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl flex flex-col" style="max-height:90vh">

        {{-- Header --}}
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between rounded-t-2xl shrink-0">
            <div>
                <h3 class="text-white text-sm font-bold">COMPLETE PROFILE DETAILS</h3>
                <p class="text-blue-200 text-xs mt-0.5">{{ $student->full_name }} · {{ $student->student_id }}</p>
            </div>
            <button onclick="closeCompleteProfileModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        {{-- Progress bar --}}
        <div class="px-6 py-3 border-b border-slate-100 dark:border-dark-border shrink-0">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs text-slate-500 dark:text-slate-400">Profile Completion</span>
                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300" id="cp-progress-label">{{ $filledCount }}/{{ $totalFields }} fields filled</span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                <div id="cp-progress-bar" class="h-2 rounded-full transition-all duration-500 {{ $isComplete ? 'bg-green-500' : 'bg-[#0d4c8f]' }}"
                     style="width: {{ $totalFields > 0 ? round($filledCount / $totalFields * 100) : 0 }}%"></div>
            </div>
        </div>

        {{-- Body --}}
        <div class="overflow-y-auto flex-1 px-6 py-5">
            @if($isComplete)
            <div class="flex flex-col items-center justify-center py-10 gap-3">
                <iconify-icon icon="solar:check-circle-bold" width="40" class="text-green-500"></iconify-icon>
                <p class="text-sm font-semibold text-slate-700 dark:text-white">Profile is already complete!</p>
                <p class="text-xs text-slate-400">All {{ $totalFields }} required fields have been filled in.</p>
            </div>
            @else
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                Fill in the <strong class="text-slate-700 dark:text-slate-200">{{ $missingCount }} missing {{ $missingCount === 1 ? 'field' : 'fields' }}</strong> below to complete this student's profile.
            </p>
            <form id="complete-profile-form" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($missingFields as $fieldKey => $fieldMeta)
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400">
                        {{ $fieldMeta['label'] }} <span class="text-red-500">*</span>
                    </label>
                    <input type="{{ $fieldMeta['type'] }}"
                           name="{{ $fieldKey }}"
                           id="cp-{{ $fieldKey }}"
                           placeholder="{{ $fieldMeta['label'] }}"
                           class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @endforeach
            </form>
            @endif
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-slate-100 dark:border-dark-border shrink-0 flex items-center justify-between">
            @if(!$isComplete)
            <button type="button" onclick="submitCompleteProfile()"
                id="cp-submit-btn"
                class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                SAVE CHANGES
            </button>
            @else
            <span></span>
            @endif
            <button type="button" onclick="closeCompleteProfileModal()"
                class="px-6 py-2.5 rounded-lg border border-slate-200 dark:border-dark-border text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                CLOSE
            </button>
        </div>
    </div>
</div>

{{-- ══ WITHDRAW MODAL ══ --}}
<div id="withdraw-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeProfileModal()"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">
        <div class="bg-red-600 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-white text-sm font-bold">WITHDRAW STUDENT — {{ $student->full_name }}</h3>
            <button onclick="closeProfileModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <div class="px-6 py-5 space-y-4">

            <div class="rounded-xl border border-slate-200 bg-slate-50 dark:bg-slate-800/40 p-4 text-xs space-y-1.5">
                <div class="flex gap-2"><span class="text-slate-400 w-28">Student ID:</span><span class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->student_id }}</span></div>
                <div class="flex gap-2"><span class="text-slate-400 w-28">Student Name:</span><span class="font-semibold text-slate-700 dark:text-slate-300">{{ $student->full_name }}</span></div>
                <div class="flex gap-2"><span class="text-slate-400 w-28">Grade & Section:</span><span class="font-semibold text-slate-700 dark:text-slate-300">{{ $gradeSection }}</span></div>
                <div class="flex gap-2"><span class="text-slate-400 w-28">Current Status:</span><span class="font-semibold text-green-600">{{ ucfirst($student->student_status ?? 'active') }}</span></div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Withdrawal Reason: <span class="text-red-500">*</span></label>
                <div class="space-y-2 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                    @foreach(['transfer'=>'Transfer to another school','financial'=>'Financial reasons','relocation'=>'Relocation','health'=>'Health reasons','academic'=>'Academic reasons','family'=>'Personal/Family reasons','other'=>'Other (please specify)'] as $val => $label)
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="radio" name="wd_reason" value="{{ $val }}" class="text-red-600 focus:ring-red-500 cursor-pointer" onchange="toggleOtherReason(this.value)">
                        <span class="text-xs text-slate-600 dark:text-slate-300">{{ $label }}</span>
                    </label>
                    @endforeach
                    <textarea id="other-reason-text" rows="2" placeholder="Please specify..."
                        class="hidden w-full mt-1 rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Effective Date:</label>
                <input type="date" id="wd-date" value="{{ date('Y-m-d') }}"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Withdrawal Details:</label>
                <textarea id="wd-details" rows="3" placeholder="Additional details..."
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
            </div>

            <div class="space-y-2.5 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3">
                <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" id="wd-notify" checked class="rounded border-slate-300 text-red-500 focus:ring-red-400"><span class="text-xs font-medium text-slate-600 dark:text-slate-300">Send notification to parent/guardian</span></label>
                <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" id="wd-refund" class="rounded border-slate-300 text-red-500 focus:ring-red-400"><span class="text-xs font-medium text-slate-600 dark:text-slate-300">Process refund (if applicable)</span></label>
                <label class="flex items-center gap-2.5 cursor-pointer"><input type="checkbox" id="wd-balance" class="rounded border-slate-300 text-red-500 focus:ring-red-400"><span class="text-xs font-medium text-slate-600 dark:text-slate-300">Clear outstanding balance</span></label>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-dark-border">
                <button type="button" onclick="confirmWithdrawal()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:user-minus-bold" width="14"></iconify-icon>
                    CONFIRM WITHDRAWAL
                </button>
                <button type="button" onclick="closeProfileModal()"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    CANCEL
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Profile Docs (Records Tab) ────────────────────────
function profileDocs(appId) {
    return {
        appId,
        saving: false,
        statuses: {},       // key → new status (only changed ones)
        submittedFlags: {}, // key → bool (mark as submitted)
        pendingFiles: {},   // key → filename string

        get hasPending() {
            return Object.keys(this.statuses).length > 0
                || Object.keys(this.pendingFiles).length > 0
                || Object.keys(this.submittedFlags).length > 0;
        },

        quickApprove(key) {
            this.statuses[key] = 'approved';
        },

        markSubmitted(event, key) {
            this.submittedFlags[key] = event.target.checked;
            if (event.target.checked && !this.statuses[key]) {
                this.statuses[key] = 'pending';
            }
        },

        handleFileSelected(event, key) {
            const file = event.target.files[0];
            if (file) {
                this.pendingFiles[key] = file.name;
                if (!this.statuses[key]) this.statuses[key] = 'pending';
            }
        },

        async saveAll() {
            if (!this.appId) return;
            this.saving = true;
            const fd    = new FormData();
            const token = document.querySelector('meta[name="csrf-token"]').content;
            fd.append('_token', token);

            // Collect all doc inputs from within this component's DOM
            const keys = ['psa', 'report_card', 'good_moral', 'medical'];
            for (const k of keys) {
                if (this.statuses[k]) fd.append(`${k}_status`, this.statuses[k]);
                if (this.submittedFlags[k]) fd.append(`${k}_submitted`, '1');
                const fileInput = this.$el.querySelector(`input[data-key="${k}"]`);
                if (fileInput && fileInput.files[0]) fd.append(`${k}_file`, fileInput.files[0]);
            }

            try {
                const res  = await fetch(`/admin/admission/${this.appId}/documents`, {
                    method:  'POST',
                    headers: { 'X-CSRF-TOKEN': token },
                    body:    fd,
                });
                const json = await res.json();
                if (json.success) {
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

// ── Edit Details Modal ────────────────────────────────
function openEditDetailsModal() {
    document.getElementById('edit-details-modal')?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEditDetailsModal() {
    document.getElementById('edit-details-modal')?.classList.add('hidden');
    document.body.style.overflow = '';
}
function submitEditDetails() {
    const btn = document.getElementById('ed-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<iconify-icon icon="solar:loading-bold" width="14" class="animate-spin"></iconify-icon> Saving...';

    const fields = [
        'first_name','middle_name','last_name','suffix','gender',
        'date_of_birth','place_of_birth','nationality','mother_tongue','religion',
        'lrn','mobile_number','personal_email','home_address','city','province','zip_code',
        'father_name','father_contact','mother_maiden_name','mother_contact',
        'guardian_name','guardian_relationship','guardian_contact',
        'guardian_email','guardian_address','guardian_occupation',
    ];
    const body = {};
    fields.forEach(f => {
        const el = document.getElementById('ed-' + f);
        if (el) body[f] = el.value;
    });

    fetch('{{ route("admin.student-records.update-profile", $student->id) }}', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(body),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> SAVE CHANGES';
        if (data.success) {
            closeEditDetailsModal();
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1400);
        } else {
            showToast(data.message || 'Update failed.', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> SAVE CHANGES';
        showToast('Request failed.', 'error');
    });
}

// ── Complete Profile Modal ─────────────────────────────
function openCompleteProfileModal() {
    document.getElementById('complete-profile-modal')?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeCompleteProfileModal() {
    document.getElementById('complete-profile-modal')?.classList.add('hidden');
    document.body.style.overflow = '';
}

function submitCompleteProfile() {
    const form = document.getElementById('complete-profile-form');
    if (!form) return;

    const inputs = [...form.querySelectorAll('input')];
    const body = {};
    inputs.forEach(i => { if (i.value.trim()) body[i.name] = i.value.trim(); });
    if (Object.keys(body).length === 0) { showToast('Please fill in at least one field.', 'error'); return; }

    const btn = document.getElementById('cp-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<iconify-icon icon="solar:loading-bold" width="14" class="animate-spin"></iconify-icon> Saving...';

    fetch('{{ route("admin.student-records.update-profile", $student->id) }}', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(body),
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> SAVE CHANGES';
        if (data.success) {
            closeCompleteProfileModal();
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1400);
        } else {
            showToast(data.message || 'Update failed.', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> SAVE CHANGES';
        showToast('Request failed.', 'error');
    });
}

// ── Withdraw Modal ─────────────────────────────────────
function openWithdrawModal()  { document.getElementById('withdraw-modal')?.classList.remove('hidden'); document.body.style.overflow='hidden'; }
function closeProfileModal()  { document.getElementById('withdraw-modal')?.classList.add('hidden');    document.body.style.overflow=''; }
function toggleOtherReason(v) { document.getElementById('other-reason-text').classList.toggle('hidden', v !== 'other'); }

function showToast(msg, type='success') {
    const t = document.createElement('div');
    t.className = 'fixed top-6 right-6 z-[100] flex items-center gap-2 rounded-xl border px-4 py-3 text-sm shadow-lg '
        + (type==='success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700');
    t.innerHTML = `<iconify-icon icon="solar:${type==='success'?'check-circle-bold':'close-circle-bold'}" width="16"></iconify-icon> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; setTimeout(()=>t.remove(), 300); }, 3500);
}

function confirmWithdrawal() {
    const reason = document.querySelector('input[name="wd_reason"]:checked')?.value;
    if (!reason) { showToast('Please select a withdrawal reason.', 'error'); return; }
    const otherText = reason === 'other' ? document.getElementById('other-reason-text').value.trim() : '';
    if (reason === 'other' && !otherText) { showToast('Please specify the reason.', 'error'); return; }

    fetch('{{ route("admin.student-records.withdraw") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            student_id:      {{ $student->id }},
            reason:          reason,
            other_reason:    otherText,
            effective_date:  document.getElementById('wd-date').value,
            details:         document.getElementById('wd-details').value,
            notify_guardian: document.getElementById('wd-notify').checked,
            process_refund:  document.getElementById('wd-refund').checked,
            clear_balance:   document.getElementById('wd-balance').checked,
        }),
    })
    .then(r => r.json())
    .then(data => {
        closeProfileModal();
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => window.location.href = '{{ route("admin.student-records.list") }}', 1600);
    })
    .catch(() => showToast('Request failed.', 'error'));
}
</script>
@endpush
@endsection