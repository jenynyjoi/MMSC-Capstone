@extends('layouts.admin_layout')

@section('title', 'Welcome Admin')

@section('content')

    {{-- ── Scrollable Content Wrapper ── --}}
    <div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

        {{-- ── Page Header ── --}}
        
        <x-admin.page-header
            title="Overview"
            subtitle="Welcome back, here's what's happening today."
            school-year="{{ $activeSchoolYear }}"
            :show-menu="true"
            
        />

        {{-- ── Stats Grid ── --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 mb-3">
                            <iconify-icon icon="solar:users-group-rounded-linear" width="22" class="text-blue-500 dark:text-blue-300"></iconify-icon>
                        </div>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Students</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Total Enrolled Students</p>
                    </div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $totalStudents }}</h3>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 mb-3">
                            <iconify-icon icon="solar:user-id-linear" width="22" class="text-yellow-500 dark:text-yellow-300"></iconify-icon>
                        </div>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Teachers</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Total Teachers</p>
                    </div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $totalTeachers }}</h3>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-red-50 dark:bg-red-900/20 mb-3">
                            <iconify-icon icon="solar:users-group-two-rounded-linear" width="22" class="text-red-500 dark:text-red-300"></iconify-icon>
                        </div>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Parents</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Total Parents</p>
                    </div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $totalParents }}</h3>
                </div>
            </div>

            <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] transition-all hover:-translate-y-1 hover:shadow-lg dark:border-dark-border dark:bg-dark-card">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-50 dark:bg-green-900/20 mb-3">
                            <iconify-icon icon="solar:widget-linear" width="22" class="text-green-600 dark:text-green-300"></iconify-icon>
                        </div>
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Sections</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Total Active Sections</p>
                    </div>
                    <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $totalSections }}</h3>
                </div>
            </div>

        </div>

        {{-- ── Quick Action, Latest Announcements & Gender Breakdown ── --}}
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
                        <iconify-icon icon="solar:bolt-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Quick Action</h3>
                </div>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('admin.enrollment.enroll') }}"
                       class="w-full rounded-lg border border-blue-400 py-2.5 text-sm font-medium text-blue-500 transition hover:bg-blue-50 dark:border-blue-500 dark:text-blue-400 dark:hover:bg-blue-900/20 text-center flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:user-plus-rounded-linear" width="16"></iconify-icon>Enroll Student
                    </a>
                    <a href="{{ route('admin.clearance.summary') }}"
                       class="w-full rounded-lg border border-yellow-400 py-2.5 text-sm font-medium text-yellow-500 transition hover:bg-yellow-50 dark:border-yellow-500 dark:text-yellow-400 dark:hover:bg-yellow-900/20 text-center flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:shield-check-linear" width="16"></iconify-icon>Update Clearance
                    </a>
                    <a href="{{ route('admin.schedule.class') }}"
                       class="w-full rounded-lg border border-green-400 py-2.5 text-sm font-medium text-green-600 transition hover:bg-green-50 dark:border-green-500 dark:text-green-400 dark:hover:bg-green-900/20 text-center flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:calendar-linear" width="16"></iconify-icon>View Schedules
                    </a>
                    <a href="{{ route('admin.announcements') }}"
                       class="w-full rounded-lg border border-red-400 py-2.5 text-sm font-medium text-red-500 transition hover:bg-red-50 dark:border-red-500 dark:text-red-400 dark:hover:bg-red-900/20 text-center flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:bell-linear" width="16"></iconify-icon>Post Announcement
                    </a>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
                <div class="mb-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
                            <iconify-icon icon="solar:document-text-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                        </div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Latest Announcements</h3>
                    </div>
                    <a href="{{ route('admin.announcements') }}"
                       class="flex h-8 items-center gap-1.5 px-3 rounded-lg border border-slate-200 text-xs font-medium text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                        View all
                        <iconify-icon icon="solar:arrow-right-linear" width="12"></iconify-icon>
                    </a>
                </div>

                @if($latestAnnouncements->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <iconify-icon icon="solar:document-text-linear" width="36" class="text-slate-300 dark:text-slate-600 mb-2"></iconify-icon>
                        <p class="text-sm text-slate-400 dark:text-slate-500">No announcements yet.</p>
                        <a href="{{ route('admin.announcements') }}" class="mt-2 text-xs text-blue-500 hover:underline">Post one now</a>
                    </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-medium text-slate-500 dark:text-slate-400">
                                <th class="pb-3 pr-4">Title</th>
                                <th class="pb-3 pr-4 whitespace-nowrap">Date</th>
                                <th class="pb-3 pr-4">Posted By</th>
                                <th class="pb-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                            @foreach($latestAnnouncements as $ann)
                            @php
                                $dot = match($ann->importance) {
                                    'high'   => 'bg-red-500',
                                    'medium' => 'bg-yellow-400',
                                    default  => 'bg-green-500',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full {{ $dot }} shrink-0"></span>
                                        <span class="text-slate-700 dark:text-slate-300 text-xs truncate max-w-[130px]" title="{{ $ann->title }}">{{ $ann->title }}</span>
                                    </div>
                                </td>
                                <td class="py-3 pr-4 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                    {{ $ann->created_at->format('d M, Y') }}
                                </td>
                                <td class="py-3 pr-4 text-xs text-slate-600 dark:text-slate-300 truncate max-w-[100px]">{{ $ann->posted_by }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.announcements') }}"
                                       class="text-slate-400 hover:text-[#0d4c8f] dark:hover:text-white transition-colors">
                                        <iconify-icon icon="solar:eye-linear" width="18"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- ── Gender Breakdown Card ── --}}
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
                <div class="mb-5 flex items-center gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 dark:bg-white/10">
                        <iconify-icon icon="solar:users-group-rounded-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Student Gender</h3>
                </div>

                @php
                    $femalePct = $totalStudents > 0 ? round($femaleStudents / $totalStudents * 100) : 0;
                    $malePct   = $totalStudents > 0 ? round($maleStudents   / $totalStudents * 100) : 0;
                @endphp

                {{-- Female --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-pink-50 dark:bg-pink-900/20">
                                <iconify-icon icon="solar:women-linear" width="18" class="text-pink-500 dark:text-pink-300"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Female</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $femalePct }}% of total</p>
                            </div>
                        </div>
                        <span class="text-2xl font-bold text-pink-500 dark:text-pink-400">{{ $femaleStudents }}</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-pink-100 dark:bg-pink-900/20">
                        <div class="h-2 rounded-full bg-pink-400 transition-all duration-500" style="width: {{ $femalePct }}%"></div>
                    </div>
                </div>

                {{-- Male --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-900/20">
                                <iconify-icon icon="solar:men-linear" width="18" class="text-sky-500 dark:text-sky-300"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">Male</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $malePct }}% of total</p>
                            </div>
                        </div>
                        <span class="text-2xl font-bold text-sky-500 dark:text-sky-400">{{ $maleStudents }}</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-sky-100 dark:bg-sky-900/20">
                        <div class="h-2 rounded-full bg-sky-400 transition-all duration-500" style="width: {{ $malePct }}%"></div>
                    </div>
                </div>

                {{-- Combined bar --}}
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-dark-border">
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mb-1.5">Distribution</p>
                    <div class="flex h-2.5 w-full overflow-hidden rounded-full">
                        <div class="h-full bg-pink-400 transition-all duration-500" style="width: {{ $femalePct }}%"></div>
                        <div class="h-full bg-sky-400 transition-all duration-500" style="width: {{ $malePct }}%"></div>
                    </div>
                    <div class="mt-2 flex justify-between text-[10px] text-slate-400">
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-pink-400 inline-block"></span> Female {{ $femalePct }}%</span>
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-sky-400 inline-block"></span> Male {{ $malePct }}%</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Charts Section ── --}}
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="flex flex-col gap-6 lg:col-span-2">

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

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
                    <div class="mb-2 flex items-center gap-2">
                        <iconify-icon icon="solar:chart-2-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Student Movement Trends</h3>
                    </div>
                    <div class="mb-4 flex items-center gap-4">
                        <div class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-green-500"></span><span class="text-xs text-slate-500 dark:text-slate-400">Enrollments</span></div>
                        <div class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-red-500"></span><span class="text-xs text-slate-500 dark:text-slate-400">Withdrawals</span></div>
                    </div>
                    <div class="relative h-64 w-full">
                        <canvas id="movementChart"></canvas>
                    </div>
                </div>

            </div>

            <div class="flex flex-col gap-6">

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card" id="reminders-widget">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="solar:bell-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Reminders</h3>
                        </div>
                        <button onclick="toggleReminderInput()" title="Add reminder"
                            class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 dark:border-dark-border dark:text-slate-400 dark:hover:bg-blue-900/20 font-bold text-base transition-colors">+</button>
                    </div>

                    {{-- Add reminder input (hidden by default) --}}
                    <div id="reminder-input-row" style="display:none" class="flex gap-2 mb-3">
                        <input id="reminder-text" type="text" placeholder="Type a reminder…" maxlength="120"
                            class="flex-1 rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onkeydown="if(event.key==='Enter')saveReminder(); if(event.key==='Escape')toggleReminderInput();">
                        <button onclick="saveReminder()"
                            class="shrink-0 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-semibold px-3 py-2 transition-colors">Save</button>
                    </div>

                    <div id="reminders-list" class="flex flex-col gap-2">
                        {{-- Populated by JS from localStorage --}}
                    </div>
                    <p id="reminders-empty" class="text-xs text-slate-400 dark:text-slate-500 text-center py-4 hidden">No reminders. Click <strong>+</strong> to add one.</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-dark-border dark:bg-dark-card">
                    <div class="mb-4 flex items-center gap-2">
                        <iconify-icon icon="solar:users-group-rounded-linear" width="20" class="text-slate-700 dark:text-slate-300"></iconify-icon>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Student Status</h3>
                    </div>
                    <div class="relative h-44 w-full flex items-center justify-center">
                        <canvas id="studentStatusChart"></canvas>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-1.5">
                        <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-blue-500 shrink-0"></span><span class="text-xs text-slate-500 dark:text-slate-400">Active <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $studentStatusData['active'] }}</span></span></div>
                        <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-yellow-400 shrink-0"></span><span class="text-xs text-slate-500 dark:text-slate-400">Graduated <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $studentStatusData['graduated'] }}</span></span></div>
                        <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-green-500 shrink-0"></span><span class="text-xs text-slate-500 dark:text-slate-400">Completed <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $studentStatusData['completed'] }}</span></span></div>
                        <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-slate-400 shrink-0"></span><span class="text-xs text-slate-500 dark:text-slate-400">Inactive <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $studentStatusData['inactive'] }}</span></span></div>
                        <div class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-red-500 shrink-0"></span><span class="text-xs text-slate-500 dark:text-slate-400">Withdrawn <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $studentStatusData['withdrawn'] }}</span></span></div>
                    </div>
                </div>

            </div>

        </div>

        {{-- ── School Calendar Widget (view only) ── --}}
        <div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm dark:border-dark-border dark:bg-dark-card" id="school-calendar-widget">

            {{-- Widget Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:calendar-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">School Calendar</h3>
                </div>
                {{-- Edit button → goes to school calendar page --}}
                <a href="{{ route('admin.school-calendar.index') }}"
                   class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border
                          bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5
                          px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors">
                    <iconify-icon icon="solar:pen-2-linear" width="14"></iconify-icon>
                    Edit
                </a>
            </div>

            {{-- Calendar Controls (view only — navigation disabled on dashboard) --}}
            <div class="flex flex-col gap-4 px-6 pt-5 pb-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <button onclick="calNav('prev-year')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:double-alt-arrow-left-linear" width="14"></iconify-icon>
                    </button>
                    <button onclick="calNav('prev-month')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:alt-arrow-left-linear" width="14"></iconify-icon>
                    </button>
                    <button onclick="calNav('next-month')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14"></iconify-icon>
                    </button>
                    <button onclick="calNav('next-year')" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 hover:bg-slate-50 dark:border-dark-border dark:text-slate-400 dark:hover:bg-white/5 transition-colors">
                        <iconify-icon icon="solar:double-alt-arrow-right-linear" width="14"></iconify-icon>
                    </button>
                    <button onclick="calNav('today')" class="flex h-8 items-center px-4 rounded-full bg-[#0d4c8f] text-white text-xs font-medium hover:bg-blue-700 transition-colors">
                        Today
                    </button>
                </div>

                <h2 id="cal-title" class="text-base font-bold text-slate-800 dark:text-white text-center"></h2>

                <div class="flex items-center rounded-lg border border-slate-200 dark:border-dark-border overflow-hidden text-xs">
                    <button id="cal-view-month" onclick="setCalView('month')" class="px-3 py-1.5 font-medium bg-[#0d4c8f] text-white transition-colors">Month</button>
                    <button id="cal-view-week"  onclick="setCalView('week')"  class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Week</button>
                    <button id="cal-view-day"   onclick="setCalView('day')"   class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Day</button>
                    <button id="cal-view-list"  onclick="setCalView('list')"  class="px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">List</button>
                </div>
            </div>

            {{-- Calendar Grid --}}
            <div class="px-6 pb-4">
                <div class="grid grid-cols-7 border-t border-l border-slate-200 dark:border-dark-border">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                    <div class="border-r border-b border-slate-200 dark:border-dark-border py-2 text-center text-xs font-medium text-slate-500 dark:text-slate-400">{{ $day }}</div>
                    @endforeach
                </div>
                <div id="cal-grid" class="grid grid-cols-7 border-l border-slate-200 dark:border-dark-border">
                    {{-- Populated by JS --}}
                </div>
            </div>

            {{-- Legend --}}
            <div class="px-6 pb-5 flex flex-wrap items-center gap-x-4 gap-y-2">
                <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">Legend</span>
                @foreach([
                    ['bg-purple-200', 'Holiday'],
                    ['bg-red-300',    'Suspended'],
                    ['bg-yellow-200', 'School Event'],
                    ['bg-amber-200',  'Early Dismissal'],
                    ['bg-blue-200',   'Exam Day'],
                    ['bg-orange-200', 'Break'],
                    ['bg-slate-100',  'Weekend/No Classes'],
                ] as $leg)
                <div class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-full {{ $leg[0] }} shrink-0"></span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $leg[1] }}</span>
                </div>
                @endforeach
            </div>

        </div>
        {{-- end school calendar widget --}}

        <p class="mt-8 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

    </div>

{{-- ── Inline data for dashboard.js (must run before the module initialises charts) ── --}}
<script>
    window.mmscStatusData = @json($studentStatusData);
</script>

@push('scripts')
<script>
    // ── Event data from database ──
    const calEvents = @json($calEvents ?? []);
 
    let calCurrent = new Date();
    let calView = 'month';
 
    // ── Helpers ──────────────────────────────────────────────
    function dateStr(y, m, d) {
        return `${y}-${String(m + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
    }
    function isWeekend(col) { return col % 7 === 0 || col % 7 === 6; }
    function getCalendarEvent(ds) {
        return calEvents[ds] || null;
    }
    function renderBadge(ds, compact = false) {
        const ev = getCalendarEvent(ds);
        if (ev) {
            const desc = ev.description
                ? `<div class="${compact ? 'mt-0' : 'mt-0.5'} opacity-75 truncate">${ev.description}</div>`
                : '';
            return `<div class="rounded ${compact ? 'px-1.5 py-0.5 text-[10px]' : 'px-2 py-1 text-[11px]'} leading-tight mb-0.5 ${ev.badge_class}">
                        <div class="truncate">${ev.label || ''}</div>${desc}
                    </div>`;
        }

        return '';
    }
 
    // ── Month View ───────────────────────────────────────────
    function renderMonth() {
        const grid  = document.getElementById('cal-grid');
        const title = document.getElementById('cal-title');
        if (!grid || !title) return;
 
        const y = calCurrent.getFullYear(), m = calCurrent.getMonth();
        const today = new Date();
        title.textContent = calCurrent.toLocaleString('default', { month: 'long', year: 'numeric' });
 
        const firstDay    = new Date(y, m, 1).getDay();
        const daysInMonth = new Date(y, m + 1, 0).getDate();
        const daysInPrev  = new Date(y, m, 0).getDate();
        const totalCells  = Math.ceil((firstDay + daysInMonth) / 7) * 7;
 
        let html = '';
        for (let i = 0; i < totalCells; i++) {
            let day, isCurrent = true, ds = '';
            const col = i % 7;
 
            if (i < firstDay) {
                day = daysInPrev - firstDay + i + 1;
                isCurrent = false;
                const pm = m === 0 ? 11 : m - 1, py = m === 0 ? y - 1 : y;
                ds = dateStr(py, pm, day);
            } else if (i >= firstDay + daysInMonth) {
                day = i - firstDay - daysInMonth + 1;
                isCurrent = false;
                const nm = m === 11 ? 0 : m + 1, ny = m === 11 ? y + 1 : y;
                ds = dateStr(ny, nm, day);
            } else {
                day = i - firstDay + 1;
                ds  = dateStr(y, m, day);
            }
 
            const isToday   = isCurrent && day === today.getDate() && m === today.getMonth() && y === today.getFullYear();
            const weekend   = col === 0 || col === 6;
            const event     = getCalendarEvent(ds);
            const cellBg    = weekend && isCurrent ? 'bg-slate-50 dark:bg-white/[0.02]' : '';
 
            html += `<div class="border-r border-b border-slate-200 dark:border-dark-border min-h-[92px] p-2 ${cellBg}">`;
            html += `<div class="flex justify-end mb-1">
                        <span class="text-xs font-medium w-6 h-6 flex items-center justify-center rounded-full
                            ${isToday ? 'bg-[#0d4c8f] text-white' : isCurrent ? 'text-slate-700 dark:text-slate-300' : 'text-slate-300 dark:text-slate-600'}">
                            ${day}
                        </span>
                     </div>`;

            if (isCurrent && !weekend) {
                html += renderBadge(ds, true);
            } else if (event) {
                html += renderBadge(ds, true);
            }

            html += `</div>`;
        }
        grid.innerHTML = html;
    }

    // ── Week View ────────────────────────────────────────────
    function renderWeek() {
        const grid  = document.getElementById('cal-grid');
        const title = document.getElementById('cal-title');
        if (!grid || !title) return;
 
        // Find Monday (or Sunday) of current week
        const d    = new Date(calCurrent);
        const day  = d.getDay(); // 0 = Sun
        d.setDate(d.getDate() - day); // go to Sunday
 
        const days = [];
        for (let i = 0; i < 7; i++) {
            const dd = new Date(d);
            dd.setDate(d.getDate() + i);
            days.push(dd);
        }
 
        const startLabel = days[0].toLocaleDateString('default', { month: 'short', day: 'numeric' });
        const endLabel   = days[6].toLocaleDateString('default', { month: 'short', day: 'numeric', year: 'numeric' });
        title.textContent = `${startLabel} – ${endLabel}`;
 
        const today = new Date();
        let html = '';
 
        days.forEach((dd, col) => {
            const ds      = dateStr(dd.getFullYear(), dd.getMonth(), dd.getDate());
            const event   = getCalendarEvent(ds);
            const weekend = col === 0 || col === 6;
            const isToday = dd.toDateString() === today.toDateString();
            const cellBg  = weekend ? 'bg-slate-50 dark:bg-white/[0.02]' : '';
 
            html += `<div class="border-r border-b border-slate-200 dark:border-dark-border min-h-[120px] p-2 ${cellBg}">`;
            html += `<div class="flex justify-center mb-2">
                        <span class="text-xs font-medium w-7 h-7 flex items-center justify-center rounded-full
                            ${isToday ? 'bg-[#0d4c8f] text-white' : 'text-slate-700 dark:text-slate-300'}">
                            ${dd.getDate()}
                        </span>
                     </div>`;
 
            if (!weekend || event) {
                html += renderBadge(ds, true);
            }

            html += `</div>`;
        });

        grid.innerHTML = html;
    }

    // ── Day View ─────────────────────────────────────────────
    function renderDay() {
        const grid  = document.getElementById('cal-grid');
        const title = document.getElementById('cal-title');
        if (!grid || !title) return;
 
        const y   = calCurrent.getFullYear();
        const m   = calCurrent.getMonth();
        const day = calCurrent.getDate();
        const ds  = dateStr(y, m, day);
        const col = calCurrent.getDay();
 
        title.textContent = calCurrent.toLocaleDateString('default', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
 
        const event   = getCalendarEvent(ds);
        const weekend = col === 0 || col === 6;
 
        let html = `<div class="col-span-7 border-r border-b border-slate-200 dark:border-dark-border min-h-[160px] p-4">`;
 
        if (weekend) {
            html += `<p class="text-sm text-slate-400 dark:text-slate-500 italic">Weekend — No Classes</p>`;
        }

        if (!weekend || event) {
            html += renderBadge(ds);
        }
 
        html += `</div>`;
        grid.innerHTML = html;
    }
 
    // ── List View ────────────────────────────────────────────
    function renderList() {
        const grid  = document.getElementById('cal-grid');
        const title = document.getElementById('cal-title');
        if (!grid || !title) return;
 
        const y = calCurrent.getFullYear(), m = calCurrent.getMonth();
        const daysInMonth = new Date(y, m + 1, 0).getDate();
        title.textContent = calCurrent.toLocaleString('default', { month: 'long', year: 'numeric' });
 
        let html = `<div class="col-span-7 divide-y divide-slate-100 dark:divide-dark-border">`;
 
        for (let d = 1; d <= daysInMonth; d++) {
            const ds  = dateStr(y, m, d);
            const dow = new Date(y, m, d).getDay();
            const weekend = dow === 0 || dow === 6;
            const event   = getCalendarEvent(ds);
            const dayLabel = new Date(y, m, d).toLocaleDateString('default', { weekday: 'short', day: 'numeric' });
 
            if (weekend && !event) continue; // skip empty weekends in list
 
            html += `<div class="flex items-start gap-4 px-4 py-3">`;
            html += `<span class="text-xs font-medium text-slate-500 dark:text-slate-400 w-16 shrink-0 pt-0.5">${dayLabel}</span>`;
            html += `<div class="flex flex-wrap gap-1">`;
 
            if (!weekend || event) {
                html += renderBadge(ds, true);
            }
 
            html += `</div></div>`;
        }
 
        html += `</div>`;
        grid.innerHTML = html;
    }
 
    // ── Router ───────────────────────────────────────────────
    function renderCal() {
        if      (calView === 'month') renderMonth();
        else if (calView === 'week')  renderWeek();
        else if (calView === 'day')   renderDay();
        else if (calView === 'list')  renderList();
    }
 
    // ── Navigation ───────────────────────────────────────────
    function calNav(dir) {
        if      (dir === 'prev-month') {
            if (calView === 'week') calCurrent.setDate(calCurrent.getDate() - 7);
            else if (calView === 'day') calCurrent.setDate(calCurrent.getDate() - 1);
            else calCurrent.setMonth(calCurrent.getMonth() - 1);
        }
        else if (dir === 'next-month') {
            if (calView === 'week') calCurrent.setDate(calCurrent.getDate() + 7);
            else if (calView === 'day') calCurrent.setDate(calCurrent.getDate() + 1);
            else calCurrent.setMonth(calCurrent.getMonth() + 1);
        }
        else if (dir === 'prev-year') {
            if (calView === 'day') calCurrent.setDate(calCurrent.getDate() - 30);
            else calCurrent.setFullYear(calCurrent.getFullYear() - 1);
        }
        else if (dir === 'next-year') {
            if (calView === 'day') calCurrent.setDate(calCurrent.getDate() + 30);
            else calCurrent.setFullYear(calCurrent.getFullYear() + 1);
        }
        else if (dir === 'today') calCurrent = new Date();
        renderCal();
    }
 
    // ── View Toggle ──────────────────────────────────────────
    function setCalView(v) {
        calView = v;
        ['month','week','day','list'].forEach(x => {
            const btn = document.getElementById('cal-view-' + x);
            if (!btn) return;
            btn.className = x === v
                ? 'px-3 py-1.5 font-medium bg-[#0d4c8f] text-white transition-colors text-xs'
                : 'px-3 py-1.5 font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors text-xs';
        });
        renderCal();
    }
 
    document.addEventListener('DOMContentLoaded', renderCal);

    // ── Student Status Chart — update with real DB data ──────
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('studentStatusChart');
        if (!canvas || !window.Chart) return;
        const chart = window.Chart.getChart(canvas);
        if (!chart) return;
        const d = window.mmscStatusData || {};
        chart.data.datasets[0].data = [
            d.active    || 0,
            d.graduated || 0,
            d.completed || 0,
            d.inactive  || 0,
            d.withdrawn || 0,
        ];
        chart.update();
    });

    // ── Reminders (localStorage) ─────────────────────────────
    const RKEY = 'mmsc_reminders_{{ auth()->id() }}';

    function loadReminders() {
        try { return JSON.parse(localStorage.getItem(RKEY) || '[]'); }
        catch { return []; }
    }
    function saveReminders(list) {
        localStorage.setItem(RKEY, JSON.stringify(list));
    }

    function renderReminders() {
        const list  = loadReminders();
        const wrap  = document.getElementById('reminders-list');
        const empty = document.getElementById('reminders-empty');
        if (!wrap) return;

        wrap.innerHTML = '';
        if (list.length === 0) {
            empty && empty.classList.remove('hidden');
            return;
        }
        empty && empty.classList.add('hidden');

        list.forEach((r, idx) => {
            const isPending = !r.done;
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between rounded-lg border px-3 py-2.5 gap-2 '
                + (isPending
                    ? 'border-yellow-200 bg-yellow-50 dark:border-yellow-800/40 dark:bg-yellow-900/10'
                    : 'border-slate-200 bg-slate-50 dark:border-dark-border dark:bg-white/5');
            item.innerHTML = `
                <button onclick="toggleReminderDone(${idx})" class="flex items-center gap-2 flex-1 text-left min-w-0">
                    <iconify-icon icon="${isPending ? 'solar:clock-circle-linear' : 'solar:check-circle-bold'}"
                        width="16" class="${isPending ? 'text-yellow-500 shrink-0' : 'text-green-500 shrink-0'}"></iconify-icon>
                    <span class="text-xs text-slate-700 dark:text-slate-300 truncate ${r.done ? 'line-through opacity-50' : ''}">${escHtml(r.text)}</span>
                </button>
                <button onclick="deleteReminder(${idx})" class="shrink-0 text-slate-300 hover:text-red-500 dark:text-slate-600 dark:hover:text-red-400 transition-colors">
                    <iconify-icon icon="solar:trash-bin-trash-linear" width="15"></iconify-icon>
                </button>`;
            wrap.appendChild(item);
        });
    }

    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    window.toggleReminderInput = function () {
        const row = document.getElementById('reminder-input-row');
        if (!row) return;
        const shown = row.style.display !== 'none';
        row.style.display = shown ? 'none' : 'flex';
        if (!shown) setTimeout(() => document.getElementById('reminder-text')?.focus(), 50);
    };

    window.saveReminder = function () {
        const inp = document.getElementById('reminder-text');
        const text = (inp?.value || '').trim();
        if (!text) return;
        const list = loadReminders();
        list.unshift({ text, done: false, created: Date.now() });
        saveReminders(list);
        inp.value = '';
        document.getElementById('reminder-input-row').style.display = 'none';
        renderReminders();
    };

    window.deleteReminder = function (idx) {
        const list = loadReminders();
        list.splice(idx, 1);
        saveReminders(list);
        renderReminders();
    };

    window.toggleReminderDone = function (idx) {
        const list = loadReminders();
        if (list[idx]) list[idx].done = !list[idx].done;
        saveReminders(list);
        renderReminders();
    };

    document.addEventListener('DOMContentLoaded', renderReminders);
</script>
@endpush

@endsection
