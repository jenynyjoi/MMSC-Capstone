@extends('layouts.teacher_layout')
@section('title', 'Attendance')
@section('page_title', 'Take Attendance')
@section('page_subtitle', 'Mark and track student attendance per class')

@push('styles')
<style>
  .class-tab { transition: all .15s; }
  .class-tab.active { background:#0d4c8f; color:#fff; border-color:#0d4c8f; }
  .status-btn { transition: all .15s; user-select: none; }
  .status-btn.present  { background:#dcfce7; color:#16a34a; border-color:#86efac; font-weight:700; }
  .status-btn.absent   { background:#fee2e2; color:#dc2626; border-color:#fca5a5; font-weight:700; }
  .status-btn.late     { background:#fef9c3; color:#ca8a04; border-color:#fde047; font-weight:700; }
  .status-btn.excused  { background:#e0e7ff; color:#4f46e5; border-color:#a5b4fc; font-weight:700; }
  .dark .status-btn.present  { background:rgba(22,163,74,0.18); color:#4ade80; border-color:#166534; }
  .dark .status-btn.absent   { background:rgba(220,38,38,0.18); color:#f87171; border-color:#991b1b; }
  .dark .status-btn.late     { background:rgba(202,138,4,0.18);  color:#fbbf24; border-color:#92400e; }
  .dark .status-btn.excused  { background:rgba(79,70,229,0.18); color:#818cf8; border-color:#3730a3; }
  .student-row:hover { background: rgba(13,76,143,0.04); }
  .dark .student-row:hover { background: rgba(255,255,255,0.03); }
  .legend-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
</style>
@endpush

@section('content')
<div class="p-5">

    {{-- Quick Class Buttons --}}
    <div class="mb-4">
        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-2">My Classes — Quick Select</p>
        <div class="flex flex-wrap gap-2">
            @forelse($classTabs as $i => $tab)
            <button onclick="selectClass(this,'{{ $tab['label'] }}','{{ $tab['grade'] }}','{{ $tab['section'] }}','{{ $tab['subject'] }}')"
                class="class-tab {{ $i === 0 ? 'active' : '' }} rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
                <span class="block text-[10px] font-medium opacity-70">Grade {{ $tab['grade'] }}</span>
                {{ $tab['label'] }}
            </button>
            @empty
            <p class="text-xs text-slate-400 dark:text-slate-500 italic">No classes assigned for this school year.</p>
            @endforelse
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-4 mb-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex flex-col gap-1 min-w-[120px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Date</label>
                <input type="date" id="sel-date" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30"/>
            </div>
            <div class="flex flex-col gap-1 min-w-[110px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Grade Level</label>
                <select id="sel-grade" onchange="updateInfo()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
                    @foreach($grades as $g)
                    <option value="{{ $g }}">Grade {{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1 min-w-[110px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Section</label>
                <select id="sel-section" onchange="updateInfo()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
                    @foreach($sections as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1 min-w-[130px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Subject</label>
                <select id="sel-subject" onchange="updateInfo()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
                    @foreach($subjects as $sub)
                    <option value="{{ $sub }}">{{ $sub }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1 flex-1 min-w-[150px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Search Student</label>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input id="search-input" oninput="filterStudents()" placeholder="Search by name..."
                        class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30"/>
                </div>
            </div>
            <button onclick="markAll('present')" class="flex items-center gap-1.5 rounded-lg bg-green-500 hover:bg-green-600 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm shrink-0">
                <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>All Present
            </button>
            <button onclick="clearAll()" class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors shrink-0">
                <iconify-icon icon="solar:refresh-linear" width="13"></iconify-icon>Reset
            </button>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4 mb-4">
        <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card p-4 shadow-sm flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 shrink-0">
                <iconify-icon icon="solar:users-group-rounded-linear" width="18" class="text-blue-500"></iconify-icon>
            </div>
            <div>
                <p class="text-xl font-bold text-slate-800 dark:text-white" id="stat-total">0</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Total Students</p>
            </div>
        </div>
        <div class="rounded-xl border border-green-200 dark:border-green-900/30 bg-white dark:bg-dark-card p-4 shadow-sm flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 dark:bg-green-900/20 shrink-0">
                <iconify-icon icon="solar:check-circle-bold" width="18" class="text-green-500"></iconify-icon>
            </div>
            <div>
                <p class="text-xl font-bold text-green-500" id="stat-present">0</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Present</p>
            </div>
        </div>
        <div class="rounded-xl border border-red-200 dark:border-red-900/30 bg-white dark:bg-dark-card p-4 shadow-sm flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 dark:bg-red-900/20 shrink-0">
                <iconify-icon icon="solar:close-circle-bold" width="18" class="text-red-500"></iconify-icon>
            </div>
            <div>
                <p class="text-xl font-bold text-red-500" id="stat-absent">0</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Absent</p>
            </div>
        </div>
        <div class="rounded-xl border border-yellow-200 dark:border-yellow-900/30 bg-white dark:bg-dark-card p-4 shadow-sm flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-yellow-50 dark:bg-yellow-900/20 shrink-0">
                <iconify-icon icon="solar:clock-circle-bold" width="18" class="text-yellow-500"></iconify-icon>
            </div>
            <div>
                <p class="text-xl font-bold text-yellow-500" id="stat-late">0</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Late / Excused</p>
            </div>
        </div>
    </div>

    {{-- Attendance Card --}}
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2.5">
                <iconify-icon icon="solar:calendar-check-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Attendance Sheet</h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500" id="sheet-subtitle">— · <span id="sheet-date"></span></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="hidden md:flex items-center gap-3 mr-2">
                    <span class="flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400"><span class="legend-dot bg-green-500"></span>Present</span>
                    <span class="flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400"><span class="legend-dot bg-red-500"></span>Absent</span>
                    <span class="flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400"><span class="legend-dot bg-yellow-400"></span>Late</span>
                    <span class="flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400"><span class="legend-dot bg-indigo-400"></span>Excused</span>
                </div>
                <button onclick="saveAttendance()" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                    <iconify-icon icon="solar:diskette-linear" width="13"></iconify-icon>Save
                </button>
                <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:printer-linear" width="13"></iconify-icon>Print
                </button>
            </div>
        </div>

        <div class="grid items-center bg-slate-50 dark:bg-dark-bg/30 border-b border-slate-100 dark:border-dark-border px-5 py-2.5 text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide"
             style="grid-template-columns: 40px 1fr 160px 1fr 100px;">
            <span>#</span>
            <span>Student Name</span>
            <span class="text-center">Status</span>
            <span class="pl-4">Remarks / Notes</span>
            <span class="text-center">Time In</span>
        </div>

        <div id="student-list" class="divide-y divide-slate-100 dark:divide-dark-border">
            {{-- Rendered by JS --}}
        </div>

        <div class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/30">
            <div class="flex items-center gap-4 text-xs">
                <span class="text-slate-500 dark:text-slate-400">
                    Marked: <span id="footer-marked" class="font-bold text-slate-700 dark:text-slate-300">0</span> / <span id="footer-total" class="font-bold text-slate-700 dark:text-slate-300">0</span>
                </span>
                <div id="progress-bar-wrap" class="hidden sm:flex items-center gap-2">
                    <div class="h-2 w-32 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                        <div id="progress-bar" class="h-2 rounded-full bg-[#0d4c8f] transition-all" style="width:0%"></div>
                    </div>
                    <span id="progress-pct" class="text-[11px] font-semibold text-[#0d4c8f] dark:text-blue-300">0%</span>
                </div>
            </div>
            <button onclick="saveAttendance()" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                <iconify-icon icon="solar:diskette-linear" width="13"></iconify-icon>Save Attendance
            </button>
        </div>
    </div>

    {{-- Recent Attendance History --}}
    <div class="mt-5 rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:history-linear" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Recent Attendance Records</h3>
            </div>
            <a href="#" class="flex h-7 items-center gap-1 px-2.5 rounded-lg border border-slate-200 text-[11px] font-medium text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5">
                View all <iconify-icon icon="solar:arrow-right-linear" width="11"></iconify-icon>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                        <th class="px-5 py-2.5">Date</th>
                        <th class="px-4 py-2.5">Class</th>
                        <th class="px-4 py-2.5">Subject</th>
                        <th class="px-4 py-2.5 text-center">Present</th>
                        <th class="px-4 py-2.5 text-center">Absent</th>
                        <th class="px-4 py-2.5 text-center">Late</th>
                        <th class="px-4 py-2.5 text-center">Rate</th>
                        <th class="px-4 py-2.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    <tr>
                        <td colspan="8" class="px-5 py-8 text-center text-xs text-slate-400 dark:text-slate-500 italic">No attendance records saved yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" style="display:none" class="fixed bottom-8 right-8 z-[200] flex items-center gap-3 rounded-xl bg-[#0d4c8f] text-white px-5 py-3 shadow-2xl text-sm font-medium">
        <iconify-icon icon="solar:check-circle-bold" width="18"></iconify-icon>
        Attendance saved successfully!
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection

@push('scripts')
<script>
const studentsMale   = [];
const studentsFemale = [];
const allStudents    = [];
let attendance   = {};
let searchFilter = '';

const pad = n => String(n).padStart(2, '0');

// Set today's date
const today    = new Date();
const todayStr = `${today.getFullYear()}-${pad(today.getMonth()+1)}-${pad(today.getDate())}`;
document.getElementById('sel-date').value = todayStr;
updateSheetDate();

function updateSheetDate() {
  const d   = document.getElementById('sel-date').value;
  const fmt = d ? new Date(d + 'T00:00').toLocaleDateString('en-PH', {month:'long', day:'numeric', year:'numeric'}) : '—';
  document.getElementById('sheet-date').textContent = fmt;
}
document.getElementById('sel-date').addEventListener('change', updateSheetDate);

function renderList() {
  const filter = searchFilter.toLowerCase();
  const list   = document.getElementById('student-list');
  const groups = [
    { label: 'MALE',   students: studentsMale },
    { label: 'FEMALE', students: studentsFemale },
  ];

  let html = '', globalIdx = 0;

  groups.forEach(g => {
    const filtered = filter ? g.students.filter(n => n.toLowerCase().includes(filter)) : g.students;
    if (!filtered.length) return;

    html += `<div class="px-5 py-1.5 bg-slate-100 dark:bg-[#1a2744] border-b border-slate-200 dark:border-dark-border">
      <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">${g.label}</span>
    </div>`;

    filtered.forEach(name => {
      globalIdx++;
      const att      = attendance[name] || {};
      const status   = att.status  || '';
      const time     = att.time    || '';
      const rem      = att.remarks || '';
      const avatarBg = g.label === 'MALE'
        ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300'
        : 'bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-300';
      const initials = name.split(',').map(p => p.trim()[0]).join('').slice(0,2).toUpperCase();
      const timeDisabled = status !== 'present' && status !== 'late';

      html += `
      <div class="student-row grid items-center px-5 py-2.5 transition-colors" style="grid-template-columns: 40px 1fr 160px 1fr 100px;" data-name="${name}">
        <div class="flex items-center gap-1.5">
          <span class="text-[11px] text-slate-400 dark:text-slate-600 w-5 text-right">${globalIdx}</span>
        </div>
        <div class="flex items-center gap-2.5">
          <div class="flex h-8 w-8 items-center justify-center rounded-full ${avatarBg} text-[10px] font-bold shrink-0">${initials}</div>
          <span class="text-sm font-medium text-slate-700 dark:text-slate-300">${name}</span>
        </div>
        <div class="flex items-center justify-center gap-1">
          <button onclick="setStatus('${name}','present')"  class="status-btn ${status==='present'?'present':''} rounded-md border border-slate-200 dark:border-dark-border px-2 py-1 text-[11px] hover:bg-green-50 dark:hover:bg-green-900/10 transition-all" title="Present">
            <iconify-icon icon="solar:check-circle-bold" width="13" class="mr-0.5"></iconify-icon>P
          </button>
          <button onclick="setStatus('${name}','absent')"   class="status-btn ${status==='absent'?'absent':''} rounded-md border border-slate-200 dark:border-dark-border px-2 py-1 text-[11px] hover:bg-red-50 dark:hover:bg-red-900/10 transition-all" title="Absent">
            <iconify-icon icon="solar:close-circle-bold" width="13" class="mr-0.5"></iconify-icon>A
          </button>
          <button onclick="setStatus('${name}','late')"     class="status-btn ${status==='late'?'late':''} rounded-md border border-slate-200 dark:border-dark-border px-2 py-1 text-[11px] hover:bg-yellow-50 dark:hover:bg-yellow-900/10 transition-all" title="Late">
            <iconify-icon icon="solar:clock-circle-bold" width="13" class="mr-0.5"></iconify-icon>L
          </button>
          <button onclick="setStatus('${name}','excused')"  class="status-btn ${status==='excused'?'excused':''} rounded-md border border-slate-200 dark:border-dark-border px-2 py-1 text-[11px] hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all" title="Excused">
            <iconify-icon icon="solar:shield-check-bold" width="13" class="mr-0.5"></iconify-icon>E
          </button>
        </div>
        <div class="pl-4">
          <input value="${rem}" onchange="setRemarks('${name}',this.value)" placeholder="Add note..."
            class="w-full text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-1.5 text-slate-600 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30 placeholder-slate-300"/>
        </div>
        <div class="flex justify-center">
          <input type="time" value="${time}" onchange="setTime('${name}',this.value)"
            class="text-[11px] rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-2 py-1.5 text-slate-600 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30 ${timeDisabled ? 'opacity-40 cursor-not-allowed' : ''}"
            ${timeDisabled ? 'disabled' : ''}/>
        </div>
      </div>`;
    });
  });

  if (!html) {
    html = `<div class="flex flex-col items-center justify-center py-14 text-center">
      <iconify-icon icon="solar:users-group-rounded-linear" width="32" class="text-slate-300 dark:text-slate-600 mb-3"></iconify-icon>
      <p class="text-sm text-slate-400 dark:text-slate-500">No students found for this class.</p>
    </div>`;
  }

  list.innerHTML = html;
  updateStats();
}

function setStatus(name, status) {
  if (!attendance[name]) attendance[name] = {};
  attendance[name].status = attendance[name].status === status ? '' : status;
  if (attendance[name].status === 'present' && !attendance[name].time) {
    const now = new Date();
    attendance[name].time = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
  }
  renderList();
}
function setRemarks(name, val) {
  if (!attendance[name]) attendance[name] = {};
  attendance[name].remarks = val;
}
function setTime(name, val) {
  if (!attendance[name]) attendance[name] = {};
  attendance[name].time = val;
}

function markAll(status) {
  const now = new Date();
  const t   = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
  allStudents.forEach(s => {
    attendance[s.name] = { status, time: status === 'present' ? t : '', remarks: attendance[s.name]?.remarks || '' };
  });
  renderList();
}
function clearAll() {
  attendance = {};
  renderList();
}

function updateStats() {
  let present = 0, absent = 0, late = 0, excused = 0, marked = 0;
  allStudents.forEach(s => {
    const st = attendance[s.name]?.status;
    if (st === 'present') present++;
    if (st === 'absent')  absent++;
    if (st === 'late')    late++;
    if (st === 'excused') excused++;
    if (st) marked++;
  });
  const total = allStudents.length;
  document.getElementById('stat-total').textContent    = total;
  document.getElementById('stat-present').textContent  = present;
  document.getElementById('stat-absent').textContent   = absent;
  document.getElementById('stat-late').textContent     = late + excused;
  document.getElementById('footer-marked').textContent = marked;
  document.getElementById('footer-total').textContent  = total;
  const pct = total ? Math.round(marked / total * 100) : 0;
  document.getElementById('progress-bar').style.width  = pct + '%';
  document.getElementById('progress-pct').textContent  = pct + '%';
  document.getElementById('progress-bar-wrap').classList.remove('hidden');
}

function updateInfo() {
  const g    = document.getElementById('sel-grade').value;
  const s    = document.getElementById('sel-section').value;
  const subj = document.getElementById('sel-subject').value;
  document.getElementById('sheet-subtitle').innerHTML =
    `Grade ${g} – Section ${s} · ${subj} · <span id="sheet-date"></span>`;
  updateSheetDate();
}

function selectClass(btn, label, grade, section, subject) {
  document.querySelectorAll('.class-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('sel-grade').value   = grade;
  document.getElementById('sel-section').value = section;
  document.getElementById('sel-subject').value = subject;
  attendance = {};
  updateInfo();
  renderList();
}

function filterStudents() {
  searchFilter = document.getElementById('search-input').value;
  renderList();
}

function saveAttendance() {
  const toast = document.getElementById('toast');
  toast.style.display = 'flex';
  setTimeout(() => { toast.style.display = 'none'; }, 2500);
}

// Init — trigger first tab if any
(function() {
  const firstTab = document.querySelector('.class-tab.active');
  if (firstTab) {
    updateInfo();
  }
  renderList();
  updateInfo();
})();
</script>
@endpush
