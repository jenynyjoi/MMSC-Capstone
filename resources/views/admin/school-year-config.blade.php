@extends('layouts.admin_layout')
@section('title', 'School Year Configuration')
@section('content')

<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Page Header --}}
    <x-admin.page-header
        title="School Year Configuration"
        subtitle="Define school years, their date ranges, class days, and statuses"
    >
        <x-slot:breadcrumb>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('admin.school-calendar.index') }}" class="hover:text-slate-600 transition-colors">School Calendar</a>
                <iconify-icon icon="solar:alt-arrow-right-linear" width="12"></iconify-icon>
                <span>School Year Configuration</span>
            </div>
        </x-slot:breadcrumb>
        <button id="btn-add-sy"
            class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 text-white px-4 py-2 text-sm font-medium transition-colors shadow-sm self-start sm:self-auto mt-2 sm:mt-0">
            <iconify-icon icon="solar:add-circle-linear" width="16"></iconify-icon>
            Add School Year
        </button>
    </x-admin.page-header>

    {{-- Toast --}}
    <div id="toast" style="display:none" class="fixed top-6 right-6 z-50 flex items-center gap-3 rounded-xl px-5 py-3.5 shadow-xl text-sm font-medium transition-all">
        <iconify-icon id="toast-icon" width="18"></iconify-icon>
        <span id="toast-msg"></span>
    </div>

    {{-- Table Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border flex items-center gap-2">
            <iconify-icon icon="solar:calendar-bold" width="17" class="text-slate-500 dark:text-slate-400"></iconify-icon>
            <h2 class="text-sm font-semibold text-slate-800 dark:text-white">Configured School Years</h2>
            <span class="ml-auto text-xs text-slate-400">{{ $schoolYears->count() }} record(s)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:780px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">
                        <th class="px-5 py-3">School Year</th>
                        <th class="px-5 py-3">Period</th>
                        <th class="px-5 py-3">Effective Date</th>
                        <th class="px-5 py-3">Class Days</th>
                        <th class="px-5 py-3 text-center">Status</th>
                        <th class="px-5 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="sy-table-body" class="divide-y divide-slate-100 dark:divide-dark-border">
                @forelse($schoolYears as $sy)
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors" data-id="{{ $sy->id }}">
                    <td class="px-5 py-3.5">
                        <span class="font-semibold text-slate-800 dark:text-white">SY {{ $sy->name }}</span>
                        @if($sy->description)
                        <p class="text-xs text-slate-400 mt-0.5">{{ $sy->description }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-xs text-slate-600 dark:text-slate-300">
                        {{ $sy->start_date->format('M j, Y') }}
                        <span class="text-slate-400 mx-1">→</span>
                        {{ $sy->end_date->format('M j, Y') }}
                    </td>
                    <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400">
                        {{ $sy->effective_date?->format('M j, Y') ?? '—' }}
                    </td>
                    <td class="px-5 py-3.5 text-xs text-slate-600 dark:text-slate-300">
                        {{ $sy->classDaysLabel() }}
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $sy->statusBadge() }}">
                            {{ ucfirst($sy->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openEdit({{ $sy->id }})"
                                class="flex items-center gap-1 rounded-lg border border-slate-200 dark:border-dark-border px-2.5 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                <iconify-icon icon="solar:pen-linear" width="12"></iconify-icon> Edit
                            </button>
                            <button onclick="deleteSY({{ $sy->id }}, '{{ $sy->name }}')"
                                class="flex items-center gap-1 rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-500 hover:bg-red-50 transition-colors">
                                <iconify-icon icon="solar:trash-bin-trash-linear" width="12"></iconify-icon> Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="empty-row">
                    <td colspan="6" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-slate-400">
                            <iconify-icon icon="solar:calendar-linear" width="36"></iconify-icon>
                            <p class="text-sm">No school years configured yet.</p>
                            <button onclick="openAdd()" class="text-xs text-[#0d4c8f] hover:underline">Add your first school year</button>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="mt-6 rounded-xl border border-blue-100 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-900/10 px-5 py-4 flex gap-3">
        <iconify-icon icon="solar:info-circle-linear" width="18" class="text-blue-500 mt-0.5 shrink-0"></iconify-icon>
        <div class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
            <p><strong>Class Days</strong> define which days of the week are regular school days within the school year period. Days outside this range are automatically non-class days.</p>
            <p><strong>Effective Date</strong> is when enrollment and admissions for that school year opens — it can be earlier than the start date.</p>
            <p>Only <strong>one school year can be Active</strong> at a time. Setting a year to Active will move any existing active year to Upcoming.</p>
        </div>
    </div>

    <p class="mt-8 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══════════════════════════════════════
     ADD / EDIT SCHOOL YEAR MODAL
     ══════════════════════════════════════ --}}
<div id="sy-modal" style="display:none" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="sy-backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white dark:bg-dark-card shadow-2xl border border-slate-200 dark:border-dark-border flex flex-col max-h-[92vh] overflow-hidden">

        {{-- Header --}}
        <div class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/5">
            <div>
                <h3 id="sy-modal-title" class="text-base font-semibold text-slate-900 dark:text-white">Add School Year</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Configure school year dates and class day rules</p>
            </div>
            <button id="sy-modal-close" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">
                <iconify-icon icon="solar:close-linear" width="18"></iconify-icon>
            </button>
        </div>

        {{-- Validation Errors --}}
        <div id="sy-errors" style="display:none" class="flex-shrink-0 mx-6 mt-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
            <p class="text-xs font-semibold text-red-600 mb-1">Please fix the following errors:</p>
            <ul id="sy-error-list" class="list-disc list-inside text-xs text-red-500 space-y-0.5"></ul>
        </div>

        {{-- Scrollable Body --}}
        <form id="sy-form" class="flex flex-col flex-1 overflow-hidden">
            <input type="hidden" id="sy-id">

            <div class="overflow-y-auto flex-1 px-6 py-5 space-y-5">

                {{-- School Year Name --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">School Year Name *</label>
                    <input type="text" id="sy-name" placeholder="e.g. 2025-2026"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-slate-400 mt-1">Format: YYYY-YYYY (e.g. 2025-2026)</p>
                </div>

                {{-- Date Range --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Start Date *</label>
                        <input type="date" id="sy-start"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">End Date *</label>
                        <input type="date" id="sy-end"
                            class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Effective Date --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Effective Date <span class="font-normal normal-case text-slate-400">(optional)</span></label>
                    <input type="date" id="sy-effective"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-slate-400 mt-1">When enrollment/admissions for this year opens</p>
                </div>

                {{-- Class Days --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2 uppercase tracking-wide">Regular Class Days *</label>
                    <p class="text-xs text-slate-400 mb-3">Select which days of the week are regular school days within the school year period</p>
                    <div class="grid grid-cols-7 gap-1.5">
                        @foreach([0=>'Sun',1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat'] as $num => $label)
                        <label class="flex flex-col items-center gap-1.5 cursor-pointer group">
                            <input type="checkbox" class="sy-day-check sr-only" value="{{ $num }}">
                            <span class="day-btn w-full text-center rounded-lg border border-slate-200 dark:border-dark-border py-2 text-xs font-medium text-slate-500 dark:text-slate-400 group-hover:border-blue-400 group-hover:text-blue-600 transition-all select-none
                                {{ in_array($num, [0,6]) ? 'opacity-60' : '' }}">
                                {{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Status *</label>
                    <select id="sy-status"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="upcoming">Upcoming</option>
                        <option value="active">Active</option>
                        <option value="ended">Ended</option>
                    </select>
                    <p class="text-xs text-slate-400 mt-1">Setting to <strong>Active</strong> will automatically move any current active year to Upcoming</p>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">Notes <span class="font-normal normal-case text-slate-400">(optional)</span></label>
                    <textarea id="sy-description" rows="2" placeholder="Any additional notes about this school year..."
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-bg px-3 py-2 text-sm text-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>

            </div>{{-- end scrollable --}}

            {{-- Footer Actions --}}
            <div class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-white dark:bg-dark-card">
                <button type="button" id="sy-delete-btn"
                    style="display:none"
                    class="flex items-center gap-1.5 text-xs text-red-500 hover:text-red-700 transition-colors">
                    <iconify-icon icon="solar:trash-bin-trash-linear" width="15"></iconify-icon>
                    Delete
                </button>
                <div class="flex items-center gap-3 ml-auto">
                    <button type="button" id="sy-modal-cancel"
                        class="rounded-lg border border-slate-200 dark:border-dark-border px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="sy-save-btn"
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
const CSRF   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const ROUTES = {
    store:   '{{ route("admin.school-year-config.store") }}',
    base:    '{{ url("admin/school-year-config") }}',
};

// ── Day checkbox visual toggle ────────────────────────────────
document.querySelectorAll('.sy-day-check').forEach(chk => {
    chk.addEventListener('change', function() {
        const span = this.closest('label').querySelector('.day-btn');
        if (this.checked) {
            span.classList.add('border-[#0d4c8f]', 'text-[#0d4c8f]', 'bg-blue-50', 'dark:bg-blue-900/20');
            span.classList.remove('text-slate-500', 'dark:text-slate-400', 'border-slate-200', 'dark:border-dark-border');
        } else {
            span.classList.remove('border-[#0d4c8f]', 'text-[#0d4c8f]', 'bg-blue-50', 'dark:bg-blue-900/20');
            span.classList.add('text-slate-500', 'dark:text-slate-400', 'border-slate-200', 'dark:border-dark-border');
        }
    });
});

// ── Modal helpers ─────────────────────────────────────────────
function showModal() { document.getElementById('sy-modal').style.display = 'flex'; }
function closeModal() {
    document.getElementById('sy-modal').style.display = 'none';
    document.getElementById('sy-errors').style.display = 'none';
    document.getElementById('sy-error-list').innerHTML = '';
}

function resetForm() {
    document.getElementById('sy-form').reset();
    document.getElementById('sy-id').value = '';
    document.getElementById('sy-delete-btn').style.display = 'none';
    document.getElementById('sy-errors').style.display = 'none';
    document.getElementById('sy-error-list').innerHTML = '';
    // Reset day checkboxes
    document.querySelectorAll('.sy-day-check').forEach(chk => {
        chk.checked = false;
        chk.dispatchEvent(new Event('change'));
    });
    // Default Mon–Fri checked
    [1,2,3,4,5].forEach(d => {
        const chk = document.querySelector(`.sy-day-check[value="${d}"]`);
        if (chk) { chk.checked = true; chk.dispatchEvent(new Event('change')); }
    });
}

function setDays(days) {
    document.querySelectorAll('.sy-day-check').forEach(chk => {
        chk.checked = days.includes(parseInt(chk.value));
        chk.dispatchEvent(new Event('change'));
    });
}

function getCheckedDays() {
    return [...document.querySelectorAll('.sy-day-check:checked')].map(c => parseInt(c.value));
}

// ── Open Add ──────────────────────────────────────────────────
function openAdd() {
    resetForm();
    document.getElementById('sy-modal-title').textContent = 'Add School Year';
    showModal();
}

// ── Open Edit ─────────────────────────────────────────────────
async function openEdit(id) {
    resetForm();
    document.getElementById('sy-modal-title').textContent = 'Edit School Year';

    try {
        const r  = await fetch(`${ROUTES.base}/${id}`, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
        const sy = await r.json();
        document.getElementById('sy-id').value          = sy.id;
        document.getElementById('sy-name').value        = sy.name;
        document.getElementById('sy-start').value       = sy.start_date;
        document.getElementById('sy-end').value         = sy.end_date;
        document.getElementById('sy-effective').value   = sy.effective_date ?? '';
        document.getElementById('sy-status').value      = sy.status;
        document.getElementById('sy-description').value = sy.description ?? '';
        setDays(sy.class_days ?? [1,2,3,4,5]);
        document.getElementById('sy-delete-btn').style.display = 'flex';
    } catch(e) {
        showToast('Failed to load school year.', 'error');
        return;
    }
    showModal();
}

// ── Delete ────────────────────────────────────────────────────
async function deleteSY(id, name) {
    if (!confirm(`Delete school year "${name}"? This cannot be undone.`)) return;
    try {
        const r    = await fetch(`${ROUTES.base}/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
        const data = await r.json();
        if (data.success) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) row.remove();
            checkEmpty();
            showToast(data.message, 'success');
            closeModal();
        } else {
            showToast(data.message || 'Delete failed.', 'error');
        }
    } catch(e) {
        showToast('Network error.', 'error');
    }
}

// ── Form submit ───────────────────────────────────────────────
document.getElementById('sy-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const id      = document.getElementById('sy-id').value;
    const days    = getCheckedDays();
    if (!days.length) {
        showErrors(['Please select at least one class day.']);
        return;
    }

    const body = {
        name:           document.getElementById('sy-name').value.trim(),
        start_date:     document.getElementById('sy-start').value,
        end_date:       document.getElementById('sy-end').value,
        effective_date: document.getElementById('sy-effective').value || null,
        class_days:     days,
        status:         document.getElementById('sy-status').value,
        description:    document.getElementById('sy-description').value.trim() || null,
    };

    const url    = id ? `${ROUTES.base}/${id}` : ROUTES.store;
    const method = id ? 'PUT' : 'POST';

    const saveBtn = document.getElementById('sy-save-btn');
    saveBtn.disabled = true;
    saveBtn.innerHTML = `<iconify-icon icon="solar:loading-bold-duotone" width="15" class="animate-spin"></iconify-icon> Saving…`;

    try {
        const r    = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(body),
        });
        const data = await r.json();

        if (r.ok && data.success) {
            upsertRow(data.school_year, id ? id : null);
            closeModal();
            showToast(data.message, 'success');
        } else if (r.status === 422 && data.errors) {
            showErrors(Object.values(data.errors).flat());
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

// ── Render / update table row ─────────────────────────────────
function upsertRow(sy, existingId) {
    const tbody = document.getElementById('sy-table-body');
    const emptyRow = document.getElementById('empty-row');
    if (emptyRow) emptyRow.remove();

    const html = `
    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors" data-id="${sy.id}">
        <td class="px-5 py-3.5">
            <span class="font-semibold text-slate-800 dark:text-white">SY ${sy.name}</span>
            ${sy.description ? `<p class="text-xs text-slate-400 mt-0.5">${sy.description}</p>` : ''}
        </td>
        <td class="px-5 py-3.5 text-xs text-slate-600 dark:text-slate-300">
            ${sy.start_fmt} <span class="text-slate-400 mx-1">→</span> ${sy.end_fmt}
        </td>
        <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400">${sy.eff_fmt}</td>
        <td class="px-5 py-3.5 text-xs text-slate-600 dark:text-slate-300">${sy.class_days_label}</td>
        <td class="px-5 py-3.5 text-center">
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ${sy.status_badge}">
                ${sy.status.charAt(0).toUpperCase() + sy.status.slice(1)}
            </span>
        </td>
        <td class="px-5 py-3.5 text-center">
            <div class="flex items-center justify-center gap-2">
                <button onclick="openEdit(${sy.id})"
                    class="flex items-center gap-1 rounded-lg border border-slate-200 dark:border-dark-border px-2.5 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:pen-linear" width="12"></iconify-icon> Edit
                </button>
                <button onclick="deleteSY(${sy.id}, '${sy.name}')"
                    class="flex items-center gap-1 rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-500 hover:bg-red-50 transition-colors">
                    <iconify-icon icon="solar:trash-bin-trash-linear" width="12"></iconify-icon> Delete
                </button>
            </div>
        </td>
    </tr>`;

    if (existingId) {
        const existing = document.querySelector(`tr[data-id="${existingId}"]`);
        if (existing) {
            existing.outerHTML = html;
            // If status changed to active, update other rows' status badges
            if (sy.status === 'active') refreshActiveStatuses(sy.id);
            return;
        }
    }
    tbody.insertAdjacentHTML('afterbegin', html);
}

function refreshActiveStatuses(activeId) {
    // When a new active is set, other rows that were active become upcoming — reload to be safe
    // Only do a full reload if there are other active rows
    const rows = document.querySelectorAll('#sy-table-body tr[data-id]');
    let needReload = false;
    rows.forEach(row => {
        if (parseInt(row.dataset.id) !== activeId) {
            const badge = row.querySelector('.rounded-full');
            if (badge && badge.textContent.trim().toLowerCase() === 'active') needReload = true;
        }
    });
    if (needReload) location.reload();
}

function checkEmpty() {
    const tbody = document.getElementById('sy-table-body');
    if (!tbody.querySelector('tr[data-id]')) {
        tbody.innerHTML = `<tr id="empty-row"><td colspan="6" class="px-5 py-16 text-center">
            <div class="flex flex-col items-center gap-3 text-slate-400">
                <iconify-icon icon="solar:calendar-linear" width="36"></iconify-icon>
                <p class="text-sm">No school years configured yet.</p>
                <button onclick="openAdd()" class="text-xs text-[#0d4c8f] hover:underline">Add your first school year</button>
            </div>
        </td></tr>`;
    }
}

// ── Error display ─────────────────────────────────────────────
function showErrors(msgs) {
    const box  = document.getElementById('sy-errors');
    const list = document.getElementById('sy-error-list');
    list.innerHTML = msgs.map(m => `<li>${m}</li>`).join('');
    box.style.display = 'block';
    box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// ── Toast ─────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    document.getElementById('toast-msg').textContent = msg;
    if (type === 'success') {
        toast.className = 'fixed top-6 right-6 z-50 flex items-center gap-3 rounded-xl px-5 py-3.5 shadow-xl text-sm font-medium bg-green-500 text-white';
        icon.setAttribute('icon', 'solar:check-circle-bold');
    } else {
        toast.className = 'fixed top-6 right-6 z-50 flex items-center gap-3 rounded-xl px-5 py-3.5 shadow-xl text-sm font-medium bg-red-500 text-white';
        icon.setAttribute('icon', 'solar:close-circle-bold');
    }
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3500);
}

// ── Event bindings ────────────────────────────────────────────
document.getElementById('btn-add-sy').addEventListener('click', openAdd);
document.getElementById('sy-modal-close').addEventListener('click', closeModal);
document.getElementById('sy-modal-cancel').addEventListener('click', closeModal);
document.getElementById('sy-backdrop').addEventListener('click', closeModal);
document.getElementById('sy-delete-btn').addEventListener('click', function() {
    const id = document.getElementById('sy-id').value;
    const name = document.getElementById('sy-name').value;
    if (id) deleteSY(id, name);
});
</script>
@endpush
@endsection
