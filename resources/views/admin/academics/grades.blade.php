@extends('layouts.admin_layout')
@section('title', 'Academics – Grades')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4 pb-24"
     x-data="{ activeTab: 'release' }">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Academics"
        subtitle="Student Academic Record"
        school-year="2025–2026"
    />

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Tabs --}}
        <div class="flex border-b border-slate-200 dark:border-dark-border overflow-x-auto">
            <button @click="activeTab = 'release'"
                :class="activeTab === 'release' ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold' : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-6 py-4 text-sm -mb-px transition-colors whitespace-nowrap">
                Release of Grades
            </button>
            <button @click="activeTab = 'classrecord'"
                :class="activeTab === 'classrecord' ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold' : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-6 py-4 text-sm -mb-px transition-colors whitespace-nowrap">
                Class Record – Quarterly Report
            </button>
            <button @click="activeTab = 'print'"
                :class="activeTab === 'print' ? 'text-[#0d4c8f] border-b-2 border-[#0d4c8f] font-semibold' : 'text-slate-500 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-6 py-4 text-sm -mb-px transition-colors whitespace-nowrap">
                Print Grade Report
            </button>
        </div>

        {{-- ══ TAB 1: RELEASE OF GRADES ══ --}}
        <div x-show="activeTab === 'release'" x-cloak x-transition.opacity>

            {{-- Section Header --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:clipboard-check-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Manage Grade Release</h2>
                    <span class="ml-2 text-xs text-slate-400">
                        Today: <span class="font-semibold text-orange-500">{{ now()->format('F d, Y') }}</span>
                    </span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors">
                        <iconify-icon icon="solar:check-circle-linear" width="13" class="text-green-600"></iconify-icon>
                        Approve All Visible
                    </button>
                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors">
                        <iconify-icon icon="solar:upload-linear" width="13" class="text-blue-600"></iconify-icon>
                        Publish All Approved
                    </button>
                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors">
                        <iconify-icon icon="solar:letter-linear" width="13" class="text-slate-500"></iconify-icon>
                        Notify All Parents
                    </button>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                @foreach([
                    ['green', 'solar:check-circle-bold',  'text-green-600',  0, 'Approved',     'Ready to Publish'],
                    ['red',   'solar:close-circle-bold',  'text-red-500',    0, 'Not Approved',  'Not visible'],
                    ['blue',  'solar:upload-square-bold', 'text-blue-500',   0, 'Published',     'Visible to students'],
                ] as [$color, $icon, $clr, $count, $label, $sub])
                <div class="flex items-center gap-4 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 dark:bg-{{ $color }}-900/10 px-5 py-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30">
                        <iconify-icon icon="{{ $icon }}" width="20" class="{{ $clr }}"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $count }}</p>
                        <p class="text-xs font-medium text-slate-600 dark:text-slate-300">{{ $label }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ $sub }}</p>
                    </div>
                </div>
                @endforeach
            </div>

       {{-- Filters --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <div class="flex flex-wrap items-end gap-3">

                    {{-- School Year --}}
                    <div class="flex flex-col gap-1.5 min-w-[140px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                        <div class="relative">
                            <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">2026-2027</option>
                                @foreach(['2025-2026','2024-2025','2023-2024','2022-2023'] as $sy)
                                <option value="{{ $sy }}" {{ request('school_year')===$sy?'selected':'' }}>SY {{ $sy }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Grade and Section --}}
                    <div class="flex flex-col gap-1.5 min-w-[140px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade and Section</label>
                        <div class="relative">
                            <select name="grade_level" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All Grades</option>
                                @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $g)
                                <option value="{{ $g }}" {{ request('grade_level')===$g?'selected':'' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Quarter --}}
                    <div class="flex flex-col gap-1.5 min-w-[130px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Quarter</label>
                        <div class="relative">
                            <select name="section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                @foreach($sections ?? [] as $sec)
                                <option value="{{ $sec }}" {{ request('section')===$sec?'selected':'' }}>{{ $sec }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Semester --}}
                    <div class="flex flex-col gap-1.5 min-w-[120px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Semester</label>
                        <div class="relative">
                            <select name="semester" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All </option>
                                @foreach(['First','Second','Third','Fourth'] as $q)
                                <option value="{{ $q }}" {{ request('quarter')===$q?'selected':'' }}>{{ $q }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Apply / Clear --}}
                    <div class="flex items-center gap-2 pb-0.5">
                        <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap">
                            <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                        </button>
                        <a href="{{ request()->url() }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors whitespace-nowrap">
                            Clear
                        </a>
                    </div>

                </div>
            </div>

            {{-- Table Controls --}}
            <div class="flex items-center justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
                <button class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-400 hover:underline flex items-center gap-1">
                    See Class Details <iconify-icon icon="solar:alt-arrow-right-linear" width="12"></iconify-icon>
                </button>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Show</span>
                    <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none"><option>10</option><option>25</option><option>50</option></select>
                    <span>Entries</span>
                </div>
            </div>

            {{-- Release Table --}}
            <div class="overflow-x-auto px-6 py-5">
                <table class="w-full text-left text-sm border-collapse rounded-xl overflow-hidden border border-slate-200 dark:border-dark-border" style="min-width:1100px">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-dark-border text-[10px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-white/[0.02]">
                            <th class="px-3 py-3 whitespace-nowrap">Student ID</th>
                            <th class="px-3 py-3 whitespace-nowrap">Student Name</th>
                            <th class="px-3 py-3 text-center">Math</th>
                            <th class="px-3 py-3 text-center">Science</th>
                            <th class="px-3 py-3 text-center">Filipino</th>
                            <th class="px-3 py-3 text-center">AP</th>
                            <th class="px-3 py-3 text-center">MAPEH</th>
                            <th class="px-3 py-3 text-center">TLE</th>
                            <th class="px-3 py-3 text-center">ESP</th>
                            <th class="px-3 py-3 text-center">HEKASI</th>
                            <th class="px-3 py-3 text-center">English</th>
                            <th class="px-3 py-3 text-center">AVE.</th>
                            <th class="px-3 py-3">Remarks</th>
                            <th class="px-3 py-3 text-center">Ranking</th>
                            <th class="px-3 py-3">Award</th>
                            <th class="px-3 py-3 text-center whitespace-nowrap">Status</th>
                            <th class="px-3 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                        @php
                            $releaseStudents = [
                                ['232-555-09','Jenny Orquiola',  90,90,90,90,90,90,90,90,90,91,'Passed',1,'W/ Honor',    'pending'],
                                ['232-555-09','Jeneva Ybanez',   89,89,89,89,89,89,89,89,89,90,'Passed',5,'W/ Honor',    'approved'],
                                ['232-555-09','Dianne Balaoro',  98,98,98,98,98,98,98,98,98,93,'Passed',2,'W/ High Honor','published'],
                                ['232-555-09','Hans Gayon',      98,98,98,98,98,98,98,98,98,98,'Passed',6,'W/ High Honor','approved'],
                            ];
                            $statusBadge = [
                                'pending'   => 'bg-amber-100 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400',
                                'approved'  => 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400',
                                'published' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400',
                            ];
                        @endphp
                        @foreach($releaseStudents as $s)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-3 py-3 text-[11px] font-mono text-slate-400 dark:text-slate-500 whitespace-nowrap">{{ $s[0] }}</td>
                            <td class="px-3 py-3 text-xs font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $s[1] }}</td>
                            @foreach(array_slice($s, 2, 9) as $grade)
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400 text-center">{{ $grade }}</td>
                            @endforeach
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400 text-center">{{ $s[11] }}</td>
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $s[12] }}</td>
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400 text-center">{{ $s[13] }}</td>
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $s[14] }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold {{ $statusBadge[$s[15]] ?? '' }}">
                                    {{ ucfirst($s[15]) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <div x-data="{ open: false }" class="relative inline-block">
                                    <button @click="open=!open" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                                        Select <iconify-icon icon="solar:alt-arrow-down-linear" width="12" :class="open?'rotate-180':''" class="transition-transform duration-200"></iconify-icon>
                                    </button>
                                    <div x-show="open" @click.outside="open=false"
                                         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                         class="absolute right-0 z-20 mt-1 w-36 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1">
                                        <button type="button" @click="open=false"
                                            class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                            <iconify-icon icon="solar:check-circle-bold" width="14" class="text-green-500"></iconify-icon> Approve
                                        </button>
                                        <button type="button" @click="open=false"
                                            class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                            <iconify-icon icon="solar:upload-square-bold" width="14" class="text-blue-500"></iconify-icon> Publish
                                        </button>
                                        <button type="button" @click="open=false"
                                            class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                            <iconify-icon icon="solar:eye-bold" width="14" class="text-amber-500"></iconify-icon> View
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination row --}}
                <div class="flex items-center justify-between pt-4">
                    <p class="text-xs text-slate-400">Showing 1–{{ count($releaseStudents) }} of {{ count($releaseStudents) }} entries</p>
                    <div class="flex items-center gap-1">
                        <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon></button>
                        <button class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">1</button>
                        <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon></button>
                    </div>
                </div>
            </div>

        </div>{{-- END TAB 1 --}}


        {{-- ══ TAB 2: CLASS RECORD – QUARTERLY REPORT ══ --}}
        <div x-show="activeTab === 'classrecord'" x-cloak x-transition.opacity>

            {{-- Section Header --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:notebook-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Class Record</h2>
                    <span class="ml-2 text-xs text-slate-400">Auto-calculated from teacher grade entries</span>
                </div>
                <div class="flex items-center gap-2">
                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                        <iconify-icon icon="solar:chart-bold" width="13" class="text-[#0d4c8f]"></iconify-icon>
                        Transmutation Table
                    </button>
                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                        <iconify-icon icon="solar:file-download-bold" width="13" class="text-green-600"></iconify-icon>
                        Export Excel
                    </button>
                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                        <iconify-icon icon="solar:document-bold" width="13" class="text-slate-500"></iconify-icon>
                        Download PDF
                    </button>
                </div>
            </div>

            {{-- Filters --}}
            
         {{-- Filters --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <div class="flex flex-wrap items-end gap-3">

                    {{-- School Year --}}
                    <div class="flex flex-col gap-1.5 min-w-[140px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                        <div class="relative">
                            <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">2026-2027</option>
                                @foreach(['2025-2026','2024-2025','2023-2024','2022-2023'] as $sy)
                                <option value="{{ $sy }}" {{ request('school_year')===$sy?'selected':'' }}>SY {{ $sy }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Grade and Section --}}
                    <div class="flex flex-col gap-1.5 min-w-[140px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade and Section</label>
                        <div class="relative">
                            <select name="grade_level" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All Grades</option>
                                @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $g)
                                <option value="{{ $g }}" {{ request('grade_level')===$g?'selected':'' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Quarter --}}
                    <div class="flex flex-col gap-1.5 min-w-[130px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Quarter</label>
                        <div class="relative">
                            <select name="section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                @foreach($sections ?? [] as $sec)
                                <option value="{{ $sec }}" {{ request('section')===$sec?'selected':'' }}>{{ $sec }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Semester --}}
                    <div class="flex flex-col gap-1.5 min-w-[120px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Semester</label>
                        <div class="relative">
                            <select name="semester" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All </option>
                                @foreach(['First','Second','Third','Fourth'] as $q)
                                <option value="{{ $q }}" {{ request('quarter')===$q?'selected':'' }}>{{ $q }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Apply / Clear --}}
                    <div class="flex items-center gap-2 pb-0.5">
                        <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap">
                            <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                        </button>
                        <a href="{{ request()->url() }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors whitespace-nowrap">
                            Clear
                        </a>
                    </div>

                </div>
            </div>

            {{-- Table Controls --}}
            <div class="flex items-center justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
                <button class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-400 hover:underline flex items-center gap-1">
                    See Class Details <iconify-icon icon="solar:alt-arrow-right-linear" width="12"></iconify-icon>
                </button>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Show</span>
                    <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none"><option>10</option><option>25</option><option>50</option></select>
                    <span>Entries</span>
                </div>
            </div>

            {{-- Class Record Table --}}
            <div class="overflow-x-auto px-6 py-5">
                <table class="w-full border-collapse text-xs rounded-xl overflow-hidden border border-slate-200 dark:border-dark-border" style="min-width:900px">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-white/[0.02] border-b border-slate-200 dark:border-dark-border">
                            <th rowspan="2" class="border border-slate-200 dark:border-dark-border px-3 py-3 text-left text-[11px] font-semibold text-slate-600 dark:text-slate-300 w-40">Student Name</th>
                            <th colspan="4" class="border border-slate-200 dark:border-dark-border px-3 py-2 text-center text-[11px] font-semibold text-slate-600 dark:text-slate-300">Written Work (25%)</th>
                            <th colspan="4" class="border border-slate-200 dark:border-dark-border px-3 py-2 text-center text-[11px] font-semibold text-slate-600 dark:text-slate-300">Performance Task (30%)</th>
                            <th colspan="4" class="border border-slate-200 dark:border-dark-border px-3 py-2 text-center text-[11px] font-semibold text-slate-600 dark:text-slate-300">Quarterly Assessment (45%)</th>
                            <th rowspan="2" class="border border-slate-200 dark:border-dark-border px-3 py-2 text-center text-[11px] font-semibold text-slate-600 dark:text-slate-300 whitespace-nowrap">Initial Grade</th>
                            <th rowspan="2" class="border border-slate-200 dark:border-dark-border px-3 py-2 text-center text-[11px] font-semibold text-slate-600 dark:text-slate-300 whitespace-nowrap">Quarterly Grade</th>
                            <th rowspan="2" class="border border-slate-200 dark:border-dark-border px-3 py-2 text-center text-[11px] font-semibold text-slate-600 dark:text-slate-300">Ranking</th>
                        </tr>
                        <tr class="bg-slate-50/50 dark:bg-white/[0.01] border-b border-slate-200 dark:border-dark-border">
                            @foreach(array_fill(0, 12, 'HPS') as $h)
                            <th class="border border-slate-200 dark:border-dark-border px-2 py-1.5 text-center text-[10px] font-medium text-slate-400 dark:text-slate-500 min-w-[44px]">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                        @foreach(['Jenny Orquiola','Jeneva Ybanez','Dianne Balaoro','Hans Gayon'] as $name)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="border border-slate-200 dark:border-dark-border px-3 py-3 text-xs font-medium text-slate-700 dark:text-slate-300">{{ $name }}</td>
                            @foreach(array_fill(0, 12, '') as $_)
                            <td class="border border-slate-200 dark:border-dark-border px-2 py-3 text-center text-xs text-slate-400 dark:text-slate-500 min-w-[44px]"></td>
                            @endforeach
                            <td class="border border-slate-200 dark:border-dark-border px-2 py-3 text-center text-xs text-slate-400 dark:text-slate-500"></td>
                            <td class="border border-slate-200 dark:border-dark-border px-2 py-3 text-center text-xs text-slate-400 dark:text-slate-500"></td>
                            <td class="border border-slate-200 dark:border-dark-border px-2 py-3 text-center text-xs text-slate-400 dark:text-slate-500"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex items-center justify-between pt-4">
                    <p class="text-xs text-slate-400">Showing 1–4 of 4 entries</p>
                    <div class="flex items-center gap-1">
                        <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon></button>
                        <button class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">1</button>
                        <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon></button>
                    </div>
                </div>
            </div>

        </div>{{-- END TAB 2 --}}


        {{-- ══ TAB 3: PRINT GRADE REPORT ══ --}}
        <div x-show="activeTab === 'print'" x-cloak x-transition.opacity>

            {{-- Section Header --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:printer-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Print Grade Report</h2>
                </div>
                <div class="flex items-center gap-2">
                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                        <iconify-icon icon="solar:file-download-bold" width="13" class="text-green-600"></iconify-icon>
                        Export Excel
                    </button>
                    <button class="flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                        <iconify-icon icon="solar:document-bold" width="13" class="text-slate-500"></iconify-icon>
                        Download PDF
                    </button>
                </div>
            </div>

             {{-- Filters --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
                <div class="flex flex-wrap items-end gap-3">

                    {{-- School Year --}}
                    <div class="flex flex-col gap-1.5 min-w-[140px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                        <div class="relative">
                            <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">2026-2027</option>
                                @foreach(['2025-2026','2024-2025','2023-2024','2022-2023'] as $sy)
                                <option value="{{ $sy }}" {{ request('school_year')===$sy?'selected':'' }}>SY {{ $sy }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Grade and Section --}}
                    <div class="flex flex-col gap-1.5 min-w-[140px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade and Section</label>
                        <div class="relative">
                            <select name="grade_level" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All Grades</option>
                                @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $g)
                                <option value="{{ $g }}" {{ request('grade_level')===$g?'selected':'' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Quarter --}}
                    <div class="flex flex-col gap-1.5 min-w-[130px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Quarter</label>
                        <div class="relative">
                            <select name="section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                @foreach($sections ?? [] as $sec)
                                <option value="{{ $sec }}" {{ request('section')===$sec?'selected':'' }}>{{ $sec }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Semester --}}
                    <div class="flex flex-col gap-1.5 min-w-[120px] flex-1">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Semester</label>
                        <div class="relative">
                            <select name="semester" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All </option>
                                @foreach(['First','Second','Third','Fourth'] as $q)
                                <option value="{{ $q }}" {{ request('quarter')===$q?'selected':'' }}>{{ $q }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Apply / Clear --}}
                    <div class="flex items-center gap-2 pb-0.5">
                        <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors whitespace-nowrap">
                            <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                        </button>
                        <a href="{{ request()->url() }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors whitespace-nowrap">
                            Clear
                        </a>
                    </div>

                </div>
            </div>

            {{-- Table Controls --}}
            <div class="flex items-center justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
                <button class="text-xs font-semibold text-[#0d4c8f] dark:text-blue-400 hover:underline flex items-center gap-1">
                    See Class Details <iconify-icon icon="solar:alt-arrow-right-linear" width="12"></iconify-icon>
                </button>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span>Show</span>
                    <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none"><option>10</option><option>25</option><option>50</option></select>
                    <span>Entries</span>
                </div>
            </div>

            {{-- Print Grade Table --}}
            <div class="overflow-x-auto px-6 py-5">
                <table class="w-full text-left text-sm border-collapse rounded-xl overflow-hidden border border-slate-200 dark:border-dark-border" style="min-width:1100px">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-dark-border text-[10px] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-white/[0.02]">
                            <th class="px-3 py-3 whitespace-nowrap">Student ID</th>
                            <th class="px-3 py-3 whitespace-nowrap">Student Name</th>
                            <th class="px-3 py-3 text-center">Math</th>
                            <th class="px-3 py-3 text-center">Science</th>
                            <th class="px-3 py-3 text-center">Filipino</th>
                            <th class="px-3 py-3 text-center">AP</th>
                            <th class="px-3 py-3 text-center">MAPEH</th>
                            <th class="px-3 py-3 text-center">TLE</th>
                            <th class="px-3 py-3 text-center">ESP</th>
                            <th class="px-3 py-3 text-center">HEKASI</th>
                            <th class="px-3 py-3 text-center">English</th>
                            <th class="px-3 py-3 text-center">AVE.</th>
                            <th class="px-3 py-3">Remarks</th>
                            <th class="px-3 py-3 text-center">Ranking</th>
                            <th class="px-3 py-3">Award</th>
                            <th class="px-3 py-3 text-center whitespace-nowrap">Release Status</th>
                            <th class="px-3 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                        @foreach($releaseStudents as $s)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-3 py-3 text-[11px] font-mono text-slate-400 dark:text-slate-500 whitespace-nowrap">{{ $s[0] }}</td>
                            <td class="px-3 py-3 text-xs font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">{{ $s[1] }}</td>
                            @foreach(array_slice($s, 2, 9) as $grade)
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400 text-center">{{ $grade }}</td>
                            @endforeach
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400 text-center">{{ $s[11] }}</td>
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400">{{ $s[12] }}</td>
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400 text-center">{{ $s[13] }}</td>
                            <td class="px-3 py-3 text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $s[14] }}</td>
                            <td class="px-3 py-3 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold {{ $statusBadge[$s[15]] ?? '' }}">
                                    {{ ucfirst($s[15]) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <button class="inline-flex items-center gap-1 rounded-lg border border-[#0d4c8f] dark:border-blue-500 bg-white dark:bg-dark-card hover:bg-blue-50 dark:hover:bg-blue-900/20 px-3 py-1 text-[11px] font-semibold text-[#0d4c8f] dark:text-blue-400 transition-colors">
                                    <iconify-icon icon="solar:printer-linear" width="11"></iconify-icon>
                                    Print
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex items-center justify-between pt-4">
                    <p class="text-xs text-slate-400">Showing 1–{{ count($releaseStudents) }} of {{ count($releaseStudents) }} entries</p>
                    <div class="flex items-center gap-1">
                        <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-left-linear" width="13"></iconify-icon></button>
                        <button class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#0d4c8f] text-white text-xs font-semibold">1</button>
                        <button class="flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 dark:border-dark-border text-slate-400 hover:bg-slate-50 transition-colors"><iconify-icon icon="solar:alt-arrow-right-linear" width="13"></iconify-icon></button>
                    </div>
                </div>
            </div>

        </div>{{-- END TAB 3 --}}

    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection
