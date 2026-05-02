@extends('layouts.admin_layout')
@section('title', 'View Application — ' . $application->reference_number)
@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Page Header --}}
    
    <x-admin.page-header
        title="Admission"
        subtitle="Screening and Approval"
        school-year="{{ $application->school_year }}"
    />

    <div class="mb-4">
        <a href="{{ route('admin.admission') }}"
           class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors">
            <iconify-icon icon="solar:alt-arrow-left-linear" width="14"></iconify-icon>
            Back to Applicant List
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-400 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600 flex-shrink-0"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif
    @if(session('warning'))
    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-800 px-4 py-3 text-sm text-amber-700 flex items-center gap-2">
        <iconify-icon icon="solar:danger-triangle-bold" width="16" class="text-amber-600 flex-shrink-0"></iconify-icon>
        {{ session('warning') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-400 flex items-center gap-2">
        <iconify-icon icon="solar:close-circle-bold" width="16" class="text-red-600 flex-shrink-0"></iconify-icon>
        {{ session('error') }}
    </div>
    @endif

    @php
        $isApproved = $application->application_status === 'approved';
        $applicantName = $application->first_name . ' ' . $application->last_name;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══ LEFT COLUMN ═══ --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Status Card --}}
            <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Application Status</p>
                @php
                    $sClass = ['pending'=>'bg-amber-100 text-amber-700 border-amber-200','incomplete'=>'bg-orange-100 text-orange-700 border-orange-200','pre_approved'=>'bg-blue-100 text-blue-700 border-blue-200','approved'=>'bg-green-100 text-green-700 border-green-200','rejected'=>'bg-red-100 text-red-700 border-red-200'][$application->application_status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                    $sIcon  = ['pending'=>'solar:clock-circle-bold','incomplete'=>'solar:danger-triangle-bold','pre_approved'=>'solar:check-read-bold','approved'=>'solar:check-circle-bold','rejected'=>'solar:close-circle-bold'][$application->application_status] ?? 'solar:info-circle-bold';
                @endphp
                <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-sm font-semibold {{ $sClass }}">
                    <iconify-icon icon="{{ $sIcon }}" width="14"></iconify-icon>
                    {{ $application->status_label ?? ucfirst(str_replace('_',' ',$application->application_status)) }}
                </span>
                @if($isApproved)
                <div class="mt-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-3 py-2 flex items-center gap-2">
                    <iconify-icon icon="solar:lock-bold" width="13" class="text-green-600"></iconify-icon>
                    <span class="text-xs text-green-700 dark:text-green-400 font-medium">Enrollment record is locked & active</span>
                </div>
                @endif
                <div class="mt-4 space-y-2 text-xs">
                    @foreach([
                        ['Reference',    '<span class="font-mono font-bold text-[#0d4c8f] dark:text-blue-400">'.$application->reference_number.'</span>'],
                        ['Submitted',    $application->submitted_at?->format('M d, Y')],
                        ['Time',         $application->submitted_at?->format('g:i A')],
                        ['School Year',  $application->school_year],
                        ['Grade Applied',$application->incoming_grade_level],
                    ] as [$label,$val])
                    <div class="flex justify-between items-center py-1.5 border-b border-slate-100 dark:border-dark-border last:border-0">
                        <span class="text-slate-400">{{ $label }}</span>
                        <span class="text-slate-700 dark:text-slate-300 text-right">{!! $val !!}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Actions Card --}}
            <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Actions</p>
                <div class="space-y-2">

                    {{-- Approve --}}
                    @if(!$isApproved)
                    <button type="button" id="show-approve-btn"
                        onclick="openFinanceModal({
                            referenceNumber: '{{ addslashes($application->reference_number) }}',
                            applicationId: {{ $application->id }},
                            studentId: null,
                            gradeLevel: '{{ addslashes($application->incoming_grade_level ?? $application->applied_level) }}',
                            studentCategory: '{{ addslashes($application->student_category ?? 'Regular') }}',
                            schoolYear: '{{ $application->school_year }}',
                            studentName: '{{ addslashes($application->first_name.' '.$application->last_name) }}',
                            onSaved: function() { openRecordsModalApprove(); }
                        })"
                        class="w-full flex items-center gap-2 rounded-lg bg-green-600 hover:bg-green-700 active:scale-95 px-4 py-2.5 text-xs font-semibold text-white transition-all">
                        <iconify-icon icon="solar:check-circle-bold" width="15"></iconify-icon>
                        Approve Application
                    </button>
                    {{-- Hidden form for actual submission --}}
                    <form id="show-approve-form" method="POST" action="{{ route('admin.admission.status', $application->id) }}" class="hidden">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="approved">
                    </form>
                    @else
                    <div class="w-full flex items-center gap-2 rounded-lg bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-2.5 text-xs font-semibold text-green-700 dark:text-green-400">
                        <iconify-icon icon="solar:check-circle-bold" width="15"></iconify-icon>
                        Application Approved
                        <iconify-icon icon="solar:lock-bold" width="13" class="ml-auto"></iconify-icon>
                    </div>
                    @endif

                    {{-- Mark Incomplete — shows lock warning if already approved --}}
                    <button type="button"
                        onclick="{{ $isApproved ? 'openShowLockModal(\'incomplete\')' : 'openReasonModal(\'incomplete\')' }}"
                        class="w-full flex items-center gap-2 rounded-lg {{ $isApproved ? 'bg-orange-400 hover:bg-orange-500' : 'bg-orange-500 hover:bg-orange-600' }} active:scale-95 px-4 py-2.5 text-xs font-semibold text-white transition-all">
                        <iconify-icon icon="solar:danger-triangle-bold" width="15"></iconify-icon>
                        Mark Incomplete
                        @if($isApproved)<iconify-icon icon="solar:lock-bold" width="13" class="ml-auto opacity-70"></iconify-icon>@endif
                    </button>

                    {{-- Reject --}}
                    <button type="button"
                        onclick="{{ $isApproved ? 'openShowLockModal(\'rejected\')' : 'openReasonModal(\'rejected\')' }}"
                        class="w-full flex items-center gap-2 rounded-lg {{ $isApproved ? 'bg-red-400 hover:bg-red-500' : 'bg-red-600 hover:bg-red-700' }} active:scale-95 px-4 py-2.5 text-xs font-semibold text-white transition-all">
                        <iconify-icon icon="solar:close-circle-bold" width="15"></iconify-icon>
                        Reject Application
                        @if($isApproved)<iconify-icon icon="solar:lock-bold" width="13" class="ml-auto opacity-70"></iconify-icon>@endif
                    </button>

                    {{-- Verify Documents --}}
                    <button type="button" onclick="window.openRecordsModal()"
                        class="w-full flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 active:scale-95 px-4 py-2.5 text-xs font-semibold text-white transition-all">
                        <iconify-icon icon="solar:folder-with-files-bold" width="15"></iconify-icon>
                        Verify Documents
                    </button>

                    {{-- Send Email Notice --}}
                    <button type="button" onclick="openNoticeModal()"
                        class="w-full flex items-center gap-2 rounded-lg bg-[#0d4c8f] hover:bg-[#0a3d73] active:scale-95 px-4 py-2.5 text-xs font-semibold text-white transition-all">
                        <iconify-icon icon="solar:letter-bold" width="15"></iconify-icon>
                        Send Email Notice
                    </button>

                    <div class="border-t border-slate-100 dark:border-dark-border pt-2 space-y-2">
                        <button type="button" onclick="openPdfPreview()"
                           class="w-full flex items-center gap-2 rounded-lg border border-amber-300 dark:border-amber-700 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 active:scale-95 px-4 py-2.5 text-xs font-semibold transition-all">
                            <iconify-icon icon="solar:eye-bold" width="15"></iconify-icon>
                            Preview Application PDF
                        </button>
                        <a href="{{ route('admin.admission.pdf', $application->id) }}"
                           class="w-full flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-dark-border active:scale-95 px-4 py-2.5 text-xs font-semibold transition-all">
                            <iconify-icon icon="solar:file-download-bold" width="15" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                            Download Application PDF
                        </a>
                    </div>
                </div>
            </div>

            {{-- Uploaded Documents --}}
            <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Uploaded Documents</p>
                @php
                    $docList = [
                        ['PSA Birth Certificate',  'psa',         $application->psa_uploaded,        $application->psa_filename],
                        ['Report Card (Form 138)', 'report_card', $application->report_card_uploaded, $application->report_card_filename],
                        ['Good Moral Certificate', 'good_moral',  $application->good_moral_uploaded,  $application->good_moral_filename],
                    ];
                    $docCount = collect($docList)->filter(fn($d) => $d[2])->count();
                @endphp
                <div class="space-y-3">
                    @foreach($docList as [$docName, $docKey, $uploaded, $filename])
                    <div class="rounded-lg border {{ $uploaded ? 'bg-green-50 dark:bg-green-900/20 border-green-100 dark:border-green-800' : 'bg-slate-50 dark:bg-dark-border/30 border-slate-100 dark:border-dark-border' }} overflow-hidden">
                        <div class="flex items-center justify-between px-3 py-2.5">
                            <div class="flex items-center gap-2 min-w-0">
                                <iconify-icon icon="{{ $uploaded ? 'solar:check-circle-bold' : 'solar:close-circle-bold' }}" width="14"
                                    class="{{ $uploaded ? 'text-green-500' : 'text-slate-300 dark:text-slate-600' }} flex-shrink-0"></iconify-icon>
                                <span class="text-xs text-slate-600 dark:text-slate-400 truncate font-medium">{{ $docName }}</span>
                            </div>
                            <span class="text-xs {{ $uploaded ? 'text-green-600 dark:text-green-400 font-semibold' : 'text-slate-400' }} flex-shrink-0 ml-2">
                                {{ $uploaded ? 'Uploaded' : 'Not uploaded' }}
                            </span>
                        </div>
                        @if($uploaded && $filename)
                        @php
                            $ext     = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                            $isPdf   = $ext === 'pdf';
                            $fileUrl = Storage::disk('public')->url('applications/'.$application->reference_number.'/'.$filename);
                                // ✅ FIXED route name: admin.admission.document (not admin.admin.admission.document) 
                            $dlRoute = route('admin.admission.document', [$application->id, $docKey]);
                        @endphp
                        <div class="flex items-center gap-2 px-3 pb-2.5 flex-wrap">
                            @if($isImage || $isPdf)
                            <button type="button"
                                onclick="openDocPreview('{{ $fileUrl }}','{{ $ext }}','{{ $docName }}')"
                                class="flex items-center gap-1.5 rounded-lg border border-amber-300 dark:border-amber-700 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 px-3 py-1.5 text-xs font-semibold transition-all">
                                <iconify-icon icon="solar:eye-bold" width="13"></iconify-icon>
                                Preview
                            </button>
                            @endif
                            <a href="{{ $dlRoute }}"
                               class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f] dark:border-blue-700 text-[#0d4c8f] dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 px-3 py-1.5 text-xs font-semibold transition-all">
                                <iconify-icon icon="solar:file-download-bold" width="13"></iconify-icon>
                                Download
                            </a>
                            <span class="text-xs text-slate-400 truncate" title="{{ $filename }}">{{ Str::limit($filename, 20) }}</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-dark-border">
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-slate-400">Documents submitted</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $docCount }}/3</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-dark-border rounded-full h-1.5">
                        <div class="h-1.5 rounded-full transition-all {{ $docCount===3?'bg-green-500':($docCount>=1?'bg-amber-500':'bg-red-400') }}"
                             style="width: {{ ($docCount/3)*100 }}%"></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ═══ RIGHT COLUMN ═══ --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Grade Level & Program --}}
            <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30">
                        <iconify-icon icon="solar:book-bold" width="15" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Grade Level & Program</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-4">
                    @foreach([['Level Applied',$application->applied_level],['Grade Level',$application->incoming_grade_level],['Student Status',$application->student_status],['Student Category',$application->student_category]] as [$l,$v])
                    <div><p class="text-xs text-slate-400 mb-0.5">{{ $l }}</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $v ?? '—' }}</p></div>
                    @endforeach
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Transferee</p>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $application->is_transferee ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">{{ $application->is_transferee ? 'Yes' : 'No' }}</span>
                    </div>
                    @if($application->is_transferee)<div><p class="text-xs text-slate-400 mb-0.5">Previous School</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->previous_school ?? '—' }}</p></div>@endif
                    @if($application->track)<div><p class="text-xs text-slate-400 mb-0.5">Track</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->track }}</p></div>@endif
                    @if($application->strand)<div><p class="text-xs text-slate-400 mb-0.5">Strand</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->strand }}</p></div>@endif
                </div>
                {{-- ESC / Voucher eligibility block --}}
                @if($application->student_category !== 'Regular Payee' && $application->subsidy_prev_school_type)
                @php
                    $subsidyLabels = [
                        'public'               => 'Public Elementary School',
                        'private'              => 'Private Elementary School',
                        'public_jhs'           => 'Public JHS Graduate',
                        'private_jhs_esc'      => 'Private JHS with ESC Subsidy',
                        'private_jhs_no_esc'   => 'Private JHS without ESC',
                    ];
                    $subsidyLabel = $subsidyLabels[$application->subsidy_prev_school_type] ?? $application->subsidy_prev_school_type;
                    $isEscCategory = $application->student_category === 'ESC Grantee';
                @endphp
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-dark-border">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">
                        {{ $isEscCategory ? 'ESC Grantee Details' : 'SHS Voucher Details' }}
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3">
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">{{ $isEscCategory ? 'Prev. Elementary Type' : 'Previous JHS Type' }}</p>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $subsidyLabel }}</p>
                        </div>
                        @if($isEscCategory && $application->subsidy_certificate_no)
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">ESC Certificate No.</p>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 font-mono">{{ $application->subsidy_certificate_no }}</p>
                        </div>
                        @endif
                        @php
                            $rateHints = [
                                'public'             => 'ESC Subsidy rate applies',
                                'private'            => 'Subject to review',
                                'public_jhs'         => 'FREE tuition (fully covered)',
                                'private_jhs_esc'    => '₱14,000 tuition',
                                'private_jhs_no_esc' => 'Regular rate (₱17,500)',
                            ];
                            $rateColors = [
                                'public'             => 'bg-green-100 text-green-700',
                                'private'            => 'bg-amber-100 text-amber-700',
                                'public_jhs'         => 'bg-green-100 text-green-700',
                                'private_jhs_esc'    => 'bg-blue-100 text-blue-700',
                                'private_jhs_no_esc' => 'bg-slate-100 text-slate-600',
                            ];
                        @endphp
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Applicable Rate</p>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $rateColors[$application->subsidy_prev_school_type] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $rateHints[$application->subsidy_prev_school_type] ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Personal Information --}}
            <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-50 dark:bg-violet-900/30">
                        <iconify-icon icon="solar:user-bold" width="15" class="text-violet-600 dark:text-violet-400"></iconify-icon>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Personal Information</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-4">
                    @foreach([['First Name',$application->first_name],['Middle Name',$application->middle_name??'—'],['Last Name',$application->last_name],['Gender',$application->gender??'—'],['Date of Birth',$application->date_of_birth?->format('F d, Y')??'—'],['Nationality',$application->nationality??'Filipino'],['Religion',$application->religion??'—'],['LRN',$application->lrn??'—'],['Mobile Number',$application->mobile_number??'—'],['City',$application->city??'—'],['ZIP Code',$application->zip_code??'—']] as [$l,$v])
                    <div><p class="text-xs text-slate-400 mb-0.5">{{ $l }}</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $v }}</p></div>
                    @endforeach
                    <div class="sm:col-span-2"><p class="text-xs text-slate-400 mb-0.5">Personal Email</p><p class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-400">{{ $application->personal_email ?? '—' }}</p></div>
                    @if($application->suffix)<div><p class="text-xs text-slate-400 mb-0.5">Suffix</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->suffix }}</p></div>@endif
                    <div class="col-span-2 sm:col-span-3"><p class="text-xs text-slate-400 mb-0.5">Home Address</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->home_address ?? '—' }}</p></div>
                </div>
            </div>

            {{-- Parent / Guardian --}}
            <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-50 dark:bg-emerald-900/30">
                        <iconify-icon icon="solar:users-group-rounded-bold" width="15" class="text-emerald-600 dark:text-emerald-400"></iconify-icon>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Parent / Guardian Information</h3>
                </div>
                <div class="mb-4 pb-4 border-b border-slate-100 dark:border-dark-border">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Father</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3">
                        <div class="sm:col-span-2"><p class="text-xs text-slate-400 mb-0.5">Father's Name</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->father_name ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Contact Number</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->father_contact ?? '—' }}</p></div>
                    </div>
                </div>
                <div class="mb-4 pb-4 border-b border-slate-100 dark:border-dark-border">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Mother</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3">
                        <div class="sm:col-span-2"><p class="text-xs text-slate-400 mb-0.5">Mother's Maiden Name</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->mother_maiden_name ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Contact Number</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->mother_contact ?? '—' }}</p></div>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Guardian</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-3">
                        <div><p class="text-xs text-slate-400 mb-0.5">Guardian Name</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->guardian_name ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Relationship</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->guardian_relationship ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Contact</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->guardian_contact ?? '—' }}</p></div>
                        <div><p class="text-xs text-slate-400 mb-0.5">Guardian Email</p><p class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-400">{{ $application->guardian_email ?? '—' }}</p></div>
                        <div class="col-span-2 sm:col-span-3"><p class="text-xs text-slate-400 mb-0.5">Guardian Address</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->guardian_address ?? '—' }}</p></div>
                    </div>
                </div>
            </div>

            {{-- ── Finance Clearance Gate (approved only) ── --}}
            @if($isApproved)
            @php
                $fc = $application->finance_clearance ?? 'not_set';
                $fcConfig = [
                    'cleared' => [
                        'label'      => 'Cleared',
                        'sub'        => 'Fully Paid',
                        'gate'       => 'Student is CLEARED for enrollment',
                        'gateColor'  => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-700 dark:text-green-400',
                        'gateIcon'   => 'solar:check-circle-bold',
                        'gateIconC'  => 'text-green-500',
                        'badgeColor' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800',
                        'borderTop'  => 'border-t-4 border-green-500',
                        'dotColor'   => 'bg-green-500',
                    ],
                    'pending' => [
                        'label'      => 'Pending',
                        'sub'        => 'Partial / Downpayment Only',
                        'gate'       => 'Student may enroll — balance confirmation required',
                        'gateColor'  => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-400',
                        'gateIcon'   => 'solar:clock-circle-bold',
                        'gateIconC'  => 'text-amber-500',
                        'badgeColor' => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                        'borderTop'  => 'border-t-4 border-amber-400',
                        'dotColor'   => 'bg-amber-400',
                    ],
                    'hold' => [
                        'label'      => 'On Hold',
                        'sub'        => 'Not Paid',
                        'gate'       => 'Enrollment BLOCKED — finance clearance required',
                        'gateColor'  => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-400',
                        'gateIcon'   => 'solar:lock-bold',
                        'gateIconC'  => 'text-red-500',
                        'badgeColor' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-red-200 dark:border-red-800',
                        'borderTop'  => 'border-t-4 border-red-500',
                        'dotColor'   => 'bg-red-500',
                    ],
                    'not_set' => [
                        'label'      => 'Not Set',
                        'sub'        => 'Awaiting assessment',
                        'gate'       => 'Finance clearance has not been assessed yet',
                        'gateColor'  => 'bg-slate-50 dark:bg-dark-border/30 border-slate-200 dark:border-dark-border text-slate-500 dark:text-slate-400',
                        'gateIcon'   => 'solar:question-circle-bold',
                        'gateIconC'  => 'text-slate-400',
                        'badgeColor' => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-dark-border',
                        'borderTop'  => 'border-t-4 border-slate-300 dark:border-slate-600',
                        'dotColor'   => 'bg-slate-400',
                    ],
                ];
                $fcd = $fcConfig[$fc];
            @endphp
            <div id="finance-clearance-card" class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden {{ $fcd['borderTop'] }}">

                {{-- Card header --}}
                <div class="flex items-center justify-between px-5 pt-4 pb-3 border-b border-slate-100 dark:border-dark-border">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30">
                            <iconify-icon icon="solar:shield-check-bold" width="15" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">Finance Clearance</h3>
                    </div>
                    <button type="button" onclick="openFinanceClearanceModal()"
                        class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-[#0a3d73] text-white px-3 py-1.5 text-xs font-semibold transition-all active:scale-95">
                        <iconify-icon icon="solar:pen-bold" width="12"></iconify-icon>
                        Edit
                    </button>
                </div>

                <div class="px-5 py-4 space-y-4">

                    {{-- Student info (from application) --}}
                    <div class="rounded-xl bg-slate-50 dark:bg-dark-border/30 border border-slate-100 dark:border-dark-border px-4 py-3">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Student Information</p>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-2">
                            <div>
                                <p class="text-xs text-slate-400">Full Name</p>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->first_name }} {{ $application->last_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Reference No.</p>
                                <p class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-400 font-mono">{{ $application->reference_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Grade Level</p>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->incoming_grade_level }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Student Category</p>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->student_category }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">School Year</p>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->school_year }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Applied Level</p>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $application->applied_level }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Payment information based on student category --}}
                    @php
                        $cat = $application->student_category ?? 'Regular Payee';
                        $prevType = $application->subsidy_prev_school_type ?? null;
                        $paymentTypeLabel = match(true) {
                            $cat === 'ESC Grantee' && $prevType === 'public'  => 'ESC Grantee — Public Elementary Graduate',
                            $cat === 'ESC Grantee' && $prevType === 'private' => 'ESC Grantee — Private Elementary Graduate',
                            $cat === 'ESC Grantee'                            => 'ESC Grantee',
                            $cat === 'SHS Voucher Recipient' && $prevType === 'public_jhs'         => 'SHS Voucher — Public JHS Graduate (FREE Tuition)',
                            $cat === 'SHS Voucher Recipient' && $prevType === 'private_jhs_esc'    => 'SHS Voucher — Private JHS with ESC (₱14,000 Subsidy)',
                            $cat === 'SHS Voucher Recipient' && $prevType === 'private_jhs_no_esc' => 'SHS Voucher — Private JHS without ESC (Regular Rate)',
                            $cat === 'SHS Voucher Recipient'                                       => 'SHS Voucher Recipient',
                            default => 'Regular Payee',
                        };
                        $paymentTypeBadge = match(true) {
                            $cat === 'ESC Grantee'                                                  => 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                            $cat === 'SHS Voucher Recipient' && $prevType === 'public_jhs'          => 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border-green-200 dark:border-green-800',
                            $cat === 'SHS Voucher Recipient'                                        => 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                            default => 'bg-slate-50 dark:bg-dark-border/30 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-dark-border',
                        };
                    @endphp
                    <div class="rounded-xl border px-4 py-3 {{ $paymentTypeBadge }}">
                        <p class="text-xs font-bold uppercase tracking-wide mb-1 opacity-60">Payment Type</p>
                        <p class="text-xs font-semibold">{{ $paymentTypeLabel }}</p>
                    </div>

                    {{-- Clearance status --}}
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Payment Status</p>
                        <div class="space-y-2" id="fc-status-display">
                            @foreach(['cleared' => ['Fully Paid', 'cleared'], 'pending' => ['Partial / Downpayment Only', 'pending'], 'hold' => ['Not Paid', 'hold']] as $val => [$lbl, $key])
                            <div class="flex items-center gap-3 rounded-lg border px-4 py-3 {{ $fc === $val ? $fcConfig[$val]['badgeColor'] : 'bg-slate-50 dark:bg-dark-border/30 border-slate-100 dark:border-dark-border' }}">
                                <div class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full border-2 {{ $fc === $val ? 'border-current' : 'border-slate-300 dark:border-slate-600' }}">
                                    @if($fc === $val)
                                    <div class="h-2 w-2 rounded-full {{ $fcConfig[$val]['dotColor'] }}"></div>
                                    @endif
                                </div>
                                <span class="text-xs font-semibold">{{ $lbl }}</span>
                                <span class="ml-auto text-xs font-bold {{ $fc === $val ? '' : 'text-slate-400' }}">
                                    @if($val === 'cleared') Cleared @else Pending @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Payment Summary (Informational Only) --}}
                    @if($application->finance_total_assessment || $application->finance_amount_paid || $application->finance_next_due_date)
                    @php
                        $balance = ($application->finance_total_assessment ?? 0) - ($application->finance_amount_paid ?? 0);
                    @endphp
                    <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                        <div class="px-4 py-2 bg-slate-50 dark:bg-slate-800/30 border-b border-slate-200 dark:border-dark-border flex items-center gap-2">
                            <iconify-icon icon="solar:notes-bold" width="13" class="text-slate-400"></iconify-icon>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Payment Summary <span class="font-normal normal-case text-slate-400">(Informational Only)</span></p>
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-dark-border">
                            @if($application->finance_total_assessment)
                            <div class="flex items-center justify-between px-4 py-2.5">
                                <span class="text-xs text-slate-500">Total Assessment</span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">₱{{ number_format($application->finance_total_assessment, 2) }}</span>
                            </div>
                            @endif
                            @if($application->finance_amount_paid !== null)
                            <div class="flex items-center justify-between px-4 py-2.5">
                                <span class="text-xs text-slate-500">Amount Paid</span>
                                <span class="text-xs font-semibold text-green-600 dark:text-green-400">
                                    ₱{{ number_format($application->finance_amount_paid, 2) }}
                                    @if($fc === 'pending') <span class="text-slate-400 font-normal">(Downpayment)</span> @endif
                                </span>
                            </div>
                            @endif
                            @if($application->finance_total_assessment)
                            <div class="flex items-center justify-between px-4 py-2.5">
                                <span class="text-xs text-slate-500">Remaining Balance</span>
                                <span class="text-xs font-semibold {{ $balance > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">₱{{ number_format($balance, 2) }}</span>
                            </div>
                            @endif
                            @if($application->finance_next_due_date)
                            <div class="flex items-center justify-between px-4 py-2.5">
                                <span class="text-xs text-slate-500">Next Due Date</span>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $application->finance_next_due_date->format('F d, Y') }} <span class="text-slate-400 font-normal">(Monthly)</span></span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Enrollment gate --}}
                    <div id="fc-gate-display" class="rounded-xl border px-4 py-3 flex items-center gap-3 {{ $fcd['gateColor'] }}">
                        <iconify-icon icon="{{ $fcd['gateIcon'] }}" width="18" class="{{ $fcd['gateIconC'] }} flex-shrink-0"></iconify-icon>
                        <p class="text-xs font-bold">{{ $fcd['gate'] }}</p>
                    </div>

                    {{-- Cleared By --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 dark:bg-dark-border/30 border border-slate-100 dark:border-dark-border px-4 py-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Cleared By</p>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ $application->finance_cleared_by ?: '—' }}
                                @if(!$application->finance_cleared_by && $fc !== 'cleared')
                                    <span class="text-slate-400 font-normal text-[10px]">(Will be filled upon full payment)</span>
                                @endif
                            </p>
                        </div>
                        <div class="rounded-xl bg-slate-50 dark:bg-dark-border/30 border border-slate-100 dark:border-dark-border px-4 py-3">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Remarks</p>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{{ $application->finance_clearance_notes ?: '—' }}</p>
                        </div>
                    </div>

                    {{-- Last updated --}}
                    @if($application->finance_clearance_updated_at)
                    <p class="text-xs text-slate-400 text-right">
                        Last updated: {{ $application->finance_clearance_updated_at->format('M d, Y g:i A') }}
                    </p>
                    @endif

                </div>
            </div>
            @endif

            @if($application->consent_given)
            <div class="rounded-2xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/10 shadow-sm p-4 flex items-start gap-3">
                <iconify-icon icon="solar:shield-check-bold" width="18" class="text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5"></iconify-icon>
                <div>
                    <p class="text-xs font-bold text-green-700 dark:text-green-400">Data Privacy Consent Given</p>
                    <p class="text-xs text-green-600 dark:text-green-500 mt-0.5">Consented by <strong>{{ $application->parent_name_consent ?? 'Parent/Guardian' }}</strong>@if($application->consent_date) on {{ $application->consent_date->format('M d, Y') }}@endif</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <p class="mt-8 text-center text-xs text-slate-400">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ════════ APPROVAL LOCK WARNING MODAL (show page) ════════ --}}
<div id="show-lock-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-amber-500 px-6 py-4 flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-600">
                <iconify-icon icon="solar:lock-bold" width="18" class="text-white"></iconify-icon>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Approval Lock Warning</h3>
                <p class="text-xs text-amber-100 mt-0.5">This application is already approved</p>
            </div>
        </div>
        <div class="px-6 py-5">
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 mb-4">
                <p class="text-xs font-bold text-amber-800 mb-1">⚠ Approved Status is Final</p>
                <p class="text-xs text-amber-700 leading-relaxed">
                    <strong>{{ $applicantName }}</strong>'s enrollment record is active and locked in the system.
                </p>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-3">
                Changing the status to <strong id="show-lock-status-label" class="text-red-600"></strong> will only flag this <em>application record</em> for admin tracking. It will <strong>not</strong> revoke the student's enrollment.
            </p>
            <ul class="text-xs text-slate-500 space-y-1.5 mb-5">
                <li class="flex items-start gap-2"><iconify-icon icon="solar:check-circle-bold" class="text-green-500 mt-0.5 flex-shrink-0" width="13"></iconify-icon> Student's approved enrollment remains <strong>active</strong></li>
                <li class="flex items-start gap-2"><iconify-icon icon="solar:check-circle-bold" class="text-green-500 mt-0.5 flex-shrink-0" width="13"></iconify-icon> Portal access credentials are NOT revoked</li>
                <li class="flex items-start gap-2"><iconify-icon icon="solar:info-circle-bold" class="text-blue-500 mt-0.5 flex-shrink-0" width="13"></iconify-icon> This action will be logged in the audit trail</li>
            </ul>
            <div class="flex gap-2">
                <button type="button" onclick="closeShowLockModal()"
                    class="flex-1 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Cancel — Keep Approved
                </button>
                <button type="button" onclick="confirmShowLockOverride()"
                    class="flex-1 rounded-xl bg-amber-500 hover:bg-amber-600 px-4 py-2.5 text-xs font-semibold text-white transition-colors">
                    Proceed Anyway
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ════════ REASON MODAL (Reject / Incomplete) ════════ --}}
<div id="reason-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeReasonModal()"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div id="reason-modal-header" class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div id="reason-modal-icon" class="flex h-8 w-8 items-center justify-center rounded-lg">
                    <iconify-icon id="reason-icon-el" width="16" class="text-white"></iconify-icon>
                </div>
                <h3 id="reason-modal-title" class="text-sm font-bold text-white"></h3>
            </div>
            <button onclick="closeReasonModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-colors">
                <iconify-icon icon="solar:close-bold" width="14"></iconify-icon>
            </button>
        </div>
        <div class="px-6 pb-6 pt-4">
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-3" id="reason-modal-desc"></p>
            <form id="reason-form" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="status" id="reason-status-input">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Reason <span class="text-slate-400 font-normal">(will be included in email notification)</span></label>
                    <textarea name="reason" id="reason-textarea" rows="4"
                        class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-bg px-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f] resize-none transition-all"
                        placeholder="Enter reason here..."></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeReasonModal()" class="flex-1 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 transition-colors">Cancel</button>
                    <button type="submit" id="reason-submit-btn" class="flex-1 rounded-xl px-4 py-2.5 text-xs font-semibold text-white transition-colors">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════ APPLICATION PDF PREVIEW MODAL ════════ --}}
<div id="pdf-preview-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closePdfPreview()"></div>
    <div class="relative w-full max-w-4xl mx-4 rounded-2xl overflow-hidden shadow-2xl bg-white dark:bg-dark-card flex flex-col" style="height:90vh; max-height:90vh;">
        <div class="flex items-center justify-between px-5 py-3 bg-[#0d4c8f] flex-shrink-0">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:document-bold" width="16" class="text-white"></iconify-icon>
                <span class="text-white text-sm font-bold">Application Form — {{ $application->reference_number }}</span>
                <span class="ml-1 rounded-full bg-white/20 px-2 py-0.5 text-xs text-white font-medium">{{ $application->incoming_grade_level }}</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.admission.pdf', $application->id) }}"
                   class="flex items-center gap-1.5 rounded-lg bg-white/20 hover:bg-white/30 px-3 py-1.5 text-white text-xs font-semibold transition-colors">
                    <iconify-icon icon="solar:file-download-bold" width="13"></iconify-icon> Download PDF
                </a>
                <button onclick="closePdfPreview()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-colors">
                    <iconify-icon icon="solar:close-bold" width="14"></iconify-icon>
                </button>
            </div>
        </div>
        <div class="overflow-y-auto flex-1 bg-slate-100 dark:bg-slate-900 p-6">
            <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm p-8 text-sm">
                <div class="text-center border-b-2 border-[#0d4c8f] pb-5 mb-6">
                    <div class="flex justify-center mb-2"><div class="w-12 h-12 rounded-full bg-[#0d4c8f] flex items-center justify-center"><iconify-icon icon="solar:book-bold" width="22" class="text-white"></iconify-icon></div></div>
                    <h2 class="text-base font-bold text-[#0d4c8f] uppercase tracking-wide">My Messiah School of Cavite</h2>
                    <p class="text-slate-600 font-semibold text-sm mt-1">Student Application Form</p>
                    <p class="text-slate-400 text-xs mt-0.5">Academic Year {{ $application->school_year }}</p>
                </div>
                <div class="flex flex-wrap gap-4 items-center justify-between rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 mb-6 text-xs">
                    <div><span class="text-slate-400">Reference No.:</span> <strong class="font-mono text-[#0d4c8f]">{{ $application->reference_number }}</strong></div>
                    <div><span class="text-slate-400">Status:</span> <strong class="uppercase ml-1 {{ $isApproved?'text-green-600':($application->application_status==='rejected'?'text-red-600':'text-amber-600') }}">{{ str_replace('_',' ',$application->application_status) }}</strong></div>
                    <div><span class="text-slate-400">Date:</span> <strong>{{ $application->submitted_at?->format('M d, Y') }}</strong></div>
                </div>
                @foreach([
                    ['Grade Level & Program',[['Level Applied',$application->applied_level],['Grade Level',$application->incoming_grade_level],['Student Status',$application->student_status],['Student Category',$application->student_category],['Transferee',$application->is_transferee?'Yes — '.($application->previous_school??''):'No']]],
                    ['Personal Information',[['Full Name',trim($application->first_name.' '.($application->middle_name??'').' '.$application->last_name.' '.($application->suffix??''))],['Gender',$application->gender],['Date of Birth',$application->date_of_birth?->format('F d, Y')],['Nationality',$application->nationality??'Filipino'],['LRN',$application->lrn??'—'],['Religion',$application->religion??'—'],['Mobile',$application->mobile_number],['Email',$application->personal_email],['City',$application->city??'—'],['ZIP',$application->zip_code??'—'],['Address',$application->home_address??'—']]],
                    ['Parent / Guardian Information',[["Father's Name",$application->father_name??'—'],["Father's Contact",$application->father_contact??'—'],["Mother's Maiden Name",$application->mother_maiden_name??'—'],["Mother's Contact",$application->mother_contact??'—'],['Guardian',$application->guardian_name??'—'],['Relationship',$application->guardian_relationship??'—'],['Guardian Contact',$application->guardian_contact??'—'],['Guardian Email',$application->guardian_email??'—'],['Guardian Address',$application->guardian_address??'—']]],
                ] as [$title,$fields])
                <div class="mb-5">
                    <div class="bg-[#0d4c8f] text-white text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded mb-3">{{ $title }}</div>
                    <div class="grid grid-cols-2 gap-x-8 gap-y-3">
                        @foreach($fields as $f)<div><p class="text-xs text-slate-400 mb-0.5">{{ $f[0] }}</p><p class="text-xs font-semibold text-slate-700 border-b border-slate-200 pb-1">{{ $f[1] ?? '—' }}</p></div>@endforeach
                    </div>
                </div>
                @endforeach
                <div class="mb-6">
                    <div class="bg-[#0d4c8f] text-white text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded mb-3">Documents</div>
                    @foreach($docList as [$docName,,$uploaded,$filename])
                    <div class="flex items-center gap-2.5 py-1.5 border-b border-slate-100 text-xs">
                        <iconify-icon icon="{{ $uploaded?'solar:check-circle-bold':'solar:close-circle-bold' }}" class="{{ $uploaded?'text-green-500':'text-slate-300' }}" width="14"></iconify-icon>
                        <span class="text-slate-600">{{ $docName }}</span>
                        @if($uploaded)<span class="text-green-600 font-medium">— {{ $filename ?? 'Uploaded' }}</span>@else<span class="text-slate-400 italic">Not uploaded</span>@endif
                    </div>
                    @endforeach
                </div>
                <div class="grid grid-cols-2 gap-10 mt-8 pt-4 border-t border-slate-200">
                    <div><div class="border-b border-slate-400 h-10 mb-2"></div><p class="text-xs text-center text-slate-400">Applicant / Guardian Signature</p></div>
                    <div><div class="border-b border-slate-400 h-10 mb-2"></div><p class="text-xs text-center text-slate-400">Date</p></div>
                </div>
                <p class="text-center text-xs text-slate-300 mt-6 pt-4 border-t border-slate-100">My Messiah School of Cavite &bull; 144 Compound, Brgy. Palenzuela I, Dasmariñas, Cavite<br>This is a computer-generated document. &bull; Ref: {{ $application->reference_number }}</p>
            </div>
        </div>
    </div>
</div>

{{-- ════════ DOCUMENT PREVIEW MODAL ════════ --}}
<div id="doc-preview-modal" class="fixed inset-0 z-[400] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeDocPreview()"></div>
    <div class="relative w-full max-w-4xl mx-4 rounded-2xl overflow-hidden shadow-2xl bg-white dark:bg-dark-card flex flex-col" style="height:90vh">
        <div class="flex items-center justify-between px-5 py-3 bg-[#0d4c8f] flex-shrink-0">
            <div class="flex items-center gap-2"><iconify-icon icon="solar:document-bold" width="16" class="text-white"></iconify-icon><span id="doc-modal-title" class="text-white text-sm font-bold"></span></div>
            <div class="flex items-center gap-2">
                <a id="doc-modal-download" href="#" class="flex items-center gap-1.5 rounded-lg bg-white/20 hover:bg-white/30 px-3 py-1.5 text-white text-xs font-semibold transition-colors"><iconify-icon icon="solar:file-download-bold" width="13"></iconify-icon> Download</a>
                <button onclick="closeDocPreview()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-colors"><iconify-icon icon="solar:close-bold" width="14"></iconify-icon></button>
            </div>
        </div>
        <div id="doc-modal-body" class="flex-1 overflow-auto bg-slate-100 dark:bg-slate-900 flex items-center justify-center p-4"></div>
    </div>
</div>

{{-- ════════ SEND EMAIL NOTICE MODAL ════════ --}}
<div id="notice-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeNoticeModal()"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2"><iconify-icon icon="solar:letter-bold" width="16" class="text-white"></iconify-icon><h3 class="text-sm font-bold text-white">Send Email Notice</h3></div>
            <button onclick="closeNoticeModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-colors"><iconify-icon icon="solar:close-bold" width="14"></iconify-icon></button>
        </div>
        <form method="POST" action="{{ route('admin.admission.send-notice') }}" class="px-6 pb-6 pt-5">
            @csrf
            <input type="hidden" name="ids[]" value="{{ $application->id }}">
            <div class="mb-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 px-3 py-2 text-xs text-blue-700 dark:text-blue-400 flex items-center gap-2">
                <iconify-icon icon="solar:info-circle-bold" width="14"></iconify-icon>
                Sending to: <strong>{{ $application->personal_email }}</strong>
                @if($application->guardian_email) + CC: <strong>{{ $application->guardian_email }}</strong>@endif
            </div>
            <div class="mb-3">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Message Type</label>
                <select name="message_type" id="notice-message-type" onchange="updateNoticeSubject(this)"
                    class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-bg px-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f] transition-all">
                    <option value="Application Approved">✅ Application Approved</option>
                    <option value="Application Rejected">❌ Application Rejected</option>
                    <option value="Missing Requirements">📋 Missing Requirements</option>
                    <option value="Pending Payment">💰 Pending Payment</option>
                    <option value="Document Verification Needed">🔍 Document Verification Needed</option>
                    <option value="Interview Schedule">📅 Interview Schedule</option>
                    <option value="Entrance Exam Schedule">📝 Entrance Exam Schedule</option>
                    <option value="Custom Message">✏️ Custom Message</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email Subject</label>
                <input type="text" name="subject" id="notice-subject" value="Update on Your Application — {{ $application->reference_number }}"
                    class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-bg px-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f] transition-all" required>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Additional Details / Reason <span class="text-slate-400 font-normal">(optional)</span></label>
                <textarea name="details" rows="4"
                    class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-bg px-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f] resize-none transition-all"
                    placeholder="Enter specific details, requirements, schedule, or reason..."></textarea>
            </div>
            <div class="mb-4 flex items-center gap-2">
                <input type="checkbox" name="send_copy" id="send-copy" value="1" class="w-3.5 h-3.5 rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f]">
                <label for="send-copy" class="text-xs text-slate-600 dark:text-slate-400">Send copy to my admin email</label>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="closeNoticeModal()" class="flex-1 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 rounded-xl bg-[#0d4c8f] hover:bg-[#0a3d73] px-4 py-2.5 text-xs font-semibold text-white transition-colors flex items-center justify-center gap-1.5">
                    <iconify-icon icon="solar:letter-bold" width="14"></iconify-icon> Send Notice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ── Show page lock modal ───────────────────────────────────
let _showLockStatus = null;

function openShowLockModal(status) {
    _showLockStatus = status;
    document.getElementById('show-lock-status-label').textContent =
        status === 'rejected' ? 'Rejected' : 'Incomplete';
    document.getElementById('show-lock-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeShowLockModal() {
    document.getElementById('show-lock-modal').classList.add('hidden');
    document.body.style.overflow = '';
    _showLockStatus = null;
}
function confirmShowLockOverride() {
    closeShowLockModal();
    // Now open the reason form with lock_confirmed = true
    openReasonModal(_showLockStatus ?? 'rejected', true);
}

// ── Reason Modal ───────────────────────────────────────────
let _reasonLockConfirmed = false;

function openReasonModal(status, lockConfirmed = false) {
    _reasonLockConfirmed = lockConfirmed;
    const header  = document.getElementById('reason-modal-header');
    const iconBox = document.getElementById('reason-modal-icon');
    const icon    = document.getElementById('reason-icon-el');
    const title   = document.getElementById('reason-modal-title');
    const desc    = document.getElementById('reason-modal-desc');
    const btn     = document.getElementById('reason-submit-btn');
    const form    = document.getElementById('reason-form');
    document.getElementById('reason-status-input').value = status;
    form.action = '{{ route("admin.admission.status", $application->id) }}';

    if (status === 'rejected') {
        header.className = 'px-6 py-4 flex items-center justify-between bg-red-600';
        iconBox.className = 'flex h-8 w-8 items-center justify-center rounded-lg bg-red-700';
        icon.setAttribute('icon','solar:close-circle-bold');
        title.textContent = 'Reject Application';
        desc.textContent  = 'This will flag the application as rejected.';
        btn.className     = 'flex-1 rounded-xl px-4 py-2.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 transition-colors';
    } else {
        header.className = 'px-6 py-4 flex items-center justify-between bg-orange-500';
        iconBox.className = 'flex h-8 w-8 items-center justify-center rounded-lg bg-orange-600';
        icon.setAttribute('icon','solar:danger-triangle-bold');
        title.textContent = 'Mark as Incomplete';
        desc.textContent  = 'This will flag the application as incomplete.';
        btn.className     = 'flex-1 rounded-xl px-4 py-2.5 text-xs font-semibold text-white bg-orange-500 hover:bg-orange-600 transition-colors';
    }

    // Inject lock_confirmed field if needed
    const existing = form.querySelector('input[name="lock_confirmed"]');
    if (existing) existing.remove();
    if (lockConfirmed) {
        const lc = document.createElement('input');
        lc.type = 'hidden'; lc.name = 'lock_confirmed'; lc.value = '1';
        form.appendChild(lc);
    }

    document.getElementById('reason-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.getElementById('reason-textarea').focus();
}
function closeReasonModal() {
    document.getElementById('reason-modal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('reason-textarea').value = '';
}

// ── App PDF Preview ────────────────────────────────────────
function openPdfPreview()  { document.getElementById('pdf-preview-modal').classList.remove('hidden'); document.body.style.overflow='hidden'; }
function closePdfPreview() { document.getElementById('pdf-preview-modal').classList.add('hidden'); document.body.style.overflow=''; }

// ── Document Preview ───────────────────────────────────────
function openDocPreview(url, ext, name) {
    const modal = document.getElementById('doc-preview-modal');
    const body  = document.getElementById('doc-modal-body');
    document.getElementById('doc-modal-title').textContent = name;
    document.getElementById('doc-modal-download').href = url;
    body.innerHTML = ['jpg','jpeg','png','gif','webp'].includes(ext)
        ? `<img src="${url}" class="max-h-full max-w-full rounded-lg shadow-lg object-contain" alt="${name}">`
        : `<iframe src="${url}" class="w-full h-full rounded-lg" style="min-height:70vh;"></iframe>`;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDocPreview() {
    document.getElementById('doc-preview-modal').classList.add('hidden');
    document.getElementById('doc-modal-body').innerHTML = '';
    document.body.style.overflow = '';
}

// ── Notice Modal ───────────────────────────────────────────
function openNoticeModal()  { document.getElementById('notice-modal').classList.remove('hidden'); document.body.style.overflow='hidden'; }
function closeNoticeModal() { document.getElementById('notice-modal').classList.add('hidden'); document.body.style.overflow=''; }

function updateNoticeSubject(select) {
    const ref = '{{ $application->reference_number }}';
    const subjects = {
        'Application Approved': `Your Application Has Been Approved — ${ref}`,
        'Application Rejected': `Application Status Update — ${ref}`,
        'Missing Requirements': `Missing Requirements — ${ref}`,
        'Pending Payment': `Pending Payment — ${ref}`,
        'Document Verification Needed': `Document Verification Required — ${ref}`,
        'Interview Schedule': `Interview Schedule — ${ref}`,
        'Entrance Exam Schedule': `Entrance Exam Schedule — ${ref}`,
        'Custom Message': `Message from MMSC Registrar — ${ref}`,
    };
    const el = document.getElementById('notice-subject');
    if (subjects[select.value]) el.value = subjects[select.value];
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeShowLockModal(); closeReasonModal();
        closeDocPreview(); closePdfPreview(); closeNoticeModal();
    }
});

function submitShowApprove() {
    document.getElementById('show-approve-form')?.submit();
}
</script>

{{-- Finance Clearance Edit Modal --}}
@if($isApproved)
<div id="fc-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeFinanceClearanceModal()"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">

        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:shield-check-bold" width="16" class="text-white"></iconify-icon>
                <h3 class="text-sm font-bold text-white">Edit Finance Clearance</h3>
            </div>
            <button onclick="closeFinanceClearanceModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-colors">
                <iconify-icon icon="solar:close-bold" width="14"></iconify-icon>
            </button>
        </div>

        {{-- Student info strip --}}
        <div class="px-6 py-3 bg-slate-50 dark:bg-dark-border/30 border-b border-slate-100 dark:border-dark-border">
            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $application->first_name }} {{ $application->last_name }}</p>
            <p class="text-xs text-slate-400">{{ $application->reference_number }} &bull; {{ $application->incoming_grade_level }} &bull; {{ $application->school_year }}</p>
        </div>

        <div class="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto">
            {{-- Payment Status --}}
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Payment Status</p>
                <div class="space-y-2" id="fc-radio-group">
                    @foreach([
                        ['cleared', 'Fully Paid',                 'Student has fully settled all required fees.',       'border-green-300 bg-green-50 dark:bg-green-900/20'],
                        ['pending', 'Partial / Downpayment Only',  'Downpayment paid; balance settlement in progress.', 'border-amber-300 bg-amber-50 dark:bg-amber-900/20'],
                        ['hold',    'Not Paid',                    'No payment received yet — enrollment is blocked.',  'border-red-300 bg-red-50 dark:bg-red-900/20'],
                    ] as [$val, $lbl, $desc, $activeClass])
                    <label class="fc-radio-label flex items-start gap-3 rounded-xl border-2 border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-3 cursor-pointer transition-all hover:border-slate-300"
                           data-active-class="{{ $activeClass }} border-current"
                           data-value="{{ $val }}">
                        <input type="radio" name="fc_status" value="{{ $val }}" class="mt-0.5 shrink-0 text-[#0d4c8f] focus:ring-[#0d4c8f]"
                               {{ ($application->finance_clearance ?? 'not_set') === $val ? 'checked' : '' }}>
                        <div>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $lbl }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $desc }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Payment Summary --}}
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">Payment Summary <span class="font-normal normal-case text-slate-400">(Informational Only)</span></p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Total Assessment</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">₱</span>
                            <input type="number" id="fc-total-assessment" min="0" step="0.01"
                                value="{{ $application->finance_total_assessment }}"
                                placeholder="0.00"
                                class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-bg pl-7 pr-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Amount Paid</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">₱</span>
                            <input type="number" id="fc-amount-paid" min="0" step="0.01"
                                value="{{ $application->finance_amount_paid }}"
                                placeholder="0.00"
                                class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-bg pl-7 pr-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]">
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Next Due Date <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="date" id="fc-next-due-date"
                        value="{{ $application->finance_next_due_date?->format('Y-m-d') }}"
                        class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-bg px-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]">
                </div>
            </div>

            {{-- Cleared By --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Cleared By <span class="text-slate-400 font-normal">(optional)</span></label>
                <input type="text" id="fc-cleared-by"
                    value="{{ $application->finance_cleared_by }}"
                    placeholder="Name of cashier / finance officer"
                    class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-bg px-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]">
            </div>

            {{-- Remarks --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5">Remarks <span class="text-slate-400 font-normal">(optional)</span></label>
                <textarea id="fc-notes-input" rows="3"
                    class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-dark-bg px-3 py-2.5 text-xs text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f] resize-none"
                    placeholder="e.g. Downpayment received on April 20. Balance due July 15.">{{ $application->finance_clearance_notes }}</textarea>
            </div>

            <div id="fc-save-error" class="hidden rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-600"></div>

            <div class="flex gap-2">
                <button type="button" onclick="closeFinanceClearanceModal()"
                    class="flex-1 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="button" id="fc-save-btn" onclick="saveFinanceClearance()"
                    class="flex-1 rounded-xl bg-[#0d4c8f] hover:bg-[#0a3d73] px-4 py-2.5 text-xs font-semibold text-white transition-colors flex items-center justify-center gap-1.5">
                    <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                    Save Clearance
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const _fcUpdateUrl = '{{ route('admin.admission.finance-clearance', $application->id) }}';
const _fcToken     = '{{ csrf_token() }}';

// Style radio cards on init and on change
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.fc-radio-label').forEach(label => {
        const radio = label.querySelector('input[type=radio]');
        function applyStyle() {
            const ac = label.dataset.activeClass;
            if (radio.checked) {
                label.className = label.className.replace(/border-slate-200[\w\/:-]*/g, '');
                ac.split(' ').forEach(c => label.classList.add(c));
            } else {
                ac.split(' ').forEach(c => label.classList.remove(c));
                label.classList.add('border-slate-200', 'dark:border-dark-border', 'bg-white', 'dark:bg-dark-card');
            }
        }
        applyStyle();
        radio.addEventListener('change', () => {
            document.querySelectorAll('.fc-radio-label').forEach(l => {
                const r = l.querySelector('input[type=radio]');
                const ac = l.dataset.activeClass;
                ac.split(' ').forEach(c => l.classList.remove(c));
                l.classList.add('border-slate-200', 'dark:border-dark-border', 'bg-white', 'dark:bg-dark-card');
            });
            applyStyle();
        });
    });
});

function openFinanceClearanceModal() {
    document.getElementById('fc-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeFinanceClearanceModal() {
    document.getElementById('fc-modal').classList.add('hidden');
    document.body.style.overflow = '';
    document.getElementById('fc-save-error').classList.add('hidden');
}

function saveFinanceClearance() {
    const status     = document.querySelector('input[name="fc_status"]:checked')?.value;
    const notes      = document.getElementById('fc-notes-input').value.trim();
    const total      = document.getElementById('fc-total-assessment').value || null;
    const paid       = document.getElementById('fc-amount-paid').value || null;
    const dueDate    = document.getElementById('fc-next-due-date').value || null;
    const clearedBy  = document.getElementById('fc-cleared-by').value.trim() || null;
    const btn        = document.getElementById('fc-save-btn');
    const errEl      = document.getElementById('fc-save-error');

    if (!status) { errEl.textContent = 'Please select a payment status.'; errEl.classList.remove('hidden'); return; }

    btn.disabled = true;
    btn.innerHTML = '<iconify-icon icon="solar:spinner-line-duotone" width="14" class="animate-spin"></iconify-icon> Saving…';
    errEl.classList.add('hidden');

    fetch(_fcUpdateUrl, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': _fcToken, 'Accept': 'application/json' },
        body: JSON.stringify({
            finance_clearance:        status,
            finance_clearance_notes:  notes,
            finance_total_assessment: total,
            finance_amount_paid:      paid,
            finance_next_due_date:    dueDate,
            finance_cleared_by:       clearedBy,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) throw new Error(data.message || 'Failed to save.');
        closeFinanceClearanceModal();
        window.location.reload();
    })
    .catch(e => {
        errEl.textContent = e.message;
        errEl.classList.remove('hidden');
        btn.disabled = false;
        btn.innerHTML = '<iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon> Save Clearance';
    });
}
</script>
@endif

{{-- Finance Modals --}}
@include('admin.partials.finance-update-modal')
@include('admin.partials.finance-config-modal')

{{-- Records / Document Verification Modal --}}
@include('admin.partials.records-modal')
@endsection