<!DOCTYPE html>
<html lang="en" class="">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MMSC – Parent Portal</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<script>
  tailwind.config = {
    darkMode: 'class',
    theme: {
      extend: {
        colors: {
          'dark-bg':     '#0b1224',
          'dark-card':   '#111827',
          'dark-border': '#1e2d45',
        },
        fontFamily: { poppins: ['Poppins', 'sans-serif'] }
      }
    }
  };
</script>
<style>
  * { font-family: 'Poppins', sans-serif; }
  .no-scrollbar::-webkit-scrollbar { display: none; }
  .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  .child-tab.active { background: #0d4c8f; color: #fff; }
  .child-tab { transition: all .15s; }
</style>
</head>
<body class="flex h-screen overflow-hidden bg-slate-50 dark:bg-dark-bg">

<!-- ═══════════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════════════ -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 lg:static flex flex-col bg-white dark:bg-dark-card border-r border-slate-100 dark:border-dark-border">

  <!-- Logo -->
  <div class="flex h-16 items-center gap-2 px-5 bg-[#0d4c8f] dark:bg-[#091e42] shrink-0">
    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 shrink-0">
      <iconify-icon icon="solar:diploma-verified-bold" width="22" class="text-white"></iconify-icon>
    </div>
    <div class="flex flex-col leading-tight">
      <span class="text-lg font-bold text-white tracking-tight">MMSC</span>
      <span class="text-[11px] text-white/70 font-medium">Parent Portal</span>
    </div>
  </div>

  <!-- Parent Info -->
  <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-100 dark:border-dark-border">
    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/30 shrink-0 text-orange-600 dark:text-orange-300 text-sm font-bold">PD</div>
    <div class="flex flex-col leading-tight overflow-hidden">
      <span class="text-sm font-semibold text-slate-800 dark:text-white truncate">Pedro Dela Cruz</span>
      <span class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Parent / Guardian</span>
    </div>
  </div>

  <!-- Nav -->
  <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-3 space-y-0.5">

    <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 bg-blue-50 text-[#0d4c8f] dark:bg-[#0d4c8f]/10 dark:text-blue-300 text-sm font-medium">
      <iconify-icon icon="boxicons:dashboard-filled" width="18" class="shrink-0"></iconify-icon>
      Dashboard
    </a>

    <div>
      <button onclick="toggleNav('children')" id="btn-children"
        class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
        <iconify-icon icon="solar:users-group-rounded-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
        <span class="flex-1 text-left">My Children</span>
        <iconify-icon id="arr-children" icon="solar:alt-arrow-down-linear" width="14" class="transition-transform text-slate-400"></iconify-icon>
      </button>
      <div id="sub-children" class="hidden mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-slate-700 space-y-0.5 py-1">
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Juan Dela Cruz</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Maria Dela Cruz</a>
      </div>
    </div>

    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="solar:medal-ribbons-star-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      Grades
    </a>

    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="solar:calendar-check-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      Attendance
    </a>

    <div>
      <button onclick="toggleNav('clearance')" id="btn-clearance"
        class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
        <iconify-icon icon="tdesign:task-checked-filled" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
        <span class="flex-1 text-left">Clearance</span>
        <iconify-icon id="arr-clearance" icon="solar:alt-arrow-down-linear" width="14" class="transition-transform text-slate-400"></iconify-icon>
      </button>
      <div id="sub-clearance" class="hidden mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-slate-700 space-y-0.5 py-1">
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Finance</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Library</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Summary</a>
      </div>
    </div>

    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="uis:schedule" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      Schedule
    </a>

    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="mdi:announcement" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      Announcements
    </a>

    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="solar:calendar-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      School Calendar
    </a>

    <div>
      <button onclick="toggleNav('settings')" id="btn-settings"
        class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
        <iconify-icon icon="material-symbols:settings" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
        <span class="flex-1 text-left">Settings</span>
        <iconify-icon id="arr-settings" icon="solar:alt-arrow-down-linear" width="14" class="transition-transform text-slate-400"></iconify-icon>
      </button>
      <div id="sub-settings" class="hidden mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-slate-700 space-y-0.5 py-1">
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">My Account</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Change Password</a>
      </div>
    </div>

  </nav>

  <div class="px-3 py-4 border-t border-slate-100 dark:border-dark-border">
    <button class="w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all">
      <iconify-icon icon="solar:logout-2-bold" width="18" class="shrink-0"></iconify-icon>
      Logout
    </button>
  </div>
</aside>

<!-- ═══════════════════════════════════════════════════
     MAIN
════════════════════════════════════════════════════ -->
<div class="flex flex-col flex-1 overflow-hidden">

  <!-- Topbar -->
  <header class="flex h-16 items-center justify-between px-6 bg-white dark:bg-dark-card border-b border-slate-100 dark:border-dark-border shrink-0">
    <div>
      <h1 class="text-base font-bold text-slate-900 dark:text-white">Dashboard</h1>
      <p class="text-xs text-slate-400 dark:text-slate-500">Welcome, Pedro. Here's your children's overview.</p>
    </div>
    <div class="flex items-center gap-2">
      <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-blue-50 dark:bg-blue-900/20 px-3 py-1 text-xs font-medium text-[#0d4c8f] dark:text-blue-300">
        <iconify-icon icon="solar:calendar-linear" width="13"></iconify-icon>
        S.Y. 2025–2026
      </span>
      <button class="relative flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
        <iconify-icon icon="solar:bell-linear" width="18"></iconify-icon>
        <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500"></span>
      </button>
      <button id="dark-toggle" onclick="document.documentElement.classList.toggle('dark')"
        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
        <iconify-icon icon="solar:moon-stars-linear" width="18"></iconify-icon>
      </button>
      <div class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-500 text-white text-sm font-semibold shrink-0">PD</div>
    </div>
  </header>

  <!-- Scrollable -->
  <main class="flex-1 overflow-y-auto p-6 bg-slate-50/50 dark:bg-dark-bg">

    <!-- ── Stats ── -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 mb-3">
              <iconify-icon icon="solar:users-group-rounded-linear" width="22" class="text-blue-500 dark:text-blue-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Children Enrolled</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">This school year</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">2</h3>
        </div>
      </div>

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-teal-50 dark:bg-teal-900/20 mb-3">
              <iconify-icon icon="solar:calendar-check-linear" width="22" class="text-teal-500 dark:text-teal-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Avg. Attendance</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">Combined this year</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-teal-500 dark:text-teal-400">95%</h3>
        </div>
      </div>

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/20 mb-3">
              <iconify-icon icon="solar:medal-ribbons-star-linear" width="22" class="text-purple-500 dark:text-purple-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Avg. Grade (Q3)</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">Across all subjects</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">90</h3>
        </div>
      </div>

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 mb-3">
              <iconify-icon icon="tdesign:task-checked-filled" width="22" class="text-yellow-500 dark:text-yellow-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Pending Clearance</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">Items to settle</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-yellow-500 dark:text-yellow-400">2</h3>
        </div>
      </div>

    </div>

    <!-- ── Children Cards ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">

      <!-- Child 1 -->
      <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card overflow-hidden">
        <!-- Card header -->
        <div class="flex items-center gap-4 px-5 py-4 border-b border-slate-100 dark:border-dark-border bg-gradient-to-r from-blue-50 to-transparent dark:from-blue-900/10 dark:to-transparent">
          <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-base font-bold text-blue-600 dark:text-blue-300 shrink-0">JD</div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-slate-800 dark:text-white">Juan Dela Cruz</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500">Grade 8 · Section Rizal &nbsp;·&nbsp; ID: 2025-08-0042</p>
          </div>
          <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[11px] font-semibold shrink-0">
            <iconify-icon icon="solar:check-circle-bold" width="12"></iconify-icon>Active
          </span>
        </div>
        <div class="grid grid-cols-3 divide-x divide-slate-100 dark:divide-dark-border border-b border-slate-100 dark:border-dark-border">
          <div class="flex flex-col items-center py-3">
            <span class="text-xl font-bold text-[#0d4c8f] dark:text-blue-300">91.5</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Avg. Grade</span>
          </div>
          <div class="flex flex-col items-center py-3">
            <span class="text-xl font-bold text-teal-500 dark:text-teal-300">97%</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Attendance</span>
          </div>
          <div class="flex flex-col items-center py-3">
            <span class="text-xl font-bold text-green-500 dark:text-green-400">5/6</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Clearance</span>
          </div>
        </div>
        <!-- Grades per subject mini -->
        <div class="px-5 py-4">
          <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3">Q3 Subject Grades</p>
          <div class="grid grid-cols-2 gap-x-6 gap-y-2">
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">Mathematics</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-blue-500" style="width:94%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">94</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">Science</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-teal-500" style="width:89%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">89</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">English</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-green-500" style="width:92%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">92</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">Filipino</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-yellow-400" style="width:88%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">88</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">Araling Panlip.</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-orange-400" style="width:91%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">91</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">MAPEH</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-purple-400" style="width:95%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">95</span>
            </div>
          </div>
          <div class="mt-3 flex items-center justify-between">
            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2.5 py-1 rounded-full">
              <iconify-icon icon="solar:medal-ribbons-star-bold" width="12"></iconify-icon>With Honors
            </span>
            <a href="#" class="text-[11px] text-[#0d4c8f] dark:text-blue-400 hover:underline font-medium">View full record →</a>
          </div>
        </div>
      </div>

      <!-- Child 2 -->
      <div class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card overflow-hidden">
        <div class="flex items-center gap-4 px-5 py-4 border-b border-slate-100 dark:border-dark-border bg-gradient-to-r from-pink-50 to-transparent dark:from-pink-900/10 dark:to-transparent">
          <div class="flex h-11 w-11 items-center justify-center rounded-full bg-pink-100 dark:bg-pink-900/30 text-base font-bold text-pink-600 dark:text-pink-300 shrink-0">MD</div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-slate-800 dark:text-white">Maria Dela Cruz</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500">Grade 6 · Section Bonifacio &nbsp;·&nbsp; ID: 2025-06-0019</p>
          </div>
          <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[11px] font-semibold shrink-0">
            <iconify-icon icon="solar:check-circle-bold" width="12"></iconify-icon>Active
          </span>
        </div>
        <div class="grid grid-cols-3 divide-x divide-slate-100 dark:divide-dark-border border-b border-slate-100 dark:border-dark-border">
          <div class="flex flex-col items-center py-3">
            <span class="text-xl font-bold text-[#0d4c8f] dark:text-blue-300">88.3</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Avg. Grade</span>
          </div>
          <div class="flex flex-col items-center py-3">
            <span class="text-xl font-bold text-teal-500 dark:text-teal-300">93%</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Attendance</span>
          </div>
          <div class="flex flex-col items-center py-3">
            <span class="text-xl font-bold text-yellow-500 dark:text-yellow-400">4/6</span>
            <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Clearance</span>
          </div>
        </div>
        <div class="px-5 py-4">
          <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-3">Q3 Subject Grades</p>
          <div class="grid grid-cols-2 gap-x-6 gap-y-2">
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">Mathematics</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-blue-500" style="width:86%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">86</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">Science</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-teal-500" style="width:90%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">90</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">English</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-green-500" style="width:88%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">88</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">Filipino</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-yellow-400" style="width:91%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">91</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">Araling Panlip.</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-orange-400" style="width:87%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">87</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-[11px] text-slate-500 dark:text-slate-400 w-24 truncate">MAPEH</span>
              <div class="flex-1 h-1.5 rounded-full bg-slate-100 dark:bg-white/10"><div class="h-1.5 rounded-full bg-purple-400" style="width:88%"></div></div>
              <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 w-6 text-right">88</span>
            </div>
          </div>
          <div class="mt-3 flex items-center justify-between">
            <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/10 px-2.5 py-1 rounded-full">
              <iconify-icon icon="solar:star-linear" width="12"></iconify-icon>Good Standing
            </span>
            <a href="#" class="text-[11px] text-[#0d4c8f] dark:text-blue-400 hover:underline font-medium">View full record →</a>
          </div>
        </div>
      </div>

    </div>

    <!-- ── Row 3: Attendance · Clearance · Announcements ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

      <!-- Attendance Chart -->
      <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <iconify-icon icon="solar:chart-2-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Monthly Attendance</h3>
          </div>
          <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-blue-500"></span><span class="text-[11px] text-slate-500 dark:text-slate-400">Juan</span></div>
            <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-pink-400"></span><span class="text-[11px] text-slate-500 dark:text-slate-400">Maria</span></div>
          </div>
        </div>
        <div class="relative h-52">
          <canvas id="attendanceChart"></canvas>
        </div>
      </div>

      <!-- Clearance + Announcements -->
      <div class="flex flex-col gap-5">

        <!-- Clearance -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
          <div class="mb-3 flex items-center gap-2.5">
            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
              <iconify-icon icon="tdesign:task-checked-filled" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            </div>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Clearance Summary</h3>
          </div>
          <!-- Juan -->
          <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1.5">Juan Dela Cruz</p>
          <div class="flex flex-col gap-1.5 mb-3">
            <div class="flex justify-between text-xs"><span class="text-slate-500 dark:text-slate-400">Academic</span><span class="text-green-500 font-medium flex items-center gap-1"><iconify-icon icon="solar:check-circle-bold" width="12"></iconify-icon>Cleared</span></div>
            <div class="flex justify-between text-xs"><span class="text-slate-500 dark:text-slate-400">Finance</span><span class="text-red-400 font-medium flex items-center gap-1"><iconify-icon icon="solar:close-circle-linear" width="12"></iconify-icon>Pending</span></div>
            <div class="flex justify-between text-xs"><span class="text-slate-500 dark:text-slate-400">Library</span><span class="text-green-500 font-medium flex items-center gap-1"><iconify-icon icon="solar:check-circle-bold" width="12"></iconify-icon>Cleared</span></div>
          </div>
          <!-- Maria -->
          <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500 mb-1.5">Maria Dela Cruz</p>
          <div class="flex flex-col gap-1.5">
            <div class="flex justify-between text-xs"><span class="text-slate-500 dark:text-slate-400">Academic</span><span class="text-green-500 font-medium flex items-center gap-1"><iconify-icon icon="solar:check-circle-bold" width="12"></iconify-icon>Cleared</span></div>
            <div class="flex justify-between text-xs"><span class="text-slate-500 dark:text-slate-400">Finance</span><span class="text-red-400 font-medium flex items-center gap-1"><iconify-icon icon="solar:close-circle-linear" width="12"></iconify-icon>Pending</span></div>
            <div class="flex justify-between text-xs"><span class="text-slate-500 dark:text-slate-400">Library</span><span class="text-green-500 font-medium flex items-center gap-1"><iconify-icon icon="solar:check-circle-bold" width="12"></iconify-icon>Cleared</span></div>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 dark:border-dark-border">
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-1.5">Pending items to settle</p>
            <div class="rounded-lg bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 px-3 py-2 flex items-center gap-2">
              <iconify-icon icon="solar:wallet-linear" width="16" class="text-red-400 shrink-0"></iconify-icon>
              <div>
                <p class="text-xs font-semibold text-red-500">Finance Clearance</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Both children · Please settle at the cashier</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Announcements -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
          <div class="mb-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
                <iconify-icon icon="mdi:announcement" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
              </div>
              <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Announcements</h3>
            </div>
            <a href="#" class="flex h-7 items-center gap-1 px-2.5 rounded-lg border border-slate-200 text-[11px] font-medium text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5">
              All <iconify-icon icon="solar:arrow-right-linear" width="11"></iconify-icon>
            </a>
          </div>
          <div class="flex flex-col gap-2">
            <div class="flex items-start gap-2 py-2 border-b border-slate-100 dark:border-dark-border">
              <span class="mt-1.5 h-2 w-2 rounded-full bg-red-500 shrink-0"></span>
              <div>
                <p class="text-xs font-medium text-slate-700 dark:text-slate-300">No Classes – Rizal Day</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Apr 25 · Admin</p>
              </div>
            </div>
            <div class="flex items-start gap-2 py-2 border-b border-slate-100 dark:border-dark-border">
              <span class="mt-1.5 h-2 w-2 rounded-full bg-yellow-400 shrink-0"></span>
              <div>
                <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Q3 Exams – Apr 28–30</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Apr 20 · Registrar</p>
              </div>
            </div>
            <div class="flex items-start gap-2 py-2">
              <span class="mt-1.5 h-2 w-2 rounded-full bg-green-500 shrink-0"></span>
              <div>
                <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Card Giving & PTC – Apr 30</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Apr 18 · Admin</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ── Row 4: Today's Schedule (both children) · Upcoming Events ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

      <!-- Today's Schedule -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-3 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <iconify-icon icon="uis:schedule" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Today's Schedule</h3>
          </div>
          <span class="text-[11px] text-slate-400">Apr 21</span>
        </div>
        <!-- Child selector -->
        <div class="flex rounded-lg overflow-hidden border border-slate-200 dark:border-dark-border mb-3 text-[11px] font-medium">
          <button onclick="switchSchedule(0)" id="sched-0" class="child-tab active flex-1 px-3 py-1.5 text-center">Juan</button>
          <button onclick="switchSchedule(1)" id="sched-1" class="child-tab flex-1 px-3 py-1.5 text-center text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-white/5">Maria</button>
        </div>
        <div id="sched-list-0" class="flex flex-col gap-2">
          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-blue-400"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Mathematics</p>
              <p class="text-[11px] text-slate-400">7:00 – 8:00 AM · Ms. Reyes</p>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300 font-semibold shrink-0">Done</span>
          </div>
          <div class="flex items-center gap-3 rounded-lg border border-[#0d4c8f]/30 bg-blue-50 dark:bg-blue-900/10 px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-[#0d4c8f]"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-300">Science</p>
              <p class="text-[11px] text-slate-500">8:00 – 9:00 AM · Mr. Cruz</p>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#0d4c8f] text-white font-semibold shrink-0">Now</span>
          </div>
          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-green-400"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">English</p>
              <p class="text-[11px] text-slate-400">10:00 – 11:00 AM · Ms. Lim</p>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-400 font-medium shrink-0">Later</span>
          </div>
          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-yellow-400"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Filipino</p>
              <p class="text-[11px] text-slate-400">1:00 – 2:00 PM · Ms. Santos</p>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-400 font-medium shrink-0">Later</span>
          </div>
        </div>
        <div id="sched-list-1" class="hidden flex flex-col gap-2">
          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-purple-400"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Filipino</p>
              <p class="text-[11px] text-slate-400">7:00 – 8:00 AM · Ms. Garcia</p>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-300 font-semibold shrink-0">Done</span>
          </div>
          <div class="flex items-center gap-3 rounded-lg border border-[#0d4c8f]/30 bg-blue-50 dark:bg-blue-900/10 px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-[#0d4c8f]"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-300">Mathematics</p>
              <p class="text-[11px] text-slate-500">8:00 – 9:00 AM · Mr. Tan</p>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#0d4c8f] text-white font-semibold shrink-0">Now</span>
          </div>
          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-teal-400"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Science</p>
              <p class="text-[11px] text-slate-400">10:00 – 11:00 AM · Ms. Reyes</p>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-400 font-medium shrink-0">Later</span>
          </div>
          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-orange-400"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">TLE</p>
              <p class="text-[11px] text-slate-400">1:00 – 2:00 PM · Mr. Bautista</p>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-400 font-medium shrink-0">Later</span>
          </div>
        </div>
      </div>

      <!-- Upcoming Events -->
      <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <iconify-icon icon="solar:calendar-bold" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Upcoming School Events</h3>
          </div>
          <a href="#" class="flex h-7 items-center gap-1 px-2.5 rounded-lg border border-slate-200 text-[11px] font-medium text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5">
            Calendar <iconify-icon icon="solar:arrow-right-linear" width="11"></iconify-icon>
          </a>
        </div>
        <div class="flex flex-col divide-y divide-slate-100 dark:divide-dark-border">

          <div class="flex items-center gap-4 py-3">
            <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 shrink-0">
              <span class="text-[10px] font-semibold text-blue-400 uppercase">Apr</span>
              <span class="text-lg font-bold text-[#0d4c8f] dark:text-blue-300 leading-none">23</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Faculty Meeting (No PM Classes)</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Wednesday · Affects all students</p>
            </div>
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300 text-[10px] font-semibold">School Event</span>
          </div>

          <div class="flex items-center gap-4 py-3">
            <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-900/20 shrink-0">
              <span class="text-[10px] font-semibold text-purple-400 uppercase">Apr</span>
              <span class="text-lg font-bold text-purple-600 dark:text-purple-300 leading-none">25</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Rizal Day – No Classes</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Friday · National Holiday</p>
            </div>
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-300 text-[10px] font-semibold">Holiday</span>
          </div>

          <div class="flex items-center gap-4 py-3">
            <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 shrink-0">
              <span class="text-[10px] font-semibold text-blue-400 uppercase">Apr</span>
              <span class="text-lg font-bold text-[#0d4c8f] dark:text-blue-300 leading-none">28</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">3rd Quarter Examinations Begin</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Mon–Wed, Apr 28–30 · All Grades</p>
            </div>
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 text-[10px] font-semibold">Exam Day</span>
          </div>

          <div class="flex items-center gap-4 py-3">
            <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-yellow-50 dark:bg-yellow-900/20 shrink-0">
              <span class="text-[10px] font-semibold text-yellow-500 uppercase">Apr</span>
              <span class="text-lg font-bold text-yellow-600 dark:text-yellow-300 leading-none">30</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Card Giving & Parent–Teacher Conference</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Wednesday · Please bring valid ID</p>
            </div>
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 text-[10px] font-semibold">School Event</span>
          </div>

          <div class="flex items-center gap-4 py-3">
            <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-900/20 shrink-0">
              <span class="text-[10px] font-semibold text-orange-400 uppercase">May</span>
              <span class="text-lg font-bold text-orange-600 dark:text-orange-300 leading-none">5</span>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Summer Break Begins</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Monday · End of S.Y. 2025–2026</p>
            </div>
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-300 text-[10px] font-semibold">Break</span>
          </div>

        </div>
      </div>

    </div>

    <p class="mt-8 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
  </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Sidebar toggles ──────────────────────────────
function toggleNav(id) {
  const sub = document.getElementById('sub-' + id);
  const arr = document.getElementById('arr-' + id);
  const open = !sub.classList.contains('hidden');
  sub.classList.toggle('hidden', open);
  arr.style.transform = open ? '' : 'rotate(180deg)';
}

// ── Schedule tab ─────────────────────────────────
function switchSchedule(idx) {
  [0,1].forEach(i => {
    document.getElementById('sched-' + i).classList.toggle('active', i === idx);
    document.getElementById('sched-list-' + i).classList.toggle('hidden', i !== idx);
  });
}

// ── Attendance Chart ─────────────────────────────
const isDark = () => document.documentElement.classList.contains('dark');
const gridColor = () => isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
const labelColor = () => isDark() ? '#94a3b8' : '#64748b';

const ctx = document.getElementById('attendanceChart').getContext('2d');
const attChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar','Apr'],
    datasets: [
      {
        label: 'Juan',
        data: [98, 97, 100, 96, 98, 97, 99, 97, 97],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59,130,246,0.08)',
        tension: 0.4, pointRadius: 4, borderWidth: 2, fill: true
      },
      {
        label: 'Maria',
        data: [95, 93, 96, 92, 94, 91, 95, 93, 93],
        borderColor: '#f472b6',
        backgroundColor: 'rgba(244,114,182,0.08)',
        tension: 0.4, pointRadius: 4, borderWidth: 2, fill: true
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: isDark() ? '#1e293b' : '#fff',
        titleColor: isDark() ? '#e2e8f0' : '#1e293b',
        bodyColor: isDark() ? '#94a3b8' : '#475569',
        borderColor: isDark() ? '#334155' : '#e2e8f0',
        borderWidth: 1,
      }
    },
    scales: {
      x: { grid: { color: gridColor() }, ticks: { color: labelColor(), font: { size: 11, family: 'Poppins' } } },
      y: { min: 80, max: 100, grid: { color: gridColor() }, ticks: { color: labelColor(), font: { size: 11, family: 'Poppins' }, callback: v => v + '%' } }
    }
  }
});

document.getElementById('dark-toggle').addEventListener('click', () => {
  setTimeout(() => {
    attChart.options.scales.x.grid.color = gridColor();
    attChart.options.scales.y.grid.color = gridColor();
    attChart.options.scales.x.ticks.color = labelColor();
    attChart.options.scales.y.ticks.color = labelColor();
    attChart.update();
  }, 50);
});

// ── Tweaks ───────────────────────────────────────
const TWEAK_DEFAULTS = /*EDITMODE-BEGIN*/{
  "parentName": "Pedro Dela Cruz",
  "schoolYear": "2025–2026",
  "darkMode": false
}/*EDITMODE-END*/;

window.addEventListener('message', e => {
  if (e.data?.type === '__activate_edit_mode')   document.getElementById('tweaks-panel').classList.remove('hidden');
  if (e.data?.type === '__deactivate_edit_mode') document.getElementById('tweaks-panel').classList.add('hidden');
});
window.parent.postMessage({ type: '__edit_mode_available' }, '*');
</script>

<!-- Tweaks Panel -->
<div id="tweaks-panel" class="hidden fixed bottom-5 right-5 z-[999] w-64 rounded-xl bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border shadow-xl p-4 text-sm">
  <p class="text-xs font-bold text-slate-700 dark:text-white mb-3 uppercase tracking-wider">Tweaks</p>
  <div class="flex flex-col gap-3">
    <div>
      <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">Parent Name</label>
      <input value="Pedro Dela Cruz" class="w-full text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5 px-2.5 py-1.5 text-slate-700 dark:text-slate-300 focus:outline-none"/>
    </div>
    <div>
      <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">School Year</label>
      <input value="2025–2026" class="w-full text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5 px-2.5 py-1.5 text-slate-700 dark:text-slate-300 focus:outline-none"/>
    </div>
    <div>
      <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">Dark Mode</label>
      <button onclick="document.documentElement.classList.toggle('dark')" class="w-full text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5 px-2.5 py-1.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">Toggle Dark / Light</button>
    </div>
  </div>
</div>

</body>
</html>
