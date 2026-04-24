@extends('layouts.admin_layout')

@section('title', 'School Calendar')

@section('content')

<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- ── Page Header ── --}}
    <x-admin.page-header title="School Calendar" subtitle="Manage School Academic Calendar">
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">School Year:</span>
            @php $allSchoolYears = \App\Models\SchoolYear::orderByDesc('start_date')->get(); @endphp
            @if($allSchoolYears->count())
            <form method="GET" action="{{ route('admin.school-calendar.index') }}">
                <select name="school_year" onchange="this.form.submit()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 text-sm font-semibold text-slate-700 dark:text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($allSchoolYears as $sy)
                    <option value="{{ $sy->name }}" {{ $sy->name === $schoolYear ? 'selected' : '' }}>
                        SY {{ $sy->name }}{{ $sy->status === 'active' ? ' ★' : '' }}
                    </option>
                    @endforeach
                </select>
            </form>
            @else
            <div class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 shadow-sm">
                <span class="text-sm font-semibold text-slate-700 dark:text-white">SY {{ $schoolYear }}</span>
            </div>
            @endif
        </div>
    </x-admin.page-header>

    {{-- ── Success / Error Toast ── --}}
    <div id="toast" style="display:none" class="fixed top-6 right-6 z-50 flex items-center gap-3 rounded-xl px-5 py-3.5 shadow-xl text-sm font-medium transition-all">
        <iconify-icon id="toast-icon" width="18"></iconify-icon>
        <span id="toast-msg"></span>
    </div>

    {{-- ── Calendar Card ── --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card mb-6">

        {{-- Card Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:calendar-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">School Academic Calendar</h3>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.school-year-config.index') }}"
                    class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border hover:bg-slate-50 dark:hover:bg-white/5 text-slate-600 dark:text-slate-300 px-4 py-2 text-sm font-medium transition-colors shadow-sm">
                    <iconify-icon icon="solar:settings-linear" width="16"></iconify-icon>
                    Configure School Year
                </a>
                <button id="btn-add-event"
                    class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 text-white px-4 py-2 text-sm font-medium transition-colors shadow-sm">
                    <iconify-icon icon="solar:add-circle-linear" width="16"></iconify-icon>
                    Add Event
                </button>
            </div>
        </div>

        {{-- Month Jump --}}
        <div class="flex items-center gap-3 px-6 py-3 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.02]">
            <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Jump to:</label>
            <select id="jump-month" class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach(range(1,12) as $mo)
                    <option value="{{ $mo }}" {{ $mo == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate(null, $mo, 1)->format('F') }}
                    </option>
                @endforeach
            </select>
            <select id="jump-year" class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 text-sm text-slate-700 w-20 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach(range(2024, 2030) as $yr)
                    <option value="{{ $yr }}" {{ $yr == $year ? 'selected' : '' }}>{{ $yr }}</option>
                @endforeach
            </select>
            <button id="btn-suspend-today"
                class="rounded-lg border border-red-400 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 px-3 py-1.5 text-xs font-medium transition-colors">
                Suspend Class Today
            </button>
        </div>

        {{-- Calendar Controls --}}
        <div class="flex flex-col gap-4 px-6 pt-5 pb-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <button onclick="calNav('prev-year')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:double-alt-arrow-left-linear" width="14"></iconify-icon>
                </button>
                <button onclick="calNav('prev-month')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-left-linear" width="14"></iconify-icon>
                </button>
                <button onclick="calNav('next-month')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:alt-arrow-right-linear" width="14"></iconify-icon>
                </button>
                <button onclick="calNav('next-year')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:double-alt-arrow-right-linear" width="14"></iconify-icon>
                </button>
                <button onclick="calNav('today')" class="flex h-8 items-center px-4 rounded-full bg-[#0d4c8f] text-white text-xs font-medium hover:bg-blue-700 transition-colors">
                    Today
                </button>
            </div>

            <h2 id="cal-title" class="text-base font-bold text-slate-800 dark:text-white text-center"></h2>

            <div class="flex items-center rounded-lg border border-slate-200 dark:border-dark-border overflow-hidden text-xs">
                <button id="cal-view-month" onclick="setCalView('month')" class="px-3 py-1.5 font-medium bg-[#0d4c8f] text-white transition-colors">Month</button>
                <button id="cal-view-week"  onclick="setCalView('week')"  class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Week</button>
                <button id="cal-view-day"   onclick="setCalView('day')"   class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Day</button>
                <button id="cal-view-list"  onclick="setCalView('list')"  class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">List</button>
            </div>
        </div>

        {{-- Day-of-week headers --}}
        <div class="px-4">
            <div class="grid grid-cols-7 border-t border-l border-slate-200 dark:border-dark-border">
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                <div class="border-r border-b border-slate-200 dark:border-dark-border py-2.5 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">{{ $day }}</div>
                @endforeach
            </div>
            {{-- Calendar Grid --}}
            <div id="cal-grid" class="grid grid-cols-7 border-l border-slate-200 dark:border-dark-border">
                {{-- JS-rendered --}}
            </div>
        </div>

        {{-- Legend + Download --}}
        <div class="px-6 py-4 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 dark:border-dark-border">
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Legend</span>
                @foreach([
                    ['bg-green-200',  'Regular'],
                    ['bg-purple-200', 'Holiday'],
                    ['bg-red-300',    'Suspended'],
                    ['bg-yellow-200', 'School Event'],
                    ['bg-amber-200',  'Early Dismissal'],
                    ['bg-blue-200',   'Exam Day'],
                    ['bg-orange-200', 'Break'],
                    ['bg-slate-100',  'Weekend/No Classes'],
                ] as $leg)
                <div class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full {{ $leg[0] }} shrink-0"></span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $leg[1] }}</span>
                </div>
                @endforeach
            </div>
            <button id="btn-download-pdf"
                class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                <iconify-icon icon="solar:download-minimalistic-linear" width="15"></iconify-icon>
                Download PDF
            </button>
        </div>
    </div>

    {{-- ── Upcoming Events Table ── --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-center gap-2 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:calendar-mark-linear" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Upcoming Events this Month</h3>
        </div>
        <div class="overflow-x-auto px-6 py-4">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <th class="pb-3 pr-6 whitespace-nowrap">Date</th>
                        <th class="pb-3 pr-6 whitespace-nowrap">Day</th>
                        <th class="pb-3 pr-6 whitespace-nowrap">Day Type</th>
                        <th class="pb-3 pr-6 whitespace-nowrap">Event Title</th>
                        <th class="pb-3 whitespace-nowrap">Description</th>
                    </tr>
                </thead>
                <tbody id="upcoming-tbody" class="divide-y divide-slate-100 dark:divide-dark-border">
                    @forelse($upcoming as $ev)
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="py-3.5 pr-6 font-semibold text-slate-800 dark:text-slate-200 whitespace-nowrap">{{ $ev->date->format('M j, Y') }}</td>
                        <td class="py-3.5 pr-6 text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $ev->date->format('l') }}</td>
                        <td class="py-3.5 pr-6 whitespace-nowrap">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $ev->badgeClass() }}">
                                {{ $ev->dayTypeLabel() }}
                            </span>
                        </td>
                        <td class="py-3.5 pr-6 font-medium text-slate-800 dark:text-slate-200">{{ $ev->event_title ?? '—' }}</td>
                        <td class="py-3.5 text-slate-500 dark:text-slate-400 max-w-xs">{{ $ev->description ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-sm text-slate-400 dark:text-slate-500">No upcoming events this month.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="mt-8 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══════════════════════════════════════════════════════════
     ADD / EDIT EVENT MODAL
     ══════════════════════════════════════════════════════════ --}}
<div id="event-modal" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div id="modal-backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    {{-- Panel --}}
    <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-dark-card shadow-2xl border border-slate-200 dark:border-dark-border overflow-hidden flex flex-col max-h-[92vh]">

        {{-- Modal Header --}}
        <div class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/5">
            <div>
                <h3 id="modal-title" class="text-base font-semibold text-slate-900 dark:text-white">Edit Day</h3>
                <p id="modal-subtitle" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5"></p>
            </div>
            <button id="modal-close" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                <iconify-icon icon="solar:close-linear" width="18"></iconify-icon>
            </button>
        </div>

        {{-- Validation Errors --}}
        <div id="modal-errors" style="display:none" class="flex-shrink-0 mx-6 mt-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
            <p class="text-xs font-semibold text-red-600 mb-1">Please fix the following errors:</p>
            <ul id="modal-error-list" class="list-disc list-inside text-xs text-red-500 space-y-0.5"></ul>
        </div>

        {{-- Modal Body (scrollable) --}}
        <form id="event-form" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <input type="hidden" id="form-event-id" name="event_id">
            <input type="hidden" id="form-date"     name="date">
            <input type="hidden" id="form-school-year" name="school_year" value="{{ $schoolYear }}">

            {{-- Scrollable Fields --}}
            <div class="overflow-y-auto flex-1 px-6 py-5 space-y-4">

            {{-- If Add mode: date picker --}}
            <div id="date-picker-row" style="display:none">
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Select Date *</label>
                <input type="date" id="input-date-picker" name="_date_picker"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Day Type --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Day Type *</label>
                <select id="input-day-type" name="day_type"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="regular">Regular Day</option>
                    <option value="holiday">Holiday</option>
                    <option value="suspended">Suspended</option>
                    <option value="early_dismissal">Early Dismissal</option>
                    <option value="exam_day">Exam Day</option>
                    <option value="school_event">School Event</option>
                    <option value="break">Break</option>
                </select>
            </div>

            {{-- Event Details Section --}}
            <div class="rounded-lg border border-slate-200 dark:border-dark-border p-4 space-y-3">
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Event Details</p>

                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Event Title</label>
                    <input type="text" id="input-event-title" name="event_title" placeholder="e.g. Araw ng Kagitingan"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Description</label>
                    <textarea id="input-description" name="description" rows="2" placeholder="Optional details..."
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>
            </div>

            {{-- Attendance Rule --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Attendance Rule *</label>
                <select id="input-attendance" name="attendance_rule"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="normal">Normal attendance taking</option>
                    <option value="no_attendance_holiday">No attendance taken – Holiday</option>
                    <option value="no_attendance_suspension">No attendance taken – Suspension</option>
                    <option value="morning_only">Morning only (Early Dismissal)</option>
                    <option value="afternoon_only">Afternoon only</option>
                    <option value="exam_present">Exam day – present if took exam</option>
                </select>
            </div>

            {{-- Applies To (UI only — functional later) --}}
            <div class="rounded-lg border border-slate-100 dark:border-dark-border p-4 bg-slate-50 dark:bg-white/5">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-2">Applies To <span class="font-normal normal-case text-slate-400">(UI — configure later)</span></p>
                <div class="flex gap-3 text-xs text-slate-500">
                    <label class="flex items-center gap-1.5"><input type="radio" name="applies_to" value="all" checked> All Grades</label>
                    <label class="flex items-center gap-1.5"><input type="radio" name="applies_to" value="specific"> Specific Grades</label>
                </div>
            </div>

            {{-- Notifications (UI only — functional later) --}}
            <div class="rounded-lg border border-slate-100 dark:border-dark-border p-4 bg-slate-50 dark:bg-white/5">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-2">Notifications <span class="font-normal normal-case text-slate-400">(UI — configure later)</span></p>
                <div class="flex flex-col gap-1.5 text-xs text-slate-500">
                    <label class="flex items-center gap-1.5"><input type="checkbox" name="notify_teachers" value="1"> Notify Teachers</label>
                    <label class="flex items-center gap-1.5"><input type="checkbox" name="notify_parents" value="1"> Notify Parents via SMS/Email</label>
                    <label class="flex items-center gap-1.5"><input type="checkbox" name="add_to_public" value="1"> Add to Public School Calendar</label>
                    <label class="flex items-center gap-1.5"><input type="checkbox" name="send_reminder" value="1"> Send reminder 1 day before</label>
                </div>
            </div>

            </div>{{-- end scrollable fields --}}

            {{-- Actions Footer (sticky, always visible) --}}
            <div class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-white dark:bg-dark-card">
                <button type="button" id="btn-delete-event"
                    style="display:none"
                    class="flex items-center gap-1.5 text-xs text-red-500 hover:text-red-700 transition-colors">
                    <iconify-icon icon="solar:trash-bin-trash-linear" width="15"></iconify-icon>
                    Delete Event
                </button>
                <div class="flex items-center gap-3 ml-auto">
                    <button type="button" id="modal-cancel"
                        class="rounded-lg border border-slate-200 dark:border-dark-border px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="modal-save"
                        class="rounded-lg bg-[#0d4c8f] hover:bg-blue-800 text-white px-5 py-2 text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                        <iconify-icon icon="solar:disk-linear" width="15"></iconify-icon>
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// ════════════════════════════════════════════════════════════
// Config — injected from PHP
// ════════════════════════════════════════════════════════════
const SCHOOL_YEAR = '{{ $schoolYear }}';
const ROUTES = {
    store:     '{{ route("admin.school-calendar.store") }}',
    update:    '{{ url("admin/school-calendar") }}',   // + /{id}
    destroy:   '{{ url("admin/school-calendar") }}',   // + /{id}
    getByDate: '{{ route("admin.school-calendar.get-by-date") }}',
    downloadPdf: '{{ route("admin.school-calendar.download-pdf") }}',
};
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ── Event store (keyed by "YYYY-MM-DD") ─────────────────────
let calEvents = @json($events);

// ── Calendar state ───────────────────────────────────────────
let calCurrent = new Date({{ $year }}, {{ $month - 1 }}, 1);
let calView    = 'month';

// ════════════════════════════════════════════════════════════
// RENDER HELPERS
// ════════════════════════════════════════════════════════════
function dateStr(y, m, d) {
    return `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
}
function badgeHtml(ds) {
    const ev = calEvents[ds];
    if (ev) {
        const title = ev.label || '';
        const desc  = ev.description ? `<div class="mt-0.5 opacity-75 truncate">${ev.description}</div>` : '';
        return `<div class="rounded-md px-2 py-1 text-[11px] font-semibold leading-snug mb-0.5 cursor-pointer ${ev.badge_class}" onclick="openEditModal('${ds}')">
                    <div class="truncate">${title}</div>${desc}
                </div>`;
    }
    return '';
}

// ════════════════════════════════════════════════════════════
// MONTH VIEW
// ════════════════════════════════════════════════════════════
function renderMonth() {
    const grid  = document.getElementById('cal-grid');
    const title = document.getElementById('cal-title');
    const y = calCurrent.getFullYear(), m = calCurrent.getMonth();
    const today     = new Date();
    const firstDay  = new Date(y, m, 1).getDay();
    const daysInM   = new Date(y, m+1, 0).getDate();
    const daysInPrev= new Date(y, m, 0).getDate();
    const total     = Math.ceil((firstDay + daysInM) / 7) * 7;

    title.textContent = new Date(y, m, 1).toLocaleString('default', { month: 'long', year: 'numeric' });

    let html = '';
    for (let i = 0; i < total; i++) {
        let day, isCur = true, ds = '';
        const col = i % 7;
        if (i < firstDay) {
            day = daysInPrev - firstDay + i + 1; isCur = false;
            const pm = m===0?11:m-1, py = m===0?y-1:y;
            ds = dateStr(py, pm, day);
        } else if (i >= firstDay + daysInM) {
            day = i - firstDay - daysInM + 1; isCur = false;
            const nm = m===11?0:m+1, ny = m===11?y+1:y;
            ds = dateStr(ny, nm, day);
        } else {
            day = i - firstDay + 1;
            ds  = dateStr(y, m, day);
        }
        const isToday  = isCur && day===today.getDate() && m===today.getMonth() && y===today.getFullYear();
        const weekend  = col===0||col===6;
        const hasSaved = !!calEvents[ds];

        const bg       = weekend && isCur ? 'bg-slate-50 dark:bg-white/[0.02]' : '';

        html += `<div class="border-r border-b border-slate-200 dark:border-dark-border min-h-[110px] p-2 ${bg}" data-date="${isCur ? ds : ''}">`;
        html += `<div class="flex justify-end mb-1.5">
                    <span class="text-sm font-bold w-8 h-8 flex items-center justify-center rounded-full
                        ${isToday ? 'bg-[#0d4c8f] text-white shadow-sm' : isCur ? 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10 cursor-pointer' : 'text-slate-300 dark:text-slate-600'}"
                        ${isCur ? `onclick="openEditModal('${ds}')"` : ''}>
                        ${day}
                    </span>
                 </div>`;
        if (hasSaved) html += badgeHtml(ds);
        html += `</div>`;
    }
    grid.innerHTML = html;
}

// ════════════════════════════════════════════════════════════
// WEEK VIEW
// ════════════════════════════════════════════════════════════
function renderWeek() {
    const grid  = document.getElementById('cal-grid');
    const title = document.getElementById('cal-title');
    const d     = new Date(calCurrent);
    d.setDate(d.getDate() - d.getDay());
    const days  = Array.from({length:7}, (_,i) => { const dd=new Date(d); dd.setDate(d.getDate()+i); return dd; });
    const today = new Date();
    title.textContent = `${days[0].toLocaleDateString('default',{month:'short',day:'numeric'})} – ${days[6].toLocaleDateString('default',{month:'short',day:'numeric',year:'numeric'})}`;
    let html = '';
    days.forEach((dd, col) => {
        const ds      = dateStr(dd.getFullYear(), dd.getMonth(), dd.getDate());
        const weekend = col===0||col===6;
        const isToday = dd.toDateString()===today.toDateString();
        const hasSaved= !!calEvents[ds];
        const bg      = weekend ? 'bg-slate-50 dark:bg-white/[0.02]' : '';
        html += `<div class="border-r border-b border-slate-200 dark:border-dark-border min-h-[140px] p-2.5 ${bg}">`;
        html += `<div class="flex justify-center mb-2"><span class="text-sm font-bold w-9 h-9 flex items-center justify-center rounded-full ${isToday?'bg-[#0d4c8f] text-white shadow-sm':'text-slate-700 dark:text-slate-200 cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10'}" onclick="openEditModal('${ds}')">${dd.getDate()}</span></div>`;
        if (hasSaved) html += badgeHtml(ds);
        html += `</div>`;
    });
    grid.innerHTML = html;
}

// ════════════════════════════════════════════════════════════
// DAY VIEW
// ════════════════════════════════════════════════════════════
function renderDay() {
    const grid  = document.getElementById('cal-grid');
    const title = document.getElementById('cal-title');
    const y=calCurrent.getFullYear(), m=calCurrent.getMonth(), day=calCurrent.getDate();
    const ds  = dateStr(y, m, day);
    const col = calCurrent.getDay();
    title.textContent = calCurrent.toLocaleDateString('default',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
    const weekend  = col===0||col===6;
    const hasSaved = !!calEvents[ds];
    let html = `<div class="col-span-7 border-r border-b border-slate-200 dark:border-dark-border min-h-[160px] p-4">`;
    if (weekend)       html += `<p class="text-sm text-slate-400 italic">Weekend — No Classes</p>`;
    else if (!hasSaved) html += `<div class="rounded px-2 py-1 text-sm bg-green-100 text-green-700 inline-block cursor-pointer" onclick="openEditModal('${ds}')">Regular Class</div>`;
    if (hasSaved) {
        const ev = calEvents[ds];
        html += `<div class="rounded px-2 py-1 text-sm mb-1 ${ev.badge_class} inline-block cursor-pointer" onclick="openEditModal('${ds}')">${(ev.label||'').replace(/\n/g,'<br>')}</div>`;
    }
    html += `</div>`;
    grid.innerHTML = html;
}

// ════════════════════════════════════════════════════════════
// LIST VIEW
// ════════════════════════════════════════════════════════════
function renderList() {
    const grid  = document.getElementById('cal-grid');
    const title = document.getElementById('cal-title');
    const y=calCurrent.getFullYear(), m=calCurrent.getMonth();
    const daysInM = new Date(y, m+1, 0).getDate();
    title.textContent = new Date(y, m, 1).toLocaleString('default',{month:'long',year:'numeric'});
    let html = `<div class="col-span-7 divide-y divide-slate-100 dark:divide-dark-border">`;
    for (let d=1; d<=daysInM; d++) {
        const ds  = dateStr(y, m, d);
        const dow = new Date(y, m, d).getDay();
        const weekend  = dow===0||dow===6;
        const hasSaved = !!calEvents[ds];
        if (weekend && !hasSaved) continue;
        const dayLabel = new Date(y, m, d).toLocaleDateString('default',{weekday:'short',day:'numeric'});
        html += `<div class="flex items-center gap-4 px-4 py-3 hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer" onclick="openEditModal('${ds}')">`;
        html += `<span class="text-xs font-medium text-slate-500 dark:text-slate-400 w-16 shrink-0">${dayLabel}</span>`;
        html += `<div class="flex flex-wrap gap-1">`;
        if (!weekend && !hasSaved) html += `<span class="rounded px-2 py-0.5 text-xs bg-green-100 text-green-700">Regular Class</span>`;
        if (hasSaved) {
            const ev = calEvents[ds];
            html += `<span class="rounded px-2 py-0.5 text-xs ${ev.badge_class}">${(ev.label||'').split('\n')[0]}</span>`;
        }
        html += `</div></div>`;
    }
    html += `</div>`;
    grid.innerHTML = html;
}

// ════════════════════════════════════════════════════════════
// ROUTER
// ════════════════════════════════════════════════════════════
function renderCal() {
    if      (calView==='month') renderMonth();
    else if (calView==='week')  renderWeek();
    else if (calView==='day')   renderDay();
    else if (calView==='list')  renderList();
}
function calNav(dir) {
    if      (dir==='prev-month') { if(calView==='week') calCurrent.setDate(calCurrent.getDate()-7); else if(calView==='day') calCurrent.setDate(calCurrent.getDate()-1); else calCurrent.setMonth(calCurrent.getMonth()-1); }
    else if (dir==='next-month') { if(calView==='week') calCurrent.setDate(calCurrent.getDate()+7); else if(calView==='day') calCurrent.setDate(calCurrent.getDate()+1); else calCurrent.setMonth(calCurrent.getMonth()+1); }
    else if (dir==='prev-year')  { calCurrent.setFullYear(calCurrent.getFullYear()-1); }
    else if (dir==='next-year')  { calCurrent.setFullYear(calCurrent.getFullYear()+1); }
    else if (dir==='today')      { calCurrent = new Date(); }
    syncJumpSelects();
    renderCal();
}
function setCalView(v) {
    calView = v;
    ['month','week','day','list'].forEach(x => {
        const btn = document.getElementById('cal-view-'+x);
        if (!btn) return;
        btn.className = x===v
            ? 'px-3 py-1.5 font-medium bg-[#0d4c8f] text-white transition-colors text-xs'
            : 'px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors text-xs';
    });
    renderCal();
}
function syncJumpSelects() {
    document.getElementById('jump-month').value = calCurrent.getMonth()+1;
    document.getElementById('jump-year').value  = calCurrent.getFullYear();
}

// ════════════════════════════════════════════════════════════
// MODAL — OPEN / CLOSE
// ════════════════════════════════════════════════════════════
function openAddModal(prefilledDate = null) {
    resetModal();
    document.getElementById('modal-title').textContent    = 'Add Event';
    document.getElementById('modal-subtitle').textContent = prefilledDate ? formatDateLabel(prefilledDate) : 'Select a date below';
    document.getElementById('date-picker-row').style.display = 'block';
    document.getElementById('btn-delete-event').style.display = 'none';
    if (prefilledDate) {
        document.getElementById('input-date-picker').value = prefilledDate;
        document.getElementById('form-date').value         = prefilledDate;
    }
    showModal();
}

async function openEditModal(ds) {
    resetModal();
    document.getElementById('modal-title').textContent    = `Edit Day`;
    document.getElementById('modal-subtitle').textContent = formatDateLabel(ds);
    document.getElementById('form-date').value            = ds;

    const saved = calEvents[ds];
    if (saved && saved.id) {
        // Load full record from server
        try {
            const r = await fetch(`${ROUTES.update}/${saved.id}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            });
            const ev = await r.json();
            fillModalFromEvent(ev);
            document.getElementById('form-event-id').value = ev.id;
            document.getElementById('btn-delete-event').style.display = 'flex';
        } catch(e) { fillModalFromSaved(saved); }
    } else {
        // New / unsaved — just open blank with date pre-filled
        document.getElementById('modal-title').textContent = 'Add / Edit Day';
    }
    showModal();
}

function fillModalFromEvent(ev) {
    document.getElementById('input-day-type').value  = ev.day_type       || 'regular';
    document.getElementById('input-event-title').value= ev.event_title   || '';
    document.getElementById('input-description').value= ev.description   || '';
    document.getElementById('input-attendance').value = ev.attendance_rule|| 'normal';
}
function fillModalFromSaved(saved) {
    document.getElementById('input-day-type').value   = saved.day_type || 'regular';
    document.getElementById('input-event-title').value= saved.event_title || '';
}
function resetModal() {
    document.getElementById('event-form').reset();
    document.getElementById('form-event-id').value = '';
    document.getElementById('form-date').value     = '';
    document.getElementById('date-picker-row').style.display        = 'none';
    document.getElementById('btn-delete-event').style.display       = 'none';
    document.getElementById('modal-errors').style.display           = 'none';
    document.getElementById('modal-error-list').innerHTML           = '';
}
function showModal()  { document.getElementById('event-modal').style.display = 'flex'; }
function closeModal() {
    document.getElementById('event-modal').style.display = 'none';
    document.getElementById('modal-errors').style.display = 'none';
}

function formatDateLabel(ds) {
    if (!ds) return '';
    const d = new Date(ds + 'T00:00:00');
    return d.toLocaleDateString('default', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}

// ════════════════════════════════════════════════════════════
// FORM SUBMIT
// ════════════════════════════════════════════════════════════
document.getElementById('event-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const eventId = document.getElementById('form-event-id').value;
    let date      = document.getElementById('form-date').value;

    // If Add mode, use date picker value
    const pickerRow = document.getElementById('date-picker-row');
    if (pickerRow.style.display !== 'none') {
        date = document.getElementById('input-date-picker').value;
        if (!date) { showToast('Please select a date.', 'error'); return; }
        document.getElementById('form-date').value = date;
    }

    const body = {
        school_year:    SCHOOL_YEAR,
        date:           date,
        day_type:       document.getElementById('input-day-type').value,
        event_title:    document.getElementById('input-event-title').value,
        description:    document.getElementById('input-description').value,
        attendance_rule:document.getElementById('input-attendance').value,
        applies_to:     'all',
        _token:         CSRF,
    };

    let url    = ROUTES.store;
    let method = 'POST';
    if (eventId) {
        url    = `${ROUTES.update}/${eventId}`;
        method = 'PUT';
        delete body.school_year; // not needed on update
        delete body.date;
    }

    const saveBtn = document.getElementById('modal-save');
    saveBtn.disabled = true;
    saveBtn.innerHTML = `<iconify-icon icon="solar:loading-bold-duotone" width="15" class="animate-spin"></iconify-icon> Saving…`;

    try {
        const r = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(body),
        });
        const data = await r.json();
        if (data.success) {
            // Update local store then close — always close first
            closeModal();
            try {
                calEvents[date] = {
                    id:          data.event.id,
                    day_type:    data.event.day_type,
                    event_title: data.event.event_title,
                    badge_class: data.event.badge_class,
                    label:       data.event.label,
                };
                renderCal();
                refreshUpcoming();
            } catch(_) {}
            showToast(data.message || 'Event saved.', 'success');
        } else if (r.status === 422 && data.errors) {
            // Show validation errors inside modal
            const errBox  = document.getElementById('modal-errors');
            const errList = document.getElementById('modal-error-list');
            errList.innerHTML = Object.values(data.errors).flat()
                .map(e => `<li>${e}</li>`).join('');
            errBox.style.display = 'block';
            errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            showToast(data.message || 'Something went wrong.', 'error');
        }
    } catch(err) {
        showToast('Network error. Please try again.', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = `<iconify-icon icon="solar:disk-linear" width="15"></iconify-icon> Save`;
    }
});

// ════════════════════════════════════════════════════════════
// DELETE EVENT
// ════════════════════════════════════════════════════════════
document.getElementById('btn-delete-event').addEventListener('click', async function() {
    const eventId = document.getElementById('form-event-id').value;
    const date    = document.getElementById('form-date').value;
    if (!eventId || !confirm('Delete this event?')) return;

    const r = await fetch(`${ROUTES.destroy}/${eventId}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
    });
    const data = await r.json();
    if (data.success) {
        closeModal();
        try {
            delete calEvents[date];
            renderCal();
            refreshUpcoming();
        } catch(_) {}
        showToast('Event deleted.', 'success');
    }
});

// ════════════════════════════════════════════════════════════
// UPCOMING TABLE REFRESH
// ════════════════════════════════════════════════════════════
const UPCOMING_TYPES = ['holiday', 'exam_day', 'school_event', 'break'];
const TYPE_LABELS = {
    regular:'Regular Class', holiday:'Holiday', suspended:'Suspended',
    early_dismissal:'Early Dismissal', exam_day:'Exam Day',
    school_event:'School Event', break:'Break'
};
const TYPE_BADGE = {
    regular:'bg-green-100 text-green-700', holiday:'bg-purple-100 text-purple-700',
    suspended:'bg-red-100 text-red-700', early_dismissal:'bg-amber-100 text-amber-700',
    exam_day:'bg-blue-100 text-blue-700', school_event:'bg-yellow-100 text-yellow-700',
    break:'bg-orange-100 text-orange-700'
};

function refreshUpcoming() {
    const y = calCurrent.getFullYear(), m = calCurrent.getMonth();
    const rows = [];
    Object.entries(calEvents).forEach(([ds, ev]) => {
        const d = new Date(ds + 'T00:00:00');
        if (d.getFullYear()===y && d.getMonth()===m && UPCOMING_TYPES.includes(ev.day_type)) {
            rows.push({ ds, d, ev });
        }
    });
    rows.sort((a,b) => a.d - b.d);
    const tbody = document.getElementById('upcoming-tbody');
    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="4" class="py-6 text-center text-sm text-slate-400">No upcoming events this month.</td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(({d, ev}) => `
        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
            <td class="py-3 pr-6 text-slate-700 dark:text-slate-300">${d.toLocaleDateString('default',{month:'short',day:'numeric'})}</td>
            <td class="py-3 pr-6 text-slate-500 dark:text-slate-400">${d.toLocaleDateString('default',{weekday:'long'})}</td>
            <td class="py-3 pr-6"><span class="rounded-full px-2.5 py-0.5 text-xs font-medium ${TYPE_BADGE[ev.day_type]||''}">${TYPE_LABELS[ev.day_type]||ev.day_type}</span></td>
            <td class="py-3 text-slate-700 dark:text-slate-300">${ev.event_title||'—'}</td>
        </tr>
    `).join('');
}

// ════════════════════════════════════════════════════════════
// SUSPEND CLASS TODAY (quick shortcut)
// ════════════════════════════════════════════════════════════
document.getElementById('btn-suspend-today').addEventListener('click', function() {
    const today = new Date();
    const ds    = dateStr(today.getFullYear(), today.getMonth(), today.getDate());
    document.getElementById('input-day-type').value   = 'suspended';
    document.getElementById('input-event-title').value = 'Class Suspension';
    document.getElementById('input-attendance').value  = 'no_attendance_suspension';
    openEditModal(ds);
    // Pre-fill after modal resets
    setTimeout(() => {
        document.getElementById('form-date').value        = ds;
        document.getElementById('input-day-type').value  = 'suspended';
        document.getElementById('input-event-title').value= 'Class Suspension';
        document.getElementById('input-attendance').value = 'no_attendance_suspension';
        document.getElementById('modal-title').textContent = 'Suspend Class Today';
        document.getElementById('modal-subtitle').textContent = formatDateLabel(ds);
    }, 50);
});

// ════════════════════════════════════════════════════════════
// ADD EVENT BUTTON
// ════════════════════════════════════════════════════════════
document.getElementById('btn-add-event').addEventListener('click', () => openAddModal());

// Date picker → sync hidden field
document.getElementById('input-date-picker').addEventListener('change', function() {
    document.getElementById('form-date').value = this.value;
});

// ════════════════════════════════════════════════════════════
// DOWNLOAD PDF
// ════════════════════════════════════════════════════════════
document.getElementById('btn-download-pdf').addEventListener('click', function() {
    window.open(`${ROUTES.downloadPdf}?school_year=${SCHOOL_YEAR}`, '_blank');
});

// ════════════════════════════════════════════════════════════
// JUMP SELECTS
// ════════════════════════════════════════════════════════════
document.getElementById('jump-month').addEventListener('change', function() {
    calCurrent.setMonth(parseInt(this.value)-1);
    renderCal(); refreshUpcoming();
});
document.getElementById('jump-year').addEventListener('change', function() {
    calCurrent.setFullYear(parseInt(this.value));
    renderCal(); refreshUpcoming();
});

// ════════════════════════════════════════════════════════════
// MODAL CLOSE TRIGGERS
// ════════════════════════════════════════════════════════════
document.getElementById('modal-close').addEventListener('click',    closeModal);
document.getElementById('modal-cancel').addEventListener('click',   closeModal);
document.getElementById('modal-backdrop').addEventListener('click', closeModal);

// ════════════════════════════════════════════════════════════
// TOAST
// ════════════════════════════════════════════════════════════
function showToast(msg, type='success') {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    const text  = document.getElementById('toast-msg');
    text.textContent = msg;
    if (type==='success') {
        toast.className = 'fixed top-6 right-6 z-50 flex items-center gap-3 rounded-xl px-5 py-3.5 shadow-xl text-sm font-medium transition-all bg-green-500 text-white';
        icon.setAttribute('icon', 'solar:check-circle-bold');
    } else {
        toast.className = 'fixed top-6 right-6 z-50 flex items-center gap-3 rounded-xl px-5 py-3.5 shadow-xl text-sm font-medium transition-all bg-red-500 text-white';
        icon.setAttribute('icon', 'solar:close-circle-bold');
    }
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3500);
}

// ════════════════════════════════════════════════════════════
// INIT
// ════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    syncJumpSelects();
    renderCal();
});
</script>
@endpush

@endsection
