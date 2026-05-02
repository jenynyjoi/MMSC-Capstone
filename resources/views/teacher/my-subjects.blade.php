<!DOCTYPE html>
<html lang="en" class="">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MMSC – My Assigned Subjects</title>
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
  .expand-row { display: none; }
  .expand-row.open { display: table-row; }
  .chevron-icon { transition: transform 0.2s ease; }
  .chevron-icon.rotated { transform: rotate(180deg); }
</style>
</head>
<body class="flex h-screen overflow-hidden bg-slate-50 dark:bg-dark-bg">

<!-- ═══════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════ -->
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
      <span class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</span>
      <span class="text-[11px] text-slate-400 dark:text-slate-500 truncate">Teacher</span>
    </div>
  </div>

  <!-- Nav -->
  <nav class="flex-1 overflow-y-auto no-scrollbar px-3 py-3 space-y-0.5">

    <!-- Dashboard -->
    <a href="{{ route('teacher.dashboard') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-blue-50 hover:text-[#0d4c8f] dark:hover:bg-[#0d4c8f]/10 dark:hover:text-blue-300 transition-all">
      <iconify-icon icon="boxicons:dashboard-filled" width="18" class="shrink-0 text-slate-400 group-hover:text-[#0d4c8f]"></iconify-icon>
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

    <!-- My Assigned Subjects (active) -->
    <a href="{{ route('teacher.my-subjects') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 bg-blue-50 text-[#0d4c8f] dark:bg-[#0d4c8f]/10 dark:text-blue-300 text-sm font-medium">
      <iconify-icon icon="solar:notebook-bold" width="18" class="shrink-0 text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
      My Assigned Subjects
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
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="w-full flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all">
        <iconify-icon icon="solar:logout-2-bold" width="18" class="shrink-0"></iconify-icon>
        Logout
      </button>
    </form>
  </div>
</aside>

<!-- ═══════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════ -->
<div class="flex flex-col flex-1 overflow-hidden">

  <!-- Topbar -->
  <header class="flex h-16 items-center justify-between px-6 bg-white dark:bg-dark-card border-b border-slate-100 dark:border-dark-border shrink-0">
    <div class="flex items-center gap-3">
      <button class="lg:hidden flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500">
        <iconify-icon icon="solar:hamburger-menu-linear" width="20"></iconify-icon>
      </button>
      <div>
        <h1 class="text-base font-bold text-slate-900 dark:text-white">My Assigned Subjects</h1>
        <p class="text-xs text-slate-400 dark:text-slate-500">Subjects allocated to you from Subject Allocation.</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <!-- School Year selector -->
      <form method="GET" action="{{ route('teacher.my-subjects') }}" class="flex items-center gap-2">
        <select name="school_year" onchange="this.form.submit()"
          class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-1.5 text-xs font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
          @foreach($schoolYears as $sy)
            <option value="{{ $sy }}" @selected($sy === $schoolYear)>S.Y. {{ $sy }}</option>
          @endforeach
        </select>
      </form>
      <!-- Dark toggle -->
      <button onclick="document.documentElement.classList.toggle('dark')"
        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-500 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
        <iconify-icon icon="solar:moon-stars-linear" width="18"></iconify-icon>
      </button>
      <!-- Avatar -->
      <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#0d4c8f] text-white text-sm font-semibold shrink-0">
        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
      </div>
    </div>
  </header>

  <!-- Scrollable page -->
  <main class="flex-1 overflow-y-auto p-6 bg-slate-50/50 dark:bg-dark-bg">

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">

      <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 flex items-center gap-4">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/20 shrink-0">
          <iconify-icon icon="solar:notebook-bold" width="22" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
        </div>
        <div>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalSubjects }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total Subjects Assigned</p>
        </div>
      </div>

      <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 flex items-center gap-4">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 dark:bg-green-900/20 shrink-0">
          <iconify-icon icon="solar:buildings-2-bold" width="22" class="text-green-600 dark:text-green-300"></iconify-icon>
        </div>
        <div>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalSections }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Sections Handled</p>
        </div>
      </div>

      <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card p-5 flex items-center gap-4">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-900/20 shrink-0">
          <iconify-icon icon="solar:clock-circle-bold" width="22" class="text-violet-600 dark:text-violet-300"></iconify-icon>
        </div>
        <div>
          <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalHours, 1) }}</p>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total Hours / Week</p>
        </div>
      </div>

    </div>

    <!-- Main Card -->
    <div class="rounded-xl border border-slate-200 bg-white dark:border-dark-border dark:bg-dark-card overflow-hidden">

      <!-- Card Header -->
      <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.02]">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#0d4c8f]/10 dark:bg-[#0d4c8f]/20 shrink-0">
          <iconify-icon icon="solar:notebook-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
        </div>
        <div>
          <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Assigned Subjects</h2>
          <p class="text-[11px] text-slate-400 dark:text-slate-500">S.Y. {{ $schoolYear }} · Click a row to expand subjects</p>
        </div>
      </div>

      @if($bySection->isEmpty())
        <!-- Empty state -->
        <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
          <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-white/5 mb-4">
            <iconify-icon icon="solar:notebook-linear" width="28" class="text-slate-400 dark:text-slate-500"></iconify-icon>
          </div>
          <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">No subjects assigned</p>
          <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5 max-w-xs">You have no subjects allocated to you for S.Y. {{ $schoolYear }}. Contact the admin to assign subjects.</p>
        </div>
      @else
        <!-- Outer table -->
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-slate-100 dark:border-dark-border bg-slate-50 dark:bg-white/[0.02]">
                <th class="w-10 px-4 py-3.5"></th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">School Year</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Grade &amp; Section</th>
                <th class="px-5 py-3.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Program Level</th>
                <th class="px-5 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Subjects</th>
                <th class="px-5 py-3.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Hrs / Week</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
              @foreach($bySection as $sectionId => $sectionSubjects)
                @php
                  $section   = $sectionSubjects->first()->section;
                  $secHours  = $sectionSubjects->sum('hours_per_week');
                  $rowId     = 'expand-' . ($section ? $section->id : $sectionId);
                @endphp

                <!-- Summary row -->
                <tr class="cursor-pointer hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-colors"
                    onclick="toggleExpand('{{ $rowId }}', this)">
                  <td class="px-4 py-4 text-center">
                    <iconify-icon id="chev-{{ $rowId }}" icon="solar:alt-arrow-down-linear" width="15"
                      class="chevron-icon text-slate-400 dark:text-slate-500"></iconify-icon>
                  </td>
                  <td class="px-5 py-4">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 dark:bg-blue-900/20 px-2.5 py-1 text-xs font-semibold text-[#0d4c8f] dark:text-blue-300">
                      <iconify-icon icon="solar:calendar-linear" width="11"></iconify-icon>
                      {{ $sectionSubjects->first()->school_year }}
                    </span>
                  </td>
                  <td class="px-5 py-4">
                    <div class="flex flex-col gap-0.5">
                      <span class="font-semibold text-slate-800 dark:text-white text-sm">
                        {{ $section ? $section->section_name : 'Unknown Section' }}
                      </span>
                      <span class="text-[11px] text-slate-400 dark:text-slate-500">
                        {{ $section ? $section->grade_level : '—' }}
                      </span>
                    </div>
                  </td>
                  <td class="px-5 py-4">
                    @if($section && $section->program_level)
                      <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold
                        {{ $section->program_level === 'SHS'
                          ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300'
                          : ($section->program_level === 'JHS'
                            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300'
                            : 'bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300') }}">
                        {{ $section->program_level }}
                      </span>
                    @else
                      <span class="text-slate-400 dark:text-slate-500">—</span>
                    @endif
                  </td>
                  <td class="px-5 py-4 text-center">
                    <span class="inline-flex items-center justify-center h-7 min-w-[28px] rounded-full bg-[#0d4c8f]/10 dark:bg-[#0d4c8f]/20 text-xs font-bold text-[#0d4c8f] dark:text-blue-300 px-2.5">
                      {{ $sectionSubjects->count() }}
                    </span>
                  </td>
                  <td class="px-5 py-4 text-center">
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ number_format($secHours, 1) }}h</span>
                  </td>
                </tr>

                <!-- Expanded subjects row -->
                <tr id="{{ $rowId }}" class="expand-row">
                  <td colspan="6" class="p-0 bg-slate-50/80 dark:bg-white/[0.015]">
                    <div class="px-6 py-4">
                      <div class="rounded-lg border border-slate-200 dark:border-dark-border overflow-hidden">
                        <table class="w-full text-xs">
                          <thead>
                            <tr class="bg-[#0d4c8f] text-white">
                              <th class="px-4 py-3 text-left font-semibold tracking-wide">#</th>
                              <th class="px-4 py-3 text-left font-semibold tracking-wide">Subject Code</th>
                              <th class="px-4 py-3 text-left font-semibold tracking-wide">Subject Name</th>
                              <th class="px-4 py-3 text-center font-semibold tracking-wide">Hrs / Week</th>
                              <th class="px-4 py-3 text-center font-semibold tracking-wide">Semester</th>
                            </tr>
                          </thead>
                          <tbody class="divide-y divide-slate-100 dark:divide-dark-border bg-white dark:bg-dark-card">
                            @foreach($sectionSubjects as $i => $subj)
                              <tr class="hover:bg-blue-50/40 dark:hover:bg-blue-900/10 transition-colors">
                                <td class="px-4 py-3 text-slate-400 dark:text-slate-500 font-medium">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 font-mono font-semibold text-slate-700 dark:text-slate-200">
                                  {{ $subj->subject_code }}
                                </td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
                                  {{ $subj->subject_name }}
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-slate-700 dark:text-slate-300">
                                  {{ $subj->hours_per_week ? number_format($subj->hours_per_week, 1) . 'h' : '—' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                  @if($subj->subject && $subj->subject->default_semester)
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold
                                      {{ str_contains($subj->subject->default_semester, '1st')
                                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300'
                                        : 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300' }}">
                                      {{ $subj->subject->default_semester }}
                                    </span>
                                  @else
                                    <span class="text-slate-400 dark:text-slate-500">—</span>
                                  @endif
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </td>
                </tr>

              @endforeach
            </tbody>
          </table>
        </div>
      @endif

    </div>

    <p class="mt-8 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

  </main>
</div>

<script>
function toggleNav(id) {
  const sub = document.getElementById('sub-' + id);
  const arr = document.getElementById('arr-' + id);
  const open = !sub.classList.contains('hidden');
  sub.classList.toggle('hidden', open);
  arr.style.transform = open ? '' : 'rotate(180deg)';
}

function toggleExpand(rowId, triggerRow) {
  const row  = document.getElementById(rowId);
  const chev = document.getElementById('chev-' + rowId);
  const isOpen = row.classList.contains('open');
  row.classList.toggle('open', !isOpen);
  chev.classList.toggle('rotated', !isOpen);
}
</script>
</body>
</html>
