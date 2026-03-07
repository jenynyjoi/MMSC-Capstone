<!DOCTYPE html>
<html lang="en" class="scroll-smooth"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MMSC Admin Dashboard</title>

    @vite(['resources/css/app.css','resources/js/dashboard.js'])

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    
</head>
<body class="bg-gray-50 text-slate-800 antialiased transition-colors duration-300 dark:bg-dark-bg dark:text-slate-300 selection:bg-brand-900 selection:text-white">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden transition-opacity opacity-0"></div>

    <!-- Layout Container -->
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-transition fixed inset-y-0 left-0 z-50 w-64 -translate-x-full bg-white lg:static lg:translate-x-0 dark:border-dark-border dark:bg-dark-card flex flex-col justify-between">
            <div class="">
                <!-- Logo -->
                <div class="flex h-16 items-center justify-between px-6 border-b border-white/10 bg-[#0d4c8f] dark:bg-[#0a3a6e]">
                    <div class="flex items-center gap-2">
                   <img src="{{ asset('img/download.jpg') }}" class="h-10 w-10 rounded-full object-cover">                        
                        <div class="flex flex-col leading-tight">
                            <span id="logo-text" class="text-xl font-bold text-white tracking-tight font-poppins">MMSC</span>
                            <span id="logo-text" class="text-xs text-white/70 font-medium">Admin</span>
                        </div>
                    </div>
                </div>

                <!-- Nav bar content --> 
                <nav class="space-y-1 px-3 py-6"> 
                    @php 
                        $navItems = [
                            'Dashboard'       => 'boxicons:dashboard-filled',
                            'Admission'       => 'material-symbols:other-admission',
                            'Enrollment'      => 'lets-icons:user-fill-add',
                            'Student Records' => 'ph:folders-fill',
                            'Clearance'       => 'tdesign:task-checked-filled',
                            'Academics'       => 'mdi:books',
                            'Classes'         => 'icon-park-solid:bell-ring',
                            'Schedule'        => 'uis:schedule',
                            'Teachers'        => 'fa6-solid:user-tie',
                            'Announcements'   => 'mdi:announcement',
                            'Reports'         => 'mage:file-2-fill',
                            'Settings'        => 'material-symbols:settings',
                        ];
                    @endphp
                    @foreach ($navItems as $label => $icon)
                        <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white transition-all @if($label === 'Dashboard') bg-brand-50 text-brand-900 dark:bg-brand-900/20 dark:text-brand-100 @endif" title="{{ $label }}">
                            <iconify-icon icon="{{ $icon }}" width="20" stroke-width="1.5"></iconify-icon>
                            <span class="nav-text">{{ $label }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden relative">
            
            <!-- Top Navigation -->
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-white/10 px-4 glass-effect lg:px-8 bg-[#0d4c8f] dark:bg-[#0a3a6e]">
                <div class="flex items-center gap-4 ml-1">
                    <button onclick="toggleSidebar()" class="flex items-center justify-center rounded-xl p-2 text-white hover:bg-white/10 lg:hidden border border-white/20">
                        <iconify-icon icon="solar:hamburger-menu-linear" width="24" stroke-width="1.5"></iconify-icon>
                    </button>
                    <!-- Toggle side bar -->
                    <button id="collapse-btn" onclick="toggleSidebarCollapse()" class="hidden lg:flex items-center justify-center rounded-lg p-1.5 text-white hover:bg-white/10 transition-all">
                        <iconify-icon icon="rivet-icons:menu" width="20" height="16"  style="color: white" ></iconify-icon>
                    </button>
                    <!-- Search -->
                    <div class="hidden md:flex items-center gap-2 rounded-lg bg-white/20 dark:bg-[#0a3a6e]/20 px-3 py-1.5 ring-1 ring-white/30 dark:ring-white/40 focus-within:ring-white/50">
                        <iconify-icon icon="solar:magnifer-linear" class="text-white/70"></iconify-icon>
                        <input type="text" placeholder="Search analytics..." class="w-64 bg-transparent text-sm text-white placeholder:text-white/60 focus:outline-none">
                        <div class="flex items-center gap-1 rounded border border-white/30 px-1.5 py-0.5">
                            <span class="text-xs text-white/60">⌘K</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button id="theme-toggle" class="flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-white transition hover:bg-white/10">
                        <iconify-icon id="theme-icon" icon="solar:sun-2-linear" width="20"></iconify-icon>
                    </button>
                    <!-- Notification -->
                    <button class="relative flex h-9 w-9 items-center justify-center rounded-full border border-white/20 text-white transition hover:bg-white/10">
                        <iconify-icon icon="solar:bell-linear" width="20"></iconify-icon>
                        <span class="absolute right-0 top-0 mr-0.5 mt-0.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring:white"></span>
                    </button>
                    <!-- Profile Dropdown -->
                    <div id="profile-dropdown" class="relative group">
                        <button onclick="toggleProfileMenu(event)" class="flex items-center gap-2 rounded-full px-2 py-1 hover:bg-white/10 transition">
                            <img src="https://ui-avatars.com/api/?name=Jenny&background=010694&color=fff" alt="User" class="h-8 w-8 rounded-full">
                            <span class="hidden sm:inline text-sm font-medium text-white">Hello, Jenny</span>
                            <iconify-icon icon="solar:chevron-down-linear" width="18" class="text-white"></iconify-icon>
                        </button>
                        <div id="profile-menu" class="absolute right-0 top-full mt-1 w-48 bg-white dark:bg-[#0a3a6e] rounded-lg shadow-lg hidden group-hover:block">
                            <a href="#" class="block px-4 py-2 text-sm text-slate-700 dark:text-white hover:bg-slate-100">Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-700 dark:text-white hover:bg-slate-100">Settings</a>
                            <div class="border-t border-slate-200 dark:border-white/20 my-1"></div>
                            <a href="#" class="block px-4 py-2 text-sm text-red-500 font-medium dark:text-red-300 hover:bg-slate-100">Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 pt-4 pr-4 pb-4 pl-4">
                
                <!-- Page Header -->
                <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div class="">
                        <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Overview</h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Welcome back, here's what's happening today.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 items-center rounded-lg border border-slate-200 bg-white px-3 shadow-sm dark:border-dark-border dark:bg-dark-card">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Oct 24, 2023</span>
                        </div>

                        <!-- button -->
                        <!-- <button class="flex h-9 items-center gap-2 rounded-lg bg-brand-900 px-4 text-sm font-medium text-white shadow-lg shadow-brand-900/20 hover:bg-brand-800 transition-colors">
                            <iconify-icon icon="solar:download-linear"></iconify-icon>
                            Export
                        </button> -->
                    </div>
                </div>
                                <!-- Stats Grid - MMSC Dashboard Style -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Card 1: Students -->
                <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card dark:shadow-none">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 mb-3">
                                <iconify-icon icon="solar:users-group-rounded-linear" width="22" class="text-blue-500 dark:text-blue-300"></iconify-icon>
                            </div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Students</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Total Enrolled Students</p>
                        </div>
                        <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">10</h3>
                    </div>
                </div>

                <!-- Card 2: Teachers -->
                <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card dark:shadow-none">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 mb-3">
                                <iconify-icon icon="solar:user-id-linear" width="22" class="text-yellow-500 dark:text-yellow-300"></iconify-icon>
                            </div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Teachers</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Total Teachers</p>
                        </div>
                        <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">10</h3>
                    </div>
                </div>

                <!-- Card 3: Parents -->
                <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card dark:shadow-none">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-red-50 dark:bg-red-900/20 mb-3">
                                <iconify-icon icon="solar:users-group-two-rounded-linear" width="22" class="text-red-500 dark:text-red-300"></iconify-icon>
                            </div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Parents</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Total Parents</p>
                        </div>
                        <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">10</h3>
                    </div>
                </div>

                <!-- Card 4: Sections -->
                <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card dark:shadow-none">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 dark:bg-green-900/20 mb-3">
                                <iconify-icon icon="solar:widget-linear" width="22" class="text-green-600 dark:text-green-300"></iconify-icon>
                            </div>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Sections</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Total Active Sections</p>
                        </div>
                        <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">10</h3>
                    </div>
                </div>
            </div>
            <!-- Quick Action & Latest Announcement -->
            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

                <!-- Quick Action -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
                            <iconify-icon icon="solar:bolt-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Quick Action</h3>
                    </div>
                    <div class="flex flex-col gap-3">
                        <button class="w-full rounded-lg border border-blue-400 py-2.5 text-sm font-medium text-blue-500 transition hover:bg-blue-50 dark:border-blue-500 dark:text-blue-400 dark:hover:bg-blue-900/20">
                            Enroll Student
                        </button>
                        <button class="w-full rounded-lg border border-yellow-400 py-2.5 text-sm font-medium text-yellow-500 transition hover:bg-yellow-50 dark:border-yellow-500 dark:text-yellow-400 dark:hover:bg-yellow-900/20">
                            Update Clearance
                        </button>
                        <button class="w-full rounded-lg border border-green-400 py-2.5 text-sm font-medium text-green-600 transition hover:bg-green-50 dark:border-green-500 dark:text-green-400 dark:hover:bg-green-900/20">
                            View Schedules
                        </button>
                        <button class="w-full rounded-lg border border-red-400 py-2.5 text-sm font-medium text-red-500 transition hover:bg-red-50 dark:border-red-500 dark:text-red-400 dark:hover:bg-red-900/20">
                            Post Announcement
                        </button>
                    </div>
                </div>

                <!-- Latest Announcement -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
                    <div class="mb-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
                                <iconify-icon icon="solar:document-text-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                            </div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Latest Announcement</h3>
                        </div>
                        <button class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 text-lg font-bold">
                            +
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-medium text-slate-500 dark:text-slate-400">
                                    <th class="pb-3 pr-4">Title</th>
                                    <th class="pb-3 pr-4">Date</th>
                                    <th class="pb-3 pr-4">Time</th>
                                    <th class="pb-3 pr-4">Sender</th>
                                    <th class="pb-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="py-3 pr-4">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-yellow-400 shrink-0"></span>
                                            <span class="text-slate-700 dark:text-slate-300 text-xs">Enrollment Procedures</span>
                                        </div>
                                    </td>
                                    <td class="py-3 pr-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">19 January, 2026</td>
                                    <td class="py-3 pr-4 text-xs text-slate-500 dark:text-slate-400">4:00 pm</td>
                                    <td class="py-3 pr-4 text-xs text-slate-600 dark:text-slate-300">Jeneva Ybanez</td>
                                    <td class="py-3 text-right">
                                        <button class="text-slate-400 hover:text-brand-900 dark:hover:text-white transition-colors">
                                            <iconify-icon icon="solar:eye-linear" width="18"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="py-3 pr-4">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-red-500 shrink-0"></span>
                                            <span class="text-slate-700 dark:text-slate-300 text-xs">Class Suspension</span>
                                        </div>
                                    </td>
                                    <td class="py-3 pr-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">23 January, 2026</td>
                                    <td class="py-3 pr-4 text-xs text-slate-500 dark:text-slate-400">4:00 pm</td>
                                    <td class="py-3 pr-4 text-xs text-slate-600 dark:text-slate-300">Hans Gayon</td>
                                    <td class="py-3 text-right">
                                        <button class="text-slate-400 hover:text-brand-900 dark:hover:text-white transition-colors">
                                            <iconify-icon icon="solar:eye-linear" width="18"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <td class="py-3 pr-4">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-green-500 shrink-0"></span>
                                            <span class="text-slate-700 dark:text-slate-300 text-xs">Report Approval</span>
                                        </div>
                                    </td>
                                    <td class="py-3 pr-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">29 January, 2026</td>
                                    <td class="py-3 pr-4 text-xs text-slate-500 dark:text-slate-400">4:00 pm</td>
                                    <td class="py-3 pr-4 text-xs text-slate-600 dark:text-slate-300">Dianne Balaoro</td>
                                    <td class="py-3 text-right">
                                        <button class="text-slate-400 hover:text-brand-900 dark:hover:text-white transition-colors">
                                            <iconify-icon icon="solar:eye-linear" width="18"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

<!-- Charts Section -->
<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

    <!-- Left Column: Enrollment Bar Chart + Student Movement Trends -->
    <div class="flex flex-col gap-6 lg:col-span-2">

        <!-- Enrollment per Educational Level -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:chart-square-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Enrollment per Educational Level</h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400">School Year:</span>
                    <select class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-600 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-dark-border dark:bg-dark-card dark:text-slate-400">
                        <option>Current</option>
                        <option>2024-2025</option>
                        <option>2023-2024</option>
                    </select>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>

        <!-- Student Movement Trends -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
            <div class="mb-2 flex items-center gap-2">
                <iconify-icon icon="solar:chart-2-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Student Movement Trends</h3>
            </div>
            <div class="mb-4 flex items-center gap-4">
                <div class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Enrollments</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Withdrawals</span>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="movementChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Right Column: Reminders + Student Status -->
    <div class="flex flex-col gap-6">

        <!-- Reminders -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:bell-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Reminders</h3>
                </div>
                <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 font-bold text-base">
                    +
                </button>
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex items-center justify-between rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2.5 dark:border-yellow-800/40 dark:bg-yellow-900/10">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="solar:clock-circle-linear" width="16" class="text-yellow-500"></iconify-icon>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Afternoon Meeting</span>
                    </div>
                    <button class="text-slate-400 hover:text-red-500 transition-colors">
                        <iconify-icon icon="solar:trash-bin-trash-linear" width="16"></iconify-icon>
                    </button>
                </div>
                <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 dark:border-dark-border dark:bg-white/5">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="solar:check-circle-linear" width="16" class="text-green-500"></iconify-icon>
                        <span class="text-sm text-slate-700 dark:text-slate-300">Applicant Screening</span>
                    </div>
                    <button class="text-slate-400 hover:text-red-500 transition-colors">
                        <iconify-icon icon="solar:trash-bin-trash-linear" width="16"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>

        <!-- Student Status -->
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
            <div class="mb-4 flex items-center gap-2">
                <iconify-icon icon="solar:users-group-rounded-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Student Status</h3>
            </div>
            <div class="relative h-44 w-full flex items-center justify-center">
                <canvas id="studentStatusChart"></canvas>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-1.5">
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-blue-500 shrink-0"></span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Active</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-yellow-400 shrink-0"></span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Graduated</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-green-500 shrink-0"></span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Completed</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-slate-400 shrink-0"></span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Inactive</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-red-500 shrink-0"></span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Withdrawn</span>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <span class="text-xs text-slate-500 dark:text-slate-400">School Year:</span>
                <select class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-600 focus:outline-none dark:border-dark-border dark:bg-dark-card dark:text-slate-400">
                    <option>Current</option>
                    <option>2024-2025</option>
                </select>
            </div>
        </div>

    </div>
</div>

<!-- Calendar Section -->
<div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card">

    <!-- Calendar Header -->
    <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-dark-border">
        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Calendar</h3>
    </div>

    <!-- Calendar Controls -->
    <div class="flex flex-col gap-4 px-6 pt-5 pb-3 sm:flex-row sm:items-center sm:justify-between">
        <!-- Navigation Arrows -->
        <div class="flex items-center gap-2">
            <button onclick="calendarNav('prev-year')" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                <iconify-icon icon="solar:double-alt-arrow-left-linear" width="16"></iconify-icon>
            </button>
            <button onclick="calendarNav('prev-month')" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                <iconify-icon icon="solar:alt-arrow-left-linear" width="16"></iconify-icon>
            </button>
            <button onclick="calendarNav('next-month')" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                <iconify-icon icon="solar:alt-arrow-right-linear" width="16"></iconify-icon>
            </button>
            <button onclick="calendarNav('next-year')" class="flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                <iconify-icon icon="solar:double-alt-arrow-right-linear" width="16"></iconify-icon>
            </button>
            <button onclick="calendarNav('today')" class="flex h-9 items-center px-4 rounded-full bg-violet-600 text-white text-sm font-medium hover:bg-violet-700 transition-colors">
                Today
            </button>
        </div>

        <!-- Month Title -->
        <h2 id="calendar-title" class="text-lg font-bold text-slate-800 dark:text-white text-center"></h2>

        <!-- View Toggle -->
        <div class="flex items-center rounded-lg border border-slate-200 dark:border-dark-border overflow-hidden text-sm">
            <button onclick="setView('month')" id="view-month" class="px-3 py-1.5 font-medium bg-violet-600 text-white transition-colors">Month</button>
            <button onclick="setView('week')" id="view-week" class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Week</button>
            <button onclick="setView('day')" id="view-day" class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Day</button>
            <button onclick="setView('list')" id="view-list" class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">List</button>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="px-6 pb-6">
        <!-- Day Labels -->
        <div class="grid grid-cols-7 border-t border-l border-slate-200 dark:border-dark-border">
            <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Sun</div>
            <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Mon</div>
            <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Tue</div>
            <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Wed</div>
            <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Thu</div>
            <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Fri</div>
            <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Sat</div>
        </div>
        <!-- Day Cells -->
        <div id="calendar-grid" class="grid grid-cols-7 border-l border-slate-200 dark:border-dark-border">
            <!-- Populated by JS -->
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div id="event-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40" onclick="closeModal()"></div>
    <!-- Modal -->
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl overflow-hidden shadow-2xl">
        <!-- Purple Header -->
        <div class="bg-violet-600 px-6 py-5 flex items-center justify-between">
            <h3 id="modal-title" class="text-white text-lg font-bold"></h3>
            <button onclick="closeModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-slate-700 hover:bg-slate-100 transition-colors font-bold text-base">✕</button>
        </div>
        <!-- White Body -->
        <div class="bg-white px-6 py-6">
            <form id="event-form" onsubmit="saveEvent(event)">
                <input type="hidden" id="event-date" name="event_date">
                @csrf
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-5">
                    <!-- Event Title -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold uppercase text-slate-500 tracking-wide">Event Title <span class="text-red-500">*</span></label>
                        <input type="text" id="event-title" name="event_title" required
                            class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent dark:border-dark-border dark:bg-dark-card dark:text-white">
                    </div>
                    <!-- Role -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold uppercase text-slate-500 tracking-wide">Role <span class="text-red-500">*</span></label>
                        <select id="event-role" name="role" required
                            class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent dark:border-dark-border dark:bg-dark-card dark:text-white">
                            <option value="" disabled selected>Select</option>
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                            <option value="parent">Parent</option>
                        </select>
                    </div>
                    <!-- Event Location -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold uppercase text-slate-500 tracking-wide">Event Location <span class="text-red-500">*</span></label>
                        <input type="text" id="event-location" name="event_location" required
                            class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent dark:border-dark-border dark:bg-dark-card dark:text-white">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 mb-6">
                    <!-- Description -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold uppercase text-slate-500 tracking-wide">Description <span class="text-red-500">*</span></label>
                        <textarea id="event-description" name="description" required rows="4"
                            class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent dark:border-dark-border dark:bg-dark-card dark:text-white resize-none"></textarea>
                    </div>
                    <!-- URL -->
                    <div class="flex flex-col gap-1">
                        <label class="text-xs font-semibold uppercase text-slate-500 tracking-wide">URL</label>
                        <textarea id="event-url" name="url" rows="4"
                            class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent dark:border-dark-border dark:bg-dark-card dark:text-white resize-none"></textarea>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="px-8 py-2.5 rounded-lg bg-violet-600 text-white text-sm font-bold uppercase tracking-widest hover:bg-violet-700 transition-colors">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- 
 Data Table -
<div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card">
    <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-dark-border">
        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Recent Transactions</h3>
        <div class="flex items-center gap-2">
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                <input type="text" placeholder="Filter..." class="h-9 w-40 rounded-lg border border-slate-200 bg-transparent pl-9 pr-3 text-sm text-slate-600 focus:border-brand-500 focus:outline-none dark:border-dark-border dark:text-slate-300">
            </div>
            <button class="flex h-9 items-center gap-2 rounded-lg border border-slate-200 px-3 text-sm font-medium text-slate-600 hover:bg-slate-50 dark:border-dark-border dark:text-slate-300 dark:hover:bg-white/5">
                <iconify-icon icon="solar:filter-linear"></iconify-icon>
                Filter
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/50 text-xs font-medium uppercase tracking-wide text-slate-500 dark:border-dark-border dark:bg-white/5 dark:text-slate-400">
                    <th class="p-4 w-4"><input type="checkbox" class="custom-checkbox h-4 w-4 rounded border-slate-300 text-brand-900 focus:ring-0 dark:border-slate-600 dark:bg-dark-bg"></th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Amount</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-dark-border text-sm">
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <td class="p-4"><input type="checkbox" class="custom-checkbox h-4 w-4 rounded border-slate-300 text-brand-900 focus:ring-0 dark:border-slate-600 dark:bg-dark-bg"></td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-brand-100 to-purple-100 dark:from-brand-900/40 dark:to-purple-900/40 flex items-center justify-center text-xs font-bold text-brand-900 dark:text-brand-200">JD</div>
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-900 dark:text-white">John Doe</span>
                                <span class="text-xs text-slate-500">john@example.com</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4"><span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400">Paid</span></td>
                    <td class="p-4 text-slate-500 dark:text-slate-400">Oct 24, 2023</td>
                    <td class="p-4 font-medium text-slate-900 dark:text-white">$350.00</td>
                    <td class="p-4 text-right"><button class="text-slate-400 hover:text-brand-900 dark:hover:text-white transition-colors"><iconify-icon icon="solar:menu-dots-linear" width="20"></iconify-icon></button></td>
                </tr>
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <td class="p-4"><input type="checkbox" class="custom-checkbox h-4 w-4 rounded border-slate-300 text-brand-900 focus:ring-0 dark:border-slate-600 dark:bg-dark-bg"></td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-orange-100 to-amber-100 dark:from-orange-900/40 dark:to-amber-900/40 flex items-center justify-center text-xs font-bold text-orange-800 dark:text-orange-200">SM</div>
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-900 dark:text-white">Sarah Miller</span>
                                <span class="text-xs text-slate-500">sarah@studio.io</span> 
                            </div>
                        </div>
                      
                    </td>
                    <td class="p-4"><span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/20 dark:text-amber-400">Pending</span></td>
                    <td class="p-4 text-slate-500 dark:text-slate-400">Oct 23, 202fdfdfd</td>
                    <td class="p-4 font-medium text-slate-900 dark:text-white">$1,200.00</td>
                    <td class="p-4 text-right"><button class="text-slate-400 hover:text-brand-900 dark:hover:text-white transition-colors"><iconify-icon icon="solar:menu-dots-linear" width="20"></iconify-icon></button></td>
                </tr>
                <tr class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                    <td class="p-4"><input type="checkbox" class="custom-checkbox h-4 w-4 rounded border-slate-300 text-brand-900 focus:ring-0 dark:border-slate-600 dark:bg-dark-bg"></td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-blue-100 to-cyan-100 dark:from-blue-900/40 dark:to-cyan-900/40 flex items-center justify-center text-xs font-bold text-blue-800 dark:text-blue-200">MK</div>
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-900 dark:text-white">Mike K.</span>
                                <span class="text-xs text-slate-500">mike@tech.co</span>
                            </div>
                        </div>
                    </td>
                    <td class="p-4"><span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400">Paid</span></td>
                    <td class="p-4 text-slate-500 dark:text-slate-400">Oct 21, 2023</td>
                    <td class="p-4 font-medium text-slate-900 dark:text-white">$850.00</td>
                    <td class="p-4 text-right"><button class="text-slate-400 hover:text-brand-900 dark:hover:text-white transition-colors"><iconify-icon icon="solar:menu-dots-linear" width="20"></iconify-icon></button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4 dark:border-dark-border">
        <span class="text-xs text-slate-500 dark:text-slate-400">Showing 1-3 of 24 results</span>
        <div class="flex items-center gap-2">
            <button class="flex h-8 w-8 items-center justify-center rounded border border-slate-200 text-slate-500 hover:bg-slate-50 disabled:opacity-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5">
                <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
            </button>
            <button class="flex h-8 w-8 items-center justify-center rounded border border-slate-200 bg-brand-900 text-white shadow-sm dark:border-brand-900">1</button>
            <button class="flex h-8 w-8 items-center justify-center rounded border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5">2</button>
            <button class="flex h-8 w-8 items-center justify-center rounded border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5">
                <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
            </button>
        </div>
    </div>
</div>

                <div class="mt-8 text-center"> -->
                    <p class="text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
                </div>

            </div>
        </main>
    </div>

</body></html>