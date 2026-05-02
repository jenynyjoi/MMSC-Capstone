{{--
    Finance Configuration Modal
    Exposes: window.openFinanceModal(config), window.closeFinanceModal()
    config = {
        referenceNumber, applicationId, studentId, gradeLevel, studentCategory, schoolYear, studentName,
        onSaved: function(financeData) {}
    }
--}}

<div id="finance-config-modal"
     x-data="financeConfigModal()"
     x-show="open"
     x-transition.opacity
     class="fixed inset-0 z-[200] flex items-center justify-center p-4"
     style="display:none">

    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>

    <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-2xl bg-white dark:bg-dark-card shadow-2xl border border-slate-200 dark:border-dark-border" @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-gradient-to-r from-blue-50 to-white dark:from-blue-900/10 dark:to-dark-card flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                    <iconify-icon icon="solar:wallet-bold" width="18" class="text-[#0d4c8f] dark:text-blue-400"></iconify-icon>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Finance Configuration</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400" x-text="studentLabel"></p>
                </div>
            </div>
            <button @click="closeModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
            </button>
        </div>

        {{-- Loading --}}
        <div x-show="loading" class="flex items-center justify-center py-16 flex-shrink-0">
            <div class="flex items-center gap-3 text-slate-500">
                <iconify-icon icon="solar:spinner-line-duotone" width="24" class="animate-spin"></iconify-icon>
                <span class="text-sm">Loading...</span>
            </div>
        </div>

        {{-- Body --}}
        <div x-show="!loading" class="overflow-y-auto flex-1 px-6 py-5 space-y-5">

            {{-- Student info + editable category --}}
            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/30 border border-slate-200 dark:border-dark-border px-4 py-3">
                <div class="grid grid-cols-3 gap-3 text-xs mb-3">
                    <div>
                        <span class="text-slate-400 font-medium uppercase tracking-wide text-[10px]">Student</span>
                        <p class="text-slate-700 dark:text-white font-semibold mt-0.5" x-text="cfg.studentName || '—'"></p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium uppercase tracking-wide text-[10px]">Grade Level</span>
                        <p class="text-slate-700 dark:text-white font-semibold mt-0.5" x-text="cfg.gradeLevel || '—'"></p>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium uppercase tracking-wide text-[10px]">School Year</span>
                        <p class="text-slate-700 dark:text-white font-semibold mt-0.5" x-text="'SY ' + (cfg.schoolYear || '—')"></p>
                    </div>
                </div>
                {{-- Editable Student Category --}}
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Student Category</span>
                        <span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-semibold">Editable</span>
                    </div>
                    <select x-model="editableCategory" @change="onCategoryChange()"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-xs text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]">
                        <option value="Regular Payee">Regular Payee</option>
                        <template x-if="isJhs">
                            <option value="ESC Grantee">ESC Grantee</option>
                        </template>
                        <template x-if="isShs">
                            <option value="SHS Voucher Recipient">SHS Voucher Recipient</option>
                        </template>
                    </select>
                    <p x-show="editableCategory !== cfg.studentCategory"
                        class="mt-1 text-[10px] text-amber-600 font-semibold">
                        ⚠ Changed from original: <span x-text="cfg.studentCategory"></span>
                    </p>
                </div>
            </div>

            {{-- Payment type info based on editable category --}}
            <div class="rounded-xl border px-4 py-3"
                :class="isShs ? 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800'
                    : editableCategory.toLowerCase().includes('esc') ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800'
                    : 'bg-slate-50 dark:bg-dark-border/30 border-slate-200 dark:border-dark-border'">
                <p class="text-[10px] font-bold uppercase tracking-wide mb-1"
                    :class="isShs ? 'text-amber-600 dark:text-amber-400'
                        : editableCategory.toLowerCase().includes('esc') ? 'text-blue-600 dark:text-blue-400'
                        : 'text-slate-400'">
                    Applicable Payment Type
                </p>
                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200" x-text="paymentTypeInfo"></p>
            </div>

            {{-- Plan selector --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">
                    <span x-text="isShs ? 'Payment Option' : 'Payment Plan'"></span>
                    <span class="text-red-500">*</span>
                </label>
                <template x-if="planOptions.length === 0">
                    <p class="text-xs text-slate-400 italic">No plans available for this combination.</p>
                </template>
                <div class="grid gap-2" :class="planOptions.length <= 2 ? 'grid-cols-2' : 'grid-cols-2 sm:grid-cols-4'">
                    <template x-for="plan in planOptions" :key="plan.key">
                        <button type="button" @click="selectPlan(plan)"
                            :class="selectedPlanKey === plan.key
                                ? 'ring-2 ring-[#0d4c8f] bg-blue-50 dark:bg-blue-900/30 border-blue-300'
                                : 'border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg hover:bg-slate-50'"
                            class="flex flex-col items-start rounded-xl border px-3 py-2.5 text-left transition-all">
                            <div class="flex items-center gap-1.5 w-full">
                                <span class="text-xs font-bold text-slate-700 dark:text-white"
                                    x-text="isShs ? 'Option ' + plan.key : 'Plan ' + plan.key"></span>
                                <template x-if="plan.isFullCash">
                                    <span class="ml-auto text-[10px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full font-bold">CASH</span>
                                </template>
                            </div>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-tight" x-text="plan.label"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-dark-border"></div>

            {{-- Payment Status (only shown after plan selected; auto-set for Plan A) --}}
            <div x-show="selectedPlanKey">
                <div class="flex items-center gap-2 mb-3">
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-300">Payment Status</p>
                    <span x-show="fcStatus === 'cleared'" class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold">Auto-set</span>
                </div>
                <div class="space-y-2">
                    <template x-for="opt in paymentStatusOptions" :key="opt.value">
                        <label class="flex items-start gap-3 rounded-xl border-2 cursor-pointer transition-all px-4 py-3"
                            :class="fcStatus === opt.value ? opt.activeClass : 'border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg hover:border-slate-300'">
                            <input type="radio" :value="opt.value" x-model="fcStatus" class="mt-0.5 shrink-0 text-[#0d4c8f]">
                            <div>
                                <p class="text-xs font-bold text-slate-700 dark:text-white" x-text="opt.label"></p>
                                <p class="text-[11px] text-slate-400 mt-0.5" x-text="opt.desc"></p>
                            </div>
                        </label>
                    </template>
                </div>
            </div>

            {{-- Fee Breakdown --}}
            <div x-show="selectedPlanKey">
                <div class="rounded-xl bg-slate-50 dark:bg-dark-border/30 border border-slate-200 dark:border-dark-border overflow-hidden">
                    <div class="px-4 py-2 border-b border-slate-200 dark:border-dark-border">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Fee Breakdown</p>
                    </div>
                    <div class="divide-y divide-slate-100 dark:divide-dark-border text-xs">

                        {{-- Downpayment / Tuition --}}
                        <div class="flex justify-between px-4 py-2">
                            <span class="text-slate-500" x-text="monthlyMonths > 0 ? 'Downpayment (due upon enrollment)' : 'Tuition Fee'"></span>
                            <span class="font-semibold text-slate-700 dark:text-slate-200" x-text="'₱' + fcFmt(enrollmentFee)"></span>
                        </div>

                        {{-- Monthly schedule table --}}
                        <template x-if="monthlyMonths > 0">
                            <div>
                                <div class="px-4 py-1.5 bg-blue-50/60 dark:bg-blue-900/10 border-t border-slate-100 dark:border-dark-border">
                                    <p class="text-[10px] font-bold text-[#0d4c8f] dark:text-blue-400 uppercase tracking-wide"
                                        x-text="'Monthly Installment Schedule (' + monthlyMonths + ' months @ ₱' + fcFmt(monthlyAmount) + '/mo)'"></p>
                                </div>
                                <table class="w-full text-xs border-t border-slate-100 dark:border-dark-border">
                                    <thead>
                                        <tr class="bg-slate-100 dark:bg-slate-800/50 text-slate-400 text-[10px] uppercase tracking-wide">
                                            <th class="px-4 py-1.5 text-left font-semibold w-8">#</th>
                                            <th class="px-4 py-1.5 text-left font-semibold">Due Date</th>
                                            <th class="px-4 py-1.5 text-right font-semibold">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                                        <template x-for="(sched, idx) in monthlySchedule" :key="idx">
                                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                                <td class="px-4 py-1.5 text-slate-400" x-text="idx + 1"></td>
                                                <td class="px-4 py-1.5 text-slate-600 dark:text-slate-300" x-text="sched.label"></td>
                                                <td class="px-4 py-1.5 text-right font-semibold text-slate-700 dark:text-slate-200" x-text="'₱' + fcFmt(monthlyAmount)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/30">
                                            <td colspan="2" class="px-4 py-2 text-slate-500 font-semibold">Monthly Total</td>
                                            <td class="px-4 py-2 text-right font-bold text-slate-700 dark:text-slate-200" x-text="'₱' + fcFmt(monthlyAmount * monthlyMonths)"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </template>

                        {{-- Miscellaneous --}}
                        <div class="flex justify-between px-4 py-2">
                            <span class="text-slate-500">Miscellaneous Fee <span class="text-slate-400">(auto)</span></span>
                            <span class="font-semibold text-blue-600 dark:text-blue-400" x-text="'₱' + fcFmt(autoMiscFee)"></span>
                        </div>

                        {{-- Total Assessment --}}
                        <div class="flex justify-between px-4 py-2.5 bg-[#0d4c8f]/5 dark:bg-blue-900/20">
                            <span class="font-bold text-slate-700 dark:text-slate-200">Total Assessment</span>
                            <span class="font-bold text-[#0d4c8f] dark:text-blue-400" x-text="'₱' + fcFmt(fcTotalAssessment)"></span>
                        </div>
                    </div>
                </div>

                {{-- Next due date (installment only) --}}
                <div x-show="monthlyMonths > 0" class="mt-3">
                    <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">First Monthly Due Date</label>
                    <input type="date" x-model="fcNextDueDate"
                        class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-xs text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]">
                </div>
            </div>

            {{-- Cleared By + Remarks --}}
            <div x-show="selectedPlanKey" class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Cleared By <span class="font-normal text-slate-400">(optional)</span></label>
                    <input type="text" x-model="fcClearedBy" placeholder="Name of cashier / finance officer"
                        class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-xs text-slate-700 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1">Remarks <span class="font-normal text-slate-400">(optional)</span></label>
                    <textarea x-model="fcRemarks" rows="2" placeholder="e.g. Downpayment received. Balance to be paid monthly."
                        class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-xs text-slate-700 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f] resize-none"></textarea>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div x-show="!loading" class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/30 flex-shrink-0">
            <button type="button" @click="closeModal()"
                class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                Cancel
            </button>
            <button type="button" @click="saveFinance()"
                :disabled="!selectedPlanKey || saving"
                :class="(!selectedPlanKey || saving) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                <iconify-icon x-show="saving" icon="solar:spinner-line-duotone" width="14" class="animate-spin"></iconify-icon>
                <iconify-icon x-show="!saving" icon="solar:check-circle-bold" width="14"></iconify-icon>
                <span x-text="saving ? 'Saving...' : 'Save & Continue'"></span>
            </button>
        </div>
    </div>
</div>

<script>
// ── Plan data ──────────────────────────────────────────────────────────
const FC_PLANS = {
    elem_1_3:   { A:[14600,0,0],  B:[5600,1000,9],  C:[4700,1100,9],  D:[3800,1100,9] },
    elem_4_6:   { A:[15500,0,0],  B:[6500,1000,9],  C:[5600,1100,9],  D:[4700,1200,9] },
    jhs_regular:{ A:[16500,0,0],  B:[7500,1000,9],  C:[6600,1100,9],  D:[5700,1200,9] },
    jhs_esc:    { A:[7500,0,0],   B:[3000,500,9]                                       },
    shs:        { A:[17500,0,0],  B:[3500,0,0],     C:[3500,0,0],     D:[0,0,0]       },
};

const FC_SHS_LABELS = {
    A: 'Regular Payee (No voucher)',
    B: 'MMSC Graduate – ESC Applied (Old Student)',
    C: 'ESC Graduate from Private JHS',
    D: 'Public JHS Graduate (FREE tuition)',
};

const FC_MISC_FEES = {
    elem_1_3:    300,
    elem_4_6:    300,
    jhs_regular: 500,
    jhs_esc:     500,
    shs:         1000,
};

const FC_NON_SHS_LABELS = {
    A: 'Full Cash Payment',
    B: 'Installment Plan B',
    C: 'Installment Plan C',
    D: 'Installment Plan D',
};

function fcResolvePlanGroup(gradeLevel, category) {
    const gl  = (gradeLevel || '').toLowerCase();
    const cat = (category  || '').toLowerCase();
    if (/grade\s*[123]|kinder/i.test(gl))            return 'elem_1_3';
    if (/grade\s*[456]/i.test(gl))                   return 'elem_4_6';
    if (/grade\s*(7|8|9|10)|junior/i.test(gl))       return cat.includes('esc') ? 'jhs_esc' : 'jhs_regular';
    return 'shs';
}

if (typeof fcFmt === 'undefined') {
    window.fcFmt = n => Number(n||0).toLocaleString('en-PH',{minimumFractionDigits:0,maximumFractionDigits:2});
}

function financeConfigModal() {
    return {
        open: false, loading: false, saving: false,
        cfg: {}, studentLabel: '',
        editableCategory: '',
        planGroupKey: '', planOptions: [], isShs: false, isJhs: false,
        selectedPlanKey: '',
        enrollmentFee: 0, monthlyAmount: 0, monthlyMonths: 0,
        miscFee: 0, referralCount: 0, noReferral: false, isFullPayment: false,
        onSavedCb: null,
        autoMiscFee: 0,
        fcStatus: '',
        fcTotalAssessment: 0,
        fcAmountPaid: 0,
        fcNextDueDate: '',
        fcClearedBy: '',
        fcRemarks: '',
        paymentStatusOptions: [
            { value: 'cleared', label: 'Fully Paid (Cleared)',        desc: 'Student has fully settled all required fees.',      activeClass: 'border-green-300 bg-green-50 dark:bg-green-900/20 border-current' },
            { value: 'pending', label: 'Partial / Downpayment Only',  desc: 'Downpayment paid; balance settlement in progress.', activeClass: 'border-amber-300 bg-amber-50 dark:bg-amber-900/20 border-current' },
        ],

        get monthlySchedule() {
            if (!this.monthlyMonths || !this.fcNextDueDate) return [];
            const months = ['January','February','March','April','May','June',
                            'July','August','September','October','November','December'];
            const base = new Date(this.fcNextDueDate + 'T00:00:00');
            if (isNaN(base)) return [];
            const rows = [];
            for (let i = 0; i < this.monthlyMonths; i++) {
                const d = new Date(base.getFullYear(), base.getMonth() + i, base.getDate());
                rows.push({ label: months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear() });
            }
            return rows;
        },

        get paymentTypeInfo() {
            const cat = (this.editableCategory || '').toLowerCase();
            if (this.isShs) {
                if (cat.includes('voucher')) return 'SHS Voucher Recipient — Applicable subsidy based on previous JHS type';
                return 'Senior High School — Regular Payee';
            }
            if (cat.includes('esc')) return 'ESC Grantee — Subsidized JHS rate applies';
            return 'Regular Payee — Standard payment rate applies';
        },

        fmt: n => fcFmt(n),

        init() {
            window.openFinanceModal  = c => this.openModal(c);
            window.closeFinanceModal = ()  => this.closeModal();
        },

        async openModal(c) {
            this.cfg             = c || {};
            this.onSavedCb       = c.onSaved || null;
            this.editableCategory= c.studentCategory || 'Regular Payee';
            this.selectedPlanKey = '';
            this.miscFee = 0; this.referralCount = 0;
            this.noReferral = false; this.isFullPayment = false;
            this.fcStatus = ''; this.fcTotalAssessment = 0;
            this.fcAmountPaid = 0; this.fcNextDueDate = '';
            this.fcClearedBy = ''; this.fcRemarks = '';

            const gl = c.gradeLevel || '';
            this.studentLabel = c.studentName ? `${c.studentName} — ${gl}` : gl;
            this._rebuildPlans(gl, this.editableCategory);

            // Auto-select SHS based on category
            if (this.isShs) {
                if (this.editableCategory.toLowerCase().includes('voucher')) this._doSelect('B');
                else if (this.editableCategory.toLowerCase().includes('public')) this._doSelect('C');
            }

            this.open = true;
            this.loading = true;

            try {
                let url = '';
                if (c.referenceNumber) url = `/admin/finance/for-application?reference_number=${encodeURIComponent(c.referenceNumber)}`;
                else if (c.studentId)  url = `/admin/finance/for-student?student_id=${encodeURIComponent(c.studentId)}`;
                if (url) {
                    const r = await fetch(url);
                    const d = await r.json();
                    if (d.finance) {
                        const f = d.finance;
                        const m = this.planOptions.find(p => p.key === f.payment_plan);
                        if (m) this.selectPlan(m);
                        this.miscFee       = parseFloat(f.misc_fee) || 0;
                        this.referralCount = parseInt(f.referral_count) || 0;
                    }
                }
            } catch(e) { /* silent */ }
            this.loading = false;
        },

        _rebuildPlans(gl, cat) {
            const glL = (gl || '').toLowerCase();
            this.isJhs        = /grade\s*(7|8|9|10)|junior/i.test(glL);
            this.planGroupKey = fcResolvePlanGroup(gl, cat);
            this.isShs        = this.planGroupKey === 'shs';
            this.autoMiscFee  = FC_MISC_FEES[this.planGroupKey] || 0;
            const grp         = FC_PLANS[this.planGroupKey] || {};
            this.planOptions  = Object.entries(grp).map(([k,[e,m,mo]]) => ({
                key: k, enroll: e, monthly: m, months: mo,
                label: this.isShs ? (FC_SHS_LABELS[k] || k) : (FC_NON_SHS_LABELS[k] || 'Plan ' + k),
                isFullCash: mo === 0 && e > 0,
            }));
        },

        onCategoryChange() {
            this._rebuildPlans(this.cfg.gradeLevel || '', this.editableCategory);
            this.selectedPlanKey  = '';
            this.fcStatus         = '';
            this.fcTotalAssessment= 0;
            this.fcAmountPaid     = 0;
            this.fcNextDueDate    = '';
            // Auto-select for SHS after category switch
            if (this.isShs) {
                if (this.editableCategory.toLowerCase().includes('voucher')) this._doSelect('B');
                else if (this.editableCategory.toLowerCase().includes('public')) this._doSelect('C');
            }
        },

        _doSelect(key) {
            const p = this.planOptions.find(x => x.key === key);
            if (p) this.selectPlan(p);
        },

        selectPlan(plan) {
            this.selectedPlanKey = plan.key;
            this.enrollmentFee   = plan.enroll;
            this.monthlyAmount   = plan.monthly;
            this.monthlyMonths   = plan.months;
            this.isFullPayment   = plan.months === 0 && plan.enroll > 0;
            this.noReferral      = this.isShs && plan.key === 'C';
            if (this.noReferral) this.referralCount = 0;

            const misc  = this.autoMiscFee;
            const total = plan.enroll + (plan.monthly * plan.months) + misc;
            this.fcTotalAssessment = total;

            if (plan.months === 0) {
                // Cash / lump-sum: fully paid at enrollment (tuition + misc)
                this.fcAmountPaid  = total;
                this.fcStatus      = 'cleared';
                this.fcNextDueDate = '';
            } else {
                // Installment: downpayment + misc collected at enrollment
                this.fcAmountPaid  = plan.enroll + misc;
                this.fcStatus      = 'pending';
                const syYear = parseInt((this.cfg.schoolYear || '').split('-')[0]) || new Date().getFullYear();
                this.fcNextDueDate = `${syYear}-08-10`;
            }
        },

        closeModal() { this.open = false; },

        async saveFinance() {
            if (!this.selectedPlanKey) return;
            if (!this.fcStatus) { alert('Please select a Payment Status.'); return; }
            this.saving = true;
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const payload = {
                payment_plan:     this.selectedPlanKey,
                enrollment_fee:   this.enrollmentFee,
                monthly_amount:   this.monthlyAmount,
                monthly_months:   this.monthlyMonths,
                misc_fee:         this.miscFee,
                referral_count:   this.noReferral ? 0 : this.referralCount,
                no_referral:      this.noReferral,
                total_fee:        this.fcTotalAssessment,
                grade_level:      this.cfg.gradeLevel,
                student_category: this.editableCategory,
                school_year:      this.cfg.schoolYear,
                reference_number: this.cfg.referenceNumber || null,
                student_id:       this.cfg.studentId || null,
            };
            try {
                const r = await fetch('/admin/finance/configure', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify(payload),
                });
                const d = await r.json();
                if (!d.success) { alert(d.message || 'Failed to save.'); this.saving = false; return; }

                // Save finance clearance + summary to application
                if (this.cfg.applicationId) {
                    await fetch(`/admin/admission/${this.cfg.applicationId}/finance-clearance`, {
                        method: 'PATCH',
                        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: JSON.stringify({
                            finance_clearance:        this.fcStatus,
                            finance_clearance_notes:  this.fcRemarks,
                            finance_total_assessment: this.fcTotalAssessment || null,
                            finance_amount_paid:      this.fcAmountPaid || null,
                            finance_next_due_date:    this.fcNextDueDate || null,
                            finance_cleared_by:       this.fcClearedBy || null,
                        }),
                    });
                }

                this.closeModal();
                if (this.onSavedCb) this.onSavedCb(d.finance);
            } catch(e) { alert('An error occurred.'); }
            this.saving = false;
        },
    };
}
</script>
