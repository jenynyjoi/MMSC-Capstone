<!DOCTYPE html>
<html lang="en" class="">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MMSC – Super Admin</title>
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
  .status-dot { width:8px; height:8px; border-radius:50%; display:inline-block; }
</style>
</head>
<body class="flex h-screen overflow-hidden bg-slate-50 dark:bg-dark-bg">

<!-- ═══════════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════════════ -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 lg:static flex flex-col bg-white dark:bg-dark-card border-r border-slate-100 dark:border-dark-border">

  <!-- Logo -->
  <div class="flex h-16 items-center gap-2 px-5 shrink-0" style="background: linear-gradient(135deg, #0a3a6e 0%, #0d4c8f 100%);">
    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-white/20 shrink-0">
      <iconify-icon icon="solar:diploma-verified-bold" width="22" class="text-white"></iconify-icon>
    </div>
    <div class="flex flex-col leading-tight">
      <span class="text-lg font-bold text-white tracking-tight">MMSC</span>
      <span class="text-[11px] font-semibold text-yellow-300">Super Admin</span>
    </div>
  </div>

  <!-- User chip -->
  <div class="flex items-center gap-3 px-4 py-4 border-b border-slate-100 dark:border-dark-border">
    <div class="relative shrink-0">
      <div class="flex h-9 w-9 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-sm font-bold text-yellow-600 dark:text-yellow-300">SA</div>
      <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-green-400 border-2 border-white dark:border-dark-card"></span>
    </div>
    <div class="flex flex-col leading-tight overflow-hidden">
      <span class="text-sm font-semibold text-slate-800 dark:text-white truncate">System Administrator</span>
      <span class="text-[11px] text-yellow-500 dark:text-yellow-400 truncate font-medium">Super Admin</span>
    </div>
  </div>

  <!-- Nav -->
  <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-3 space-y-0.5">

    <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 bg-blue-50 text-[#0d4c8f] dark:bg-[#0d4c8f]/10 dark:text-blue-300 text-sm font-medium">
      <iconify-icon icon="boxicons:dashboard-filled" width="18" class="shrink-0"></iconify-icon>
      Dashboard
    </a>

    <!-- Users -->
    <div>
      <button onclick="toggleNav('users')" id="btn-users"
        class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
        <iconify-icon icon="solar:users-group-rounded-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
        <span class="flex-1 text-left">User Management</span>
        <iconify-icon id="arr-users" icon="solar:alt-arrow-down-linear" width="14" class="transition-transform text-slate-400"></iconify-icon>
      </button>
      <div id="sub-users" class="hidden mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-slate-700 space-y-0.5 py-1">
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">All Users</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Roles & Permissions</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Admins</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Teachers</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Students</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Parents</a>
      </div>
    </div>

    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="solar:calendar-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
      School Year Config
    </a>

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
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Enrollment Summary</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">System Audit Log</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">User Activity</a>
      </div>
    </div>

    <!-- System -->
    <div>
      <button onclick="toggleNav('system')" id="btn-system"
        class="group w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
        <iconify-icon icon="solar:server-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
        <span class="flex-1 text-left">System</span>
        <iconify-icon id="arr-system" icon="solar:alt-arrow-down-linear" width="14" class="transition-transform text-slate-400"></iconify-icon>
      </button>
      <div id="sub-system" class="hidden mt-0.5 ml-8 pl-3 border-l-2 border-slate-200 dark:border-slate-700 space-y-0.5 py-1">
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">System Health</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Backup & Restore</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Error Logs</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">Email Config</a>
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
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">General Settings</a>
        <a href="#" class="block px-3 py-2 text-xs font-medium text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 dark:hover:text-blue-300 transition-colors">My Account</a>
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
      <h1 class="text-base font-bold text-slate-900 dark:text-white">System Dashboard</h1>
      <p class="text-xs text-slate-400 dark:text-slate-500">Full system overview · S.Y. 2025–2026</p>
    </div>
    <div class="flex items-center gap-2">
      <!-- System status badge -->
      <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-green-50 dark:bg-green-900/20 px-3 py-1 text-xs font-medium text-green-600 dark:text-green-400">
        <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
        All Systems Operational
      </span>
      <button class="relative flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
        <iconify-icon icon="solar:bell-linear" width="18"></iconify-icon>
        <span class="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-red-500"></span>
      </button>
      <button id="dark-toggle" onclick="document.documentElement.classList.toggle('dark')"
        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
        <iconify-icon icon="solar:moon-stars-linear" width="18"></iconify-icon>
      </button>
      <div class="flex h-9 w-9 items-center justify-center rounded-full text-white text-sm font-bold shrink-0" style="background: linear-gradient(135deg,#0d4c8f,#1a73e8);">SA</div>
    </div>
  </header>

  <!-- Scrollable -->
  <main class="flex-1 overflow-y-auto p-6 bg-slate-50/50 dark:bg-dark-bg">

    <!-- ── Stats Row 1 ── -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 mb-3">
              <iconify-icon icon="solar:users-group-rounded-linear" width="22" class="text-blue-500 dark:text-blue-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Total Students</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">All enrolled</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">1,284</h3>
        </div>
        <p class="mt-2 text-[11px] text-green-500 flex items-center gap-1"><iconify-icon icon="solar:arrow-up-linear" width="12"></iconify-icon>+42 this year</p>
      </div>

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 mb-3">
              <iconify-icon icon="fa6-solid:user-tie" width="20" class="text-yellow-500 dark:text-yellow-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Total Teachers</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">Active staff</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">58</h3>
        </div>
        <p class="mt-2 text-[11px] text-green-500 flex items-center gap-1"><iconify-icon icon="solar:arrow-up-linear" width="12"></iconify-icon>+4 this year</p>
      </div>

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 dark:bg-green-900/20 mb-3">
              <iconify-icon icon="solar:widget-linear" width="22" class="text-green-600 dark:text-green-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Active Sections</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">All grade levels</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">36</h3>
        </div>
        <p class="mt-2 text-[11px] text-slate-400 flex items-center gap-1"><iconify-icon icon="solar:minus-linear" width="12"></iconify-icon>Same as last year</p>
      </div>

      <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.08)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/20 mb-3">
              <iconify-icon icon="solar:users-group-two-rounded-linear" width="22" class="text-purple-500 dark:text-purple-300"></iconify-icon>
            </div>
            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Registered Users</p>
            <p class="text-xs text-slate-400 dark:text-slate-500">All portal accounts</p>
          </div>
          <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">2,741</h3>
        </div>
        <p class="mt-2 text-[11px] text-green-500 flex items-center gap-1"><iconify-icon icon="solar:arrow-up-linear" width="12"></iconify-icon>+118 this month</p>
      </div>

    </div>

    <!-- ── Row 2: System Health + User Breakdown + Quick Actions ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

      <!-- System Health -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center gap-2.5">
          <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
            <iconify-icon icon="solar:server-bold" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
          </div>
          <h3 class="text-sm font-semibold text-slate-900 dark:text-white">System Health</h3>
        </div>

        <div class="flex flex-col gap-3">
          <div class="flex items-center justify-between rounded-lg border border-green-100 dark:border-green-900/30 bg-green-50 dark:bg-green-900/10 px-3 py-2.5">
            <div class="flex items-center gap-2">
              <span class="status-dot bg-green-500"></span>
              <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">Web Server</span>
            </div>
            <span class="text-[11px] font-semibold text-green-500">Operational</span>
          </div>
          <div class="flex items-center justify-between rounded-lg border border-green-100 dark:border-green-900/30 bg-green-50 dark:bg-green-900/10 px-3 py-2.5">
            <div class="flex items-center gap-2">
              <span class="status-dot bg-green-500"></span>
              <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">Database</span>
            </div>
            <span class="text-[11px] font-semibold text-green-500">Operational</span>
          </div>
          <div class="flex items-center justify-between rounded-lg border border-green-100 dark:border-green-900/30 bg-green-50 dark:bg-green-900/10 px-3 py-2.5">
            <div class="flex items-center gap-2">
              <span class="status-dot bg-green-500"></span>
              <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">Email Service</span>
            </div>
            <span class="text-[11px] font-semibold text-green-500">Operational</span>
          </div>
          <div class="flex items-center justify-between rounded-lg border border-yellow-100 dark:border-yellow-900/30 bg-yellow-50 dark:bg-yellow-900/10 px-3 py-2.5">
            <div class="flex items-center gap-2">
              <span class="status-dot bg-yellow-400"></span>
              <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">File Storage</span>
            </div>
            <span class="text-[11px] font-semibold text-yellow-500">78% Used</span>
          </div>
          <div class="flex items-center justify-between rounded-lg border border-green-100 dark:border-green-900/30 bg-green-50 dark:bg-green-900/10 px-3 py-2.5">
            <div class="flex items-center gap-2">
              <span class="status-dot bg-green-500"></span>
              <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">Backup Service</span>
            </div>
            <span class="text-[11px] font-semibold text-green-500">Last: Today 3AM</span>
          </div>
        </div>

        <!-- Uptime -->
        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-dark-border">
          <div class="flex justify-between mb-1.5">
            <span class="text-[11px] text-slate-500 dark:text-slate-400">System Uptime (30 days)</span>
            <span class="text-[11px] font-bold text-green-500">99.8%</span>
          </div>
          <div class="h-2 w-full rounded-full bg-slate-100 dark:bg-white/10">
            <div class="h-2 rounded-full bg-green-500" style="width:99.8%"></div>
          </div>
        </div>
      </div>

      <!-- User Breakdown -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center gap-2.5">
          <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
            <iconify-icon icon="solar:pie-chart-2-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
          </div>
          <h3 class="text-sm font-semibold text-slate-900 dark:text-white">User Breakdown</h3>
        </div>
        <div class="relative h-44 flex items-center justify-center">
          <canvas id="userBreakdownChart"></canvas>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2">
          <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-blue-500 shrink-0"></span><span class="text-xs text-slate-500 dark:text-slate-400">Students (1,284)</span></div>
          <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-yellow-400 shrink-0"></span><span class="text-xs text-slate-500 dark:text-slate-400">Teachers (58)</span></div>
          <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-green-500 shrink-0"></span><span class="text-xs text-slate-500 dark:text-slate-400">Parents (1,381)</span></div>
          <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-red-400 shrink-0"></span><span class="text-xs text-slate-500 dark:text-slate-400">Admins (18)</span></div>
        </div>
      </div>

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
            <iconify-icon icon="solar:user-plus-rounded-linear" width="16"></iconify-icon>Add New User
          </button>
          <button class="w-full rounded-lg border border-green-400 py-2.5 text-sm font-medium text-green-600 transition hover:bg-green-50 dark:border-green-500 dark:text-green-400 dark:hover:bg-green-900/20 flex items-center justify-center gap-2">
            <iconify-icon icon="solar:database-linear" width="16"></iconify-icon>Backup Now
          </button>
          <button class="w-full rounded-lg border border-purple-400 py-2.5 text-sm font-medium text-purple-500 transition hover:bg-purple-50 dark:border-purple-500 dark:text-purple-400 dark:hover:bg-purple-900/20 flex items-center justify-center gap-2">
            <iconify-icon icon="mage:file-2-fill" width="16"></iconify-icon>Generate Report
          </button>
          <button class="w-full rounded-lg border border-red-400 py-2.5 text-sm font-medium text-red-500 transition hover:bg-red-50 dark:border-red-500 dark:text-red-400 dark:hover:bg-red-900/20 flex items-center justify-center gap-2">
            <iconify-icon icon="mdi:announcement" width="16"></iconify-icon>Post Announcement
          </button>
        </div>
      </div>

    </div>

    <!-- ── Row 3: Enrollment Chart + Recent Audit Log ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

      <!-- Enrollment Chart -->
      <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <iconify-icon icon="solar:chart-square-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Enrollment per Grade Level</h3>
          </div>
          <select class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-2.5 py-1.5 text-xs text-slate-600 dark:text-slate-400 focus:outline-none">
            <option>S.Y. 2025–2026</option>
            <option>S.Y. 2024–2025</option>
          </select>
        </div>
        <div class="relative h-52">
          <canvas id="enrollmentChart"></canvas>
        </div>
      </div>

      <!-- Audit Log -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2.5">
            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
              <iconify-icon icon="solar:document-text-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            </div>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Recent Activity</h3>
          </div>
          <a href="#" class="flex h-7 items-center gap-1 px-2.5 rounded-lg border border-slate-200 text-[11px] font-medium text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5">
            View all <iconify-icon icon="solar:arrow-right-linear" width="11"></iconify-icon>
          </a>
        </div>
        <div class="flex flex-col gap-2.5">

          <div class="flex items-start gap-2.5">
            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 shrink-0 mt-0.5">
              <iconify-icon icon="solar:user-plus-rounded-linear" width="13" class="text-blue-500"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-medium text-slate-700 dark:text-slate-300">New teacher account created</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Mr. Jose Ramos · Admin · 2 hrs ago</p>
            </div>
          </div>

          <div class="flex items-start gap-2.5">
            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 shrink-0 mt-0.5">
              <iconify-icon icon="solar:database-linear" width="13" class="text-green-500"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Automated backup completed</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">System · Today 3:00 AM</p>
            </div>
          </div>

          <div class="flex items-start gap-2.5">
            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30 shrink-0 mt-0.5">
              <iconify-icon icon="solar:settings-linear" width="13" class="text-yellow-500"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-medium text-slate-700 dark:text-slate-300">General settings updated</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Super Admin · Yesterday 4:12 PM</p>
            </div>
          </div>

          <div class="flex items-start gap-2.5">
            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30 shrink-0 mt-0.5">
              <iconify-icon icon="solar:calendar-add-linear" width="13" class="text-purple-500"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-medium text-slate-700 dark:text-slate-300">School year 2025–2026 opened</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Super Admin · Apr 1, 2025</p>
            </div>
          </div>

          <div class="flex items-start gap-2.5">
            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 shrink-0 mt-0.5">
              <iconify-icon icon="solar:trash-bin-trash-linear" width="13" class="text-red-400"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Inactive accounts archived (12)</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Super Admin · Mar 28, 2025</p>
            </div>
          </div>

          <div class="flex items-start gap-2.5">
            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-teal-100 dark:bg-teal-900/30 shrink-0 mt-0.5">
              <iconify-icon icon="solar:letter-linear" width="13" class="text-teal-500"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-medium text-slate-700 dark:text-slate-300">Email config updated (SMTP)</p>
              <p class="text-[11px] text-slate-400 dark:text-slate-500">Super Admin · Mar 25, 2025</p>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- ── Row 4: Admin Accounts · User Growth ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

      <!-- User Growth Chart -->
      <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <iconify-icon icon="solar:chart-2-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">User Registration Growth</h3>
          </div>
          <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-blue-500"></span><span class="text-[11px] text-slate-500 dark:text-slate-400">Students</span></div>
            <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-green-500"></span><span class="text-[11px] text-slate-500 dark:text-slate-400">Parents</span></div>
          </div>
        </div>
        <div class="relative h-52">
          <canvas id="growthChart"></canvas>
        </div>
      </div>

      <!-- Admin Accounts -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <iconify-icon icon="solar:shield-user-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Admin Accounts</h3>
          </div>
          <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 text-lg font-medium">+</button>
        </div>
        <div class="flex flex-col gap-2.5">

          <div class="flex items-center justify-between rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5">
            <div class="flex items-center gap-2">
              <div class="flex h-7 w-7 items-center justify-center rounded-full bg-[#0d4c8f] text-white text-[10px] font-bold shrink-0">SA</div>
              <div>
                <p class="text-xs font-semibold text-slate-800 dark:text-white">System Admin</p>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">Super Admin</p>
              </div>
            </div>
            <span class="h-2 w-2 rounded-full bg-green-400"></span>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5">
            <div class="flex items-center gap-2">
              <div class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 text-[10px] font-bold shrink-0">RP</div>
              <div>
                <p class="text-xs font-semibold text-slate-800 dark:text-white">Rosa Perez</p>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">School Admin</p>
              </div>
            </div>
            <span class="h-2 w-2 rounded-full bg-green-400"></span>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5">
            <div class="flex items-center gap-2">
              <div class="flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-300 text-[10px] font-bold shrink-0">MB</div>
              <div>
                <p class="text-xs font-semibold text-slate-800 dark:text-white">Mario Bautista</p>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">Registrar</p>
              </div>
            </div>
            <span class="h-2 w-2 rounded-full bg-green-400"></span>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5">
            <div class="flex items-center gap-2">
              <div class="flex h-7 w-7 items-center justify-center rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-300 text-[10px] font-bold shrink-0">LT</div>
              <div>
                <p class="text-xs font-semibold text-slate-800 dark:text-white">Luz Torres</p>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">Cashier</p>
              </div>
            </div>
            <span class="h-2 w-2 rounded-full bg-yellow-400"></span>
          </div>

          <div class="flex items-center justify-between rounded-lg border border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.03] px-3 py-2.5">
            <div class="flex items-center gap-2">
              <div class="flex h-7 w-7 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-300 text-[10px] font-bold shrink-0">DC</div>
              <div>
                <p class="text-xs font-semibold text-slate-800 dark:text-white">Dan Cruz</p>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">Librarian</p>
              </div>
            </div>
            <span class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-600"></span>
          </div>

        </div>
        <a href="#" class="mt-3 block text-center text-[11px] text-[#0d4c8f] dark:text-blue-400 hover:underline font-medium">View all 18 admins →</a>
      </div>
    </div>

    <!-- ── Row 5: Announcements + Pending Items ── -->
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">

      <!-- Pending Clearance Actions -->
      <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center gap-2.5">
          <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
            <iconify-icon icon="solar:bell-linear" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
          </div>
          <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Pending Items</h3>
        </div>
        <div class="flex flex-col gap-2.5">
          <div class="flex items-center justify-between rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2.5 dark:border-yellow-800/40 dark:bg-yellow-900/10">
            <div class="flex items-center gap-2">
              <iconify-icon icon="solar:user-plus-rounded-linear" width="15" class="text-yellow-500"></iconify-icon>
              <span class="text-xs text-slate-700 dark:text-slate-300">New Admission Requests</span>
            </div>
            <span class="text-xs font-bold text-yellow-600 dark:text-yellow-400">12</span>
          </div>
          <div class="flex items-center justify-between rounded-lg border border-red-100 bg-red-50 px-3 py-2.5 dark:border-red-900/30 dark:bg-red-900/10">
            <div class="flex items-center gap-2">
              <iconify-icon icon="solar:wallet-linear" width="15" class="text-red-400"></iconify-icon>
              <span class="text-xs text-slate-700 dark:text-slate-300">Unpaid Finance Clearance</span>
            </div>
            <span class="text-xs font-bold text-red-500">38</span>
          </div>
          <div class="flex items-center justify-between rounded-lg border border-blue-100 bg-blue-50 px-3 py-2.5 dark:border-blue-900/30 dark:bg-blue-900/10">
            <div class="flex items-center gap-2">
              <iconify-icon icon="solar:pen-2-linear" width="15" class="text-blue-500"></iconify-icon>
              <span class="text-xs text-slate-700 dark:text-slate-300">Grade Submissions Due</span>
            </div>
            <span class="text-xs font-bold text-blue-600 dark:text-blue-400">8</span>
          </div>
          <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 dark:border-dark-border dark:bg-white/5">
            <div class="flex items-center gap-2">
              <iconify-icon icon="solar:book-linear" width="15" class="text-slate-500"></iconify-icon>
              <span class="text-xs text-slate-700 dark:text-slate-300">Overdue Library Books</span>
            </div>
            <span class="text-xs font-bold text-slate-600 dark:text-slate-400">5</span>
          </div>
          <div class="flex items-center justify-between rounded-lg border border-purple-100 bg-purple-50 px-3 py-2.5 dark:border-purple-900/30 dark:bg-purple-900/10">
            <div class="flex items-center gap-2">
              <iconify-icon icon="solar:user-id-linear" width="15" class="text-purple-500"></iconify-icon>
              <span class="text-xs text-slate-700 dark:text-slate-300">Inactive User Accounts</span>
            </div>
            <span class="text-xs font-bold text-purple-600 dark:text-purple-400">24</span>
          </div>
        </div>
      </div>

      <!-- Announcements -->
      <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-dark-border dark:bg-dark-card">
        <div class="mb-4 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <iconify-icon icon="mdi:announcement" width="18" class="text-slate-700 dark:text-slate-300"></iconify-icon>
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Latest Announcements</h3>
          </div>
          <a href="#" class="flex h-7 items-center gap-1.5 px-3 rounded-lg border border-slate-200 text-[11px] font-medium text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5">
            View all <iconify-icon icon="solar:arrow-right-linear" width="11"></iconify-icon>
          </a>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                <th class="pb-2.5 pr-4">Title</th>
                <th class="pb-2.5 pr-4">Date</th>
                <th class="pb-2.5 pr-4">Posted By</th>
                <th class="pb-2.5 pr-4">Audience</th>
                <th class="pb-2.5 text-right">Priority</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-3 pr-4">
                  <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-red-500 shrink-0"></span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">No Classes – Rizal Day</span>
                  </div>
                </td>
                <td class="py-3 pr-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">Apr 25, 2025</td>
                <td class="py-3 pr-4 text-slate-600 dark:text-slate-300">Admin</td>
                <td class="py-3 pr-4"><span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-400 text-[10px] font-medium">All</span></td>
                <td class="py-3 text-right"><span class="px-2 py-0.5 rounded-full bg-red-100 text-red-500 dark:bg-red-900/30 dark:text-red-400 text-[10px] font-semibold">High</span></td>
              </tr>
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-3 pr-4">
                  <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-yellow-400 shrink-0"></span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Faculty Meeting – Apr 23</span>
                  </div>
                </td>
                <td class="py-3 pr-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">Apr 20, 2025</td>
                <td class="py-3 pr-4 text-slate-600 dark:text-slate-300">Super Admin</td>
                <td class="py-3 pr-4"><span class="px-2 py-0.5 rounded-full bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400 text-[10px] font-medium">Teachers</span></td>
                <td class="py-3 text-right"><span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400 text-[10px] font-semibold">Medium</span></td>
              </tr>
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-3 pr-4">
                  <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-green-500 shrink-0"></span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Q3 Card Giving – Apr 30</span>
                  </div>
                </td>
                <td class="py-3 pr-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">Apr 18, 2025</td>
                <td class="py-3 pr-4 text-slate-600 dark:text-slate-300">Registrar</td>
                <td class="py-3 pr-4"><span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-400 text-[10px] font-medium">All</span></td>
                <td class="py-3 text-right"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-semibold">Normal</span></td>
              </tr>
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-3 pr-4">
                  <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-blue-500 shrink-0"></span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">System Maintenance – May 2</span>
                  </div>
                </td>
                <td class="py-3 pr-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">Apr 15, 2025</td>
                <td class="py-3 pr-4 text-slate-600 dark:text-slate-300">Super Admin</td>
                <td class="py-3 pr-4"><span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-medium">Admins</span></td>
                <td class="py-3 text-right"><span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400 text-[10px] font-semibold">Medium</span></td>
              </tr>
              <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                <td class="py-3 pr-4">
                  <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-green-500 shrink-0"></span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Summer Enrollment Open</span>
                  </div>
                </td>
                <td class="py-3 pr-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">Apr 10, 2025</td>
                <td class="py-3 pr-4 text-slate-600 dark:text-slate-300">Admin</td>
                <td class="py-3 pr-4"><span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-400 text-[10px] font-medium">All</span></td>
                <td class="py-3 text-right"><span class="px-2 py-0.5 rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-semibold">Normal</span></td>
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
// ── Sidebar toggles ──────────────────────────────
function toggleNav(id) {
  const sub = document.getElementById('sub-' + id);
  const arr = document.getElementById('arr-' + id);
  if (!sub) return;
  const open = !sub.classList.contains('hidden');
  sub.classList.toggle('hidden', open);
  arr.style.transform = open ? '' : 'rotate(180deg)';
}

const isDark = () => document.documentElement.classList.contains('dark');
const gridColor = () => isDark() ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
const labelColor = () => isDark() ? '#94a3b8' : '#64748b';

// ── User Breakdown Donut ─────────────────────────
const dCtx = document.getElementById('userBreakdownChart').getContext('2d');
const donutChart = new Chart(dCtx, {
  type: 'doughnut',
  data: {
    labels: ['Students','Teachers','Parents','Admins'],
    datasets: [{
      data: [1284, 58, 1381, 18],
      backgroundColor: ['#3b82f6','#facc15','#22c55e','#f87171'],
      borderWidth: 0,
      hoverOffset: 6,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false, cutout: '70%',
    plugins: {
      legend: { display: false },
      tooltip: {
        backgroundColor: isDark() ? '#1e293b' : '#fff',
        titleColor: isDark() ? '#e2e8f0' : '#1e293b',
        bodyColor: isDark() ? '#94a3b8' : '#475569',
        borderColor: isDark() ? '#334155' : '#e2e8f0',
        borderWidth: 1,
      }
    }
  }
});

// ── Enrollment Chart ─────────────────────────────
const eCtx = document.getElementById('enrollmentChart').getContext('2d');
const enrollChart = new Chart(eCtx, {
  type: 'bar',
  data: {
    labels: ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10'],
    datasets: [{
      label: 'Students',
      data: [72, 108, 115, 122, 118, 110, 105, 145, 148, 138, 103],
      backgroundColor: 'rgba(13,76,143,0.8)',
      borderRadius: 5,
      borderSkipped: false,
    }]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
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
      x: { grid: { color: gridColor() }, ticks: { color: labelColor(), font: { size: 10, family: 'Poppins' } } },
      y: { grid: { color: gridColor() }, ticks: { color: labelColor(), font: { size: 11, family: 'Poppins' } } }
    }
  }
});

// ── Growth Chart ─────────────────────────────────
const gCtx = document.getElementById('growthChart').getContext('2d');
const growthChart = new Chart(gCtx, {
  type: 'line',
  data: {
    labels: ['Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar','Apr'],
    datasets: [
      {
        label: 'Students',
        data: [1180, 1210, 1222, 1230, 1235, 1250, 1262, 1275, 1284],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59,130,246,0.08)',
        tension: 0.4, pointRadius: 4, borderWidth: 2, fill: true,
      },
      {
        label: 'Parents',
        data: [1100, 1160, 1195, 1220, 1240, 1290, 1320, 1355, 1381],
        borderColor: '#22c55e',
        backgroundColor: 'rgba(34,197,94,0.07)',
        tension: 0.4, pointRadius: 4, borderWidth: 2, fill: true,
      }
    ]
  },
  options: {
    responsive: true, maintainAspectRatio: false,
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
      y: { grid: { color: gridColor() }, ticks: { color: labelColor(), font: { size: 11, family: 'Poppins' } } }
    }
  }
});

// Update charts on dark toggle
document.getElementById('dark-toggle').addEventListener('click', () => {
  setTimeout(() => {
    [enrollChart, growthChart].forEach(c => {
      c.options.scales.x.grid.color = gridColor();
      c.options.scales.y.grid.color = gridColor();
      c.options.scales.x.ticks.color = labelColor();
      c.options.scales.y.ticks.color = labelColor();
      c.update();
    });
  }, 50);
});

// ── Tweaks ───────────────────────────────────────
const TWEAK_DEFAULTS = /*EDITMODE-BEGIN*/{
  "adminName": "System Administrator",
  "schoolYear": "2025–2026",
  "darkMode": false
}/*EDITMODE-END*/;

window.addEventListener('message', e => {
  if (e.data?.type === '__activate_edit_mode')   document.getElementById('tweaks-panel').classList.remove('hidden');
  if (e.data?.type === '__deactivate_edit_mode') document.getElementById('tweaks-panel').classList.add('hidden');
});
window.parent.postMessage({ type: '__edit_mode_available' }, '*');
</script>


</div>

</body>
</html>
