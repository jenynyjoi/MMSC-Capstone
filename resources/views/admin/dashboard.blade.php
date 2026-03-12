
<!-- <!DOCTYPE html>
<html>
<body>
    <h1>✅ Admin Dashboard Works!</h1>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html> -->


@extends('layout.admin_layout')

@section('title', 'Welcome Admin')

@section('content')

    <!-- Scrollable Content Wrapper -->
    <div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

        <!-- Page Header -->
        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Overview</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Welcome back, here's what's happening today.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex h-9 items-center rounded-lg border border-slate-200 bg-white px-3 shadow-sm dark:border-dark-border dark:bg-dark-card">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Oct 24, 2023</span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <!-- Card 1: Students -->
            <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
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
            <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
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
            <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
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
            <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
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
        {{-- end stats grid --}}

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
        {{-- end quick action & announcement --}}

        <!-- Charts Section -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

            <!-- Left Column: Charts -->
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
                            <select class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-600 focus:outline-none dark:border-dark-border dark:bg-dark-card dark:text-slate-400">
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
            {{-- end left column --}}

            <!-- Right Column -->
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
            {{-- end right column --}}

        </div>
        {{-- end charts section --}}

        <!-- Calendar Section -->
        <div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card">

            <!-- Calendar Header -->
            <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between dark:border-dark-border">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Calendar</h3>
            </div>

            <!-- Calendar Controls -->
            <div class="flex flex-col gap-4 px-6 pt-5 pb-3 sm:flex-row sm:items-center sm:justify-between">
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

                <h2 id="calendar-title" class="text-lg font-bold text-slate-800 dark:text-white text-center"></h2>

                <div class="flex items-center rounded-lg border border-slate-200 dark:border-dark-border overflow-hidden text-sm">
                    <button onclick="setView('month')" id="view-month" class="px-3 py-1.5 font-medium bg-violet-600 text-white transition-colors">Month</button>
                    <button onclick="setView('week')" id="view-week" class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Week</button>
                    <button onclick="setView('day')" id="view-day" class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Day</button>
                    <button onclick="setView('list')" id="view-list" class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">List</button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="px-6 pb-6">
                <div class="grid grid-cols-7 border-t border-l border-slate-200 dark:border-dark-border">
                    <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Sun</div>
                    <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Mon</div>
                    <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Tue</div>
                    <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Wed</div>
                    <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Thu</div>
                    <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Fri</div>
                    <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">Sat</div>
                </div>
                <div id="calendar-grid" class="grid grid-cols-7 border-l border-slate-200 dark:border-dark-border">
                    {{-- Populated by JS --}}
                </div>
            </div>

        </div>
        {{-- end calendar --}}

        <!-- Add Event Modal -->
        <div id="event-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
            <div class="absolute inset-0 bg-black/40" onclick="closeModal()"></div>
            <div class="relative w-full max-w-2xl mx-4 rounded-2xl overflow-hidden shadow-2xl">
                <div class="bg-violet-600 px-6 py-5 flex items-center justify-between">
                    <h3 id="modal-title" class="text-white text-lg font-bold"></h3>
                    <button onclick="closeModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-slate-700 hover:bg-slate-100 transition-colors font-bold text-base">✕</button>
                </div>
                <div class="bg-white px-6 py-6">
                    <form id="event-form" onsubmit="saveEvent(event)">
                        <input type="hidden" id="event-date" name="event_date">
                        @csrf
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-5">
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-semibold uppercase text-slate-500 tracking-wide">Event Title <span class="text-red-500">*</span></label>
                                <input type="text" id="event-title" name="event_title" required
                                    class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent dark:border-dark-border dark:bg-dark-card dark:text-white">
                            </div>
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
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-semibold uppercase text-slate-500 tracking-wide">Event Location <span class="text-red-500">*</span></label>
                                <input type="text" id="event-location" name="event_location" required
                                    class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent dark:border-dark-border dark:bg-dark-card dark:text-white">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 mb-6">
                            <div class="flex flex-col gap-1">
                                <label class="text-xs font-semibold uppercase text-slate-500 tracking-wide">Description <span class="text-red-500">*</span></label>
                                <textarea id="event-description" name="description" required rows="4"
                                    class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent dark:border-dark-border dark:bg-dark-card dark:text-white resize-none"></textarea>
                            </div>
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
        {{-- end modal --}}

        <!-- Footer -->
        <p class="mt-8 text-center text-xs text-slate-400">
            © 2026 My Messiah School of Cavite. All rights reserved.
        </p>

    </div>
    {{-- end scrollable content wrapper --}}

@endsection