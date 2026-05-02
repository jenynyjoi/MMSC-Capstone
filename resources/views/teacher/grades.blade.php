@extends('layouts.teacher_layout')
@section('title', 'Grade Encoding')
@section('page_title', 'Grade Encoding')
@section('page_subtitle', 'Input and manage student grades per subject')

@push('styles')
<style>
  .grade-table { border-collapse: collapse; min-width: 100%; }
  .grade-table th, .grade-table td {
    border: 1px solid #e2e8f0;
    padding: 0;
    text-align: center;
    font-size: 11px;
    white-space: nowrap;
  }
  .dark .grade-table th, .dark .grade-table td { border-color: #1e293b; }
  .grade-table input {
    width: 100%; min-width: 32px; height: 28px;
    border: none; background: transparent;
    text-align: center; font-size: 11px;
    font-family: 'Poppins', sans-serif;
    color: #1e293b; outline: none; padding: 0 2px;
  }
  .dark .grade-table input { color: #e2e8f0; }
  .grade-table input:focus { background: #eff6ff; }
  .dark .grade-table input:focus { background: rgba(13,76,143,0.15); }
  .th-group { background: #0d4c8f; color: #fff; font-weight: 700; font-size: 11px; padding: 6px 4px; }
  .th-sub   { background: #f1f5f9; color: #475569; font-weight: 600; font-size: 10px; padding: 4px 3px; }
  .dark .th-sub { background: #1a2744; color: #94a3b8; }
  .th-num   { background: #f8fafc; color: #64748b; font-weight: 500; font-size: 10px; padding: 3px; min-width: 28px; width: 28px; }
  .dark .th-num { background: #0f1b33; color: #64748b; }
  .tr-hps   { background: #f1f5f9; }
  .dark .tr-hps { background: #1a2744; }
  .tr-group { background: #e0e7ff; }
  .dark .tr-group { background: rgba(13,76,143,0.18); }
  .tr-group td { font-weight: 700; font-size: 11px; color: #0d4c8f; padding: 4px 8px; text-align: left; }
  .dark .tr-group td { color: #93c5fd; }
  .td-name  { text-align: left; padding: 0 8px; min-width: 180px; font-weight: 500; color: #334155; font-size: 11px; height: 28px; }
  .dark .td-name { color: #cbd5e1; }
  .td-calc  { background: #f8fafc; color: #0d4c8f; font-weight: 700; font-size: 11px; padding: 0 4px; height: 28px; }
  .dark .td-calc { background: #0f1b33; color: #93c5fd; }
  .td-grade { background: #eff6ff; color: #1d4ed8; font-weight: 700; font-size: 12px; padding: 0 4px; height: 28px; }
  .dark .td-grade { background: rgba(13,76,143,0.2); color: #60a5fa; }
  .ww-bg  { background: #fff7ed; }
  .dark .ww-bg  { background: rgba(249,115,22,0.05); }
  .pt-bg  { background: #f0fdf4; }
  .dark .pt-bg  { background: rgba(34,197,94,0.05); }
  .qa-bg  { background: #eff6ff; }
  .dark .qa-bg  { background: rgba(59,130,246,0.05); }
  .class-tab { transition: all .15s; }
  .class-tab.active { background: #0d4c8f; color: #fff; border-color: #0d4c8f; }
</style>
@endpush

@section('content')
<main class="flex-1 overflow-y-auto p-5 bg-slate-50/50 dark:bg-dark-bg">

    {{-- Quick Class Buttons --}}
    <div class="mb-4">
        <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-2">My Classes — Quick Select</p>
        <div class="flex flex-wrap gap-2" id="class-tabs">
            @forelse($classTabs as $tab)
            <button onclick="selectClass(this,'{{ $tab['label'] }}','{{ $tab['grade'] }}','{{ $tab['section'] }}','{{ $tab['subject'] }}')"
                class="class-tab {{ $loop->first ? 'active' : '' }} rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
                <span class="block text-[10px] font-medium opacity-70">Grade {{ $tab['grade'] }}</span>
                {{ $tab['label'] }}
            </button>
            @empty
            <p class="text-xs text-slate-400 dark:text-slate-500">No subjects assigned. Contact admin to assign subjects.</p>
            @endforelse
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-4 mb-4">
        <div class="flex flex-wrap items-end gap-3">

            <div class="flex flex-col gap-1 min-w-[130px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">School Year</label>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
                    @foreach($schoolYears as $sy)
                    <option @selected($sy === $schoolYear)>{{ $sy }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1 min-w-[120px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Quarter</label>
                <select id="sel-quarter" onchange="updateHeader()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
                    <option value="1st">1st Quarter</option>
                    <option value="2nd">2nd Quarter</option>
                    <option value="3rd" selected>3rd Quarter</option>
                    <option value="4th">4th Quarter</option>
                </select>
            </div>

            <div class="flex flex-col gap-1 min-w-[110px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Grade Level</label>
                <select id="sel-grade" onchange="updateHeader()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
                    @foreach(['7','8','9','10','11','12'] as $g)
                    <option value="{{ $g }}">Grade {{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1 min-w-[110px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Section</label>
                <select id="sel-section" onchange="updateHeader()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
                    @foreach($sections as $sec)
                    <option value="{{ $sec }}">{{ $sec }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1 min-w-[130px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Subject</label>
                <select id="sel-subject" onchange="updateHeader()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
                    @foreach($subjects as $subj)
                    <option value="{{ $subj }}">{{ $subj }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1 flex-1 min-w-[160px]">
                <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Search Student</label>
                <div class="relative">
                    <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input id="search-input" oninput="filterStudents()" placeholder="Search by name…"
                        class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30"/>
                </div>
            </div>

            <button onclick="updateHeader()"
                class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm shrink-0">
                <iconify-icon icon="solar:refresh-linear" width="13"></iconify-icon>Load
            </button>
        </div>
    </div>

    {{-- Info Bar --}}
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm px-5 py-3 mb-4 flex flex-wrap items-center gap-x-6 gap-y-1.5">
        <div class="flex items-center gap-2 text-xs">
            <span class="text-slate-400 dark:text-slate-500">Quarter:</span>
            <span id="info-quarter" class="font-semibold text-[#0d4c8f] dark:text-blue-300">3rd Quarter</span>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-slate-400 dark:text-slate-500">Grade &amp; Section:</span>
            <span id="info-gs" class="font-semibold text-slate-700 dark:text-slate-300">—</span>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-slate-400 dark:text-slate-500">Teacher:</span>
            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ auth()->user()->name }}</span>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="text-slate-400 dark:text-slate-500">Subject:</span>
            <span id="info-subject" class="font-semibold text-slate-700 dark:text-slate-300">—</span>
        </div>
        <div class="ml-auto flex items-center gap-2">
            <span class="text-[11px] text-slate-400 dark:text-slate-500">Status:</span>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400 text-[11px] font-semibold">
                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>In Progress
            </span>
        </div>
    </div>

    {{-- Grade Table Card --}}
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Toolbar --}}
        <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:medal-ribbons-star-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Class Record</h3>
                <span class="text-[11px] text-slate-400 dark:text-slate-500">– <span id="tbl-title">—</span></span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="saveGrades()"
                    class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                    <iconify-icon icon="solar:diskette-linear" width="13"></iconify-icon>Save Grades
                </button>
                <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <iconify-icon icon="solar:printer-linear" width="13"></iconify-icon>Print
                </button>
            </div>
        </div>

        {{-- Legend --}}
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 px-5 py-2 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/30">
            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">Legend:</span>
            <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400"><span class="h-3 w-3 rounded bg-orange-100 border border-orange-200"></span>Written Works (WW) 30%</span>
            <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400"><span class="h-3 w-3 rounded bg-green-100 border border-green-200"></span>Performance Tasks (PT) 50%</span>
            <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400"><span class="h-3 w-3 rounded bg-blue-100 border border-blue-200"></span>Quarterly Assessment (QA) 20%</span>
            <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400"><span class="font-bold text-slate-500">PS</span> = Percentage Score &nbsp; <span class="font-bold text-slate-500">WS</span> = Weighted Score</span>
        </div>

        {{-- Scrollable table --}}
        <div class="overflow-x-auto">
            <table class="grade-table">
                <thead>
                    <tr>
                        <th class="th-group" style="min-width:180px;" id="th-quarter-label">— QUARTER</th>
                        <th class="th-group text-left px-3" colspan="14" id="th-gs">GRADE &amp; SECTION: &nbsp; —</th>
                        <th class="th-group text-left px-3" colspan="14">TEACHER: &nbsp; {{ auth()->user()->name }}</th>
                        <th class="th-group text-left px-3" colspan="7" id="th-subj">SUBJECT: &nbsp; —</th>
                    </tr>
                    <tr>
                        <th class="th-sub" rowspan="2" style="min-width:180px;">LEARNERS' NAMES</th>
                        <th class="th-group" colspan="13" style="background:#ea580c;">WRITTEN WORKS (30%)</th>
                        <th class="th-group" colspan="13" style="background:#16a34a;">PERFORMANCE TASKS (50%)</th>
                        <th class="th-group" colspan="4" style="background:#1d4ed8;">QUARTERLY ASSESSMENT (20%)</th>
                        <th class="th-group" style="background:#0d4c8f;">INITIAL GRADE</th>
                        <th class="th-group" style="background:#0d4c8f;">QUARTERLY GRADE</th>
                    </tr>
                    <tr>
                        @foreach(range(1,10) as $i)<th class="th-num ww-bg">{{ $i }}</th>@endforeach
                        <th class="th-sub ww-bg" style="min-width:36px;">Total</th>
                        <th class="th-sub ww-bg" style="min-width:36px;">PS</th>
                        <th class="th-sub ww-bg" style="min-width:36px;">WS</th>
                        @foreach(range(1,10) as $i)<th class="th-num pt-bg">{{ $i }}</th>@endforeach
                        <th class="th-sub pt-bg" style="min-width:36px;">Total</th>
                        <th class="th-sub pt-bg" style="min-width:36px;">PS</th>
                        <th class="th-sub pt-bg" style="min-width:36px;">WS</th>
                        <th class="th-num qa-bg">1</th>
                        <th class="th-sub qa-bg" style="min-width:36px;">PS</th>
                        <th class="th-sub qa-bg" style="min-width:36px;">WS</th>
                        <th class="th-sub qa-bg" style="min-width:40px;">QA Score</th>
                        <th class="th-sub" style="min-width:56px;background:#dbeafe;color:#1d4ed8;">Initial</th>
                        <th class="th-sub" style="min-width:60px;background:#bfdbfe;color:#1e40af;">Quarterly</th>
                    </tr>
                    <tr class="tr-hps">
                        <td class="td-name font-semibold text-slate-500 dark:text-slate-400 text-[11px] uppercase">Highest Possible Score</td>
                        @foreach(range(1,10) as $i)<td class="ww-bg" id="hps-ww-{{ $i }}"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>@endforeach
                        <td class="td-calc ww-bg" id="hps-ww-total">####</td>
                        <td class="td-calc ww-bg">30%</td>
                        <td class="td-calc ww-bg">–</td>
                        @foreach(range(1,10) as $i)<td class="pt-bg" id="hps-pt-{{ $i }}"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>@endforeach
                        <td class="td-calc pt-bg" id="hps-pt-total">####</td>
                        <td class="td-calc pt-bg">50%</td>
                        <td class="td-calc pt-bg">–</td>
                        <td class="qa-bg" id="hps-qa-1"><input class="qa-bg" placeholder="–" onchange="recalcAll()"/></td>
                        <td class="td-calc qa-bg">####</td>
                        <td class="td-calc qa-bg">20%</td>
                        <td class="td-calc qa-bg">–</td>
                        <td class="td-calc" style="background:#dbeafe;">–</td>
                        <td class="td-calc" style="background:#bfdbfe;">–</td>
                    </tr>
                </thead>
                <tbody id="grade-tbody"></tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/30">
            <p class="text-[11px] text-slate-400 dark:text-slate-500">
                <span class="font-semibold text-slate-600 dark:text-slate-300" id="student-count">0</span> students · Grades auto-calculate based on DepEd Order No. 8, s. 2015
            </p>
            <button onclick="saveGrades()"
                class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                <iconify-icon icon="solar:diskette-linear" width="13"></iconify-icon>Save &amp; Submit Grades
            </button>
        </div>
    </div>

    {{-- Toast --}}
    <div id="toast" style="display:none" class="fixed bottom-8 right-8 z-[200] flex items-center gap-3 rounded-xl bg-[#0d4c8f] text-white px-5 py-3 shadow-2xl text-sm font-medium">
        <iconify-icon icon="solar:check-circle-bold" width="18"></iconify-icon>
        Grades saved successfully!
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>
</main>
@endsection

@push('scripts')
<script>
// Student data (populated from DB in future; using mock for now)
const allStudents = {
  male:   ['Aguilar, Marco Luis','Bautista, Rafael Jr.','Cruz, Angelo Miguel','Dela Cruz, Juan Pablo','Espiritu, Christian','Garcia, Ben James'],
  female: ['Castillo, Sophia Mae','Dela Cruz, Maria Clara','Flores, Patricia Anne','Lim, Angela Rose','Santos, Isabella Joy']
};

function renderTable(filter = '') {
  const tbody = document.getElementById('grade-tbody');
  let html = '', count = 0;
  const renderGroup = (label, students) => {
    const filtered = filter ? students.filter(n => n.toLowerCase().includes(filter.toLowerCase())) : students;
    if (!filtered.length) return;
    html += `<tr class="tr-group"><td colspan="32">${label}</td></tr>`;
    filtered.forEach((name, i) => {
      count++;
      const rowId = label.toLowerCase().replace(' ','') + '-' + i;
      html += `<tr id="row-${rowId}">
        <td class="td-name">${i+1}. ${name}</td>
        ${wwCols(rowId)}${ptCols(rowId)}${qaCols(rowId)}
        <td class="td-grade" id="${rowId}-initial">—</td>
        <td class="td-grade" id="${rowId}-quarterly">—</td>
      </tr>`;
    });
  };
  renderGroup('MALE', allStudents.male);
  renderGroup('FEMALE', allStudents.female);
  tbody.innerHTML = html;
  document.getElementById('student-count').textContent = count;
  tbody.querySelectorAll('input').forEach(inp => inp.addEventListener('input', () => recalcRow(inp.closest('tr'))));
}

const wwCols = id => Array.from({length:10},(_,i)=>`<td class="ww-bg"><input class="ww-bg" type="number" min="0" id="${id}-ww-${i+1}" placeholder=""/></td>`).join('')
  + `<td class="td-calc ww-bg" id="${id}-ww-total">—</td><td class="td-calc ww-bg" id="${id}-ww-ps">—</td><td class="td-calc ww-bg" id="${id}-ww-ws">—</td>`;
const ptCols = id => Array.from({length:10},(_,i)=>`<td class="pt-bg"><input class="pt-bg" type="number" min="0" id="${id}-pt-${i+1}" placeholder=""/></td>`).join('')
  + `<td class="td-calc pt-bg" id="${id}-pt-total">—</td><td class="td-calc pt-bg" id="${id}-pt-ps">—</td><td class="td-calc pt-bg" id="${id}-pt-ws">—</td>`;
const qaCols = id => `<td class="qa-bg"><input class="qa-bg" type="number" min="0" id="${id}-qa-1" placeholder=""/></td>
  <td class="td-calc qa-bg" id="${id}-qa-ps">—</td><td class="td-calc qa-bg" id="${id}-qa-ws">—</td><td class="td-calc qa-bg" id="${id}-qa-score">—</td>`;

function getHPS(type, count) {
  let sum = 0, has = false;
  for (let i = 1; i <= count; i++) {
    const el = document.getElementById(`hps-${type}-${i}`);
    const v = el ? parseFloat(el.querySelector('input')?.value) : NaN;
    if (!isNaN(v) && v > 0) { sum += v; has = true; }
  }
  return has ? sum : null;
}

function recalcRow(row) {
  if (!row || row.classList.contains('tr-group') || row.classList.contains('tr-hps')) return;
  const id = row.id.replace('row-', '');
  let wwSum=0,wwCount=0;
  for (let i=1;i<=10;i++){const v=parseFloat(document.getElementById(`${id}-ww-${i}`)?.value);if(!isNaN(v)){wwSum+=v;wwCount++;}}
  const wwHPS=getHPS('ww',10), wwPS=wwHPS&&wwCount?+(wwSum/wwHPS*100).toFixed(1):null, wwWS=wwPS?+(wwPS*0.30).toFixed(2):null;
  setText(`${id}-ww-total`,wwCount?wwSum:'—'); setText(`${id}-ww-ps`,wwPS!==null?wwPS+'%':'—'); setText(`${id}-ww-ws`,wwWS!==null?wwWS:'—');
  let ptSum=0,ptCount=0;
  for (let i=1;i<=10;i++){const v=parseFloat(document.getElementById(`${id}-pt-${i}`)?.value);if(!isNaN(v)){ptSum+=v;ptCount++;}}
  const ptHPS=getHPS('pt',10), ptPS=ptHPS&&ptCount?+(ptSum/ptHPS*100).toFixed(1):null, ptWS=ptPS?+(ptPS*0.50).toFixed(2):null;
  setText(`${id}-pt-total`,ptCount?ptSum:'—'); setText(`${id}-pt-ps`,ptPS!==null?ptPS+'%':'—'); setText(`${id}-pt-ws`,ptWS!==null?ptWS:'—');
  const qaRaw=parseFloat(document.getElementById(`${id}-qa-1`)?.value);
  const qaHPS=(()=>{const el=document.getElementById('hps-qa-1');return el?parseFloat(el.querySelector('input')?.value):NaN;})();
  const qaPS=(!isNaN(qaRaw)&&!isNaN(qaHPS)&&qaHPS>0)?+(qaRaw/qaHPS*100).toFixed(1):null, qaWS=qaPS?+(qaPS*0.20).toFixed(2):null;
  setText(`${id}-qa-ps`,qaPS!==null?qaPS+'%':'—'); setText(`${id}-qa-ws`,qaWS!==null?qaWS:'—'); setText(`${id}-qa-score`,qaPS!==null?qaPS+'%':'—');
  if (wwWS!==null&&ptWS!==null&&qaWS!==null){
    const init=+(wwWS+ptWS+qaWS).toFixed(2), qGrade=transmute(init);
    setText(`${id}-initial`,init); setText(`${id}-quarterly`,qGrade);
    const el=document.getElementById(`${id}-quarterly`);
    if(el) el.style.color=qGrade>=75?'#16a34a':'#ef4444';
  } else { setText(`${id}-initial`,'—'); setText(`${id}-quarterly`,'—'); }
}

function transmute(ps){
  const table=[[100,100],[98.40,99],[96.80,98],[95.20,97],[93.60,96],[92.00,95],[90.40,94],[88.80,93],[87.20,92],[85.60,91],[84.00,90],[82.40,89],[80.80,88],[79.20,87],[77.60,86],[76.00,85],[74.40,84],[72.80,83],[71.20,82],[69.60,81],[68.00,80],[66.40,79],[64.80,78],[63.20,77],[61.60,76],[60.00,75],[56.00,74],[52.00,73],[48.00,72],[44.00,71],[40.00,70]];
  for(const [min,grade] of table) if(ps>=min) return grade;
  return Math.max(60,Math.round(ps));
}

function setText(id,val){const el=document.getElementById(id);if(el)el.textContent=val;}
function recalcAll(){document.querySelectorAll('#grade-tbody tr').forEach(recalcRow);}

function updateHeader(){
  const q=document.getElementById('sel-quarter').value;
  const g=document.getElementById('sel-grade').value;
  const s=document.getElementById('sel-section').value;
  const subj=document.getElementById('sel-subject').value;
  document.getElementById('info-quarter').textContent=q+' Quarter';
  document.getElementById('info-gs').textContent='Grade '+g+' – Section '+s;
  document.getElementById('info-subject').textContent=subj;
  document.getElementById('th-quarter-label').textContent=q.toUpperCase()+' QUARTER';
  document.getElementById('th-gs').innerHTML='GRADE &amp; SECTION: &nbsp; Grade '+g+' – '+s;
  document.getElementById('th-subj').innerHTML='SUBJECT: &nbsp; '+subj;
  document.getElementById('tbl-title').textContent=q+' Quarter · Grade '+g+'-'+s+' · '+subj;
}

function selectClass(btn,label,grade,section,subject){
  document.querySelectorAll('.class-tab').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('sel-grade').value=grade;
  document.getElementById('sel-section').value=section;
  document.getElementById('sel-subject').value=subject;
  updateHeader();
}

function filterStudents(){renderTable(document.getElementById('search-input').value);}

function saveGrades(){
  const toast=document.getElementById('toast');
  toast.style.display='flex';
  setTimeout(()=>{ toast.style.display='none'; },2500);
}

renderTable();
updateHeader();
</script>
@endpush
