{{--
    Records / Document Verification Modal
    Exposes: window.openRecordsModal(), window.closeRecordsModal()
    Included in admission-show.blade.php — has access to $application
--}}

@php
    $appId        = $application->id;
    $appRef       = $application->reference_number;
    $isEscGrantee = $application->student_category === 'ESC Grantee';
    $appApproved  = $application->application_status === 'approved';

    // Auto-resolve status: if applicant uploaded but not yet reviewed → pending
    $autoStatus = fn($uploaded, $status) =>
        ($uploaded && ($status ?? 'not_uploaded') === 'not_uploaded') ? 'pending' : ($status ?? 'not_uploaded');

    // Auto-check submitted: if nothing uploaded but application is approved
    $autoSubmitted = fn($uploaded, $submitted, $approved) =>
        ($approved && !$uploaded && !((bool)$submitted)) ? true : (bool)$submitted;

    $requiredDocs = [
        [
            'key'      => 'psa',
            'label'    => 'PSA / Birth Certificate',
            'required' => true,
            'uploaded' => $application->psa_uploaded,
            'filename' => $application->psa_filename,
            'path'     => $application->psa_path,
            'status'   => $autoStatus($application->psa_uploaded, $application->psa_status),
            'submitted'=> $autoSubmitted($application->psa_uploaded, $application->psa_submitted ?? false, $appApproved),
        ],
        [
            'key'      => 'report_card',
            'label'    => 'Original Report Card / Form 137',
            'required' => true,
            'uploaded' => $application->report_card_uploaded,
            'filename' => $application->report_card_filename,
            'path'     => $application->report_card_path,
            'status'   => $autoStatus($application->report_card_uploaded, $application->report_card_status),
            'submitted'=> $autoSubmitted($application->report_card_uploaded, $application->report_card_submitted ?? false, $appApproved),
        ],
        [
            'key'      => 'good_moral',
            'label'    => 'Good Moral Character',
            'required' => true,
            'uploaded' => $application->good_moral_uploaded,
            'filename' => $application->good_moral_filename,
            'path'     => $application->good_moral_path,
            'status'   => $autoStatus($application->good_moral_uploaded, $application->good_moral_status),
            'submitted'=> $autoSubmitted($application->good_moral_uploaded, $application->good_moral_submitted ?? false, $appApproved),
        ],
        [
            'key'      => 'medical',
            'label'    => 'Medical Clearance',
            'required' => $isEscGrantee,
            'uploaded' => $application->medical_uploaded ?? false,
            'filename' => $application->medical_filename ?? null,
            'path'     => $application->medical_path     ?? null,
            'status'   => $autoStatus($application->medical_uploaded ?? false, $application->medical_status),
            'submitted'=> $autoSubmitted($application->medical_uploaded ?? false, $application->medical_submitted ?? false, $appApproved),
        ],
    ];

    $requiredCount = collect($requiredDocs)->where('required', true)->count();
    $optionalCount = collect($requiredDocs)->where('required', false)->count();
    $requiredKeys  = collect($requiredDocs)->where('required', true)->pluck('key')->values()->toArray();
@endphp

<div x-data="recordsModal()"
     x-show="open"
     x-transition.opacity
     class="fixed inset-0 z-[300] flex items-center justify-center p-4"
     style="display:none">

    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>

    <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white dark:bg-dark-card shadow-2xl border border-slate-200 dark:border-dark-border" @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-gradient-to-r from-indigo-50 to-white dark:from-indigo-900/10 dark:to-dark-card sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/30">
                    <iconify-icon icon="solar:folder-with-files-bold" width="18" class="text-indigo-600 dark:text-indigo-400"></iconify-icon>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Required Documents</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Verify applicant records before approval</p>
                </div>
            </div>
            <button @click="closeModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>

        <div class="px-6 py-5 space-y-4">

            {{-- Document Cards --}}
            @foreach($requiredDocs as $doc)
            @php
                $fileUrl = null;
                if ($doc['uploaded'] && $doc['path']) {
                    $fileUrl = Storage::disk('public')->url($doc['path']);
                }
                $ext = $doc['filename'] ? strtolower(pathinfo($doc['filename'], PATHINFO_EXTENSION)) : '';
            @endphp

            <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">

                {{-- Doc header --}}
                <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-dark-border">
                    <iconify-icon icon="solar:file-text-bold" width="15" class="text-slate-400 shrink-0"></iconify-icon>
                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 flex-1">{{ $doc['label'] }}</span>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $doc['required'] ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                        {{ $doc['required'] ? 'REQUIRED' : 'OPTIONAL' }}
                    </span>
                </div>

                <div class="px-4 py-3 space-y-3">

                    {{-- Status Radio --}}
                    <div>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mb-2">Status</p>
                        <div class="flex items-center gap-2 flex-wrap">
                            @foreach(['not_uploaded' => ['Not Uploaded','bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400','ring-slate-300'], 'pending' => ['Uploaded (Pending)','bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400','ring-amber-400'], 'approved' => ['Approved (Cleared)','bg-green-600 text-white dark:bg-green-700','ring-green-600']] as $val => [$statusLabel, $badgeCls, $ringCls])
                            <label class="cursor-pointer">
                                <input type="radio"
                                    name="doc_status_{{ $doc['key'] }}"
                                    value="{{ $val }}"
                                    x-model="docs.{{ $doc['key'] }}.status"
                                    class="sr-only">
                                <span :class="docs.{{ $doc['key'] }}.status === '{{ $val }}' ? 'ring-2 {{ $ringCls }} opacity-100' : 'opacity-60 hover:opacity-80'"
                                    class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold transition-all {{ $badgeCls }} cursor-pointer select-none">
                                    @if($val === 'approved')
                                    <iconify-icon icon="solar:check-circle-bold" width="12"></iconify-icon>
                                    @elseif($val === 'pending')
                                    <iconify-icon icon="solar:clock-circle-bold" width="12"></iconify-icon>
                                    @else
                                    <iconify-icon icon="solar:close-circle-bold" width="12"></iconify-icon>
                                    @endif
                                    {{ $statusLabel }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Applicant-uploaded file --}}
                    @if($doc['uploaded'] && $doc['filename'])
                    <div class="flex items-center gap-2 flex-wrap rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 px-3 py-2">
                        <iconify-icon icon="solar:file-check-bold" width="14" class="text-green-500 shrink-0"></iconify-icon>
                        <span class="text-xs text-green-700 dark:text-green-400 font-medium flex-1 truncate" title="{{ $doc['filename'] }}">
                            {{ Str::limit($doc['filename'], 35) }}
                        </span>
                        @if($fileUrl)
                        <button type="button"
                            onclick="openDocPreview('{{ $fileUrl }}','{{ $ext }}','{{ $doc['label'] }}')"
                            class="flex items-center gap-1 rounded-lg border border-green-300 dark:border-green-700 bg-white dark:bg-dark-card text-green-700 dark:text-green-400 hover:bg-green-50 px-2.5 py-1 text-xs font-semibold transition-colors">
                            <iconify-icon icon="solar:eye-bold" width="12"></iconify-icon> View
                        </button>
                        <a href="{{ route('admin.admission.document', [$appId, $doc['key']]) }}"
                           class="flex items-center gap-1 rounded-lg border border-[#0d4c8f] dark:border-blue-700 text-[#0d4c8f] dark:text-blue-400 hover:bg-blue-50 px-2.5 py-1 text-xs font-semibold transition-colors">
                            <iconify-icon icon="solar:file-download-bold" width="12"></iconify-icon> Download
                        </a>
                        @endif
                    </div>
                    @else
                    {{-- Not uploaded by applicant — admin options --}}
                    <div class="space-y-2">
                        {{-- Mark as physically submitted --}}
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                x-model="docs.{{ $doc['key'] }}.submitted"
                                @change="if (docs.{{ $doc['key'] }}.submitted && docs.{{ $doc['key'] }}.status === 'not_uploaded') docs.{{ $doc['key'] }}.status = 'pending'"
                                class="rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                            <span class="text-xs text-slate-600 dark:text-slate-400">Physical copy submitted (no digital file)</span>
                        </label>

                        {{-- Admin upload --}}
                        <div>
                            <label class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide block mb-1">Upload Document</label>
                            <input type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                @change="handleFile('{{ $doc['key'] }}', $event)"
                                class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-slate-200 file:bg-slate-50 file:text-xs file:font-semibold file:text-slate-600 hover:file:bg-blue-50 hover:file:text-[#0d4c8f] hover:file:border-blue-300 transition-colors cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">PDF, JPG, PNG accepted</p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endforeach

            {{-- Summary --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/30 px-5 py-4 space-y-2">
                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wide mb-3">Summary</p>
                <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-xs">
                    <div class="flex justify-between"><span class="text-slate-500">Required Documents</span><span class="font-semibold text-slate-700 dark:text-white">{{ $requiredCount }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Optional Documents</span><span class="font-semibold text-slate-700 dark:text-white">{{ $optionalCount }}</span></div>
                    <div class="flex justify-between"><span class="text-green-600">Approved</span><span class="font-bold text-green-600" x-text="approvedCount"></span></div>
                    <div class="flex justify-between"><span class="text-amber-600">Pending</span><span class="font-bold text-amber-600" x-text="pendingCount"></span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Not Uploaded</span><span class="font-bold text-slate-500" x-text="notUploadedCount"></span></div>
                </div>
                <div x-show="!allRequiredApproved" class="mt-3 flex items-start gap-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 px-3 py-2">
                    <iconify-icon icon="solar:danger-triangle-bold" width="14" class="text-amber-500 mt-0.5 shrink-0"></iconify-icon>
                    <p class="text-xs text-amber-700 dark:text-amber-400">Cannot proceed until all required documents are approved.</p>
                </div>
                <div x-show="allRequiredApproved" class="mt-3 flex items-center gap-2 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-3 py-2">
                    <iconify-icon icon="solar:check-circle-bold" width="14" class="text-green-500 shrink-0"></iconify-icon>
                    <p class="text-xs text-green-700 dark:text-green-400 font-medium">All required documents approved. Ready to proceed.</p>
                </div>
            </div>

            {{-- Notify checkbox --}}
            <label class="flex items-center gap-2 cursor-pointer rounded-lg border border-slate-200 dark:border-dark-border px-4 py-3 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                <input type="checkbox" x-model="notifyStudent" class="rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f]">
                <span class="text-xs text-slate-600 dark:text-slate-300">Send notification to student/parent about document status</span>
            </label>

        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01] sticky bottom-0">
            <button @click="skipForNow()"
                class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-5 py-2.5 text-xs font-semibold text-slate-600 dark:text-slate-300 transition-colors">
                Skip For Now
            </button>
            <button @click="saveAndContinue()"
                :disabled="saving"
                class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 disabled:opacity-60 disabled:cursor-not-allowed px-6 py-2.5 text-xs font-semibold text-white transition-colors">
                <iconify-icon x-show="saving" icon="solar:spinner-line-duotone" width="14" class="animate-spin"></iconify-icon>
                <iconify-icon x-show="!saving" icon="solar:check-circle-bold" width="14"></iconify-icon>
                <span x-text="saving ? 'Saving…' : 'Save & Continue'"></span>
            </button>
        </div>

    </div>
</div>

<script>
const _requiredDocKeys = @json($requiredKeys);

function recordsModal() {
    return {
        open: false,
        saving: false,
        notifyStudent: true,
        docs: {
            @foreach($requiredDocs as $d)
            {{ $d['key'] }}: { status: '{{ $d['status'] }}', submitted: {{ $d['submitted'] ? 'true' : 'false' }}, file: null },
            @endforeach
        },

        get approvedCount() {
            return _requiredDocKeys.filter(k => this.docs[k].status === 'approved').length;
        },
        get pendingCount() {
            return _requiredDocKeys.filter(k => this.docs[k].status === 'pending').length;
        },
        get notUploadedCount() {
            return _requiredDocKeys.filter(k => this.docs[k].status === 'not_uploaded').length;
        },
        get allRequiredApproved() {
            return _requiredDocKeys.every(k => this.docs[k].status === 'approved');
        },

        _pendingApprove: false,

        init() {
            window.openRecordsModal         = () => { this._pendingApprove = false; this.open = true; document.body.style.overflow = 'hidden'; };
            window.openRecordsModalApprove  = () => { this._pendingApprove = true;  this.open = true; document.body.style.overflow = 'hidden'; };
            window.closeRecordsModal        = () => { this.closeModal(); };
        },

        closeModal() {
            this.open = false;
            document.body.style.overflow = '';
        },

        handleFile(key, event) {
            const file = event.target.files[0];
            if (!file) return;
            this.docs[key].file = file;
            // Auto-set to pending if a file is chosen and status is still not_uploaded
            if (this.docs[key].status === 'not_uploaded') {
                this.docs[key].status = 'pending';
            }
        },

        async saveAndContinue() {
            this.saving = true;
            const fd = new FormData();
            fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            ['psa', 'report_card', 'good_moral', 'medical'].forEach(k => {
                fd.append(k + '_status',    this.docs[k].status);
                fd.append(k + '_submitted', this.docs[k].submitted ? '1' : '0');
                if (this.docs[k].file) fd.append(k + '_file', this.docs[k].file);
            });

            try {
                const r = await fetch('{{ route("admin.admission.documents", $application->id) }}', {
                    method: 'POST',
                    body: fd,
                });
                const d = await r.json();
                if (d.success) {
                    this.closeModal();
                    if (this._pendingApprove) submitShowApprove();
                    else window.location.reload();
                } else {
                    alert(d.message || 'Failed to save documents.');
                }
            } catch(e) {
                alert('An error occurred while saving documents.');
            }
            this.saving = false;
        },

        skipForNow() {
            this.closeModal();
            if (this._pendingApprove) submitShowApprove();
        },
    };
}
</script>
