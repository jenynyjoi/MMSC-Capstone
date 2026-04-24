@extends('layouts.admin_layout')
@section('title', 'Section Management')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <x-admin.page-header
        title="Classes"
        subtitle="Manage Classes, Class Rosters, Classroom and Sections"
        school-year="{{ $schoolYear ?? $activeSchoolYear }}"
    />

    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:target-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Section Management</h2>
            </div>
            <button onclick="openSectionModal('add-section-modal')"
                class="flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 hover:bg-blue-100 dark:border-blue-700 dark:bg-blue-900/20 px-4 py-2 text-xs font-semibold text-blue-700 dark:text-blue-400 transition-colors">
                <iconify-icon icon="solar:add-circle-linear" width="15"></iconify-icon>
                Add Section
            </button>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            @foreach([
                ['blue',   'solar:check-circle-bold',  $stats['total'],           'Total Section'],
                ['green',  'solar:list-heart-bold',     $stats['available'],       'Available Section'],
                ['red',    'solar:close-circle-bold',   $stats['without_adviser'], 'Without Adviser'],
                ['yellow', 'solar:clipboard-bold',      $stats['available_slots'], 'Available Slot'],
            ] as [$color, $icon, $count, $label])
            <div class="flex items-center gap-3 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 dark:border-{{ $color }}-900/30 dark:bg-{{ $color }}-900/10 px-4 py-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30">
                    <iconify-icon icon="{{ $icon }}" width="20" class="text-{{ $color }}-600 dark:text-{{ $color }}-400"></iconify-icon>
                </div>
                <div>
                    <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $count }}</p>
                    <p class="text-xs text-{{ $color }}-600 dark:text-{{ $color }}-400 mt-1 leading-tight">{{ $label }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <form method="GET" action="{{ route('admin.classes.sections') }}">
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-3">
                    {{-- School Year (dynamic) --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">School Year</label>
                        <div class="relative">
                            <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                @foreach($schoolYears as $sy)
                                <option value="{{ $sy->name }}" {{ request('school_year', $schoolYear) === $sy->name ? 'selected' : '' }}>SY {{ $sy->name }}</option>
                                @endforeach
                                @if($schoolYears->isEmpty())
                                <option value="{{ $schoolYear }}" selected>SY {{ $schoolYear }}</option>
                                @endif
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    {{-- Other filters --}}
                    @foreach([
                        ['grade',          'Grade Level',       [''=>'All','Kinder'=>'Kinder','Grade 1'=>'Grade 1','Grade 2'=>'Grade 2','Grade 3'=>'Grade 3','Grade 4'=>'Grade 4','Grade 5'=>'Grade 5','Grade 6'=>'Grade 6','Grade 7'=>'Grade 7','Grade 8'=>'Grade 8','Grade 9'=>'Grade 9','Grade 10'=>'Grade 10','Grade 11'=>'Grade 11','Grade 12'=>'Grade 12']],
                        ['adviser_status', 'Adviser Status',    [''=>'All','assigned'=>'Assigned','tba'=>'No Adviser']],
                        ['availability',   'Availability Status',[''=>'All','available'=>'Available','full'=>'Full','near_capacity'=>'Near Capacity','below_minimum'=>'Below Minimum']],
                        ['status',         'Section Status',    [''=>'All','active'=>'Active','inactive'=>'Inactive','archived'=>'Archived']],
                    ] as [$name, $label, $opts])
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500">{{ $label }}</label>
                        <div class="relative">
                            <select name="{{ $name }}" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                @foreach($opts as $val => $text)
                                <option value="{{ $val }}" {{ request($name, '') === $val ? 'selected' : '' }}>{{ $text }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                    </button>
                    <a href="{{ route('admin.classes.sections') }}" class="rounded-lg border border-slate-200 px-5 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">Clear All</a>
                </div>
            </form>
        </div>

        {{-- Table Controls --}}
        <div class="flex items-center justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span>Show</span>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                    <option>10</option><option>25</option><option>50</option>
                </select>
                <span>entries</span>
            </div>
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                <input type="text" placeholder="Search.."
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-44">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:900px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3 whitespace-nowrap">Section ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade Level</th>
                        <th class="px-4 py-3 whitespace-nowrap">Section</th>
                        <th class="px-4 py-3 whitespace-nowrap">Room</th>
                        <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Capacity</th>
                        <th class="px-4 py-3 whitespace-nowrap">Homeroom Adviser</th>
                        <th class="px-4 py-3 whitespace-nowrap">Adviser Status</th>
                        <th class="px-4 py-3 whitespace-nowrap">Availability</th>
                        <th class="px-4 py-3 whitespace-nowrap">Section Status</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @forelse ($sections as $section)
                    @php
                        $adviserClass = $section->adviser_status === 'assigned'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700';
                        $availClass = [
                            'available'    => 'bg-green-100 text-green-700',
                            'full'         => 'bg-red-100 text-red-700',
                            'near_capacity'=> 'bg-amber-100 text-amber-700',
                            'below_minimum'=> 'bg-orange-100 text-orange-700',
                        ][$section->availability] ?? 'bg-slate-100 text-slate-600';
                        $statusClass = $section->section_status === 'active'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-slate-100 text-slate-500';
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                        <td class="px-4 py-3 text-xs font-mono text-slate-400">{{ $section->section_id ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300 font-medium">{{ $section->grade_level }}</td>
                        <td class="px-4 py-3 text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $section->section_name }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $section->room ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $section->school_year }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs font-semibold {{ $section->current_enrollment >= $section->capacity ? 'text-red-600' : 'text-slate-700' }}">
                                {{ $section->capacity }}
                            </span>
                            <span class="text-xs text-slate-400">({{ $section->current_enrollment }}/{{ $section->capacity }})</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300">{{ $section->homeroom_adviser_name ?? 'TBA' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $adviserClass }}">
                                {{ $section->adviser_status === 'assigned' ? 'Assigned' : 'No Adviser' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $availClass }}">
                                {{ ucfirst(str_replace('_', ' ', $section->availability)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($section->section_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open" type="button"
                                    class="flex items-center gap-1.5 rounded-lg border border-[#0d4c8f] bg-white hover:bg-blue-50 px-3 py-1 text-xs font-medium text-[#0d4c8f] transition-colors">
                                    Select
                                    <iconify-icon icon="solar:alt-arrow-down-linear" width="12" :class="open ? 'rotate-180' : ''" class="transition-transform duration-200"></iconify-icon>
                                </button>
                                <div x-show="open" @click.outside="open = false"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute right-0 z-20 mt-1 w-36 rounded-xl border border-slate-200 bg-white dark:bg-dark-card shadow-lg py-1">
                                    <button onclick="openViewSectionModal({{ json_encode($section) }})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 hover:bg-slate-50 transition-colors">
                                        <iconify-icon icon="solar:eye-linear" width="14" class="text-slate-500"></iconify-icon>
                                        View
                                    </button>
                                    <button onclick="openEditSectionModal({{ $section->id }}, {{ json_encode($section) }})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 hover:bg-slate-50 transition-colors">
                                        <iconify-icon icon="solar:pen-linear" width="14" class="text-blue-500"></iconify-icon>
                                        Edit
                                    </button>
                                    <button onclick="deleteSection({{ $section->id }})"
                                        class="flex w-full items-center gap-2 px-3 py-2 text-xs text-red-500 hover:bg-red-50 transition-colors">
                                        <iconify-icon icon="solar:trash-bin-trash-linear" width="14"></iconify-icon>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-14 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <iconify-icon icon="solar:inbox-linear" width="32" class="text-slate-300"></iconify-icon>
                                <p class="text-xs text-slate-400">No sections found.</p>
                                <button onclick="openSectionModal('add-section-modal')"
                                    class="text-xs text-blue-600 hover:underline">Create your first section</button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400">Showing {{ $sections->firstItem() ?? 0 }}–{{ $sections->lastItem() ?? 0 }} of {{ $sections->total() }}</p>
            {{ $sections->links() }}
        </div>

    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══════ ADD SECTION MODAL ══════ --}}
<div id="add-section-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeSectionModal('add-section-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" style="max-height:90vh;overflow-y:auto">

        {{-- Header --}}
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-white text-sm font-bold tracking-wide">CREATE NEW SECTION</h3>
            <button onclick="closeSectionModal('add-section-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        {{-- Validation errors --}}
        @if($errors->any())
        <div class="mx-6 mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
            <p class="text-xs font-semibold text-red-600 mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                <li class="text-xs text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.classes.sections.store') }}" class="px-6 py-5 space-y-4">
            @csrf

            {{-- School Year --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">School Year <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                        @foreach($schoolYears as $sy)
                        <option value="{{ $sy->name }}" {{ old('school_year', $schoolYear) === $sy->name ? 'selected' : '' }}>SY {{ $sy->name }}</option>
                        @endforeach
                        @if($schoolYears->isEmpty())
                        <option value="{{ $schoolYear }}" selected>SY {{ $schoolYear }}</option>
                        @endif
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>

            {{-- Grade Level --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Grade Level <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="grade_level" id="add-grade-level"
                        onchange="updatePendingCount(this.value)"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                        <option value="">— Select Grade Level —</option>
                        @foreach(['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $g)
                        <option value="{{ $g }}" {{ old('grade_level')===$g?'selected':'' }}>{{ $g }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
            </div>

            {{-- Section Name --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Section Name <span class="text-red-500">*</span></label>
                <input type="text" name="section_name" value="{{ old('section_name') }}"
                    placeholder="e.g., Rose, Narra, Section A"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Room --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Room
                    <span class="ml-1 text-[10px] font-normal text-slate-400">(available rooms only)</span>
                </label>
                <div class="relative">
                    <select name="room"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                        <option value="">— No Room —</option>
                        @forelse($availableRooms as $rm)
                            <option value="{{ $rm->room_number }}" {{ old('room') === $rm->room_number ? 'selected' : '' }}>
                                {{ $rm->room_number }} ({{ $rm->room_type }}, cap. {{ $rm->capacity }})
                            </option>
                        @empty
                            <option value="" disabled>No available rooms</option>
                        @endforelse
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
                @if($availableRooms->isEmpty())
                <p class="text-xs text-amber-500">
                    <iconify-icon icon="solar:danger-triangle-linear" width="12" class="inline"></iconify-icon>
                    No available rooms. <a href="{{ route('admin.classes.classrooms') }}" class="underline font-medium">Manage classrooms →</a>
                </p>
                @endif
            </div>

            {{-- Capacity --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Capacity</label>
                <input type="number" name="capacity" value="{{ old('capacity', 30) }}" min="20" max="30"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-slate-400">Min: 20 &bull; Max: 30</p>
            </div>

            {{-- Homeroom Adviser --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Homeroom Adviser</label>
                <div class="relative">
                    <select name="homeroom_adviser_id" id="add-adviser-select"
                        class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                        <option value="">— Select Teacher —</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher['id'] }}">{{ $teacher['name'] }}</option>
                        @endforeach
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                </div>
                @if($teachers->isEmpty())
                <p class="text-xs text-amber-500">
                    <iconify-icon icon="solar:danger-triangle-linear" width="12" class="inline"></iconify-icon>
                    No teachers found. <a href="{{ route('admin.teachers') }}" class="underline font-medium">Add teachers first →</a>
                </p>
                @endif
            </div>

            {{-- Track & Strand (SHS only) --}}
            <div id="shs-fields" class="space-y-4 hidden">
                <div class="rounded-xl border border-blue-100 bg-blue-50 dark:bg-blue-900/10 px-4 py-3">
                    <p class="text-xs font-semibold text-blue-600 mb-3">
                        <iconify-icon icon="solar:diploma-linear" width="13" class="inline mr-1"></iconify-icon>
                        Senior High School Fields
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Track</label>
                            <div class="relative">
                                <select name="track" id="add-track"
                                    onchange="filterStrands(this.value)"
                                    class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-7">
                                    <option value="">— Select Track —</option>
                                    <option value="Academic"      {{ old('track')==='Academic'?'selected':'' }}>Academic</option>
                                    <option value="TVL"           {{ old('track')==='TVL'?'selected':'' }}>TVL</option>
                                    <option value="Arts & Design" {{ old('track')==='Arts & Design'?'selected':'' }}>Arts & Design</option>
                                    <option value="Sports"        {{ old('track')==='Sports'?'selected':'' }}>Sports</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-medium text-slate-500">Strand</label>
                            <div class="relative">
                                <select name="strand" id="add-strand"
                                    class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-7">
                                    <option value="">— Select Strand —</option>
                                    <option value="STEM"  data-track="Academic" {{ old('strand')==='STEM'?'selected':'' }}>STEM</option>
                                    <option value="ABM"   data-track="Academic" {{ old('strand')==='ABM'?'selected':'' }}>ABM</option>
                                    <option value="HUMSS" data-track="Academic" {{ old('strand')==='HUMSS'?'selected':'' }}>HUMSS</option>
                                    <option value="GAS"   data-track="Academic" {{ old('strand')==='GAS'?'selected':'' }}>GAS</option>
                                    <option value="HE"    data-track="TVL"      {{ old('strand')==='HE'?'selected':'' }}>HE</option>
                                    <option value="ICT"   data-track="TVL"      {{ old('strand')==='ICT'?'selected':'' }}>ICT</option>
                                    <option value="IA"    data-track="TVL"      {{ old('strand')==='IA'?'selected':'' }}>IA</option>
                                </select>
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="12" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Students to Assign --}}
            <div id="pending-count-row" class="hidden rounded-xl border border-slate-200 bg-slate-50 dark:bg-slate-800/40 px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:users-group-rounded-linear" width="16" class="text-slate-500"></iconify-icon>
                    <span class="text-xs text-slate-600 dark:text-slate-300">Students to Assign:</span>
                </div>
                <span id="pending-count-value" class="text-sm font-bold text-[#0d4c8f]">0</span>
            </div>

            {{-- Send notification --}}
            <label class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-dark-border px-4 py-3 cursor-pointer hover:bg-slate-50 transition-colors">
                <input type="checkbox" name="send_notification" value="1" {{ old('send_notification') ? 'checked' : '' }}
                    class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <span class="text-xs font-medium text-slate-600 dark:text-slate-300">Send notification to teacher upon assignment</span>
            </label>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-dark-border">
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:add-circle-bold" width="14"></iconify-icon>
                    CREATE SECTION
                </button>
                <button type="button" onclick="closeSectionModal('add-section-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    CANCEL
                </button>
            </div>
        </form>
    </div>
</div>

<div id="edit-section-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeSectionModal('edit-section-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
            <h3 class="text-white text-sm font-bold">EDIT SECTION</h3>
            <button onclick="closeSectionModal('edit-section-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>
        <form id="edit-section-form" method="POST" action="" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500">Section Name <span class="text-red-500">*</span></label>
                    <input type="text" name="section_name" id="edit-section-name"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500">Room</label>
                    <div class="relative">
                        <select name="room" id="edit-section-room"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— No Room —</option>
                            @foreach($allActiveRooms as $rm)
                                <option value="{{ $rm->room_number }}"
                                    data-status="{{ $rm->availability_status }}">
                                    {{ $rm->room_number }} ({{ $rm->room_type }}{{ $rm->availability_status !== 'available' ? ' · occupied' : '' }})
                                </option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500">Capacity</label>
                    <input type="number" name="capacity" id="edit-section-capacity" min="20" max="30"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-medium text-slate-500">Homeroom Adviser</label>
                    <div class="relative">
                        <select name="homeroom_adviser_id" id="edit-section-adviser"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="">— No Adviser —</option>
                            @foreach($teachers as $teacher)
                            <option value="{{ $teacher['id'] }}">{{ $teacher['name'] }}</option>
                            @endforeach
                        </select>
                        <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5 col-span-2">
                    <label class="text-xs font-medium text-slate-500">Section Status</label>
                    <div class="relative">
                        <select name="section_status" id="edit-section-status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="archived">Archived</option>
                        </select>
                        <iconify-icon icon="solar:alt-arrow-right-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    Save Changes
                </button>
                <button type="button" onclick="closeSectionModal('edit-section-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════ VIEW SECTION MODAL ══════ --}}
<div id="view-section-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeSectionModal('view-section-modal')"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" style="max-height:90vh;overflow-y:auto">

        {{-- Header --}}
        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:target-bold" width="16" class="text-white/80"></iconify-icon>
                <h3 class="text-white text-sm font-bold tracking-wide">SECTION DETAILS</h3>
            </div>
            <button onclick="closeSectionModal('view-section-modal')" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        <div class="px-6 py-5 space-y-4">

            {{-- ID + Status row --}}
            <div class="flex items-center justify-between">
                <span id="view-section-id" class="font-mono text-xs text-slate-400"></span>
                <span id="view-section-status-badge" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"></span>
            </div>

            {{-- Main info grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-0.5">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Section Name</span>
                    <span id="view-section-name" class="text-sm font-semibold text-slate-700 dark:text-white"></span>
                </div>
                <div class="flex flex-col gap-0.5">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Grade Level</span>
                    <span id="view-section-grade" class="text-sm text-slate-700 dark:text-slate-300"></span>
                </div>
                <div class="flex flex-col gap-0.5">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">School Year</span>
                    <span id="view-section-sy" class="text-sm text-slate-700 dark:text-slate-300"></span>
                </div>
                <div class="flex flex-col gap-0.5">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Room</span>
                    <span id="view-section-room" class="text-sm text-slate-700 dark:text-slate-300"></span>
                </div>
                <div class="flex flex-col gap-0.5">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Program Level</span>
                    <span id="view-section-program" class="text-sm text-slate-700 dark:text-slate-300"></span>
                </div>
                <div class="flex flex-col gap-0.5">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Capacity</span>
                    <span id="view-section-capacity" class="text-sm text-slate-700 dark:text-slate-300"></span>
                </div>
                <div class="flex flex-col gap-0.5 col-span-2">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Homeroom Adviser</span>
                    <span id="view-section-adviser" class="text-sm text-slate-700 dark:text-slate-300"></span>
                </div>
                <div id="view-shs-block" class="col-span-2 grid grid-cols-2 gap-4 hidden">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Track</span>
                        <span id="view-section-track" class="text-sm text-slate-700 dark:text-slate-300"></span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Strand</span>
                        <span id="view-section-strand" class="text-sm text-slate-700 dark:text-slate-300"></span>
                    </div>
                </div>
            </div>

            {{-- Badges row --}}
            <div class="flex flex-wrap items-center gap-2 pt-1 border-t border-slate-100 dark:border-dark-border">
                <span id="view-section-adviser-badge" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"></span>
                <span id="view-section-avail-badge" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"></span>
            </div>

            {{-- Footer action --}}
            <div class="flex justify-end pt-2">
                <button type="button" onclick="closeSectionModal('view-section-modal')"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openSectionModal(id) {
    document.getElementById(id)?.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeSectionModal(id) {
    document.getElementById(id)?.classList.add('hidden');
    document.body.style.overflow = '';
}


// Auto-open add modal if validation errors exist
@if($errors->any())
document.addEventListener('DOMContentLoaded', () => openSectionModal('add-section-modal'));
@endif

// Pending counts from controller
const pendingByGrade = @json($pendingByGrade);

// Show/hide SHS fields + update pending count when grade changes
function updatePendingCount(grade) {
    const shsGrades  = ['Grade 11', 'Grade 12'];
    const shsFields  = document.getElementById('shs-fields');
    const countRow   = document.getElementById('pending-count-row');
    const countVal   = document.getElementById('pending-count-value');

    // SHS fields visibility
    if (shsGrades.includes(grade)) {
        shsFields?.classList.remove('hidden');
    } else {
        shsFields?.classList.add('hidden');
        // Reset track/strand
        const track  = document.getElementById('add-track');
        const strand = document.getElementById('add-strand');
        if (track)  track.value  = '';
        if (strand) strand.value = '';
        filterStrands('');
    }

    // Pending student count
    if (grade) {
        const count = pendingByGrade[grade] ?? 0;
        countVal.textContent = count;
        countRow?.classList.remove('hidden');
        countRow?.classList.add('flex');
    } else {
        countRow?.classList.add('hidden');
        countRow?.classList.remove('flex');
    }
}

// Filter strands by selected track
function filterStrands(track) {
    const strandSelect = document.getElementById('add-strand');
    if (!strandSelect) return;
    const options = strandSelect.querySelectorAll('option');
    options.forEach(opt => {
        if (!opt.value) return; // keep placeholder
        if (!track || opt.dataset.track === track) {
            opt.hidden = false;
        } else {
            opt.hidden = true;
            if (opt.selected) opt.selected = false;
        }
    });
    // Reset if nothing matches
    if (track && !strandSelect.value) strandSelect.value = '';
}

// Init on page load (in case of old() values after validation fail)
document.addEventListener('DOMContentLoaded', function () {
    const gradeSelect = document.getElementById('add-grade-level');
    if (gradeSelect?.value) updatePendingCount(gradeSelect.value);
    const trackSelect = document.getElementById('add-track');
    if (trackSelect?.value) filterStrands(trackSelect.value);
});

function openViewSectionModal(data) {
    document.getElementById('view-section-id').textContent      = data.section_id || '—';
    document.getElementById('view-section-name').textContent    = data.section_name || '—';
    document.getElementById('view-section-grade').textContent   = data.grade_level || '—';
    document.getElementById('view-section-sy').textContent      = data.school_year || '—';
    document.getElementById('view-section-room').textContent    = data.room || '—';
    document.getElementById('view-section-program').textContent = data.program_level || '—';
    document.getElementById('view-section-capacity').textContent =
        (data.current_enrollment ?? '0') + ' / ' + (data.capacity ?? '—') + ' enrolled';
    document.getElementById('view-section-adviser').textContent = data.homeroom_adviser_name || 'TBA';

    // SHS track/strand
    const shsBlock = document.getElementById('view-shs-block');
    if (data.track || data.strand) {
        document.getElementById('view-section-track').textContent  = data.track  || '—';
        document.getElementById('view-section-strand').textContent = data.strand || '—';
        shsBlock.classList.remove('hidden');
    } else {
        shsBlock.classList.add('hidden');
    }

    // Status badge
    const statusBadge = document.getElementById('view-section-status-badge');
    const statusMap = { active: ['bg-green-100 text-green-700', 'Active'], inactive: ['bg-slate-100 text-slate-500', 'Inactive'], archived: ['bg-red-100 text-red-600', 'Archived'] };
    const [sCls, sLbl] = statusMap[data.section_status] ?? ['bg-slate-100 text-slate-500', data.section_status];
    statusBadge.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ' + sCls;
    statusBadge.textContent = sLbl;

    // Adviser badge
    const advBadge = document.getElementById('view-section-adviser-badge');
    const advCls = data.adviser_status === 'assigned' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
    advBadge.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ' + advCls;
    advBadge.textContent = data.adviser_status === 'assigned' ? 'Adviser Assigned' : 'No Adviser';

    // Availability badge
    const availBadge = document.getElementById('view-section-avail-badge');
    const availMap = { available: 'bg-green-100 text-green-700', full: 'bg-red-100 text-red-700', near_capacity: 'bg-amber-100 text-amber-700', below_minimum: 'bg-orange-100 text-orange-700' };
    availBadge.className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ' + (availMap[data.availability] ?? 'bg-slate-100 text-slate-600');
    availBadge.textContent = (data.availability ?? '—').replace('_', ' ').replace(/\b\w/g, c => c.toUpperCase());

    openSectionModal('view-section-modal');
}

function openEditSectionModal(id, data) {
    document.getElementById('edit-section-form').action = '/admin/classes/sections/' + id;
    document.getElementById('edit-section-name').value      = data.section_name || '';
    document.getElementById('edit-section-capacity').value  = data.capacity || 30;
    document.getElementById('edit-section-adviser').value   = data.homeroom_adviser_id || '';
    document.getElementById('edit-section-status').value    = data.section_status || 'active';

    // Room select: set value, adding a temporary option if not in list
    const roomSelect = document.getElementById('edit-section-room');
    const currentRoom = data.room || '';
    if (currentRoom && !Array.from(roomSelect.options).some(o => o.value === currentRoom)) {
        const opt = new Option(currentRoom + ' (current)', currentRoom);
        roomSelect.add(opt, 1);
    }
    roomSelect.value = currentRoom;

    openSectionModal('edit-section-modal');
}

function deleteSection(id) {
    if (!confirm('Are you sure you want to delete this section? This cannot be undone.')) return;
    fetch('/admin/classes/sections/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
    })
    .then(r => r.json().then(data => ({ ok: r.ok, data })))
    .then(({ ok, data }) => {
        if (ok && data.success) location.reload();
        else alert(data.message || 'Failed to delete section.');
    })
    .catch(() => alert('Request failed. Check your connection.'));
}
</script>
@endpush
@endsection