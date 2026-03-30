@extends('layouts.admin_layout')

@section('title', 'Student Profile')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- ── Page Header ── --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Student Records</h1>
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">Student Record and Information</p>
        </div>
        <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <span class="text-xs text-slate-500 dark:text-slate-400 whitespace-nowrap">Current school year:</span>
            <div class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-3 py-1.5 shadow-sm">
                <span class="text-sm font-semibold text-slate-700 dark:text-white">SY 2025–2026</span>
                <button class="text-slate-400 hover:text-slate-600 transition-colors">
                    <iconify-icon icon="solar:menu-dots-bold" width="14"></iconify-icon>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Back ── --}}
    <div class="mb-4">
        <a href="{{ route('admin.student-records.list') }}"
           class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-[#0d4c8f] dark:text-slate-400 transition-colors">
            <iconify-icon icon="solar:alt-arrow-left-linear" width="14"></iconify-icon>
            Back to Student List
        </a>
    </div>

    {{-- ── Two-column layout ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-6 items-start">

        {{-- ── LEFT: Avatar + Summary ── --}}
        <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm p-6 flex flex-col items-center gap-5">

            {{-- Avatar --}}
            <div class="relative mt-2">
                <div class="flex h-28 w-28 items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                    <iconify-icon icon="solar:user-bold" width="64" class="text-slate-400 dark:text-slate-500 mt-4"></iconify-icon>
                </div>
                <button class="absolute bottom-1 right-1 flex h-7 w-7 items-center justify-center rounded-full bg-slate-600 dark:bg-slate-500 text-white shadow hover:bg-slate-700 transition-colors">
                    <iconify-icon icon="solar:pen-bold" width="13"></iconify-icon>
                </button>
            </div>

            {{-- Name --}}
            <div class="text-center">
                <p class="text-sm font-bold text-slate-800 dark:text-white">Jenny Joy A. Orquiola</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">234-434-23</p>
            </div>

            <div class="w-full border-t border-slate-100 dark:border-dark-border"></div>

            {{-- Summary fields --}}
            <div class="w-full flex flex-col gap-2">
                @foreach([
                    ['School Year',     '2025-2026'],
                    ['Grade Level',     'Grade 12 (STEM)'],
                    ['Section',         'E'],
                    ['Enrollment Date', '01/21/26'],
                    ['Student Status',  'Active'],
                    ['Academic Status', 'Enrolled'],
                ] as $row)
                <div class="flex items-center justify-between rounded-lg bg-slate-50 dark:bg-slate-800/40 px-3 py-1.5">
                    <span class="text-xs text-slate-400 dark:text-slate-500">{{ $row[0] }}</span>
                    <span class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $row[1] }}</span>
                </div>
                @endforeach
            </div>

        </div>
        {{-- end left --}}

        {{-- ── RIGHT: Tabs ── --}}
        <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden"
             x-data="{ tab: 'profile', recordOpen: 'records-office' }">

            {{-- Tab Bar --}}
            <div class="flex border-b border-slate-200 dark:border-dark-border">
                @foreach([
                    ['profile',   'Profile'],
                    ['academics', 'Academics'],
                    ['grades',    'Grades'],
                    ['records',   'Records'],
                ] as $t)
                <button @click="tab = '{{ $t[0] }}'"
                    :class="tab === '{{ $t[0] }}'
                        ? 'border-b-2 border-green-500 text-green-600 dark:border-green-400 dark:text-green-400'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 border-b-2 border-transparent'"
                    class="px-5 py-3 text-xs font-semibold transition-all whitespace-nowrap">
                    {{ $t[1] }}
                </button>
                @endforeach
            </div>

            {{-- ══════════════════════════════════════
                 PROFILE TAB
            ══════════════════════════════════════ --}}
            <div x-show="tab === 'profile'" class="p-6">

                {{-- Student Information --}}
                <div class="mb-5">
                    <div class="flex items-center gap-2 mb-4">
                        <iconify-icon icon="solar:user-bold" width="15" class="text-slate-500 dark:text-slate-400"></iconify-icon>
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Student Information</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-x-6 gap-y-4 mb-4">
                        <div><p class="text-xs text-slate-400">First Name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Jenny Joy</p></div>
                        <div><p class="text-xs text-slate-400">Suffix</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">N/A</p></div>
                        <div><p class="text-xs text-slate-400">Nationality</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Pilipino</p></div>
                        <div><p class="text-xs text-slate-400">Last name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Orquiola</p></div>
                        <div><p class="text-xs text-slate-400">Gender</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Female</p></div>
                        <div><p class="text-xs text-slate-400">Religion</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Catholic</p></div>
                        <div><p class="text-xs text-slate-400">Middle name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Jenny</p></div>
                        <div><p class="text-xs text-slate-400">Date of Birth</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">July 23, 2005</p></div>
                        <div><p class="text-xs text-slate-400">Civil Status</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Single</p></div>
                    </div>
                    <div class="border-t border-slate-100 dark:border-dark-border my-4"></div>
                    <div class="grid grid-cols-3 gap-x-6 gap-y-4">
                        <div><p class="text-xs text-slate-400">Student ID</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">234-434-23</p></div>
                        <div><p class="text-xs text-slate-400">City / Municipality</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Dasma</p></div>
                        <div><p class="text-xs text-slate-400">Contact Number</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">09265226614</p></div>
                        <div><p class="text-xs text-slate-400">LRN</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">107922100774</p></div>
                        <div><p class="text-xs text-slate-400">Province</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Cavite</p></div>
                        <div><p class="text-xs text-slate-400">Emergency Contact</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">09265226614</p></div>
                        <div><p class="text-xs text-slate-400">Home Address</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">blk 1, lot 100</p></div>
                        <div><p class="text-xs text-slate-400">ZIP Code</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">4114</p></div>
                        <div><p class="text-xs text-slate-400">Email</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">jen@gmail.com</p></div>
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-dark-border mb-5"></div>

                {{-- Guardian Information --}}
                <div class="mb-5">
                    <div class="flex items-center gap-2 mb-4">
                        <iconify-icon icon="solar:users-group-rounded-bold" width="15" class="text-slate-500 dark:text-slate-400"></iconify-icon>
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-white">Guardian Information</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-x-6 gap-y-4">
                        <div><p class="text-xs text-slate-400">Guardian Name</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Conrado Orquiola Jr.</p></div>
                        <div><p class="text-xs text-slate-400">Contact Number</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">0965665615</p></div>
                        <div><p class="text-xs text-slate-400">Permanent Address</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">blk 1, lot 100, Dasma</p></div>
                        <div><p class="text-xs text-slate-400">Relationship</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Father</p></div>
                        <div><p class="text-xs text-slate-400">Email</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Orquiola@gmail.com</p></div>
                        <div><p class="text-xs text-slate-400">Current Address</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">blk 1, lot 100, Dasma</p></div>
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-dark-border mb-5"></div>

                {{-- School Information --}}
                <div class="mb-5">
                    <div class="flex items-center gap-2 mb-4">
                        <iconify-icon icon="solar:buildings-bold" width="15" class="text-slate-500 dark:text-slate-400"></iconify-icon>
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-white">School Information</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-x-6 gap-y-4">
                        <div><p class="text-xs text-slate-400">Admission Type</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Return</p></div>
                        <div><p class="text-xs text-slate-400">Student Status</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Active</p></div>
                        <div><p class="text-xs text-slate-400">Clearance Status</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Cleared</p></div>
                        <div><p class="text-xs text-slate-400">Enrolled Date</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">05/22/225</p></div>
                        <div><p class="text-xs text-slate-400">Academic Status</p><p class="text-xs font-medium text-slate-700 dark:text-slate-300 mt-0.5">Passed</p></div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-dark-border">
                    <p class="text-xs text-slate-400">Last Edited: January 25, 2025</p>
                    <button class="flex items-center gap-2 rounded-xl bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                        <iconify-icon icon="solar:pen-bold" width="13"></iconify-icon>
                        Edit Profile
                    </button>
                </div>
            </div>

            {{-- ══════════════════════════════════════
                 ACADEMICS TAB
            ══════════════════════════════════════ --}}
            <div x-show="tab === 'academics'" class="p-6">

                {{-- School Year / Section / Grade / Adviser --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 dark:text-slate-400 w-24 shrink-0">School Year:</span>
                        <span class="flex-1 border-b border-slate-300 dark:border-slate-600 text-xs font-medium text-slate-700 dark:text-slate-300 pb-0.5 text-center">2025 - 2026</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 dark:text-slate-400 w-24 shrink-0">Section:</span>
                        <span class="flex-1 border-b border-slate-300 dark:border-slate-600 text-xs font-medium text-slate-700 dark:text-slate-300 pb-0.5 text-center">E</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 dark:text-slate-400 w-24 shrink-0">Grade:</span>
                        <span class="flex-1 border-b border-slate-300 dark:border-slate-600 text-xs font-medium text-slate-700 dark:text-slate-300 pb-0.5 text-center">12</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 dark:text-slate-400 w-24 shrink-0">Class Adviser:</span>
                        <span class="flex-1 border-b border-slate-300 dark:border-slate-600 text-xs font-medium text-slate-700 dark:text-slate-300 pb-0.5 text-center">Ms. Yve Mae Cisco</span>
                    </div>
                </div>

                {{-- Class Schedule --}}
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Class Schedule</h3>
                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-xs border border-slate-200 dark:border-slate-700" style="min-width:560px">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800">
                                <th class="border border-slate-200 dark:border-slate-700 px-3 py-2 text-left font-semibold text-slate-600 dark:text-slate-300 w-28">Time</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-3 py-2 text-center font-semibold text-slate-600 dark:text-slate-300">Monday</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-3 py-2 text-center font-semibold text-slate-600 dark:text-slate-300">Tuesday</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-3 py-2 text-center font-semibold text-slate-600 dark:text-slate-300">Wednesday</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-3 py-2 text-center font-semibold text-slate-600 dark:text-slate-300">Thursday</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-3 py-2 text-center font-semibold text-slate-600 dark:text-slate-300">Friday</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([
                                ['6:00AM - 7:00AM',   'Capstone 1',    'Physics',        'General Biology', 'Entrepreneurship',         'Capstone 1'],
                                ['8:00AM - 9:00AM',   'Pre Calculus',  'Filipino 1',     'Pre Calculus',    'General Biology',          'Statistics and Probability'],
                                ['9:00AM - 9:30AM',   '',              '',               'BREAK TIME',      '',                         ''],
                                ['9:30AM - 10:30AM',  'MAPEH',         'Entrepreneurship','Physics',        'Statistics and Probability','Pre Calculus'],
                                ['10:30AM - 11:30AM', 'Filipino 1',    'MAPEH',          'MAPEH',           'MAPEH',                    'Physics'],
                            ] as $row)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="border border-slate-200 dark:border-slate-700 px-3 py-2 text-slate-500 dark:text-slate-400 whitespace-nowrap">{{ $row[0] }}</td>
                                @if($row[3] === 'BREAK TIME' && $row[1] === '')
                                    <td colspan="5" class="border border-slate-200 dark:border-slate-700 px-3 py-2 text-center text-slate-500 dark:text-slate-400 font-medium">BREAK TIME</td>
                                @else
                                    @foreach(array_slice($row, 1) as $cell)
                                    <td class="border border-slate-200 dark:border-slate-700 px-3 py-2 text-center text-slate-600 dark:text-slate-300">{{ $cell }}</td>
                                    @endforeach
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Subject Teacher --}}
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Subject Teacher</h3>
                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-xs border border-slate-200 dark:border-slate-700">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800">
                                <th class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-left font-semibold text-slate-600 dark:text-slate-300">Subject</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-left font-semibold text-slate-600 dark:text-slate-300">Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([
                                ['Capstone 1',                 'Ms. Jeneva Ybanez'],
                                ['Physics',                    'Ms. Dianne Balaoro'],
                                ['Statistics and Probability', 'Mr. Hans Gayon'],
                                ['General Biology',            'Mrs. Mia Joy Pabia'],
                                ['Pre Calculus',               'Mr. David Conception'],
                            ] as $row)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-slate-600 dark:text-slate-300">{{ $row[0] }}</td>
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-slate-600 dark:text-slate-300">{{ $row[1] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary of Attendance --}}
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-3">Summary of Attendance</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs border border-slate-200 dark:border-slate-700">
                        <tbody>
                            @foreach([
                                ['No. of School Days',  '72'],
                                ['No. of Days Present', '70'],
                                ['No. of Times Absent', '2'],
                            ] as $row)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-slate-600 dark:text-slate-300 w-1/2">{{ $row[0] }}</td>
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-center text-slate-700 dark:text-slate-300 font-medium">{{ $row[1] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- ══════════════════════════════════════
                 GRADES TAB
            ══════════════════════════════════════ --}}
            <div x-show="tab === 'grades'" class="p-6">

                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">REPORT CARD</h3>

                {{-- First Quarter --}}
                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mb-2">First Quarter</p>
                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-xs border border-slate-200 dark:border-slate-700">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800">
                                <th class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-left font-semibold text-slate-600 dark:text-slate-300">Subjects</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-center font-semibold text-slate-600 dark:text-slate-300">Grades</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-center font-semibold text-slate-600 dark:text-slate-300">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([
                                ['Capstone 1',                 '91', 'Passed'],
                                ['Physics 2',                  '93', 'Passed'],
                                ['General Biology',            '90', 'Passed'],
                                ['Entrepreneurship',           '97', 'Passed'],
                                ['Filipino 1',                 '90', 'Passed'],
                                ['Mapeh',                      '87', 'Passed'],
                                ['Statistics and Probability', '90', 'Passed'],
                                ['Pre Calculus',               '91', 'Passed'],
                            ] as $row)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-slate-600 dark:text-slate-300">{{ $row[0] }}</td>
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-center text-slate-700 dark:text-slate-300">{{ $row[1] }}</td>
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-center text-slate-600 dark:text-slate-300">{{ $row[2] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Second Quarter --}}
                <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 mb-2">Second Quarter</p>
                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-xs border border-slate-200 dark:border-slate-700">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800">
                                <th class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-left font-semibold text-slate-600 dark:text-slate-300">Subjects</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-center font-semibold text-slate-600 dark:text-slate-300">Grades</th>
                                <th class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-center font-semibold text-slate-600 dark:text-slate-300">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach([
                                ['Capstone 2',                   '91', 'Passed'],
                                ['MIL',                          '93', 'Passed'],
                                ['General Biology',              '90', 'Passed'],
                                ['Basic Calculus',               '97', 'Passed'],
                                ['Pananaliksik sa Filipino',      '90', 'Passed'],
                                ['Mapeh',                        '87', 'Passed'],
                            ] as $row)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-slate-600 dark:text-slate-300">{{ $row[0] }}</td>
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-center text-slate-700 dark:text-slate-300">{{ $row[1] }}</td>
                                <td class="border border-slate-200 dark:border-slate-700 px-4 py-2 text-center text-slate-600 dark:text-slate-300">{{ $row[2] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- ══════════════════════════════════════
                 RECORDS TAB
            ══════════════════════════════════════ --}}
            <div x-show="tab === 'records'" class="p-6" x-data="{ open: 'records-office' }">

                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Records</h3>

                {{-- Records Office - Admission Requirements --}}
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 mb-3 overflow-hidden">
                    <button @click="open = open === 'records-office' ? '' : 'records-office'"
                        class="w-full flex items-center justify-between px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <span>Records Office - Admission Requirements</span>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="14"
                            :class="open === 'records-office' ? 'rotate-180' : ''"
                            class="transition-transform duration-200 text-slate-400"></iconify-icon>
                    </button>
                    <div x-show="open === 'records-office'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        <table class="w-full text-xs border-t border-slate-200 dark:border-slate-700">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/40">
                                    <th class="px-4 py-2 text-left font-semibold text-slate-500 dark:text-slate-400">Requirements</th>
                                    <th class="px-4 py-2 text-left font-semibold text-slate-500 dark:text-slate-400">Status</th>
                                    <th class="px-4 py-2 text-left font-semibold text-slate-500 dark:text-slate-400">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach([
                                    ['NSO / PSA', 'Cleared'],
                                    ['2x2 picture', 'Cleared'],
                                    ['Form 137', 'Cleared'],
                                ] as $req)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5">
                                    <td class="px-4 py-2.5 text-slate-600 dark:text-slate-300">{{ $req[0] }}</td>
                                    <td class="px-4 py-2.5">
                                        <span class="text-green-600 dark:text-green-400 font-medium">{{ $req[1] }}</span>
                                    </td>
                                    <td class="px-4 py-2.5">
                                        <button class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400 transition-colors">
                                            <iconify-icon icon="solar:eye-bold" width="13"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Fees Collected - Finance --}}
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 mb-3 overflow-hidden">
                    <button @click="open = open === 'fees' ? '' : 'fees'"
                        class="w-full flex items-center justify-between px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <span>Fees Collected - Finance</span>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14"
                            :class="open === 'fees' ? 'rotate-90' : ''"
                            class="transition-transform duration-200 text-slate-400"></iconify-icon>
                    </button>
                    <div x-show="open === 'fees'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        <div class="px-4 py-4 text-xs text-slate-400 dark:text-slate-500 border-t border-slate-100 dark:border-slate-700">
                            No fees data available.
                        </div>
                    </div>
                </div>

                {{-- Library --}}
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 mb-3 overflow-hidden">
                    <button @click="open = open === 'library' ? '' : 'library'"
                        class="w-full flex items-center justify-between px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <span>Library</span>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14"
                            :class="open === 'library' ? 'rotate-90' : ''"
                            class="transition-transform duration-200 text-slate-400"></iconify-icon>
                    </button>
                    <div x-show="open === 'library'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        <div class="px-4 py-4 text-xs text-slate-400 dark:text-slate-500 border-t border-slate-100 dark:border-slate-700">
                            No library records available.
                        </div>
                    </div>
                </div>

                {{-- Behavioral Record --}}
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <button @click="open = open === 'behavioral' ? '' : 'behavioral'"
                        class="w-full flex items-center justify-between px-4 py-3 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <span>Behavioral Record</span>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="14"
                            :class="open === 'behavioral' ? 'rotate-90' : ''"
                            class="transition-transform duration-200 text-slate-400"></iconify-icon>
                    </button>
                    <div x-show="open === 'behavioral'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        <div class="px-4 py-4 text-xs text-slate-400 dark:text-slate-500 border-t border-slate-100 dark:border-slate-700">
                            No behavioral records available.
                        </div>
                    </div>
                </div>

            </div>

        </div>
        {{-- end right --}}

    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>

</div>
@endsection