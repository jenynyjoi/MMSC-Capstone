{{--
    Finance Update / Details / Receipt / Reminder Modal
    Exposes:
        window.openUpdateFeeModal(studentId)       — record payment
        window.openFinanceDetails(studentId)       — view payment history
        window.openFinanceReceipt(paymentId)       — view receipt for a specific payment
        window.openLatestReceipt(studentId)        — view most recent receipt
        window.openFinanceReminder(studentId,name) — send payment reminder
--}}

<div id="finance-update-modal"
     x-data="financeUpdateModal()"
     x-show="open"
     x-transition.opacity
     class="fixed inset-0 z-[200] flex items-center justify-center p-4"
     style="display:none">

    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>

    <div class="relative w-full max-w-2xl max-h-[90vh] flex flex-col rounded-2xl bg-white dark:bg-dark-card shadow-2xl border border-slate-200 dark:border-dark-border" @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl"
                     :class="{
                         'bg-green-100 dark:bg-green-900/30':  mode==='pay',
                         'bg-blue-100 dark:bg-blue-900/30':    mode==='details',
                         'bg-amber-100 dark:bg-amber-900/30':  mode==='receipt',
                         'bg-violet-100 dark:bg-violet-900/30':mode==='reminder',
                     }">
                    <iconify-icon x-show="mode==='pay'"      icon="solar:card-bold"          width="18" class="text-green-600 dark:text-green-400"></iconify-icon>
                    <iconify-icon x-show="mode==='details'"  icon="solar:document-text-bold" width="18" class="text-blue-600 dark:text-blue-400"></iconify-icon>
                    <iconify-icon x-show="mode==='receipt'"  icon="solar:receipt-bold"       width="18" class="text-amber-600 dark:text-amber-400"></iconify-icon>
                    <iconify-icon x-show="mode==='reminder'" icon="solar:bell-bing-bold"     width="18" class="text-violet-600 dark:text-violet-400"></iconify-icon>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800 dark:text-white"
                        x-text="mode==='pay' ? 'Record Payment' : mode==='details' ? 'Payment Details' : mode==='receipt' ? 'Official Receipt' : 'Send Payment Reminder'"></h3>
                    <p class="text-xs text-slate-500" x-text="studentLabel"></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <template x-if="mode==='receipt'">
                    <button @click="printReceipt()" class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        <iconify-icon icon="solar:printer-bold" width="14"></iconify-icon> Print
                    </button>
                </template>
                <button @click="closeModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:close-circle-linear" width="18"></iconify-icon>
                </button>
            </div>
        </div>

        {{-- Loading --}}
        <div x-show="loading" class="flex items-center justify-center py-16 flex-shrink-0">
            <div class="flex items-center gap-3 text-slate-500">
                <iconify-icon icon="solar:spinner-line-duotone" width="24" class="animate-spin"></iconify-icon>
                <span class="text-sm">Loading...</span>
            </div>
        </div>

        {{-- ── MODE: PAY ────────────────────────────────────── --}}
        <div x-show="!loading && mode==='pay'" class="overflow-y-auto flex-1 px-6 py-5 space-y-5">

            {{-- Finance summary --}}
            <div x-show="finance" class="grid grid-cols-3 gap-3">
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/30 px-4 py-3">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Total Fee</span>
                    <p class="text-base font-bold text-slate-800 dark:text-white mt-0.5" x-text="'₱' + fcFmt(finance?.total_fee)"></p>
                </div>
                <div class="rounded-xl bg-green-50 dark:bg-green-900/20 px-4 py-3">
                    <span class="text-[10px] text-green-600 uppercase font-semibold">Paid</span>
                    <p class="text-base font-bold text-green-700 dark:text-green-400 mt-0.5" x-text="'₱' + fcFmt(finance?.amount_paid)"></p>
                </div>
                <div class="rounded-xl px-4 py-3"
                    :class="(finance?.balance ?? 0) > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20'">
                    <span class="text-[10px] uppercase font-semibold"
                        :class="(finance?.balance ?? 0) > 0 ? 'text-red-600' : 'text-green-600'">Balance</span>
                    <p class="text-base font-bold mt-0.5"
                        :class="(finance?.balance ?? 0) > 0 ? 'text-red-700 dark:text-red-400' : 'text-green-700 dark:text-green-400'"
                        x-text="'₱' + fcFmt(finance?.balance)"></p>
                </div>
            </div>

            {{-- Fully paid notice --}}
            <div x-show="finance && (finance?.balance ?? 0) <= 0"
                class="flex items-center gap-3 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 px-4 py-3">
                <iconify-icon icon="solar:check-circle-bold" width="18" class="text-green-600 shrink-0"></iconify-icon>
                <p class="text-sm font-semibold text-green-700 dark:text-green-400">This student's balance is fully paid.</p>
            </div>

            {{-- Payment form --}}
            <div x-show="finance && (finance?.balance ?? 0) > 0" class="space-y-4">

                {{-- Monthly schedule (select which months to cover) --}}
                <div x-show="finance?.payment_months?.length > 0 || finance?.paymentMonths?.length > 0">
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">Apply Payment To (select months)</label>
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="m in (finance?.paymentMonths || finance?.payment_months || [])" :key="m.id">
                            <label :class="m.status==='paid' ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer hover:border-blue-300'"
                                class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 transition-all">
                                <input type="checkbox" :value="m.id"
                                    x-model="selectedMonthIds"
                                    :disabled="m.status==='paid'"
                                    class="rounded text-blue-600 focus:ring-blue-500">
                                <div class="min-w-0">
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300" x-text="m.month_name"></span>
                                    <div class="flex items-center gap-1 mt-0.5">
                                        <span class="text-[10px] text-slate-400" x-text="'₱' + fcFmt(m.amount_due)"></span>
                                        <span class="text-[10px] rounded-full px-1.5 font-semibold"
                                            :class="{
                                                'bg-green-100 text-green-700': m.status==='paid',
                                                'bg-amber-100 text-amber-700': m.status==='pending',
                                                'bg-red-100 text-red-700': m.status==='overdue',
                                                'bg-blue-100 text-blue-700': m.status==='partial',
                                            }" x-text="m.status"></span>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                {{-- Amount + Date --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Amount Paid <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">₱</span>
                            <input type="number" x-model.number="payAmount" min="0.01" step="0.01"
                                :max="finance?.balance"
                                :placeholder="'Max ₱' + fcFmt(finance?.balance)"
                                class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg pl-7 pr-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <p x-show="selectedMonthIds.length > 0" class="mt-1 text-[11px] text-blue-600 font-medium">
                            Auto-filled from selected months
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Payment Date <span class="text-red-500">*</span></label>
                        <input type="date" x-model="payDate" :max="today"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Payment Method --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">Payment Method <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        {{-- Cash --}}
                        <label :class="payMethodGroup==='cash' ? 'ring-2 ring-[#0d4c8f] border-blue-300 bg-blue-50 dark:bg-blue-900/20' : 'border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg hover:bg-slate-50'"
                            class="flex items-center gap-2.5 rounded-xl border px-4 py-3 cursor-pointer transition-all">
                            <input type="radio" value="cash" x-model="payMethodGroup" class="sr-only">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg"
                                :class="payMethodGroup==='cash' ? 'bg-blue-100' : 'bg-slate-100 dark:bg-slate-700'">
                                <iconify-icon icon="solar:banknote-bold" width="16"
                                    :class="payMethodGroup==='cash' ? 'text-[#0d4c8f]' : 'text-slate-400'"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-700 dark:text-white">Cash</p>
                                <p class="text-[10px] text-slate-400">In-person payment</p>
                            </div>
                        </label>
                        {{-- Online Payment --}}
                        <label :class="payMethodGroup==='online' ? 'ring-2 ring-violet-500 border-violet-300 bg-violet-50 dark:bg-violet-900/20' : 'border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg hover:bg-slate-50'"
                            class="flex items-center gap-2.5 rounded-xl border px-4 py-3 cursor-pointer transition-all">
                            <input type="radio" value="online" x-model="payMethodGroup" class="sr-only">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg"
                                :class="payMethodGroup==='online' ? 'bg-violet-100' : 'bg-slate-100 dark:bg-slate-700'">
                                <iconify-icon icon="solar:smartphone-bold" width="16"
                                    :class="payMethodGroup==='online' ? 'text-violet-600' : 'text-slate-400'"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-700 dark:text-white">Online Payment</p>
                                <p class="text-[10px] text-slate-400">GCash, PayMaya, PayPal</p>
                            </div>
                        </label>
                    </div>

                    {{-- Online sub-options --}}
                    <div x-show="payMethodGroup==='online'" x-transition class="mt-3 space-y-3 rounded-xl border border-violet-200 dark:border-violet-800 bg-violet-50/50 dark:bg-violet-900/10 p-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wide">Select Platform</label>
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="p in onlineProviders" :key="p.key">
                                    <button type="button" @click="onlineProvider = p.key"
                                        :class="onlineProvider===p.key
                                            ? 'ring-2 ring-violet-500 border-violet-300 bg-white dark:bg-dark-card'
                                            : 'border-slate-200 dark:border-dark-border bg-white/60 dark:bg-dark-bg hover:bg-white'"
                                        class="flex flex-col items-center rounded-xl border px-2 py-2.5 transition-all">
                                        <iconify-icon :icon="p.icon" width="20" :class="onlineProvider===p.key?'text-violet-600':'text-slate-400'"></iconify-icon>
                                        <span class="text-[11px] font-semibold mt-1"
                                            :class="onlineProvider===p.key?'text-violet-700 dark:text-violet-300':'text-slate-600 dark:text-slate-400'"
                                            x-text="p.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                Reference / Transaction Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="onlineReference"
                                placeholder="e.g. 1234567890"
                                class="w-full rounded-lg border border-violet-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-violet-500">
                            <p class="mt-1 text-[10px] text-slate-400">Proof of transaction — required for online payments</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5">Notes <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="text" x-model="payNotes" placeholder="e.g. OR #1234, partial payment..."
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

        </div>

        {{-- ── MODE: DETAILS ─────────────────────────────────── --}}
        <div x-show="!loading && mode==='details'" class="overflow-y-auto flex-1 px-6 py-5 space-y-5">

            {{-- Summary strip --}}
            <div x-show="finance" class="grid grid-cols-4 gap-3">
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/30 px-3 py-2.5 text-center">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Plan</span>
                    <p class="text-sm font-bold text-slate-800 dark:text-white mt-0.5" x-text="finance?.payment_plan ? (finance?.payment_months?.length?'Plan':'Option')+' '+finance.payment_plan : '—'"></p>
                </div>
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/30 px-3 py-2.5 text-center">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold block">Total Fee</span>
                    <p class="text-sm font-bold text-slate-800 dark:text-white mt-0.5" x-text="'₱'+fcFmt(finance?.total_fee)"></p>
                </div>
                <div class="rounded-xl bg-green-50 dark:bg-green-900/20 px-3 py-2.5 text-center">
                    <span class="text-[10px] text-green-600 uppercase font-semibold block">Paid</span>
                    <p class="text-sm font-bold text-green-700 dark:text-green-400 mt-0.5" x-text="'₱'+fcFmt(finance?.amount_paid)"></p>
                </div>
                <div class="rounded-xl px-3 py-2.5 text-center"
                    :class="(finance?.balance??0)>0?'bg-red-50 dark:bg-red-900/20':'bg-green-50 dark:bg-green-900/20'">
                    <span class="text-[10px] uppercase font-semibold block" :class="(finance?.balance??0)>0?'text-red-600':'text-green-600'">Balance</span>
                    <p class="text-sm font-bold mt-0.5" :class="(finance?.balance??0)>0?'text-red-700':'text-green-700'" x-text="'₱'+fcFmt(finance?.balance)"></p>
                </div>
            </div>

            {{-- Monthly schedule --}}
            <div x-show="(finance?.paymentMonths||finance?.payment_months||[]).length > 0">
                <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Monthly Schedule</h4>
                <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                    <table class="w-full text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-800/30">
                            <tr class="text-[10px] uppercase tracking-wide text-slate-500 border-b border-slate-200 dark:border-dark-border">
                                <th class="px-3 py-2 text-left">Month</th>
                                <th class="px-3 py-2 text-right">Due</th>
                                <th class="px-3 py-2 text-right">Amount Due</th>
                                <th class="px-3 py-2 text-right">Paid</th>
                                <th class="px-3 py-2 text-center">Status</th>
                                <th class="px-3 py-2 text-left">Paid Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                            <template x-for="m in (finance?.paymentMonths||finance?.payment_months||[])" :key="m.id">
                                <tr>
                                    <td class="px-3 py-2 font-medium text-slate-700 dark:text-slate-300" x-text="m.month_name+' '+m.month_year"></td>
                                    <td class="px-3 py-2 text-right text-slate-500" x-text="m.due_date ? m.due_date.substr(0,10) : '—'"></td>
                                    <td class="px-3 py-2 text-right font-semibold text-slate-700 dark:text-slate-200" x-text="'₱'+fcFmt(m.amount_due)"></td>
                                    <td class="px-3 py-2 text-right text-green-700 font-semibold" x-text="m.amount_paid>0?'₱'+fcFmt(m.amount_paid):'—'"></td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold"
                                            :class="{
                                                'bg-green-100 text-green-700':m.status==='paid',
                                                'bg-amber-100 text-amber-700':m.status==='pending',
                                                'bg-red-100 text-red-700':m.status==='overdue',
                                                'bg-blue-100 text-blue-700':m.status==='partial',
                                            }" x-text="m.status"></span>
                                    </td>
                                    <td class="px-3 py-2 text-slate-400" x-text="m.paid_date?m.paid_date.substr(0,10):'—'"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Payment history --}}
            <div x-show="(finance?.payments||[]).length > 0">
                <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Payment History</h4>
                <div class="space-y-2">
                    <template x-for="p in (finance?.payments||[])" :key="p.id">
                        <div class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-2.5">
                            <iconify-icon icon="solar:receipt-linear" width="16" class="text-amber-500 shrink-0"></iconify-icon>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-300" x-text="p.receipt_number"></p>
                                <p class="text-[11px] text-slate-400"
                                   x-text="(p.payment_date||'').substr(0,10)+' · '+(p.payment_method||'').replace('_',' ')+(p.online_reference?' #'+p.online_reference:'')+(p.notes?' · '+p.notes:'')"></p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-green-700 dark:text-green-400" x-text="'₱'+fcFmt(p.amount)"></p>
                            </div>
                            <button @click="viewReceiptFromPayment(p)" class="ml-1 flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors" title="View Receipt">
                                <iconify-icon icon="solar:eye-linear" width="13"></iconify-icon>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="(finance?.payments||[]).length === 0 && !loading" class="text-center text-xs text-slate-400 py-4">No payments recorded yet.</div>

        </div>

        {{-- ── MODE: RECEIPT ─────────────────────────────────── --}}
        <div x-show="!loading && mode==='receipt'" class="overflow-y-auto flex-1 px-6 py-6" id="receipt-print-area">

            {{-- Printable Receipt --}}
            <div class="max-w-sm mx-auto space-y-4">

                {{-- School header --}}
                <div class="text-center border-b border-slate-200 pb-4">
                    <p class="text-base font-bold text-[#0d4c8f] uppercase tracking-wide">My Messiah School of Cavite</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Official Payment Receipt</p>
                    <p class="text-[11px] text-slate-400">mmsc.edu.ph</p>
                </div>

                {{-- Receipt header --}}
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase">Receipt No.</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white" x-text="receipt?.receipt_number || '—'"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 uppercase">Date</p>
                        <p class="text-sm font-semibold text-slate-700" x-text="(receipt?.payment_date||'').substr(0,10)"></p>
                    </div>
                </div>

                {{-- Student info --}}
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/30 px-4 py-3 space-y-1.5">
                    <div class="flex gap-2">
                        <span class="text-[10px] text-slate-400 w-24 shrink-0 uppercase font-medium">Student</span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-white" x-text="receipt?.finance?.student?.first_name + ' ' + receipt?.finance?.student?.last_name"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-[10px] text-slate-400 w-24 shrink-0 uppercase font-medium">Student ID</span>
                        <span class="text-xs text-slate-600 font-mono" x-text="receipt?.finance?.student?.student_id || '—'"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-[10px] text-slate-400 w-24 shrink-0 uppercase font-medium">Grade</span>
                        <span class="text-xs text-slate-600" x-text="receipt?.finance?.grade_level || '—'"></span>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-[10px] text-slate-400 w-24 shrink-0 uppercase font-medium">School Year</span>
                        <span class="text-xs text-slate-600" x-text="'SY ' + (receipt?.finance?.school_year || '—')"></span>
                    </div>
                </div>

                {{-- Payment details --}}
                <div class="space-y-2 border-b border-dashed border-slate-200 pb-4">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Payment Method</span>
                        <span class="font-semibold text-slate-700" x-text="(receipt?.payment_method||'').replace('_',' ').replace(/\b\w/g,c=>c.toUpperCase())"></span>
                    </div>
                    <div x-show="receipt?.online_reference" class="flex justify-between text-xs">
                        <span class="text-slate-500">Reference No.</span>
                        <span class="text-slate-700 font-mono" x-text="receipt?.online_reference"></span>
                    </div>
                    <div x-show="receipt?.notes" class="flex justify-between text-xs">
                        <span class="text-slate-500">Notes</span>
                        <span class="text-slate-700 text-right max-w-[60%]" x-text="receipt?.notes"></span>
                    </div>
                </div>

                {{-- Amount --}}
                <div class="rounded-xl bg-[#0d4c8f] px-4 py-3 flex items-center justify-between">
                    <span class="text-sm font-semibold text-white">Amount Paid</span>
                    <span class="text-xl font-bold text-white" x-text="'₱' + fcFmt(receipt?.amount)"></span>
                </div>

                {{-- Running balance --}}
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Total Fee</span>
                    <span class="font-semibold text-slate-600" x-text="'₱' + fcFmt(receipt?.finance?.total_fee)"></span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-400">Total Paid to Date</span>
                    <span class="font-semibold text-green-600" x-text="'₱' + fcFmt(receipt?.finance?.amount_paid)"></span>
                </div>
                <div class="flex justify-between text-xs border-t border-slate-200 pt-2">
                    <span class="text-slate-600 font-semibold">Remaining Balance</span>
                    <span class="font-bold" :class="(receipt?.finance?.balance??0)>0?'text-red-600':'text-green-600'" x-text="'₱' + fcFmt(receipt?.finance?.balance)"></span>
                </div>

                {{-- Footer --}}
                <div class="text-center text-[10px] text-slate-400 border-t border-slate-200 pt-4">
                    <p>This is an official receipt issued by MMSC.</p>
                    <p class="mt-0.5">Thank you for your payment!</p>
                </div>
            </div>
        </div>

        {{-- ── MODE: REMINDER ────────────────────────────────── --}}
        <div x-show="!loading && mode==='reminder'" class="overflow-y-auto flex-1 px-6 py-5 space-y-5">

            {{-- Finance summary strip --}}
            <div x-show="finance" class="grid grid-cols-3 gap-3">
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/30 px-4 py-3">
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Total Fee</span>
                    <p class="text-base font-bold text-slate-800 dark:text-white mt-0.5" x-text="'₱' + fcFmt(finance?.total_fee)"></p>
                </div>
                <div class="rounded-xl bg-green-50 dark:bg-green-900/20 px-4 py-3">
                    <span class="text-[10px] text-green-600 uppercase font-semibold">Paid</span>
                    <p class="text-base font-bold text-green-700 dark:text-green-400 mt-0.5" x-text="'₱' + fcFmt(finance?.amount_paid)"></p>
                </div>
                <div class="rounded-xl px-4 py-3"
                    :class="(finance?.balance ?? 0) > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20'">
                    <span class="text-[10px] uppercase font-semibold"
                        :class="(finance?.balance ?? 0) > 0 ? 'text-red-600' : 'text-green-600'">Balance</span>
                    <p class="text-base font-bold mt-0.5"
                        :class="(finance?.balance ?? 0) > 0 ? 'text-red-700 dark:text-red-400' : 'text-green-700 dark:text-green-400'"
                        x-text="'₱' + fcFmt(finance?.balance)"></p>
                </div>
            </div>

            {{-- Reminder type --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">Reminder Type <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="t in reminderTypes" :key="t.key">
                        <button type="button" @click="reminderType = t.key"
                            :class="reminderType === t.key
                                ? 'ring-2 ring-violet-500 bg-violet-50 dark:bg-violet-900/30 border-violet-300'
                                : 'border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg hover:bg-slate-50'"
                            class="flex flex-col items-start rounded-xl border px-3 py-2.5 text-left transition-all">
                            <iconify-icon :icon="t.icon" width="16" class="mb-1"
                                :class="reminderType===t.key ? 'text-violet-600' : 'text-slate-400'"></iconify-icon>
                            <span class="text-xs font-semibold text-slate-700 dark:text-white" x-text="t.label"></span>
                            <span class="text-[10px] text-slate-400 mt-0.5 leading-tight" x-text="t.desc"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Recipients (always personal + guardian) --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">Will Be Sent To</label>
                <div class="space-y-2">
                    <template x-for="r in reminderRecipients" :key="r.key">
                        <div class="flex items-center gap-3 rounded-xl border border-violet-200 dark:border-violet-800 bg-violet-50 dark:bg-violet-900/10 px-4 py-2.5">
                            <iconify-icon :icon="r.icon" width="15" class="text-violet-500 shrink-0"></iconify-icon>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-700 dark:text-white" x-text="r.label"></p>
                                <p class="text-[11px] text-violet-600 dark:text-violet-400 truncate font-mono" x-text="r.email"></p>
                            </div>
                            <iconify-icon icon="solar:check-circle-bold" width="14" class="text-violet-500 ml-auto shrink-0"></iconify-icon>
                        </div>
                    </template>
                    <div x-show="reminderRecipients.length === 0"
                        class="flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 dark:bg-amber-900/10 px-4 py-2.5">
                        <iconify-icon icon="solar:danger-triangle-bold" width="14" class="text-amber-500"></iconify-icon>
                        <p class="text-xs text-amber-700 dark:text-amber-400">No personal or guardian email on record for this student.</p>
                    </div>
                </div>
            </div>

            {{-- Message preview --}}
            <div x-show="reminderType">
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">Additional Note <span class="text-slate-400 font-normal">(optional)</span></label>
                <textarea x-model="reminderNote" rows="3" placeholder="e.g. Please settle your account by end of the month..."
                    class="w-full rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-xs text-slate-700 dark:text-white resize-none focus:outline-none focus:ring-2 focus:ring-violet-500"></textarea>
                <div class="mt-3 rounded-xl border border-violet-200 dark:border-violet-800 bg-violet-50 dark:bg-violet-900/10 px-4 py-3 space-y-1.5">
                    <p class="text-[10px] font-bold text-violet-600 uppercase tracking-wide">Message Preview</p>
                    <p class="text-xs text-slate-700 dark:text-slate-300" x-text="buildReminderPreview()"></p>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div x-show="!loading" class="flex items-center justify-between gap-3 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/30 flex-shrink-0">
            <div class="flex items-center gap-2">
                <template x-if="mode==='receipt'">
                    <button @click="mode='details'" class="text-xs text-blue-600 hover:underline font-medium">
                        ← Back to Details
                    </button>
                </template>
                <template x-if="mode==='reminder'">
                    <button @click="mode='details'" class="text-xs text-blue-600 hover:underline font-medium">
                        ← Back to Details
                    </button>
                </template>
                <template x-if="mode==='details'">
                    <button @click="mode='pay'" x-show="(finance?.balance??0)>0"
                        class="flex items-center gap-1.5 rounded-lg border border-green-300 bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700 hover:bg-green-100 transition-colors">
                        <iconify-icon icon="solar:card-linear" width="13"></iconify-icon>
                        Record Payment
                    </button>
                </template>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="closeModal()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Close
                </button>
                <template x-if="mode==='pay'">
                    <button type="button" @click="submitPayment()"
                        :disabled="!payAmount || !payDate || saving"
                        :class="(!payAmount||!payDate||saving)?'opacity-50 cursor-not-allowed':'hover:bg-green-700'"
                        class="flex items-center gap-2 rounded-lg bg-green-600 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                        <iconify-icon x-show="saving" icon="solar:spinner-line-duotone" width="14" class="animate-spin"></iconify-icon>
                        <iconify-icon x-show="!saving" icon="solar:check-circle-bold" width="14"></iconify-icon>
                        <span x-text="saving ? 'Saving...' : 'Record Payment'"></span>
                    </button>
                </template>
                <template x-if="mode==='reminder'">
                    <button type="button" @click="submitReminder()"
                        :disabled="!reminderType || reminderRecipients.length===0 || saving"
                        :class="(!reminderType||reminderRecipients.length===0||saving)?'opacity-50 cursor-not-allowed':'hover:bg-violet-700'"
                        class="flex items-center gap-2 rounded-lg bg-violet-600 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                        <iconify-icon x-show="saving"  icon="solar:spinner-line-duotone" width="14" class="animate-spin"></iconify-icon>
                        <iconify-icon x-show="!saving" icon="solar:bell-bing-bold"       width="14"></iconify-icon>
                        <span x-text="saving ? 'Sending...' : 'Send Reminder'"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
function financeUpdateModal() {
    return {
        open: false, loading: false, saving: false,
        mode: 'pay', // 'pay' | 'details' | 'receipt' | 'reminder'
        studentId: null, studentLabel: '', student: null,
        finance: null, receipt: null,
        // Pay form
        payAmount: null, payDate: '', payNotes: '',
        payMethodGroup: 'cash',    // 'cash' | 'online'
        onlineProvider: 'gcash',   // 'gcash' | 'paymaya' | 'paypal'
        onlineReference: '',
        selectedMonthIds: [],
        onlineProviders: [
            { key: 'gcash',   label: 'GCash',   icon: 'simple-icons:gcash'   },
            { key: 'paymaya', label: 'PayMaya', icon: 'simple-icons:maya'    },
            { key: 'paypal',  label: 'PayPal',  icon: 'simple-icons:paypal'  },
        ],
        today: new Date().toISOString().split('T')[0],
        // Reminder form
        reminderType: 'overdue',
        reminderNote: '',
        reminderTypes: [
            { key: 'overdue',  label: 'Overdue Payment',  desc: 'Past due date',      icon: 'solar:danger-triangle-bold' },
            { key: 'upcoming', label: 'Upcoming Payment', desc: 'Due soon this month', icon: 'solar:clock-circle-bold'    },
            { key: 'general',  label: 'General Reminder', desc: 'Outstanding balance', icon: 'solar:bell-bing-bold'       },
        ],
        reminderRecipients: [],

        init() {
            window.openUpdateFeeModal  = (sid) => this.open_('pay', sid);
            window.openFinanceDetails  = (sid) => this.open_('details', sid);
            window.openLatestReceipt   = (sid) => this.open_('details', sid, true);
            window.openFinanceReceipt  = (pid) => this.openReceiptById(pid);
            window.openFinanceReminder = (sid, name) => this.openReminder(sid, name);

            // Auto-fill amount from selected months — registered once here, not inside open_()
            this.$watch('selectedMonthIds', (ids) => {
                if (!ids.length) { return; }
                const months = this.finance?.paymentMonths || this.finance?.payment_months || [];
                const numIds = ids.map(Number);
                const sum = months
                    .filter(m => numIds.includes(Number(m.id)) && m.status !== 'paid')
                    .reduce((s, m) => s + Math.max(0, parseFloat(m.amount_due || 0) - parseFloat(m.amount_paid || 0)), 0);
                if (sum > 0) this.payAmount = Math.round(sum * 100) / 100;
            });
        },

        async open_(mode, studentId, autoReceipt = false) {
            this.mode           = mode;
            this.studentId      = studentId;
            this.finance        = null; this.receipt = null;
            this.payAmount      = null; this.payDate = this.today;
            this.payMethodGroup = 'cash'; this.onlineProvider = 'gcash';
            this.onlineReference = ''; this.payNotes = '';
            this.selectedMonthIds = [];
            this.open    = true;
            this.loading = true;
            this.studentLabel = '';

            try {
                const r = await fetch(`/admin/finance/for-student?student_id=${studentId}`);
                const d = await r.json();
                this.finance = d.finance;
                this.student = d.student || null;
                if (d.student) this.studentLabel = (d.student.first_name||'') + ' ' + (d.student.last_name||'');
                if (autoReceipt && d.finance?.payments?.length) {
                    this.receipt = d.finance.payments[0];
                    this.receipt.finance = { ...d.finance, student: d.student || null };
                    this.mode = 'receipt';
                }
            } catch(e) { /* silent */ }
            this.loading = false;
        },

        async openReceiptById(paymentId) {
            this.mode    = 'receipt';
            this.open    = true;
            this.loading = true;
            this.receipt = null;
            try {
                const r = await fetch(`/admin/finance/receipt?payment_id=${paymentId}`);
                const d = await r.json();
                this.receipt = d.payment;
                if (this.receipt?.finance?.student) {
                    this.studentLabel = this.receipt.finance.student.first_name + ' ' + this.receipt.finance.student.last_name;
                }
            } catch(e) {}
            this.loading = false;
        },

        viewReceiptFromPayment(p) {
            this.receipt = { ...p, finance: { ...this.finance, student: this.student } };
            this.mode = 'receipt';
        },

        async submitPayment() {
            if (!this.payAmount || !this.payDate || !this.finance) return;
            if (this.payMethodGroup === 'online' && !this.onlineReference.trim()) {
                alert('Please enter the reference/transaction number for online payment.');
                return;
            }
            this.saving = true;
            const method = this.payMethodGroup === 'cash' ? 'cash' : this.onlineProvider;
            const payload = {
                student_finance_id: this.finance.id,
                amount:             this.payAmount,
                payment_date:       this.payDate,
                payment_method:     method,
                online_reference:   this.payMethodGroup === 'online' ? this.onlineReference.trim() : null,
                month_ids:          this.selectedMonthIds.length ? this.selectedMonthIds : null,
                notes:              this.payNotes || null,
            };
            try {
                const r = await fetch('/admin/finance/record-payment', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify(payload),
                });
                const d = await r.json();
                if (d.success) {
                    this.finance = d.finance;
                    // Show receipt
                    const pmt = d.payment;
                    pmt.finance = d.finance;
                    this.receipt = pmt;
                    this.mode = 'receipt';
                    // Notify parent page
                    if (typeof window.onFinancePaymentSaved === 'function') {
                        window.onFinancePaymentSaved(d.finance, this.studentId);
                    }
                } else { alert(d.message || 'Failed to record payment.'); }
            } catch(e) { alert('An error occurred.'); }
            this.saving = false;
        },

        async openReminder(studentId, name) {
            this.mode             = 'reminder';
            this.studentId        = studentId;
            this.studentLabel     = name || '';
            this.finance          = null;
            this.reminderType     = 'overdue';
            this.reminderNote     = '';
            this.reminderRecipients = [];
            this.open    = true;
            this.loading = true;
            try {
                const r = await fetch(`/admin/finance/for-student?student_id=${studentId}`);
                const d = await r.json();
                this.finance = d.finance;
                if (d.student) {
                    const s = d.student;
                    this.studentLabel = (s.first_name || '') + ' ' + (s.last_name || '');
                    // Always send to personal email and guardian email only
                    this.reminderRecipients = [
                        s.personal_email ? { key: 'personal', label: 'Personal Email',  email: s.personal_email, icon: 'solar:letter-bold' } : null,
                        s.guardian_email ? { key: 'guardian', label: 'Guardian Email',  email: s.guardian_email, icon: 'solar:users-group-two-rounded-bold' } : null,
                    ].filter(Boolean);
                }
            } catch(e) {}
            this.loading = false;
        },

        buildReminderPreview() {
            const name = this.studentLabel || 'Student';
            const bal  = '₱' + fcFmt(this.finance?.balance || 0);
            const type = this.reminderTypes.find(t => t.key === this.reminderType);
            let msg = '';
            if (this.reminderType === 'overdue') {
                msg = `Dear ${name}, your account has an overdue balance of ${bal}. Please settle your payment as soon as possible to avoid penalties.`;
            } else if (this.reminderType === 'upcoming') {
                msg = `Dear ${name}, this is a reminder that your upcoming payment of ${bal} is due soon. Please prepare for timely payment.`;
            } else {
                msg = `Dear ${name}, you have an outstanding balance of ${bal}. Please visit the Finance Office to settle your account.`;
            }
            if (this.reminderNote) msg += ` Note: ${this.reminderNote}`;
            return msg;
        },

        async submitReminder() {
            if (!this.reminderType || !this.studentId) return;
            if (this.reminderRecipients.length === 0) {
                alert('No email address on record for this student or guardian.');
                return;
            }
            this.saving = true;
            try {
                const r = await fetch('/admin/finance/send-reminder', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({
                        student_id:    this.studentId,
                        reminder_type: this.reminderType,
                        note:          this.reminderNote || null,
                    }),
                });
                const d = await r.json();
                if (d.success) {
                    this.closeModal();
                    if (typeof window.financeToast === 'function') {
                        window.financeToast('Reminder sent to ' + this.studentLabel, 'success');
                    }
                } else {
                    alert(d.message || 'Failed to send reminder.');
                }
            } catch(e) { alert('An error occurred.'); }
            this.saving = false;
        },

        printReceipt() {
            const r = this.receipt;
            if (!r) return;
            const s = r.finance?.student || {};
            const fmt = (n) => Number(n||0).toLocaleString('en-PH',{minimumFractionDigits:0,maximumFractionDigits:2});
            const methodLabel = (r.payment_method||'').replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase());
            const balColor = (r.finance?.balance||0) > 0 ? '#dc2626' : '#16a34a';

            const html = `<!DOCTYPE html>
<html><head>
<meta charset="UTF-8">
<title>Receipt ${r.receipt_number||''}</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: Arial, sans-serif; font-size: 12px; color: #1e293b; background: white; padding: 24px; max-width: 420px; margin: 0 auto; }
  .school-name { font-size: 15px; font-weight: 800; color: #0d4c8f; text-align: center; text-transform: uppercase; letter-spacing: .05em; }
  .sub { font-size: 10px; color: #64748b; text-align: center; margin-top: 2px; }
  .divider { border: none; border-top: 1px solid #e2e8f0; margin: 12px 0; }
  .row { display: flex; justify-content: space-between; margin-bottom: 6px; }
  .label { color: #64748b; font-size: 11px; }
  .value { font-size: 11px; font-weight: 600; color: #1e293b; }
  .info-box { background: #f8fafc; border-radius: 8px; padding: 12px; margin: 12px 0; }
  .info-row { display: flex; gap: 8px; margin-bottom: 4px; }
  .info-label { font-size: 10px; color: #94a3b8; text-transform: uppercase; font-weight: 600; width: 88px; flex-shrink: 0; }
  .info-val { font-size: 11px; font-weight: 600; color: #1e293b; }
  .amount-box { background: #0d4c8f; color: white; border-radius: 8px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; margin: 12px 0; }
  .amount-label { font-size: 12px; font-weight: 600; }
  .amount-val { font-size: 20px; font-weight: 800; }
  .footer { text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 12px; margin-top: 16px; }
  @media print { body { padding: 12px; } }
</style>
</head>
<body>
<p class="school-name">My Messiah School of Cavite</p>
<p class="sub">Official Payment Receipt</p>
<p class="sub">mmsc.edu.ph</p>
<hr class="divider">
<div class="row">
  <div><p class="label" style="font-size:10px;text-transform:uppercase">Receipt No.</p><p class="value" style="font-size:13px;font-weight:800">${r.receipt_number||'—'}</p></div>
  <div style="text-align:right"><p class="label" style="font-size:10px;text-transform:uppercase">Date</p><p class="value">${(r.payment_date||'').substr(0,10)}</p></div>
</div>
<div class="info-box">
  <div class="info-row"><span class="info-label">Student</span><span class="info-val">${(s.first_name||'')+' '+(s.last_name||'')}</span></div>
  <div class="info-row"><span class="info-label">Student ID</span><span class="info-val" style="font-family:monospace">${s.student_id||'—'}</span></div>
  <div class="info-row"><span class="info-label">Grade</span><span class="info-val">${r.finance?.grade_level||'—'}</span></div>
  <div class="info-row"><span class="info-label">School Year</span><span class="info-val">SY ${r.finance?.school_year||'—'}</span></div>
</div>
<hr class="divider" style="border-style:dashed">
<div class="row"><span class="label">Payment Method</span><span class="value">${methodLabel}</span></div>
${r.online_reference ? `<div class="row"><span class="label">Reference No.</span><span class="value" style="font-family:monospace">${r.online_reference}</span></div>` : ''}
${r.notes ? `<div class="row"><span class="label">Notes</span><span class="value">${r.notes}</span></div>` : ''}
<hr class="divider" style="border-style:dashed">
<div class="amount-box">
  <span class="amount-label">Amount Paid</span>
  <span class="amount-val">₱${fmt(r.amount)}</span>
</div>
<div class="row" style="margin-top:8px"><span class="label">Total Fee</span><span class="value">₱${fmt(r.finance?.total_fee)}</span></div>
<div class="row"><span class="label">Total Paid to Date</span><span class="value" style="color:#16a34a">₱${fmt(r.finance?.amount_paid)}</span></div>
<div class="row" style="border-top:1px solid #e2e8f0;padding-top:8px;margin-top:4px">
  <span style="font-size:12px;font-weight:700;color:#1e293b">Remaining Balance</span>
  <span style="font-size:12px;font-weight:800;color:${balColor}">₱${fmt(r.finance?.balance)}</span>
</div>
<div class="footer">
  <p>This is an official receipt issued by MMSC.</p>
  <p style="margin-top:2px">Thank you for your payment!</p>
</div>
</body></html>`;

            const popup = window.open('', '_blank', 'width=520,height=740,scrollbars=yes');
            if (!popup) { alert('Pop-up blocked. Please allow pop-ups for this site to print receipts.'); return; }
            popup.document.write(html);
            popup.document.close();
            popup.focus();
            setTimeout(() => popup.print(), 400);
        },

        closeModal() {
            this.open = false;
            this.selectedMonthIds = [];
            this.payAmount = null;
        },
        fcFmt: fcFmt,
    };
}

// Global fmt function for use by both modals
function fcFmt(n) {
    return Number(n||0).toLocaleString('en-PH',{minimumFractionDigits:0,maximumFractionDigits:2});
}
</script>
