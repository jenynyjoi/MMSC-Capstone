<!DOCTYPE html>
<html lang="en" class="">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MMSC – Teacher Portal</title>
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
  .sidebar-transition { transition: transform 0.3s ease; }
</style>
</head>
<body class="flex h-screen overflow-hidden bg-slate-50 dark:bg-dark-bg">

<!-- ═══════════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════════════ -->
<aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 w-64 lg:static flex flex-col bg-white dark:bg-dark-card border-r border-slate-100 dark:border-dark-border">

  <!-- Logo -->
  <div class="flex h-16 items-center gap-2 px-5 bg-[#0d4c8f] dark:bg-[#091e42] shrink-0">
    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 shrink-0">
      <iconify-icon icon="solar:diploma-verified-bold" width="22" class="text-white"></iconify-icon>
    </div>
    <div class="flex flex-col leading-tight">
      <span class="text-lg font-bold text-white tracking-tight">MMSC</span>
      <span class="text-[11px] text-white/70 font-medium">Teacher Portal</span>
    </div>
  </div>

  <!-- Teacher Info -->
  <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-100 dark:border-dark-border">
    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 shrink-0">
      <iconify-icon icon="solar:user-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
    </div>
    <div class="flex flex-col leading-tight overflow-hidden">
      <span class="text-sm font-semibold text-slate-800 dark:text-white truncate">Ms. Ana Reyes</span>
      <span class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Math · Science</span>
    </div>
  </div>

  <!-- Nav -->
  <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-3 space-y-0.5">

    <!-- Dashboard -->
    <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 bg-blue-50 text-[#0d4c8f] dark:bg-[#0d4c8f]/10 dark:text-blue-300 text-sm font-medium">
      <iconify-icon icon="boxicons:dashboard-filled" width="18" class="shrink-0 text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
      Dashboard
    </a>

    <!-- My Classes -->
    <div>
      <button onclick="toggleNav('classes')" id="btn-classes"
        class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
        <iconify-icon icon="solar:book-2-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
        <span class="flex-1 text-left">My Classes</span>
        <iconify-icon id="arr-classes" icon="solar:alt-arrow-down-linear" width="14" class="transition-transform text-slate-400"></iconify-icon>
      </button>
      <div id="sub-classes" class="hidden mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-slate-700 space-y-0.5 py-1">
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Class List</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Class Rosters</a>
      </div>
    </div>

    <!-- Attendance -->
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="solar:calendar-check-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      Attendance
    </a>

    <!-- Grades -->
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="solar:medal-ribbons-star-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      Grades
    </a>

    <!-- Schedule -->
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="uis:schedule" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      Schedule
    </a>

    <!-- My Students -->
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="solar:users-group-rounded-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      My Students
    </a>

    <!-- Announcements -->
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="mdi:announcement" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      Announcements
    </a>

    <!-- Reports -->
    <div>
      <button onclick="toggleNav('reports')" id="btn-reports"
        class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
        <iconify-icon icon="mage:file-2-fill" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
        <span class="flex-1 text-left">Reports</span>
        <iconify-icon id="arr-reports" icon="solar:alt-arrow-down-linear" width="14" class="transition-transform text-slate-400"></iconify-icon>
      </button>
      <div id="sub-reports" class="hidden mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-slate-700 space-y-0.5 py-1">
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Attendance Record</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Class Record</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Grade Sheet</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Teacher Schedule</a>
      </div>
    </div>

    <!-- Settings -->
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

  <!-- Logout -->
  <div class="px-3 py-4 border-t border-slate-100 dark:border-dark-border">
    <button class="w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all">
      <iconify-icon icon="solar:logout-2-bold" width="18" class="shrink-0"></iconify-icon>
      Logout
    </button>
  </div>
</aside>

<!-- ═══════════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════════════ -->
<div class="flex flex-col flex-1 overflow-hidden">

  <!-- Topbar -->
  <header class="flex h-16 items-center justify-between px-6 bg-white dark:bg-dark-card border-b border-slate-100 dark:border-dark-border shrink-0">
    <div class="flex items-center gap-3">
      <button class="lg:hidden flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500">
        <iconify-icon icon="solar:hamburger-menu-linear" width="20"></iconify-icon>
      </button>
      <div>
        <h1 class="text-base font-bold text-slate-900 dark:text-white">Dashboard</h1>
        <p class="text-xs text-slate-400 dark:text-slate-500">Welcome back, Ms. Reyes. Here's today's overview.</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-blue-50 dark:bg-blue-900/20 px-3 py-1 text-xs font-medium text-[#0d4c8f] dark:text-blue-300">
        <iconify-icon icon="solar:calendar-linear" width="13"></iconify-icon>
        S.Y. 2025–2026
      </span>
      <!-- Notification -->
      <button class="relative flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
        <iconify-icon icon="solar:bell-linear" width="18"></iconify-icon>
        <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500"></span>
      </button>
      <!-- Dark toggle -->
      <button onclick="document.documentElement.classList.toggle('dark')"
        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
        <iconify-icon icon="solar:moon-stars-linear" width="18"></iconify-icon>
      </button>
      <!-- Avatar -->
      <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#0d4c8f] text-white text-sm font-semibold shrink-0">AR</div>
    </div>
  </header>

  <!-- Scrollable page -->
  <main class="flex-1 overflow-y-auto p-6 bg-slate-50/50 dark:bg-dark-bg">

    <!-- ── Stats Grid ── -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 mb-3">
              <iconify-icon icon="solar:book-2-bold" width="22" class="text-blue-500 dark:text-blue-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">My Classes</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">Active this term</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">6</h3>
        </div>
      </div>

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 dark:bg-green-900/20 mb-3">
              <iconify-icon icon="solar:users-group-rounded-linear" width="22" class="text-green-600 dark:text-green-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">My Students</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">Across all sections</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">148</h3>
        </div>
      </div>

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 mb-3">
              <iconify-icon icon="solar:pen-new-square-linear" width="22" class="text-yellow-500 dark:text-yellow-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Pending Grades</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">Awaiting submission</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-yellow-500 dark:text-yellow-400">14</h3>
        </div>
      </div>

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-teal-50 dark:bg-teal-900/20 mb-3">
              <iconify-icon icon="solar:chart-2-linear" width="22" class="text-teal-500 dark:text-teal-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Attendance Rate</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">This week average</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-teal-500 dark:text-teal-400">94%</h3>
        </div>
      </div>

    </div>

    <!-- ── Row 2: Quick Actions · Today's Schedule · Reminders ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

      <!-- Quick Actions -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center gap-2.5">
          <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
            <iconify-icon icon="solar:bolt-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
          </div>
          <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Quick Actions</h3>
        </div>
        <div class="flex flex-col gap-2.5">
          <button class="w-full rounded-lg border border-blue-400 py-2.5 text-sm font-medium text-blue-500 transition hover:bg-blue-50 dark:border-blue-500 dark:text-blue-400 dark:hover:bg-blue-900/20 flex items-center justify-center gap-2">
            <iconify-icon icon="solar:calendar-check-linear" width="16"></iconify-icon>Take Attendance
          </button>
          <button class="w-full rounded-lg border border-yellow-400 py-2.5 text-sm font-medium text-yellow-500 transition hover:bg-yellow-50 dark:border-yellow-500 dark:text-yellow-400 dark:hover:bg-yellow-900/20 flex items-center justify-center gap-2">
            <iconify-icon icon="solar:pen-2-linear" width="16"></iconify-icon>Enter Grades
          </button>
          <button class="w-full rounded-lg border border-green-400 py-2.5 text-sm font-medium text-green-600 transition hover:bg-green-50 dark:border-green-500 dark:text-green-400 dark:hover:bg-green-900/20 flex items-center justify-center gap-2">
            <iconify-icon icon="uis:schedule" width="16"></iconify-icon>View My Schedule
          </button>
          <button class="w-full rounded-lg border border-red-400 py-2.5 text-sm font-medium text-red-500 transition hover:bg-red-50 dark:border-red-500 dark:text-red-400 dark:hover:bg-red-900/20 flex items-center justify-center gap-2">
            <iconify-icon icon="solar:users-group-rounded-linear" width="16"></iconify-icon>View Class Roster
          </button>
        </div>
      </div>

      <!-- Today's Schedule -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
              <iconify-icon icon="uis:schedule" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            </div>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Today's Schedule</h3>
          </div>
          <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500">Mon, Apr 21</span>
        </div>
        <div class="flex flex-col gap-2">

          <!-- Period items -->
          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-blue-500"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Mathematics 7-A</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">7:00 – 8:00 AM · Room 201</p>
            </div>
            <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300">Done</span>
          </div>

          <div class="flex items-center gap-3 rounded-lg border border-[#0d4c8f]/30 dark:border-blue-500/40 bg-blue-50 dark:bg-blue-900/10 px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-[#0d4c8f]"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-300 truncate">Science 8-B</p>
              <p class="text-[11px] text-slate-500 dark:text-slate-400">8:00 – 9:00 AM · Room 105</p>
            </div>
            <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#0d4c8f] text-white dark:bg-blue-600">Now</span>
          </div>

          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-green-400"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Mathematics 9-C</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">10:00 – 11:00 AM · Room 203</p>
            </div>
            <span class="shrink-0 text-[10px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-400">Up next</span>
          </div>

          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-purple-400"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Science 7-B</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">1:00 – 2:00 PM · Room 105</p>
            </div>
            <span class="shrink-0 text-[10px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-400">Later</span>
          </div>

          <div class="flex items-center gap-3 rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-lg bg-orange-400"></div>
            <div class="ml-1 flex-1 min-w-0">
              <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">Advisory – Grade 8</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">3:00 – 3:30 PM · Room 108</p>
            </div>
            <span class="shrink-0 text-[10px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-slate-400">Later</span>
          </div>

        </div>
      </div>

      <!-- Reminders + Announcements -->
      <div class="flex flex-col gap-5">

        <!-- Reminders -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
          <div class="mb-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
                <iconify-icon icon="solar:bell-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
              </div>
              <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Reminders</h3>
            </div>
            <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 text-lg font-medium">+</button>
          </div>
          <div class="flex flex-col gap-2">
            <div class="flex items-center justify-between rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2.5 dark:border-yellow-800/40 dark:bg-yellow-900/10">
              <div class="flex items-center gap-2">
                <iconify-icon icon="solar:clock-circle-linear" width="15" class="text-yellow-500"></iconify-icon>
                <span class="text-xs text-slate-700 dark:text-slate-300">Submit Q3 grades by Friday</span>
              </div>
              <button class="text-slate-400 hover:text-red-500 transition-colors">
                <iconify-icon icon="solar:trash-bin-trash-linear" width="14"></iconify-icon>
              </button>
            </div>
            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 dark:border-dark-border dark:bg-white/5">
              <div class="flex items-center gap-2">
                <iconify-icon icon="solar:check-circle-linear" width="15" class="text-green-500"></iconify-icon>
                <span class="text-xs text-slate-700 dark:text-slate-300">Parent-Teacher Meeting Prep</span>
              </div>
              <button class="text-slate-400 hover:text-red-500 transition-colors">
                <iconify-icon icon="solar:trash-bin-trash-linear" width="14"></iconify-icon>
              </button>
            </div>
            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 dark:border-dark-border dark:bg-white/5">
              <div class="flex items-center gap-2">
                <iconify-icon icon="solar:clock-circle-linear" width="15" class="text-slate-400"></iconify-icon>
                <span class="text-xs text-slate-700 dark:text-slate-300">Update lesson plan – Week 18</span>
              </div>
              <button class="text-slate-400 hover:text-red-500 transition-colors">
                <iconify-icon icon="solar:trash-bin-trash-linear" width="14"></iconify-icon>
              </button>
            </div>
          </div>
        </div>

        <!-- Latest Announcement -->
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
          <div class="mb-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
                <iconify-icon icon="mdi:announcement" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
              </div>
              <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Announcements</h3>
            </div>
            <a href="#" class="flex h-7 items-center gap-1 px-2.5 rounded-lg border border-slate-200 text-[11px] font-medium text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
              View all <iconify-icon icon="solar:arrow-right-linear" width="11"></iconify-icon>
            </a>
          </div>
          <div class="flex flex-col gap-2">
            <div class="flex items-start gap-2 py-2 border-b border-slate-100 dark:border-dark-border last:border-0">
              <span class="mt-1.5 h-2 w-2 rounded-full bg-red-500 shrink-0"></span>
              <div>
                <p class="text-xs font-medium text-slate-700 dark:text-slate-300">No Classes – Rizal Day</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Apr 25 · Admin</p>
              </div>
            </div>
            <div class="flex items-start gap-2 py-2 border-b border-slate-100 dark:border-dark-border last:border-0">
              <span class="mt-1.5 h-2 w-2 rounded-full bg-yellow-400 shrink-0"></span>
              <div>
                <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Faculty Meeting – Apr 23</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Apr 20 · Admin</p>
              </div>
            </div>
            <div class="flex items-start gap-2 py-2">
              <span class="mt-1.5 h-2 w-2 rounded-full bg-green-500 shrink-0"></span>
              <div>
                <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Q3 Card Giving – Apr 30</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500">Apr 18 · Registrar</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- ── Row 3: Attendance Chart · Student Performance · Class Overview ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

      <!-- Attendance Trend -->
      <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <iconify-icon icon="solar:chart-2-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Weekly Attendance Trend</h3>
          </div>
          <div class="flex items-center gap-2">
            <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-[#0d4c8f]"></span><span class="text-[11px] text-slate-500 dark:text-slate-400">Present</span></div>
            <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-red-400"></span><span class="text-[11px] text-slate-500 dark:text-slate-400">Absent</span></div>
          </div>
        </div>
        <div class="relative h-52">
          <canvas id="attendanceChart"></canvas>
        </div>
      </div>

      <!-- Class Overview -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center gap-2">
          <iconify-icon icon="solar:book-2-bold" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
          <h3 class="text-sm font-semibold text-slate-900 dark:text-white">My Classes</h3>
        </div>
        <div class="flex flex-col gap-2.5">

          <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-dark-border px-3 py-2.5">
            <div>
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Math 7-A</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">28 students</p>
            </div>
            <span class="text-xs font-bold text-green-500">96%</span>
          </div>
          <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-dark-border px-3 py-2.5">
            <div>
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Science 8-B</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">30 students</p>
            </div>
            <span class="text-xs font-bold text-green-500">94%</span>
          </div>
          <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-dark-border px-3 py-2.5">
            <div>
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Math 9-C</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">26 students</p>
            </div>
            <span class="text-xs font-bold text-yellow-500">88%</span>
          </div>
          <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-dark-border px-3 py-2.5">
            <div>
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Science 7-B</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">27 students</p>
            </div>
            <span class="text-xs font-bold text-green-500">95%</span>
          </div>
          <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-dark-border px-3 py-2.5">
            <div>
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Math 8-A</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">29 students</p>
            </div>
            <span class="text-xs font-bold text-red-400">82%</span>
          </div>
          <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-dark-border px-3 py-2.5">
            <div>
              <p class="text-xs font-semibold text-slate-800 dark:text-white">Science 9-A</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">28 students</p>
            </div>
            <span class="text-xs font-bold text-green-500">97%</span>
          </div>

        </div>
      </div>
    </div>

    <!-- ── Row 4: Grade Status · Recent Students ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

      <!-- Grade Submission Status -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center gap-2">
          <iconify-icon icon="solar:medal-ribbons-star-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
          <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Grade Submission</h3>
        </div>

        <div class="mb-3">
          <div class="flex items-center justify-between mb-1.5">
            <span class="text-xs text-slate-600 dark:text-slate-400">Overall Progress</span>
            <span class="text-xs font-bold text-[#0d4c8f] dark:text-blue-300">4/6 submitted</span>
          </div>
          <div class="h-2 w-full rounded-full bg-slate-100 dark:bg-white/10">
            <div class="h-2 rounded-full bg-[#0d4c8f] transition-all duration-500" style="width:67%"></div>
          </div>
        </div>

        <div class="flex flex-col gap-2 mt-4">
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 dark:text-slate-400">Math 7-A</span>
            <span class="flex items-center gap-1 text-green-500 font-medium"><iconify-icon icon="solar:check-circle-bold" width="13"></iconify-icon>Submitted</span>
          </div>
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 dark:text-slate-400">Science 8-B</span>
            <span class="flex items-center gap-1 text-green-500 font-medium"><iconify-icon icon="solar:check-circle-bold" width="13"></iconify-icon>Submitted</span>
          </div>
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 dark:text-slate-400">Math 9-C</span>
            <span class="flex items-center gap-1 text-yellow-500 font-medium"><iconify-icon icon="solar:clock-circle-linear" width="13"></iconify-icon>In Progress</span>
          </div>
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 dark:text-slate-400">Science 7-B</span>
            <span class="flex items-center gap-1 text-green-500 font-medium"><iconify-icon icon="solar:check-circle-bold" width="13"></iconify-icon>Submitted</span>
          </div>
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 dark:text-slate-400">Math 8-A</span>
            <span class="flex items-center gap-1 text-red-400 font-medium"><iconify-icon icon="solar:close-circle-linear" width="13"></iconify-icon>Not yet</span>
          </div>
          <div class="flex items-center justify-between text-xs">
            <span class="text-slate-600 dark:text-slate-400">Science 9-A</span>
            <span class="flex items-center gap-1 text-green-500 font-medium"><iconify-icon icon="solar:check-circle-bold" width="13"></iconify-icon>Submitted</span>
          </div>
        </div>
      </div>

      <!-- Recent Students -->
      <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <iconify-icon icon="solar:users-group-rounded-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Students — At a Glance</h3>
          </div>
          <a href="#" class="flex h-7 items-center gap-1 px-2.5 rounded-lg border border-slate-200 text-[11px] font-medium text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
            View all <iconify-icon icon="solar:arrow-right-linear" width="11"></iconify-icon>
          </a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                <th class="pb-2.5 pr-4">Student</th>
                <th class="pb-2.5 pr-4">Section</th>
                <th class="pb-2.5 pr-4">Attendance</th>
                <th class="pb-2.5 pr-4">Grade</th>
                <th class="pb-2.5">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-2.5 pr-4">
                  <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-[10px] font-bold text-blue-600 dark:text-blue-300 shrink-0">JD</div>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Juan Dela Cruz</span>
                  </div>
                </td>
                <td class="py-2.5 pr-4 text-slate-500 dark:text-slate-400">Math 7-A</td>
                <td class="py-2.5 pr-4 text-green-500 font-semibold">98%</td>
                <td class="py-2.5 pr-4 font-semibold text-slate-700 dark:text-slate-300">92</td>
                <td class="py-2.5"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-semibold">Excellent</span></td>
              </tr>
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-2.5 pr-4">
                  <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center text-[10px] font-bold text-pink-600 dark:text-pink-300 shrink-0">MS</div>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Maria Santos</span>
                  </div>
                </td>
                <td class="py-2.5 pr-4 text-slate-500 dark:text-slate-400">Science 8-B</td>
                <td class="py-2.5 pr-4 text-yellow-500 font-semibold">85%</td>
                <td class="py-2.5 pr-4 font-semibold text-slate-700 dark:text-slate-300">78</td>
                <td class="py-2.5"><span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400 text-[10px] font-semibold">Needs Attn.</span></td>
              </tr>
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-2.5 pr-4">
                  <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-[10px] font-bold text-green-600 dark:text-green-300 shrink-0">AL</div>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Angelo Lopez</span>
                  </div>
                </td>
                <td class="py-2.5 pr-4 text-slate-500 dark:text-slate-400">Math 9-C</td>
                <td class="py-2.5 pr-4 text-green-500 font-semibold">100%</td>
                <td class="py-2.5 pr-4 font-semibold text-slate-700 dark:text-slate-300">97</td>
                <td class="py-2.5"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-semibold">Excellent</span></td>
              </tr>
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-2.5 pr-4">
                  <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-[10px] font-bold text-orange-600 dark:text-orange-300 shrink-0">RC</div>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Rosa Cruz</span>
                  </div>
                </td>
                <td class="py-2.5 pr-4 text-slate-500 dark:text-slate-400">Science 7-B</td>
                <td class="py-2.5 pr-4 text-red-400 font-semibold">72%</td>
                <td class="py-2.5 pr-4 font-semibold text-slate-700 dark:text-slate-300">65</td>
                <td class="py-2.5"><span class="px-2 py-0.5 rounded-full bg-red-100 text-red-500 dark:bg-red-900/30 dark:text-red-400 text-[10px] font-semibold">At Risk</span></td>
              </tr>
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-2.5 pr-4">
                  <div class="flex items-center gap-2">
                    <div class="h-7 w-7 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-[10px] font-bold text-purple-600 dark:text-purple-300 shrink-0">BG</div>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Ben Garcia</span>
                  </div>
                </td>
                <td class="py-2.5 pr-4 text-slate-500 dark:text-slate-400">Math 8-A</td>
                <td class="py-2.5 pr-4 text-green-500 font-semibold">93%</td>
                <td class="py-2.5 pr-4 font-semibold text-slate-700 dark:text-slate-300">85</td>
                <td class="py-2.5"><span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 text-[10px] font-semibold">Good</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <p class="mt-8 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

  </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Sidebar toggles ──────────────────────────────────────
function toggleNav(id) {
  const sub = document.getElementById('sub-' + id);
  const arr = document.getElementById('arr-' + id);
  const open = !sub.classList.contains('hidden');
  sub.classList.toggle('hidden', open);
  arr.style.transform = open ? '' : 'rotate(180deg)';
}

// ── Attendance Chart ─────────────────────────────────────
const isDark = () => document.documentElement.classList.contains('dark');
const gridColor = () => isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
const labelColor = () => isDark() ? '#94a3b8' : '#64748b';

const attCtx = document.getElementById('attendanceChart').getContext('2d');
const attChart = new Chart(attCtx, {
  type: 'bar',
  data: {
    labels: ['Mon','Tue','Wed','Thu','Fri','Mon','Tue','Wed','Thu','Fri'],
    datasets: [
      {
        label: 'Present',
        data: [138,141,135,143,140,139,144,137,145,142],
        backgroundColor: 'rgba(13,76,143,0.75)',
        borderRadius: 5,
        borderSkipped: false,
      },
      {
        label: 'Absent',
        data: [10,7,13,5,8,9,4,11,3,6],
        backgroundColor: 'rgba(248,113,113,0.7)',
        borderRadius: 5,
        borderSkipped: false,
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
      x: {
        stacked: true,
        grid: { color: gridColor() },
        ticks: { color: labelColor(), font: { size: 11, family: 'Poppins' } }
      },
      y: {
        stacked: true,
        grid: { color: gridColor() },
        ticks: { color: labelColor(), font: { size: 11, family: 'Poppins' } }
      }
    }
  }
});

// Re-render chart on dark toggle
document.querySelector('[onclick="document.documentElement.classList.toggle(\'dark\')"]')
  .addEventListener('click', () => {
    setTimeout(() => {
      attChart.options.scales.x.grid.color = gridColor();
      attChart.options.scales.y.grid.color = gridColor();
      attChart.options.scales.x.ticks.color = labelColor();
      attChart.options.scales.y.ticks.color = labelColor();
      attChart.update();
    }, 50);
  });

// ── Tweaks system ────────────────────────────────────────
const TWEAK_DEFAULTS = /*EDITMODE-BEGIN*/{
  "accentColor": "#0d4c8f",
  "teacherName": "Ms. Ana Reyes",
  "schoolYear": "2025–2026",
  "darkMode": false
}/*EDITMODE-END*/;

let tweaks = { ...TWEAK_DEFAULTS };

window.addEventListener('message', e => {
  if (e.data?.type === '__activate_edit_mode') showTweaks(true);
  if (e.data?.type === '__deactivate_edit_mode') showTweaks(false);
});
window.parent.postMessage({ type: '__edit_mode_available' }, '*');

function showTweaks(on) {
  document.getElementById('tweaks-panel').classList.toggle('hidden', !on);
}

function applyTweaks() {
  document.documentElement.style.setProperty('--accent', tweaks.accentColor);
  window.parent.postMessage({ type: '__edit_mode_set_keys', edits: tweaks }, '*');
}
</script>

<!-- Tweaks Panel -->
<div id="tweaks-panel" class="hidden fixed bottom-5 right-5 z-[999] w-64 rounded-xl bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border shadow-xl p-4 text-sm">
  <p class="text-xs font-bold text-slate-700 dark:text-white mb-3 uppercase tracking-wider">Tweaks</p>
  <div class="flex flex-col gap-3">
    <div>
      <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">Teacher Name</label>
      <input id="tw-name" value="Ms. Ana Reyes" class="w-full text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5 px-2.5 py-1.5 text-slate-700 dark:text-slate-300 focus:outline-none" oninput="document.querySelectorAll('.tw-teacher-name').forEach(el=>el.textContent=this.value)"/>
    </div>
    <div>
      <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">School Year</label>
      <input id="tw-sy" value="2025–2026" class="w-full text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5 px-2.5 py-1.5 text-slate-700 dark:text-slate-300 focus:outline-none"/>
    </div>
    <div>
      <label class="block text-[11px] font-medium text-slate-500 dark:text-slate-400 mb-1">Dark Mode</label>
      <button onclick="document.documentElement.classList.toggle('dark')" class="w-full text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5 px-2.5 py-1.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">Toggle Dark / Light</button>
    </div>
  </div>
</div>

</body>
</html>
