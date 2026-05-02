{{--
    Dynamic Records / Document Verification Modal
    Exposes: window.openDynamicRecordsModalApprove(appId, onApproved)
    Included in admission.blade.php (table view) — loads data via AJAX.
--}}

<div x-data="dynamicRecordsModal()"
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

        {{-- Loading state --}}
        <div x-show="loading" class="px-6 py-16 flex flex-col items-center gap-3">
            <iconify-icon icon="solar:spinner-line-duotone" width="32" class="text-[#0d4c8f] animate-spin"></iconify-icon>
            <p class="text-sm text-slate-500">Loading documents…</p>
        </div>

        {{-- Content --}}
        <div x-show="!loading" class="px-6 py-5 space-y-4">

            <template x-for="key in Object.keys(docs)" :key="key">
                <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">

                    {{-- Doc header --}}
                    <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-dark-border">
                        <iconify-icon icon="solar:file-text-bold" width="15" class="text-slate-400 shrink-0"></iconify-icon>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 flex-1" x-text="docs[key].label"></span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                            :class="docs[key].required ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400'"
                            x-text="docs[key].required ? 'REQUIRED' : 'OPTIONAL'"></span>
                    </div>

                    <div class="px-4 py-3 space-y-3">

                        {{-- Status radios --}}
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mb-2">Status</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <template x-for="opt in statusOptions" :key="opt.value">
                                    <label class="cursor-pointer" @click="docs[key].status = opt.value">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold transition-all cursor-pointer select-none"
                                            :class="[opt.badgeCls, docs[key].status === opt.value ? 'ring-2 opacity-100 ' + opt.ringCls : 'opacity-60 hover:opacity-80']">
                                            <iconify-icon :icon="opt.icon" width="12"></iconify-icon>
                                            <span x-text="opt.label"></span>
                                        </span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        {{-- Uploaded file row --}}
                        <template x-if="docs[key].uploaded && docs[key].filename">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 px-3 py-2">
                                    <iconify-icon icon="solar:file-check-bold" width="14" class="text-green-500 shrink-0"></iconify-icon>
                                    <span class="text-xs text-green-700 dark:text-green-400 font-medium flex-1 truncate" x-text="docs[key].filename"></span>
                                </div>
                                {{-- Uploaded & Approved quick-confirm --}}
                                <label class="flex items-center gap-2 cursor-pointer mt-2">
                                    <input type="checkbox"
                                        :checked="docs[key].status === 'approved'"
                                        @change="docs[key].status = $event.target.checked ? 'approved' : 'pending'"
                                        class="rounded border-slate-300 text-green-600 focus:ring-green-500 cursor-pointer">
                                    <span class="text-xs text-slate-600 dark:text-slate-400">Uploaded and Approved</span>
                                </label>
                            </div>
                        </template>

                        {{-- Not uploaded — physical submitted checkbox --}}
                        <template x-if="!docs[key].uploaded">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                    x-model="docs[key].submitted"
                                    @change="if (docs[key].submitted && docs[key].status === 'not_uploaded') docs[key].status = 'pending'"
                                    class="rounded border-slate-300 text-[#0d4c8f] focus:ring-[#0d4c8f] cursor-pointer">
                                <span class="text-xs text-slate-600 dark:text-slate-400">Physical copy submitted (no digital file)</span>
                            </label>
                        </template>

                    </div>
                </div>
            </template>

            {{-- Summary --}}
            <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/30 px-5 py-4 space-y-2">
                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wide mb-3">Summary</p>
                <div class="flex items-center gap-3 text-xs flex-wrap">
                    <span class="text-green-600 font-semibold">Approved: <span x-text="approvedCount"></span></span>
                    <span class="text-amber-600 font-semibold">Pending: <span x-text="pendingCount"></span></span>
                    <span class="text-slate-400 font-semibold">Not Uploaded: <span x-text="notUploadedCount"></span></span>
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

        </div>

        {{-- Footer --}}
        <div x-show="!loading" class="flex items-center justify-between gap-3 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01] sticky bottom-0">
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
function dynamicRecordsModal() {
    return {
        open: false,
        loading: false,
        saving: false,
        docs: {},
        requiredKeys: [],
        saveUrl: '',
        _onApproved: null,

        statusOptions: [
            { value: 'not_uploaded', label: 'Not Uploaded',        badgeCls: 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400', ringCls: 'ring-slate-300',  icon: 'solar:close-circle-bold'  },
            { value: 'pending',      label: 'Uploaded (Pending)',   badgeCls: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', ringCls: 'ring-amber-400', icon: 'solar:clock-circle-bold'  },
            { value: 'approved',     label: 'Approved (Cleared)',   badgeCls: 'bg-green-600 text-white dark:bg-green-700',                            ringCls: 'ring-green-600', icon: 'solar:check-circle-bold'  },
        ],

        get approvedCount()     { return this.requiredKeys.filter(k => this.docs[k]?.status === 'approved').length; },
        get pendingCount()      { return this.requiredKeys.filter(k => this.docs[k]?.status === 'pending').length; },
        get notUploadedCount()  { return this.requiredKeys.filter(k => this.docs[k]?.status === 'not_uploaded').length; },
        get allRequiredApproved() { return this.requiredKeys.length > 0 && this.requiredKeys.every(k => this.docs[k]?.status === 'approved'); },

        init() {
            window.openDynamicRecordsModalApprove = (appId, onApproved) => {
                this._onApproved = onApproved || null;
                this.docs = {};
                this.requiredKeys = [];
                this.saveUrl = '';
                this.loading = true;
                this.open = true;
                document.body.style.overflow = 'hidden';

                fetch(`/admin/admission/${appId}/documents-data`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                })
                .then(r => r.json())
                .then(data => {
                    const docs = {};
                    Object.entries(data.docs).forEach(([k, d]) => {
                        docs[k] = { label: d.label, required: d.required, uploaded: d.uploaded, filename: d.filename, status: d.status, submitted: d.submitted };
                        // Auto-approve pending docs (same as show page openRecordsModalApprove)
                        if (docs[k].status === 'pending') docs[k].status = 'approved';
                        if (!docs[k].submitted && docs[k].status === 'not_uploaded') docs[k].submitted = true;
                    });
                    this.docs = docs;
                    this.requiredKeys = data.required_keys;
                    this.saveUrl = data.save_url;
                    this.loading = false;
                })
                .catch(() => {
                    this.loading = false;
                    alert('Failed to load document data.');
                    this.closeModal();
                });
            };
        },

        closeModal() {
            this.open = false;
            document.body.style.overflow = '';
        },

        async saveAndContinue() {
            this.saving = true;
            const fd = new FormData();
            fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            Object.keys(this.docs).forEach(k => {
                fd.append(k + '_status',    this.docs[k].status);
                fd.append(k + '_submitted', this.docs[k].submitted ? '1' : '0');
            });

            try {
                const r = await fetch(this.saveUrl, { method: 'POST', body: fd });
                const d = await r.json();
                if (d.success) {
                    this.closeModal();
                    if (this._onApproved) this._onApproved();
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
            if (this._onApproved) this._onApproved();
        },
    };
}
</script>
