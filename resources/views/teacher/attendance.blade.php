<!DOCTYPE html>
<html lang="en" class="">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MMSC – Teacher Attendance</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<script>
  tailwind.config = {
    darkMode: 'class',
    theme: { extend: { colors: { 'dark-bg': '#0b1224', 'dark-card': '#111827', 'dark-border': '#1e2d45' } } }
  };
</script>
<style>
  * { font-family: 'Poppins', sans-serif; }
  .no-scrollbar::-webkit-scrollbar{display:none;} .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none;}
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
</head>
<body class="flex h-screen overflow-hidden bg-slate-50 dark:bg-dark-bg">

<!-- ═══ SIDEBAR ═══ -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 lg:static flex flex-col bg-white dark:bg-dark-card border-r border-slate-100 dark:border-dark-border">
  <div class="flex h-16 items-center gap-2 px-5 bg-[#0d4c8f] dark:bg-[#091e42] shrink-0">
    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 shrink-0">
      <iconify-icon icon="solar:diploma-verified-bold" width="22" class="text-white"></iconify-icon>
    </div>
    <div class="flex flex-col leading-tight">
      <span class="text-lg font-bold text-white tracking-tight">MMSC</span>
      <span class="text-[11px] text-white/70 font-medium">Teacher Portal</span>
    </div>
  </div>
  <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-100 dark:border-dark-border">
    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 shrink-0 text-[#0d4c8f] dark:text-blue-300 text-sm font-bold">AR</div>
    <div class="flex flex-col leading-tight overflow-hidden">
      <span class="text-sm font-semibold text-slate-800 dark:text-white truncate">Ms. Ana Reyes</span>
      <span class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Math · Science</span>
    </div>
  </div>
  <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-3 space-y-0.5">
    <a href="Teacher Portal Dashboard.html" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 transition-all"><iconify-icon icon="boxicons:dashboard-filled" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>Dashboard</a>
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 transition-all"><iconify-icon icon="solar:book-2-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>My Classes</a>
    <!-- Attendance active -->
    <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 bg-blue-50 text-[#0d4c8f] dark:bg-[#0d4c8f]/10 dark:text-blue-300 text-sm font-medium"><iconify-icon icon="solar:calendar-check-bold" width="18" class="shrink-0"></iconify-icon>Attendance</a>
    <a href="Teacher Grades Input.html" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 transition-all"><iconify-icon icon="solar:medal-ribbons-star-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>Grades</a>
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 transition-all"><iconify-icon icon="uis:schedule" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>Schedule</a>
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 transition-all"><iconify-icon icon="solar:users-group-rounded-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>My Students</a>
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 transition-all"><iconify-icon icon="mdi:announcement" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>Announcements</a>
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 transition-all"><iconify-icon icon="mage:file-2-fill" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>Reports</a>
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 transition-all"><iconify-icon icon="material-symbols:settings" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>Settings</a>
  </nav>
  <div class="px-3 py-4 border-t border-slate-100 dark:border-dark-border">
    <button class="w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all">
      <iconify-icon icon="solar:logout-2-bold" width="18" class="shrink-0"></iconify-icon>Logout
    </button>
  </div>
</aside>

<!-- ═══ MAIN ═══ -->
<div class="flex flex-col flex-1 overflow-hidden">

  <!-- Topbar -->
  <header class="flex h-16 items-center justify-between px-6 bg-white dark:bg-dark-card border-b border-slate-100 dark:border-dark-border shrink-0">
    <div>
      <h1 class="text-base font-bold text-slate-900 dark:text-white">Attendance</h1>
      <p class="text-xs text-slate-400 dark:text-slate-500">Mark and track student attendance per class</p>
    </div>
    <div class="flex items-center gap-2">
      <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-blue-50 dark:bg-blue-900/20 px-3 py-1 text-xs font-medium text-[#0d4c8f] dark:text-blue-300">
        <iconify-icon icon="solar:calendar-linear" width="13"></iconify-icon>S.Y. 2025–2026
      </span>
      <button onclick="document.documentElement.classList.toggle('dark')" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
        <iconify-icon icon="solar:moon-stars-linear" width="18"></iconify-icon>
      </button>
      <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#0d4c8f] text-white text-sm font-semibold shrink-0">AR</div>
    </div>
  </header>

  <!-- Scrollable content -->
  <main class="flex-1 overflow-y-auto p-5 bg-slate-50/50 dark:bg-dark-bg">

    <!-- ── Quick Class Buttons ── -->
    <div class="mb-4">
      <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-2">My Classes — Quick Select</p>
      <div class="flex flex-wrap gap-2">
        <button onclick="selectClass(this,'Math 7-A','7','A','Mathematics')"    class="class-tab active rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 7</span>Math 7-A
        </button>
        <button onclick="selectClass(this,'Science 8-B','8','B','Science')"     class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 8</span>Science 8-B
        </button>
        <button onclick="selectClass(this,'Math 9-C','9','C','Mathematics')"    class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 9</span>Math 9-C
        </button>
        <button onclick="selectClass(this,'Science 7-B','7','B','Science')"     class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 7</span>Science 7-B
        </button>
        <button onclick="selectClass(this,'Math 8-A','8','A','Mathematics')"    class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 8</span>Math 8-A
        </button>
        <button onclick="selectClass(this,'Science 9-A','9','A','Science')"     class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 9</span>Science 9-A
        </button>
      </div>
    </div>

    <!-- ── Filters ── -->
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-4 mb-4">
      <div class="flex flex-wrap items-end gap-3">
        <div class="flex flex-col gap-1 min-w-[120px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Date</label>
          <input type="date" id="sel-date" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30"/>
        </div>
        <div class="flex flex-col gap-1 min-w-[110px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Grade Level</label>
          <select id="sel-grade" onchange="updateInfo()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
            <option value="7" selected>Grade 7</option>
            <option value="8">Grade 8</option>
            <option value="9">Grade 9</option>
            <option value="10">Grade 10</option>
          </select>
        </div>
        <div class="flex flex-col gap-1 min-w-[110px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Section</label>
          <select id="sel-section" onchange="updateInfo()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
            <option value="A" selected>Section A</option>
            <option value="B">Section B</option>
            <option value="C">Section C</option>
            <option value="D">Section D</option>
          </select>
        </div>
        <div class="flex flex-col gap-1 min-w-[130px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Subject</label>
          <select id="sel-subject" onchange="updateInfo()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
            <option value="Mathematics" selected>Mathematics</option>
            <option value="Science">Science</option>
            <option value="English">English</option>
            <option value="Filipino">Filipino</option>
            <option value="MAPEH">MAPEH</option>
            <option value="TLE">TLE</option>
            <option value="ESP">ESP</option>
            <option value="Araling Panlipunan">Araling Panlipunan</option>
          </select>
        </div>
        <div class="flex flex-col gap-1 flex-1 min-w-[150px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Search Student</label>
          <div class="relative">
            <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
            <input id="search-input" oninput="filterStudents()" placeholder="Search by name..." class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30"/>
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

    <!-- ── Summary Stats ── -->
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4 mb-4">
      <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card p-4 shadow-sm flex items-center gap-3">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/20 shrink-0">
          <iconify-icon icon="solar:users-group-rounded-linear" width="18" class="text-blue-500"></iconify-icon>
        </div>
        <div>
          <p class="text-xl font-bold text-slate-800 dark:text-white" id="stat-total">17</p>
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

    <!-- ── Attendance Card ── -->
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

      <!-- Card header -->
      <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 dark:border-dark-border">
        <div class="flex items-center gap-2.5">
          <iconify-icon icon="solar:calendar-check-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
          <div>
            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Attendance Sheet</h3>
            <p class="text-[11px] text-slate-400 dark:text-slate-500" id="sheet-subtitle">Grade 7 – Section A · Mathematics · <span id="sheet-date"></span></p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <!-- Legend -->
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

      <!-- Table header -->
      <div class="grid items-center bg-slate-50 dark:bg-dark-bg/30 border-b border-slate-100 dark:border-dark-border px-5 py-2.5 text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide"
           style="grid-template-columns: 40px 1fr 160px 1fr 100px;">
        <span>#</span>
        <span>Student Name</span>
        <span class="text-center">Status</span>
        <span class="pl-4">Remarks / Notes</span>
        <span class="text-center">Time In</span>
      </div>

      <!-- Student list -->
      <div id="student-list" class="divide-y divide-slate-100 dark:divide-dark-border">
        <!-- Rendered by JS -->
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-between px-5 py-3.5 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/30">
        <div class="flex items-center gap-4 text-xs">
          <span class="text-slate-500 dark:text-slate-400">
            Marked: <span id="footer-marked" class="font-bold text-slate-700 dark:text-slate-300">0</span> / <span id="footer-total" class="font-bold text-slate-700 dark:text-slate-300">17</span>
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

    <!-- ── Recent Attendance History ── -->
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
            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
              <td class="px-5 py-2.5 font-medium text-slate-700 dark:text-slate-300">Apr 23, 2026</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Grade 7 – A</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Mathematics</td>
              <td class="px-4 py-2.5 text-center text-green-500 font-bold">16</td>
              <td class="px-4 py-2.5 text-center text-red-400 font-bold">1</td>
              <td class="px-4 py-2.5 text-center text-yellow-500 font-bold">0</td>
              <td class="px-4 py-2.5 text-center"><span class="font-bold text-green-500">94%</span></td>
              <td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-semibold">Saved</span></td>
            </tr>
            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
              <td class="px-5 py-2.5 font-medium text-slate-700 dark:text-slate-300">Apr 23, 2026</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Grade 8 – B</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Science</td>
              <td class="px-4 py-2.5 text-center text-green-500 font-bold">28</td>
              <td class="px-4 py-2.5 text-center text-red-400 font-bold">2</td>
              <td class="px-4 py-2.5 text-center text-yellow-500 font-bold">1</td>
              <td class="px-4 py-2.5 text-center"><span class="font-bold text-green-500">90%</span></td>
              <td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-semibold">Saved</span></td>
            </tr>
            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
              <td class="px-5 py-2.5 font-medium text-slate-700 dark:text-slate-300">Apr 22, 2026</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Grade 9 – C</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Mathematics</td>
              <td class="px-4 py-2.5 text-center text-green-500 font-bold">25</td>
              <td class="px-4 py-2.5 text-center text-red-400 font-bold">1</td>
              <td class="px-4 py-2.5 text-center text-yellow-500 font-bold">0</td>
              <td class="px-4 py-2.5 text-center"><span class="font-bold text-green-500">96%</span></td>
              <td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-semibold">Saved</span></td>
            </tr>
            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
              <td class="px-5 py-2.5 font-medium text-slate-700 dark:text-slate-300">Apr 22, 2026</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Grade 7 – B</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Science</td>
              <td class="px-4 py-2.5 text-center text-green-500 font-bold">26</td>
              <td class="px-4 py-2.5 text-center text-red-400 font-bold">0</td>
              <td class="px-4 py-2.5 text-center text-yellow-500 font-bold">1</td>
              <td class="px-4 py-2.5 text-center"><span class="font-bold text-green-500">100%</span></td>
              <td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-semibold">Saved</span></td>
            </tr>
            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
              <td class="px-5 py-2.5 font-medium text-slate-700 dark:text-slate-300">Apr 21, 2026</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Grade 8 – A</td>
              <td class="px-4 py-2.5 text-slate-500 dark:text-slate-400">Mathematics</td>
              <td class="px-4 py-2.5 text-center text-green-500 font-bold">27</td>
              <td class="px-4 py-2.5 text-center text-red-400 font-bold">2</td>
              <td class="px-4 py-2.5 text-center text-yellow-500 font-bold">0</td>
              <td class="px-4 py-2.5 text-center"><span class="font-bold text-yellow-500">93%</span></td>
              <td class="px-4 py-2.5 text-center"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-semibold">Saved</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="hidden fixed bottom-8 right-8 z-[200] flex items-center gap-3 rounded-xl bg-[#0d4c8f] text-white px-5 py-3 shadow-2xl text-sm font-medium">
      <iconify-icon icon="solar:check-circle-bold" width="18"></iconify-icon>
      Attendance saved successfully!
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
  </main>
</div>

<!-- Tweaks -->
<div id="tweaks-panel" class="hidden fixed bottom-5 right-5 z-[999] w-56 rounded-xl bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border shadow-xl p-4">
  <p class="text-xs font-bold text-slate-700 dark:text-white mb-3 uppercase tracking-wider">Tweaks</p>
  <button onclick="document.documentElement.classList.toggle('dark')" class="w-full text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5 px-2.5 py-1.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">Toggle Dark / Light</button>
</div>

<script>
const TWEAK_DEFAULTS = /*EDITMODE-BEGIN*/{"darkMode": false}/*EDITMODE-END*/;

// ── Students ───────────────────────────────────────
const studentsMale = [
  'Aguilar, Marco Luis', 'Bautista, Rafael Jr.', 'Cruz, Angelo Miguel',
  'Dela Cruz, Juan Pablo', 'Espiritu, Christian Jay', 'Garcia, Ben James',
];
const studentsFemale = [
  'Castillo, Sophia Mae', 'Dela Cruz, Maria Clara', 'Flores, Patricia Anne',
  'Lim, Angela Rose', 'Santos, Isabella Joy', 'Torres, Kyla Marie',
  'Villanueva, Pamela Grace', 'Reyes, Nicole Ann', 'Gomez, Tricia Faye',
  'Bautista, Camille Joy', 'Morales, Diane Pearl',
];
const allStudents = [...studentsMale.map(n => ({name:n,gender:'male'})), ...studentsFemale.map(n => ({name:n,gender:'female'}))];
let attendance = {}; // name → {status, time, remarks}
let searchFilter = '';

// ── Set today's date ───────────────────────────────
const today = new Date();
const pad = n => String(n).padStart(2,'0');
const todayStr = `${today.getFullYear()}-${pad(today.getMonth()+1)}-${pad(today.getDate())}`;
document.getElementById('sel-date').value = todayStr;
updateSheetDate();

function updateSheetDate() {
  const d = document.getElementById('sel-date').value;
  const fmt = d ? new Date(d + 'T00:00').toLocaleDateString('en-PH', {month:'long', day:'numeric', year:'numeric'}) : '—';
  document.getElementById('sheet-date').textContent = fmt;
}
document.getElementById('sel-date').addEventListener('change', updateSheetDate);

// ── Render attendance list ─────────────────────────
function renderList() {
  const filter = searchFilter.toLowerCase();
  const list = document.getElementById('student-list');
  const groups = [
    { label: 'MALE', students: studentsMale },
    { label: 'FEMALE', students: studentsFemale },
  ];

  let html = '';
  let globalIdx = 0;

  groups.forEach(g => {
    const filtered = filter ? g.students.filter(n => n.toLowerCase().includes(filter)) : g.students;
    if (!filtered.length) return;

    html += `<div class="px-5 py-1.5 bg-slate-100 dark:bg-[#1a2744] border-b border-slate-200 dark:border-dark-border">
      <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">${g.label}</span>
    </div>`;

    filtered.forEach(name => {
      globalIdx++;
      const att = attendance[name] || {};
      const status = att.status || '';
      const time   = att.time   || '';
      const rem    = att.remarks || '';
      const avatarBg = g.label === 'MALE' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300' : 'bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-300';
      const initials = name.split(',').map(p => p.trim()[0]).join('').slice(0,2).toUpperCase();

      html += `
      <div class="student-row grid items-center px-5 py-2.5 transition-colors" style="grid-template-columns: 40px 1fr 160px 1fr 100px;" data-name="${name}">
        <!-- # + Avatar -->
        <div class="flex items-center gap-1.5">
          <span class="text-[11px] text-slate-400 dark:text-slate-600 w-5 text-right">${globalIdx}</span>
        </div>
        <!-- Name -->
        <div class="flex items-center gap-2.5">
          <div class="flex h-8 w-8 items-center justify-center rounded-full ${avatarBg} text-[10px] font-bold shrink-0">${initials}</div>
          <span class="text-sm font-medium text-slate-700 dark:text-slate-300">${name}</span>
        </div>
        <!-- Status buttons -->
        <div class="flex items-center justify-center gap-1">
          <button onclick="setStatus('${name}','present')"  class="status-btn ${status==='present' ?'present' :''} rounded-md border border-slate-200 dark:border-dark-border px-2 py-1 text-[11px] hover:bg-green-50 dark:hover:bg-green-900/10 transition-all" title="Present">
            <iconify-icon icon="solar:check-circle-bold" width="13" class="mr-0.5"></iconify-icon>P
          </button>
          <button onclick="setStatus('${name}','absent')"   class="status-btn ${status==='absent'  ?'absent'  :''} rounded-md border border-slate-200 dark:border-dark-border px-2 py-1 text-[11px] hover:bg-red-50 dark:hover:bg-red-900/10 transition-all"   title="Absent">
            <iconify-icon icon="solar:close-circle-bold" width="13" class="mr-0.5"></iconify-icon>A
          </button>
          <button onclick="setStatus('${name}','late')"     class="status-btn ${status==='late'    ?'late'    :''} rounded-md border border-slate-200 dark:border-dark-border px-2 py-1 text-[11px] hover:bg-yellow-50 dark:hover:bg-yellow-900/10 transition-all"  title="Late">
            <iconify-icon icon="solar:clock-circle-bold" width="13" class="mr-0.5"></iconify-icon>L
          </button>
          <button onclick="setStatus('${name}','excused')"  class="status-btn ${status==='excused' ?'excused' :''} rounded-md border border-slate-200 dark:border-dark-border px-2 py-1 text-[11px] hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all" title="Excused">
            <iconify-icon icon="solar:shield-check-bold" width="13" class="mr-0.5"></iconify-icon>E
          </button>
        </div>
        <!-- Remarks -->
        <div class="pl-4">
          <input value="${rem}" onchange="setRemarks('${name}',this.value)" placeholder="Add note..." class="w-full text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-1.5 text-slate-600 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30 placeholder-slate-300"/>
        </div>
        <!-- Time -->
        <div class="flex justify-center">
          <input type="time" value="${time}" onchange="setTime('${name}',this.value)" class="text-[11px] rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-2 py-1.5 text-slate-600 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30 ${status!=='present' && status!=='late' ? 'opacity-40 cursor-not-allowed':''}" ${status!=='present' && status!=='late' ? 'disabled':''}/>
        </div>
      </div>`;
    });
  });

  list.innerHTML = html;
  updateStats();
}

// ── Set status ─────────────────────────────────────
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

// ── Bulk actions ───────────────────────────────────
function markAll(status) {
  const now = new Date();
  const t = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
  allStudents.forEach(s => {
    attendance[s.name] = { status, time: status === 'present' ? t : '', remarks: attendance[s.name]?.remarks || '' };
  });
  renderList();
}
function clearAll() {
  attendance = {};
  renderList();
}

// ── Stats ──────────────────────────────────────────
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
  document.getElementById('stat-total').textContent   = total;
  document.getElementById('stat-present').textContent = present;
  document.getElementById('stat-absent').textContent  = absent;
  document.getElementById('stat-late').textContent    = late + excused;
  document.getElementById('footer-marked').textContent = marked;
  document.getElementById('footer-total').textContent  = total;
  const pct = total ? Math.round(marked / total * 100) : 0;
  document.getElementById('progress-bar').style.width = pct + '%';
  document.getElementById('progress-pct').textContent = pct + '%';
  document.getElementById('progress-bar-wrap').classList.remove('hidden');
}

// ── Info update ────────────────────────────────────
function updateInfo() {
  const g = document.getElementById('sel-grade').value;
  const s = document.getElementById('sel-section').value;
  const subj = document.getElementById('sel-subject').value;
  document.getElementById('sheet-subtitle').innerHTML =
    `Grade ${g} – Section ${s} · ${subj} · <span id="sheet-date"></span>`;
  updateSheetDate();
}

// ── Quick class select ─────────────────────────────
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

// ── Search ─────────────────────────────────────────
function filterStudents() {
  searchFilter = document.getElementById('search-input').value;
  renderList();
}

// ── Save ───────────────────────────────────────────
function saveAttendance() {
  const toast = document.getElementById('toast');
  toast.classList.remove('hidden');
  setTimeout(() => toast.classList.add('hidden'), 2500);
}

// ── Init ───────────────────────────────────────────
renderList();
updateInfo();

window.addEventListener('message', e => {
  if (e.data?.type === '__activate_edit_mode')   document.getElementById('tweaks-panel').classList.remove('hidden');
  if (e.data?.type === '__deactivate_edit_mode') document.getElementById('tweaks-panel').classList.add('hidden');
});
window.parent.postMessage({ type: '__edit_mode_available' }, '*');
</script>
</body>
</html>
