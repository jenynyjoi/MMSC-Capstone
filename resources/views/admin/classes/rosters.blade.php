@extends('layouts.admin_layout')
@section('title', 'Class Rosters')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Classes"
        subtitle="Manage Classes, Roster, Classroom and Sections"
        school-year="{{ $schoolYear ?? $activeSchoolYear }}"
    />

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:users-group-rounded-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <div>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Class Rosters</h2>
                <p class="text-xs text-slate-400 mt-0.5">View Section Student List</p>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

                {{-- School Year --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 dark:text-slate-400">School Year <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="filter-sy" onchange="loadSections(this.value)"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            @foreach($school_year as $sy)
                            <option value="{{ $sy }}" {{ $schoolYear === $sy ? 'selected' : '' }}>SY {{ $sy }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                {{-- Grade and Section --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500 dark:text-slate-400">Grade and Section <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="filter-section"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— Select Section —</option>
                            @foreach($sections as $sec)
                            <option value="{{ $sec->id }}"
                                data-grade="{{ $sec->grade_level }}"
                                data-section="{{ $sec->section_name }}"
                                data-adviser="{{ $sec->homeroom_adviser_name ?? 'TBA' }}"
                                data-room="{{ $sec->room ?? '—' }}"
                                data-capacity="{{ $sec->capacity }}">
                                {{ $sec->display_name }}
                            </option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>

                {{-- View button aligned bottom --}}
                <div class="flex items-end">
                    <button onclick="loadRoster()"
                        class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2.5 text-sm font-bold text-white transition-colors">
                        <iconify-icon icon="solar:users-group-rounded-bold" width="16"></iconify-icon>
                        VIEW ROSTER
                    </button>
                </div>
            </div>

            {{-- Validation hint --}}
            <div id="filter-error" class="hidden rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-xs text-red-600 flex items-center gap-2">
                <iconify-icon icon="solar:danger-triangle-bold" width="14"></iconify-icon>
                Please select both a School Year and a Grade/Section before viewing the roster.
            </div>
        </div>

        {{-- Roster Results (hidden until loaded) --}}
        <div id="roster-area" class="hidden">

            {{-- Section Header Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4 bg-[#0d4c8f]/5 dark:bg-blue-900/10 border-b border-slate-100 dark:border-dark-border">
                <div>
                    <h3 id="roster-title" class="text-base font-bold text-slate-800 dark:text-white uppercase tracking-wide"></h3>
                    <div class="flex flex-wrap gap-x-5 gap-y-1 mt-1">
                        <span class="text-xs text-slate-500">Adviser: <strong id="roster-adviser" class="text-slate-700 dark:text-slate-300">—</strong></span>
                        <span class="text-xs text-slate-500">Room: <strong id="roster-room" class="text-slate-700 dark:text-slate-300">—</strong></span>
                        <span class="text-xs text-slate-500">Total: <strong id="roster-total" class="text-slate-700 dark:text-slate-300">0</strong> students</span>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="flex items-center gap-2 flex-wrap">
                    {{-- Export Excel --}}
                    <button onclick="exportExcel()"
                        class="flex items-center gap-2 rounded-xl border border-green-300 bg-green-50 hover:bg-green-100 dark:border-green-700 dark:bg-green-900/20 px-4 py-2 text-xs font-semibold text-green-700 dark:text-green-400 transition-colors">
                        <iconify-icon icon="solar:file-download-bold" width="14"></iconify-icon>
                        Export to Excel
                    </button>

                    {{-- PDF modal --}}
                    <button onclick="openPdfModal()"
                        class="flex items-center gap-2 rounded-xl border border-red-300 bg-red-50 hover:bg-red-100 dark:border-red-700 dark:bg-red-900/20 px-4 py-2 text-xs font-semibold text-red-600 dark:text-red-400 transition-colors">
                        <iconify-icon icon="solar:document-bold" width="14"></iconify-icon>
                        Download as PDF
                    </button>

                    {{-- View Class Record (coming soon) --}}
                    <button onclick="showComingSoon()"
                        class="flex items-center gap-2 rounded-xl border border-slate-300 bg-slate-50 hover:bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-500 transition-colors">
                        <iconify-icon icon="solar:notebook-bold" width="14"></iconify-icon>
                        View Class Record
                    </button>
                </div>
            </div>

            {{-- Table Controls --}}
            <div class="flex items-center justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Show</span>
                    <select id="roster-page-size" onchange="filterRosterTable()"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                        <option>10</option><option selected>25</option><option>50</option><option>100</option>
                    </select>
                    <span>Entries</span>
                </div>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    <input type="text" id="roster-search" oninput="filterRosterTable()" placeholder="Search student.."
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
                </div>
            </div>

            {{-- Roster Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" style="min-width:700px">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                            <th class="px-4 py-3 text-center w-12">#</th>
                            <th class="px-4 py-3 whitespace-nowrap">Student ID</th>
                            <th class="px-4 py-3">Student Name</th>
                            <th class="px-4 py-3 text-center">Gender</th>
                            <th class="px-4 py-3">Email</th>
                        </tr>
                    </thead>
                    <tbody id="roster-tbody" class="divide-y divide-slate-100 dark:divide-dark-border">
                        {{-- Populated by JS --}}
                    </tbody>
                </table>
            </div>

            {{-- Roster Pagination --}}
            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
                <p class="text-xs text-slate-400" id="roster-count-label">Showing 0 of 0</p>
                <div id="roster-pagination" class="flex items-center gap-1"></div>
            </div>
        </div>

        {{-- Empty state (before any filter applied) --}}
        <div id="roster-empty-state" class="px-6 py-20 text-center">
            <iconify-icon icon="solar:users-group-rounded-linear" width="44" class="text-slate-200 dark:text-slate-700 block mx-auto mb-3"></iconify-icon>
            <p class="text-sm font-medium text-slate-400">Select a School Year and Section, then click <strong>VIEW ROSTER</strong></p>
            <p class="text-xs text-slate-300 mt-1">The class roster will appear here.</p>
        </div>

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══ PDF CONFIGURATION MODAL ══ --}}
<div id="pdf-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closePdfModal()"></div>
    <div class="relative w-full max-w-5xl mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl flex flex-col" style="height:92vh">

        {{-- Header --}}
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between shrink-0">
            <h3 class="text-white text-sm font-bold">PDF CONFIGURATION</h3>
            <button onclick="closePdfModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        <div class="flex flex-1 overflow-hidden">

            {{-- Left: Options --}}
            <div class="w-64 shrink-0 border-r border-slate-100 dark:border-dark-border overflow-y-auto px-5 py-5 space-y-5">

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-2">Page Settings</p>

                    <div class="space-y-3">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Page Size</label>
                            <div class="relative">
                                <select id="pdf-size" onchange="updatePreview()"
                                    class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                    <option value="a4" selected>A4 (210 × 297 mm)</option>
                                    <option value="letter">Letter (216 × 279 mm)</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Orientation</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(['portrait'=>['solar:document-bold','Portrait'],'landscape'=>['solar:document-bold rotate-90','Landscape']] as $val=>[$icon,$lbl])
                                <label class="flex flex-col items-center gap-1.5 rounded-xl border p-3 cursor-pointer transition-colors pdf-orient-label" data-val="{{ $val }}">
                                    <input type="radio" name="pdf-orient" value="{{ $val }}" {{ $val==='portrait'?'checked':'' }} class="sr-only" onchange="updatePreview()">
                                    <iconify-icon icon="{{ $icon }}" width="22" class="text-slate-400"></iconify-icon>
                                    <span class="text-[10px] font-semibold text-slate-500">{{ $lbl }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-2">Content Options</p>
                    <div class="space-y-2">
                        @foreach([
                            ['pdf-logo',   'Show School Logo',  true],
                            ['pdf-lrn',    'Include LRN',       true],
                            ['pdf-gender', 'Include Gender',    true],
                            ['pdf-email',  'Include Email',     true],
                        ] as [$id,$label,$checked])
                        <label class="flex items-center gap-2.5 cursor-pointer rounded-lg px-2 py-1.5 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <input type="checkbox" id="{{ $id }}" {{ $checked?'checked':'' }} onchange="updatePreview()"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-xs text-slate-600 dark:text-slate-300">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-2">Document Info</p>
                    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 p-3 space-y-1.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Section:</span>
                            <span id="pdf-info-section" class="font-semibold text-slate-700 dark:text-slate-300">—</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Students:</span>
                            <span id="pdf-info-count" class="font-semibold text-slate-700 dark:text-slate-300">0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Adviser:</span>
                            <span id="pdf-info-adviser" class="font-semibold text-slate-700 dark:text-slate-300">—</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Preview --}}
            <div class="flex-1 overflow-hidden flex flex-col bg-slate-100 dark:bg-slate-900">
                <div class="flex items-center gap-2 px-4 py-2.5 border-b border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card">
                    <iconify-icon icon="solar:eye-bold" width="14" class="text-slate-400"></iconify-icon>
                    <span class="text-xs font-semibold text-slate-500">Live Preview</span>
                    <div id="preview-loading" class="hidden ml-auto">
                        <iconify-icon icon="solar:loading-bold" width="14" class="text-slate-400 animate-spin"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1 overflow-auto p-4">
                    <div id="pdf-preview-frame" class="bg-white shadow-md mx-auto rounded overflow-hidden text-xs"
                         style="width:100%;min-height:400px;transform-origin:top left;">
                        {{-- Preview built by JS --}}
                        <div id="pdf-preview-content" class="p-4"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="shrink-0 flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-white dark:bg-dark-card">
            <p class="text-xs text-slate-400">PDF will open in a new tab — use browser Print dialog to save.</p>
            <div class="flex gap-2">
                <button onclick="closePdfModal()" class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">CANCEL</button>
                <button onclick="generatePdf()"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:document-bold" width="14"></iconify-icon>
                    GENERATE &amp; DOWNLOAD
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ COMING SOON TOAST ══ --}}
<div id="coming-soon-toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[200] hidden">
    <div class="flex items-center gap-3 rounded-2xl border border-blue-200 bg-white shadow-xl px-6 py-4 text-sm">
        <iconify-icon icon="solar:rocket-bold" width="20" class="text-blue-500"></iconify-icon>
        <div>
            <p class="font-semibold text-slate-800">Coming Soon — Class Record</p>
            <p class="text-xs text-slate-500 mt-0.5">Class Record feature is currently under development.</p>
        </div>
        <button onclick="document.getElementById('coming-soon-toast').classList.add('hidden')" class="ml-2 text-slate-400 hover:text-slate-600 text-lg leading-none">✕</button>
    </div>
</div>

@push('scripts')
<script>
const ROSTER_URL  = '{{ route("admin.classes.rosters.students") }}';
const EXCEL_URL   = '{{ route("admin.classes.rosters.export-excel") }}';
const PDF_URL     = '{{ route("admin.classes.rosters.export-pdf") }}';
const CSRF        = '{{ csrf_token() }}';

let _rosterData   = [];   // all students loaded
let _sectionData  = {};   // section info
let _currentPage  = 1;
let _pageSize     = 25;

// ── Load sections on school year change ───────────────────
function loadSections(sy) {
    window.location.href = '{{ route("admin.classes.rosters") }}?school_year=' + sy;
}

// ── Load roster via AJAX ──────────────────────────────────
function loadRoster() {
    const sy     = document.getElementById('filter-sy').value;
    const secId  = document.getElementById('filter-section').value;
    const errBox = document.getElementById('filter-error');

    if (!sy || !secId) {
        errBox.classList.remove('hidden');
        return;
    }
    errBox.classList.add('hidden');

    const tbody = document.getElementById('roster-tbody');
    tbody.innerHTML = `<tr><td colspan="5" class="px-4 py-10 text-center">
        <iconify-icon icon="solar:loading-bold" width="24" class="text-slate-300 animate-spin block mx-auto mb-2"></iconify-icon>
        <span class="text-xs text-slate-400">Loading roster...</span>
    </td></tr>`;

    document.getElementById('roster-area').classList.remove('hidden');
    document.getElementById('roster-empty-state').classList.add('hidden');

    fetch(`${ROSTER_URL}?section_id=${secId}&school_year=${sy}`)
        .then(r => r.json())
        .then(data => {
            if (!data.success) { showRosterError('Failed to load roster.'); return; }

            _rosterData  = data.students;
            _sectionData = data.section;
            _currentPage = 1;
            _pageSize    = parseInt(document.getElementById('roster-page-size').value) || 25;

            // Section header
            document.getElementById('roster-title').textContent =
                `${data.section.display_name} Class Roster — SY ${data.section.school_year}`;
            document.getElementById('roster-adviser').textContent = data.section.adviser;
            document.getElementById('roster-room').textContent    = data.section.room;
            document.getElementById('roster-total').textContent   = data.total;

            renderRosterTable();
        })
        .catch(() => showRosterError('Network error. Please try again.'));
}

function showRosterError(msg) {
    document.getElementById('roster-tbody').innerHTML =
        `<tr><td colspan="5" class="px-4 py-10 text-center text-xs text-red-500">${msg}</td></tr>`;
}

// ── Render paginated table ────────────────────────────────
function filterRosterTable() {
    _currentPage = 1;
    _pageSize    = parseInt(document.getElementById('roster-page-size').value) || 25;
    renderRosterTable();
}

function renderRosterTable() {
    const q      = (document.getElementById('roster-search').value || '').toLowerCase();
    const filtered = _rosterData.filter(s =>
        !q || s.full_name.toLowerCase().includes(q)
            || s.student_id.toLowerCase().includes(q)
            || (s.email||'').toLowerCase().includes(q)
    );

    const total  = filtered.length;
    const start  = (_currentPage - 1) * _pageSize;
    const page   = filtered.slice(start, start + _pageSize);

    const genderBadge = g => g === 'Female'
        ? '<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-pink-100 text-pink-700">Female</span>'
        : '<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700">Male</span>';

    const tbody = document.getElementById('roster-tbody');
    if (!page.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="px-4 py-10 text-center text-xs text-slate-400">No students found${q ? ' matching "'+q+'"' : ''}.</td></tr>`;
    } else {
        tbody.innerHTML = page.map((s, i) => `
            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                <td class="px-4 py-3 text-xs text-slate-400 text-center">${start + i + 1}</td>
                <td class="px-4 py-3 text-xs font-mono text-slate-500 whitespace-nowrap">${s.student_id}</td>
                <td class="px-4 py-3 text-sm font-medium"><a href="{{ url('/admin/student-records/profile') }}/${s.id}" class="text-slate-700 dark:text-slate-300 hover:text-[#0d4c8f] dark:hover:text-blue-400 transition-colors">${s.full_name}</a></td>
                <td class="px-4 py-3 text-center">${genderBadge(s.gender)}</td>
                <td class="px-4 py-3 text-xs text-slate-500">${s.email}</td>
            </tr>`).join('');
    }

    // Label
    document.getElementById('roster-count-label').textContent =
        `Showing ${Math.min(start+1, total)}–${Math.min(start+_pageSize, total)} of ${total} students`;

    // Pagination
    renderPagination(Math.ceil(total / _pageSize));
}

function renderPagination(totalPages) {
    const pg = document.getElementById('roster-pagination');
    if (totalPages <= 1) { pg.innerHTML = ''; return; }

    let html = `<button onclick="goPage(${_currentPage-1})" ${_currentPage===1?'disabled':''} class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition-colors">
        <iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon></button>`;
    for (let p = 1; p <= totalPages; p++) {
        if (p === 1 || p === totalPages || Math.abs(p - _currentPage) <= 1) {
            html += `<button onclick="goPage(${p})" class="flex h-7 w-7 items-center justify-center rounded-lg text-xs font-semibold transition-colors ${p===_currentPage ? 'bg-[#0d4c8f] text-white' : 'border border-slate-200 text-slate-500 hover:bg-slate-50'}">${p}</button>`;
        } else if (Math.abs(p - _currentPage) === 2) {
            html += `<span class="flex h-7 w-7 items-center justify-center text-slate-400 text-xs">…</span>`;
        }
    }
    html += `<button onclick="goPage(${_currentPage+1})" ${_currentPage===totalPages?'disabled':''} class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 disabled:opacity-40 transition-colors">
        <iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon></button>`;
    pg.innerHTML = html;
}

function goPage(p) {
    _currentPage = p;
    renderRosterTable();
}

// ── Excel Export ──────────────────────────────────────────
function exportExcel() {
    const sy    = document.getElementById('filter-sy').value;
    const secId = document.getElementById('filter-section').value;
    if (!secId) { alert('Please load a roster first.'); return; }
    window.location.href = `${EXCEL_URL}?section_id=${secId}&school_year=${sy}`;
}

// ── PDF Modal ─────────────────────────────────────────────
function openPdfModal() {
    if (!_sectionData.id) { alert('Please load a roster first.'); return; }

    document.getElementById('pdf-info-section').textContent = _sectionData.display_name;
    document.getElementById('pdf-info-count').textContent   = _rosterData.length;
    document.getElementById('pdf-info-adviser').textContent = _sectionData.adviser;

    document.getElementById('pdf-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    updateOrientationUI();
    updatePreview();
}

function closePdfModal() {
    document.getElementById('pdf-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Radio orientation visual
document.querySelectorAll('.pdf-orient-label').forEach(lbl => {
    lbl.addEventListener('click', () => {
        document.querySelectorAll('.pdf-orient-label').forEach(l => {
            l.classList.remove('border-blue-500', 'bg-blue-50');
            l.classList.add('border-slate-200');
        });
        lbl.classList.add('border-blue-500', 'bg-blue-50');
        lbl.classList.remove('border-slate-200');
        updatePreview();
    });
});

function updateOrientationUI() {
    const selected = document.querySelector('input[name="pdf-orient"]:checked')?.value || 'portrait';
    document.querySelectorAll('.pdf-orient-label').forEach(l => {
        const match = l.dataset.val === selected;
        l.classList.toggle('border-blue-500', match);
        l.classList.toggle('bg-blue-50', match);
        l.classList.toggle('border-slate-200', !match);
    });
}

// Live preview
function updatePreview() {
    updateOrientationUI();
    const showLrn    = document.getElementById('pdf-lrn').checked;
    const showGender = document.getElementById('pdf-gender').checked;
    const showEmail  = document.getElementById('pdf-email').checked;
    const showLogo   = document.getElementById('pdf-logo').checked;
    const orient     = document.querySelector('input[name="pdf-orient"]:checked')?.value || 'portrait';

    const previewStudents = _rosterData.slice(0, 8); // show first 8 in preview

    const cols = ['#','Student ID','Name',showLrn?'LRN':'',showGender?'Gender':'',showEmail?'Email':''].filter(Boolean);

    const rows = previewStudents.map((s, i) => {
        const cells = [
            `<td style="padding:4px 6px;text-align:center;color:#888;font-size:9px">${i+1}</td>`,
            `<td style="padding:4px 6px;font-family:monospace;font-size:9px;color:#555">${s.student_id}</td>`,
            `<td style="padding:4px 6px;font-size:9px;font-weight:600">${s.full_name}</td>`,
            showLrn    ? `<td style="padding:4px 6px;font-family:monospace;font-size:9px;color:#555">${s.lrn||'—'}</td>` : '',
            showGender ? `<td style="padding:4px 6px;text-align:center;font-size:9px">${s.gender}</td>` : '',
            showEmail  ? `<td style="padding:4px 6px;font-size:9px;color:#555">${s.email}</td>` : '',
        ].filter(Boolean).join('');
        return `<tr style="border-bottom:1px solid #f1f5f9">${cells}</tr>`;
    }).join('');

    const more = _rosterData.length > 8
        ? `<tr><td colspan="${cols.length}" style="padding:6px;text-align:center;font-size:9px;color:#94a3b8;font-style:italic">… and ${_rosterData.length-8} more student(s)</td></tr>`
        : '';

    const frame = document.getElementById('pdf-preview-frame');
    frame.style.width = '100%';

    document.getElementById('pdf-preview-content').innerHTML = `
        <div style="font-family:Arial,sans-serif;padding:12px">
            <div style="display:flex;align-items:center;gap:8px;border-bottom:2px solid #0d4c8f;padding-bottom:8px;margin-bottom:8px">
                ${showLogo ? '<div style="width:32px;height:32px;border-radius:50%;background:#0d4c8f;display:flex;align-items:center;justify-content:center;color:white;font-size:6px;font-weight:800;flex-shrink:0">MMSC</div>' : ''}
                <div style="flex:1;text-align:center">
                    <div style="font-size:9px;font-weight:800;color:#0d4c8f">MY MESSIAH SCHOOL OF CAVITE</div>
                    <div style="font-size:7px;color:#555">Class Roster — SY ${_sectionData.school_year}</div>
                </div>
            </div>
            <div style="background:#f0f5fc;border-radius:4px;padding:5px 8px;margin-bottom:8px;font-size:8px;display:grid;grid-template-columns:1fr 1fr;gap:2px">
                <div><span style="color:#888">Grade:</span> <strong>${_sectionData.grade_level}</strong></div>
                <div><span style="color:#888">Section:</span> <strong>${_sectionData.section_name}</strong></div>
                <div><span style="color:#888">Adviser:</span> <strong>${_sectionData.adviser}</strong></div>
                <div><span style="color:#888">Total:</span> <strong>${_rosterData.length}</strong></div>
            </div>
            <table style="width:100%;border-collapse:collapse">
                <thead><tr style="background:#0d4c8f">${cols.map(c=>`<th style="padding:4px 6px;text-align:left;color:white;font-size:8px;font-weight:700">${c}</th>`).join('')}</tr></thead>
                <tbody>${rows}${more}</tbody>
            </table>
        </div>`;
}

function generatePdf() {
    const sy       = document.getElementById('filter-sy').value;
    const secId    = _sectionData.id;
    const orient   = document.querySelector('input[name="pdf-orient"]:checked')?.value || 'portrait';
    const size     = document.getElementById('pdf-size').value;
    const showLogo = document.getElementById('pdf-logo').checked ? 1 : 0;
    const showLrn  = document.getElementById('pdf-lrn').checked ? 1 : 0;
    const showGen  = document.getElementById('pdf-gender').checked ? 1 : 0;
    const showEml  = document.getElementById('pdf-email').checked ? 1 : 0;

    const url = `${PDF_URL}?section_id=${secId}&school_year=${sy}&orientation=${orient}&page_size=${size}&show_logo=${showLogo}&show_lrn=${showLrn}&show_gender=${showGen}&show_email=${showEml}`;
    window.open(url, '_blank');
    closePdfModal();
}

// ── Coming soon toast ─────────────────────────────────────
function showComingSoon() {
    const toast = document.getElementById('coming-soon-toast');
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 4500);
}

// ── Allow Enter key on filter ─────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key === 'Enter' && document.activeElement?.id === 'filter-section') loadRoster();
});
</script>
@endpush
@endsection