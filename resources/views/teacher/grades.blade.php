<!DOCTYPE html>
<html lang="en" class="">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MMSC – Teacher Grades</title>
<link rel="stylesheet" href="https://unpkg.com/tailwindcss@3.4.16/dist/tailwind.min.css"/>
<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<style>
  .dark\:bg-dark-bg { background-color: #0b1224; }
  .dark\:bg-dark-card { background-color: #111827; }
  .dark\:border-dark-border { border-color: #1e2d45; }
  .bg-dark-bg { background-color: #0b1224; }
  .bg-dark-card { background-color: #111827; }
  .border-dark-border { border-color: #1e2d45; }
</style>
<style>
  * { font-family: 'Poppins', sans-serif; }
  .no-scrollbar::-webkit-scrollbar{display:none;} .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none;}

  /* Grade table */
  .grade-table { border-collapse: collapse; min-width: 100%; }
  .grade-table th, .grade-table td {
    border: 1px solid #e2e8f0;
    padding: 0;
    text-align: center;
    font-size: 11px;
    white-space: nowrap;
  }
  .dark .grade-table th, .dark .grade-table td { border-color: #1e2d45; }
  .grade-table input {
    width: 100%; min-width: 32px; height: 28px;
    border: none; background: transparent;
    text-align: center; font-size: 11px;
    font-family: 'Poppins', sans-serif;
    color: #1e293b; outline: none;
    padding: 0 2px;
  }
  .dark .grade-table input { color: #e2e8f0; }
  .grade-table input:focus { background: #eff6ff; }
  .dark .grade-table input:focus { background: rgba(13,76,143,0.15); }

  /* Section header rows */
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

  /* class tab */
  .class-tab { transition: all .15s; }
  .class-tab.active { background: #0d4c8f; color: #fff; border-color: #0d4c8f; }
</style>

<template id="__bundler_thumbnail" data-bg-color="#0b1224">
  <svg viewBox="0 0 1200 800" xmlns="http://www.w3.org/2000/svg">
    <rect width="1200" height="800" fill="#0b1224"/>
    <!-- Sidebar -->
    <rect x="0" y="0" width="220" height="800" fill="#111827"/>
    <rect x="0" y="0" width="220" height="56" fill="#0d4c8f"/>
    <circle cx="34" cy="28" r="16" fill="rgba(255,255,255,0.2)"/>
    <rect x="58" y="18" width="60" height="10" rx="3" fill="white" opacity="0.9"/>
    <rect x="58" y="32" width="40" height="7" rx="2" fill="white" opacity="0.5"/>
    <!-- Nav items -->
    <rect x="12" y="70" width="196" height="28" rx="6" fill="#0d4c8f" opacity="0.4"/>
    <rect x="12" y="105" width="196" height="24" rx="5" fill="rgba(255,255,255,0.05)"/>
    <rect x="12" y="135" width="196" height="24" rx="5" fill="#0d4c8f"/>
    <rect x="12" y="165" width="196" height="24" rx="5" fill="rgba(255,255,255,0.05)"/>
    <!-- Main area -->
    <rect x="220" y="0" width="980" height="56" fill="#111827"/>
    <!-- Quick class buttons -->
    <rect x="240" y="72" width="90" height="36" rx="8" fill="#0d4c8f"/>
    <rect x="338" y="72" width="90" height="36" rx="8" fill="#1e2d45"/>
    <rect x="436" y="72" width="90" height="36" rx="8" fill="#1e2d45"/>
    <rect x="534" y="72" width="90" height="36" rx="8" fill="#1e2d45"/>
    <!-- Filter bar -->
    <rect x="240" y="120" width="940" height="60" rx="10" fill="#111827" stroke="#1e2d45" stroke-width="1"/>
    <rect x="260" y="136" width="100" height="28" rx="6" fill="#1e2d45"/>
    <rect x="370" y="136" width="100" height="28" rx="6" fill="#1e2d45"/>
    <rect x="480" y="136" width="100" height="28" rx="6" fill="#1e2d45"/>
    <rect x="590" y="136" width="100" height="28" rx="6" fill="#1e2d45"/>
    <rect x="1090" y="136" width="80" height="28" rx="6" fill="#0d4c8f"/>
    <!-- Grade table -->
    <rect x="240" y="196" width="940" height="560" rx="10" fill="#111827" stroke="#1e2d45" stroke-width="1"/>
    <rect x="240" y="196" width="940" height="36" rx="10" fill="#0d4c8f"/>
    <rect x="240" y="232" width="940" height="24" fill="#ea580c" opacity="0.7"/>
    <rect x="240" y="256" width="940" height="20" fill="#1a2744"/>
    <!-- Student rows -->
    <rect x="240" y="276" width="940" height="20" fill="#1e3a5f" opacity="0.5"/>
    <rect x="240" y="296" width="940" height="18" fill="rgba(255,255,255,0.02)"/>
    <rect x="240" y="314" width="940" height="18" fill="rgba(255,255,255,0.04)"/>
    <rect x="240" y="332" width="940" height="18" fill="rgba(255,255,255,0.02)"/>
    <rect x="240" y="350" width="940" height="18" fill="rgba(255,255,255,0.04)"/>
    <rect x="240" y="368" width="940" height="18" fill="rgba(255,255,255,0.02)"/>
    <!-- Medal icon -->
    <circle cx="600" cy="550" r="60" fill="#0d4c8f" opacity="0.15"/>
    <text x="600" y="566" text-anchor="middle" font-size="48" fill="#0d4c8f" opacity="0.6">🏅</text>
  </svg>
</template>
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
    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 transition-all"><iconify-icon icon="solar:calendar-check-bold" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>Attendance</a>
    <!-- Grades active -->
    <a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2.5 bg-blue-50 text-[#0d4c8f] dark:bg-[#0d4c8f]/10 dark:text-blue-300 text-sm font-medium"><iconify-icon icon="solar:medal-ribbons-star-bold" width="18" class="shrink-0"></iconify-icon>Grades</a>
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
      <h1 class="text-base font-bold text-slate-900 dark:text-white">Grade Encoding</h1>
      <p class="text-xs text-slate-400 dark:text-slate-500">Input and manage student grades per subject</p>
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

  <!-- Scrollable -->
  <main class="flex-1 overflow-y-auto p-5 bg-slate-50/50 dark:bg-dark-bg">

    <!-- ── Quick Class Buttons ── -->
    <div class="mb-4">
      <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide mb-2">My Classes — Quick Select</p>
      <div class="flex flex-wrap gap-2">
        <button onclick="selectClass(this,'Math 7-A','7','A','Mathematics')"   class="class-tab active rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 7</span>Math 7-A
        </button>
        <button onclick="selectClass(this,'Science 8-B','8','B','Science')"    class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 8</span>Science 8-B
        </button>
        <button onclick="selectClass(this,'Math 9-C','9','C','Mathematics')"   class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 9</span>Math 9-C
        </button>
        <button onclick="selectClass(this,'Science 7-B','7','B','Science')"    class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 7</span>Science 7-B
        </button>
        <button onclick="selectClass(this,'Math 8-A','8','A','Mathematics')"   class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 8</span>Math 8-A
        </button>
        <button onclick="selectClass(this,'Science 9-A','9','A','Science')"    class="class-tab rounded-lg border border-slate-200 dark:border-dark-border px-3 py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] hover:border-[#0d4c8f]">
          <span class="block text-[10px] font-medium opacity-70">Grade 9</span>Science 9-A
        </button>
      </div>
    </div>

    <!-- ── Filters Card ── -->
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-4 mb-4">
      <div class="flex flex-wrap items-end gap-3">

        <!-- School Year -->
        <div class="flex flex-col gap-1 min-w-[130px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">School Year</label>
          <select class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
            <option>2025–2026</option>
            <option>2024–2025</option>
            <option>2023–2024</option>
          </select>
        </div>

        <!-- Quarter -->
        <div class="flex flex-col gap-1 min-w-[120px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Quarter</label>
          <select id="sel-quarter" onchange="updateHeader()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
            <option value="1st">1st Quarter</option>
            <option value="2nd">2nd Quarter</option>
            <option value="3rd" selected>3rd Quarter</option>
            <option value="4th">4th Quarter</option>
          </select>
        </div>

        <!-- Grade Level -->
        <div class="flex flex-col gap-1 min-w-[110px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Grade Level</label>
          <select id="sel-grade" onchange="updateHeader()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
            <option value="7" selected>Grade 7</option>
            <option value="8">Grade 8</option>
            <option value="9">Grade 9</option>
            <option value="10">Grade 10</option>
          </select>
        </div>

        <!-- Section -->
        <div class="flex flex-col gap-1 min-w-[110px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Section</label>
          <select id="sel-section" onchange="updateHeader()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
            <option value="A" selected>Section A</option>
            <option value="B">Section B</option>
            <option value="C">Section C</option>
            <option value="D">Section D</option>
          </select>
        </div>

        <!-- Subject -->
        <div class="flex flex-col gap-1 min-w-[130px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Subject</label>
          <select id="sel-subject" onchange="updateHeader()" class="rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-3 py-2 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30">
            <option value="Mathematics" selected>Mathematics</option>
            <option value="Science">Science</option>
            <option value="English">English</option>
            <option value="Filipino">Filipino</option>
            <option value="Araling Panlipunan">Araling Panlipunan</option>
            <option value="MAPEH">MAPEH</option>
            <option value="TLE">TLE</option>
            <option value="ESP">ESP</option>
          </select>
        </div>

        <!-- Search -->
        <div class="flex flex-col gap-1 flex-1 min-w-[160px]">
          <label class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Search Student</label>
          <div class="relative">
            <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
            <input id="search-input" oninput="filterStudents()" placeholder="Search by name..." class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-[#0d4c8f]/30"/>
          </div>
        </div>

        <!-- Load Button -->
        <button onclick="updateHeader()" class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm shrink-0">
          <iconify-icon icon="solar:refresh-linear" width="13"></iconify-icon>Load
        </button>
      </div>
    </div>

    <!-- ── Info Bar ── -->
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm px-5 py-3 mb-4 flex flex-wrap items-center gap-x-6 gap-y-1.5">
      <div class="flex items-center gap-2 text-xs">
        <span class="text-slate-400 dark:text-slate-500">Quarter:</span>
        <span id="info-quarter" class="font-semibold text-[#0d4c8f] dark:text-blue-300">3rd Quarter</span>
      </div>
      <div class="flex items-center gap-2 text-xs">
        <span class="text-slate-400 dark:text-slate-500">Grade &amp; Section:</span>
        <span id="info-gs" class="font-semibold text-slate-700 dark:text-slate-300">Grade 7 – Section A</span>
      </div>
      <div class="flex items-center gap-2 text-xs">
        <span class="text-slate-400 dark:text-slate-500">Teacher:</span>
        <span class="font-semibold text-slate-700 dark:text-slate-300">Ms. Ana Reyes</span>
      </div>
      <div class="flex items-center gap-2 text-xs">
        <span class="text-slate-400 dark:text-slate-500">Subject:</span>
        <span id="info-subject" class="font-semibold text-slate-700 dark:text-slate-300">Mathematics</span>
      </div>
      <div class="ml-auto flex items-center gap-2">
        <span class="text-[11px] text-slate-400 dark:text-slate-500">Status:</span>
        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400 text-[11px] font-semibold">
          <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>In Progress
        </span>
      </div>
    </div>

    <!-- ── Grade Table Card ── -->
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

      <!-- Table toolbar -->
      <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 dark:border-dark-border">
        <div class="flex items-center gap-2">
          <iconify-icon icon="solar:medal-ribbons-star-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
          <h3 class="text-sm font-bold text-slate-800 dark:text-white">Class Record</h3>
          <span class="text-[11px] text-slate-400 dark:text-slate-500">– <span id="tbl-title">3rd Quarter · Grade 7-A · Mathematics</span></span>
        </div>
        <div class="flex items-center gap-2">
          <button onclick="saveGrades()" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
            <iconify-icon icon="solar:diskette-linear" width="13"></iconify-icon>Save Grades
          </button>
          <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border px-4 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
            <iconify-icon icon="solar:printer-linear" width="13"></iconify-icon>Print
          </button>
        </div>
      </div>

      <!-- Legend -->
      <div class="flex flex-wrap items-center gap-x-4 gap-y-1 px-5 py-2 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/30">
        <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">Legend:</span>
        <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400"><span class="h-3 w-3 rounded bg-orange-100 border border-orange-200"></span>Written Works (WW) 30%</span>
        <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400"><span class="h-3 w-3 rounded bg-green-100 border border-green-200"></span>Performance Tasks (PT) 50%</span>
        <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400"><span class="h-3 w-3 rounded bg-blue-100 border border-blue-200"></span>Quarterly Assessment (QA) 20%</span>
        <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400"><span class="font-bold text-slate-500">PS</span> = Percentage Score &nbsp; <span class="font-bold text-slate-500">WS</span> = Weighted Score</span>
      </div>

      <!-- Scrollable table -->
      <div class="overflow-x-auto">
        <table class="grade-table">
          <thead>
            <!-- Row 1: Quarter + Grade&Section + Teacher + Subject -->
            <tr>
              <th class="th-group" style="min-width:180px;" id="th-quarter-label">3RD QUARTER</th>
              <th class="th-group text-left px-3" colspan="14" id="th-gs">GRADE &amp; SECTION: &nbsp; Grade 7 – A</th>
              <th class="th-group text-left px-3" colspan="14">TEACHER: &nbsp; Ms. Ana Reyes</th>
              <th class="th-group text-left px-3" colspan="7" id="th-subj">SUBJECT: &nbsp; Mathematics</th>
            </tr>
            <!-- Row 2: Section headers -->
            <tr>
              <th class="th-sub" rowspan="2" style="min-width:180px;">LEARNERS' NAMES</th>
              <th class="th-group" colspan="13" style="background:#ea580c;">WRITTEN WORKS (30%)</th>
              <th class="th-group" colspan="13" style="background:#16a34a;">PERFORMANCE TASKS (50%)</th>
              <th class="th-group" colspan="4" style="background:#1d4ed8;">QUARTERLY ASSESSMENT (20%)</th>
              <th class="th-group" style="background:#0d4c8f;">INITIAL GRADE</th>
              <th class="th-group" style="background:#0d4c8f;">QUARTERLY GRADE</th>
            </tr>
            <!-- Row 3: Column numbers -->
            <tr>
              <!-- WW -->
              <th class="th-num ww-bg">1</th><th class="th-num ww-bg">2</th><th class="th-num ww-bg">3</th>
              <th class="th-num ww-bg">4</th><th class="th-num ww-bg">5</th><th class="th-num ww-bg">6</th>
              <th class="th-num ww-bg">7</th><th class="th-num ww-bg">8</th><th class="th-num ww-bg">9</th>
              <th class="th-num ww-bg">10</th>
              <th class="th-sub ww-bg" style="min-width:36px;">Total</th>
              <th class="th-sub ww-bg" style="min-width:36px;">PS</th>
              <th class="th-sub ww-bg" style="min-width:36px;">WS</th>
              <!-- PT -->
              <th class="th-num pt-bg">1</th><th class="th-num pt-bg">2</th><th class="th-num pt-bg">3</th>
              <th class="th-num pt-bg">4</th><th class="th-num pt-bg">5</th><th class="th-num pt-bg">6</th>
              <th class="th-num pt-bg">7</th><th class="th-num pt-bg">8</th><th class="th-num pt-bg">9</th>
              <th class="th-num pt-bg">10</th>
              <th class="th-sub pt-bg" style="min-width:36px;">Total</th>
              <th class="th-sub pt-bg" style="min-width:36px;">PS</th>
              <th class="th-sub pt-bg" style="min-width:36px;">WS</th>
              <!-- QA -->
              <th class="th-num qa-bg">1</th>
              <th class="th-sub qa-bg" style="min-width:36px;">PS</th>
              <th class="th-sub qa-bg" style="min-width:36px;">WS</th>
              <th class="th-sub qa-bg" style="min-width:40px;">QA Score</th>
              <!-- Grades -->
              <th class="th-sub" style="min-width:56px;background:#dbeafe;color:#1d4ed8;">Initial</th>
              <th class="th-sub" style="min-width:60px;background:#bfdbfe;color:#1e40af;">Quarterly</th>
            </tr>
            <!-- Row 4: HPS -->
            <tr class="tr-hps">
              <td class="td-name font-semibold text-slate-500 dark:text-slate-400 text-[11px] uppercase">Highest Possible Score</td>
              <!-- WW HPS -->
              <td class="ww-bg" id="hps-ww-1"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="ww-bg" id="hps-ww-2"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="ww-bg" id="hps-ww-3"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="ww-bg" id="hps-ww-4"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="ww-bg" id="hps-ww-5"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="ww-bg" id="hps-ww-6"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="ww-bg" id="hps-ww-7"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="ww-bg" id="hps-ww-8"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="ww-bg" id="hps-ww-9"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="ww-bg" id="hps-ww-10"><input class="ww-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="td-calc ww-bg" id="hps-ww-total">####</td>
              <td class="td-calc ww-bg">30%</td>
              <td class="td-calc ww-bg">–</td>
              <!-- PT HPS -->
              <td class="pt-bg" id="hps-pt-1"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="pt-bg" id="hps-pt-2"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="pt-bg" id="hps-pt-3"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="pt-bg" id="hps-pt-4"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="pt-bg" id="hps-pt-5"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="pt-bg" id="hps-pt-6"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="pt-bg" id="hps-pt-7"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="pt-bg" id="hps-pt-8"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="pt-bg" id="hps-pt-9"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="pt-bg" id="hps-pt-10"><input class="pt-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="td-calc pt-bg" id="hps-pt-total">####</td>
              <td class="td-calc pt-bg">50%</td>
              <td class="td-calc pt-bg">–</td>
              <!-- QA HPS -->
              <td class="qa-bg" id="hps-qa-1"><input class="qa-bg" placeholder="–" onchange="recalcAll()"/></td>
              <td class="td-calc qa-bg">####</td>
              <td class="td-calc qa-bg">20%</td>
              <td class="td-calc qa-bg">–</td>
              <td class="td-calc" style="background:#dbeafe;">–</td>
              <td class="td-calc" style="background:#bfdbfe;">–</td>
            </tr>
          </thead>
          <tbody id="grade-tbody">
            <!-- Rendered by JS -->
          </tbody>
        </table>
      </div>

      <!-- Footer -->
      <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-dark-bg/30">
        <p class="text-[11px] text-slate-400 dark:text-slate-500">
          <span class="font-semibold text-slate-600 dark:text-slate-300" id="student-count">10</span> students · Grades auto-calculate based on DepEd Order No. 8, s. 2015
        </p>
        <button onclick="saveGrades()" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
          <iconify-icon icon="solar:diskette-linear" width="13"></iconify-icon>Save &amp; Submit Grades
        </button>
      </div>
    </div>

    <!-- Save toast -->
    <div id="toast" class="hidden fixed bottom-8 right-8 z-[200] flex items-center gap-3 rounded-xl bg-[#0d4c8f] text-white px-5 py-3 shadow-2xl text-sm font-medium">
      <iconify-icon icon="solar:check-circle-bold" width="18"></iconify-icon>
      Grades saved successfully!
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
  </main>
</div>

<!-- Tweaks -->
<div id="tweaks-panel" class="hidden fixed bottom-5 right-5 z-[999] w-56 rounded-xl bg-white dark:bg-dark-card border border-slate-200 dark:border-dark-border shadow-xl p-4">
  <p class="text-xs font-bold text-slate-700 dark:text-white mb-3 uppercase tracking-wider">Tweaks</p>
  <button onclick="document.documentElement.classList.toggle('dark')" class="w-full mb-2 text-xs rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-white/5 px-2.5 py-1.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors">Toggle Dark / Light</button>
</div>

<script>
const TWEAK_DEFAULTS = /*EDITMODE-BEGIN*/{"darkMode": false}/*EDITMODE-END*/;

// ── Student data ──────────────────────────────────────────
const allStudents = {
  male: [
    'Aguilar, Marco Luis', 'Bautista, Rafael Jr.', 'Cruz, Angelo Miguel',
    'Dela Cruz, Juan Pablo', 'Espiritu, Christian', 'Garcia, Ben James',
  ],
  female: [
    'Castillo, Sophia Mae', 'Dela Cruz, Maria Clara', 'Flores, Patricia Anne',
    'Lim, Angela Rose', 'Santos, Isabella Joy',
  ]
};

// ── Render table body ─────────────────────────────────────
function renderTable(filter = '') {
  const tbody = document.getElementById('grade-tbody');
  let html = '';
  let count = 0;

  const renderGroup = (label, students) => {
    const filtered = filter
      ? students.filter(n => n.toLowerCase().includes(filter.toLowerCase()))
      : students;
    if (!filtered.length) return;
    html += `<tr class="tr-group"><td colspan="32">${label}</td></tr>`;
    filtered.forEach((name, i) => {
      count++;
      const rowId = label.toLowerCase() + '-' + i;
      html += `<tr id="row-${rowId}">
        <td class="td-name">${i + 1}. ${name}</td>
        ${wwCols(rowId)} ${ptCols(rowId)} ${qaCols(rowId)}
        <td class="td-grade" id="${rowId}-initial">—</td>
        <td class="td-grade" id="${rowId}-quarterly">—</td>
      </tr>`;
    });
  };

  renderGroup('MALE', allStudents.male);
  renderGroup('FEMALE', allStudents.female);
  tbody.innerHTML = html;
  document.getElementById('student-count').textContent = count;

  // Attach recalc listeners
  tbody.querySelectorAll('input').forEach(inp => {
    inp.addEventListener('input', () => recalcRow(inp.closest('tr')));
  });
}

const wwCols = (id) => Array.from({length:10}, (_,i) =>
  `<td class="ww-bg"><input class="ww-bg" type="number" min="0" id="${id}-ww-${i+1}" placeholder=""/></td>`
).join('') +
`<td class="td-calc ww-bg" id="${id}-ww-total">—</td>
 <td class="td-calc ww-bg" id="${id}-ww-ps">—</td>
 <td class="td-calc ww-bg" id="${id}-ww-ws">—</td>`;

const ptCols = (id) => Array.from({length:10}, (_,i) =>
  `<td class="pt-bg"><input class="pt-bg" type="number" min="0" id="${id}-pt-${i+1}" placeholder=""/></td>`
).join('') +
`<td class="td-calc pt-bg" id="${id}-pt-total">—</td>
 <td class="td-calc pt-bg" id="${id}-pt-ps">—</td>
 <td class="td-calc pt-bg" id="${id}-pt-ws">—</td>`;

const qaCols = (id) =>
`<td class="qa-bg"><input class="qa-bg" type="number" min="0" id="${id}-qa-1" placeholder=""/></td>
 <td class="td-calc qa-bg" id="${id}-qa-ps">—</td>
 <td class="td-calc qa-bg" id="${id}-qa-ws">—</td>
 <td class="td-calc qa-bg" id="${id}-qa-score">—</td>`;

// ── Recalculate one row ───────────────────────────────────
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

  // WW
  let wwSum = 0, wwCount = 0;
  for (let i = 1; i <= 10; i++) {
    const v = parseFloat(document.getElementById(`${id}-ww-${i}`)?.value);
    if (!isNaN(v)) { wwSum += v; wwCount++; }
  }
  const wwHPS = getHPS('ww', 10);
  const wwPS = wwHPS && wwCount ? +(wwSum / wwHPS * 100).toFixed(1) : null;
  const wwWS = wwPS ? +(wwPS * 0.30).toFixed(2) : null;
  setText(`${id}-ww-total`, wwCount ? wwSum : '—');
  setText(`${id}-ww-ps`, wwPS !== null ? wwPS + '%' : '—');
  setText(`${id}-ww-ws`, wwWS !== null ? wwWS : '—');

  // PT
  let ptSum = 0, ptCount = 0;
  for (let i = 1; i <= 10; i++) {
    const v = parseFloat(document.getElementById(`${id}-pt-${i}`)?.value);
    if (!isNaN(v)) { ptSum += v; ptCount++; }
  }
  const ptHPS = getHPS('pt', 10);
  const ptPS = ptHPS && ptCount ? +(ptSum / ptHPS * 100).toFixed(1) : null;
  const ptWS = ptPS ? +(ptPS * 0.50).toFixed(2) : null;
  setText(`${id}-pt-total`, ptCount ? ptSum : '—');
  setText(`${id}-pt-ps`, ptPS !== null ? ptPS + '%' : '—');
  setText(`${id}-pt-ws`, ptWS !== null ? ptWS : '—');

  // QA
  const qaRaw = parseFloat(document.getElementById(`${id}-qa-1`)?.value);
  const qaHPS = (() => {
    const el = document.getElementById('hps-qa-1');
    return el ? parseFloat(el.querySelector('input')?.value) : NaN;
  })();
  const qaPS = (!isNaN(qaRaw) && !isNaN(qaHPS) && qaHPS > 0) ? +(qaRaw / qaHPS * 100).toFixed(1) : null;
  const qaWS = qaPS ? +(qaPS * 0.20).toFixed(2) : null;
  setText(`${id}-qa-ps`,    qaPS !== null ? qaPS + '%' : '—');
  setText(`${id}-qa-ws`,    qaWS !== null ? qaWS : '—');
  setText(`${id}-qa-score`, qaPS !== null ? qaPS + '%' : '—');

  // Initial & Quarterly Grade
  if (wwWS !== null && ptWS !== null && qaWS !== null) {
    const initial = +(wwWS + ptWS + qaWS).toFixed(2);
    const qGrade  = transmute(initial);
    setText(`${id}-initial`,   initial);
    setText(`${id}-quarterly`, qGrade);
    const qEl = document.getElementById(`${id}-quarterly`);
    if (qEl) qEl.style.color = qGrade >= 75 ? '#16a34a' : '#ef4444';
  } else {
    setText(`${id}-initial`,   '—');
    setText(`${id}-quarterly`, '—');
  }
}

// DepEd transmutation table (simplified)
function transmute(ps) {
  if (ps >= 100) return 100;
  if (ps >= 98.40) return 99;
  if (ps >= 96.80) return 98;
  if (ps >= 95.20) return 97;
  if (ps >= 93.60) return 96;
  if (ps >= 92.00) return 95;
  if (ps >= 90.40) return 94;
  if (ps >= 88.80) return 93;
  if (ps >= 87.20) return 92;
  if (ps >= 85.60) return 91;
  if (ps >= 84.00) return 90;
  if (ps >= 82.40) return 89;
  if (ps >= 80.80) return 88;
  if (ps >= 79.20) return 87;
  if (ps >= 77.60) return 86;
  if (ps >= 76.00) return 85;
  if (ps >= 74.40) return 84;
  if (ps >= 72.80) return 83;
  if (ps >= 71.20) return 82;
  if (ps >= 69.60) return 81;
  if (ps >= 68.00) return 80;
  if (ps >= 66.40) return 79;
  if (ps >= 64.80) return 78;
  if (ps >= 63.20) return 77;
  if (ps >= 61.60) return 76;
  if (ps >= 60.00) return 75;
  if (ps >= 56.00) return 74;
  if (ps >= 52.00) return 73;
  if (ps >= 48.00) return 72;
  if (ps >= 44.00) return 71;
  if (ps >= 40.00) return 70;
  return Math.max(60, Math.round(ps));
}

function setText(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val;
}

function recalcAll() {
  document.querySelectorAll('#grade-tbody tr').forEach(recalcRow);
}

// ── Update header info ────────────────────────────────────
function updateHeader() {
  const q = document.getElementById('sel-quarter').value;
  const g = document.getElementById('sel-grade').value;
  const s = document.getElementById('sel-section').value;
  const subj = document.getElementById('sel-subject').value;

  document.getElementById('info-quarter').textContent = q + ' Quarter';
  document.getElementById('info-gs').textContent = 'Grade ' + g + ' – Section ' + s;
  document.getElementById('info-subject').textContent = subj;
  document.getElementById('th-quarter-label').textContent = q.toUpperCase() + ' QUARTER';
  document.getElementById('th-gs').innerHTML = 'GRADE &amp; SECTION: &nbsp; Grade ' + g + ' – ' + s;
  document.getElementById('th-subj').innerHTML = 'SUBJECT: &nbsp; ' + subj;
  document.getElementById('tbl-title').textContent = q + ' Quarter · Grade ' + g + '-' + s + ' · ' + subj;
}

// ── Quick class select ────────────────────────────────────
function selectClass(btn, label, grade, section, subject) {
  document.querySelectorAll('.class-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('sel-grade').value   = grade;
  document.getElementById('sel-section').value = section;
  document.getElementById('sel-subject').value = subject;
  updateHeader();
}

// ── Search ────────────────────────────────────────────────
function filterStudents() {
  const q = document.getElementById('search-input').value;
  renderTable(q);
}

// ── Save ──────────────────────────────────────────────────
function saveGrades() {
  const toast = document.getElementById('toast');
  toast.classList.remove('hidden');
  setTimeout(() => toast.classList.add('hidden'), 2500);
}

// ── Init ──────────────────────────────────────────────────
renderTable();
updateHeader();

window.addEventListener('message', e => {
  if (e.data?.type === '__activate_edit_mode')   document.getElementById('tweaks-panel').classList.remove('hidden');
  if (e.data?.type === '__deactivate_edit_mode') document.getElementById('tweaks-panel').classList.add('hidden');
});
window.parent.postMessage({ type: '__edit_mode_available' }, '*');
</script>
</body>
</html>
