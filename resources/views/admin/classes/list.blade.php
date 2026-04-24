@extends('layouts.admin_layout')
@section('title', 'Class List')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Page Header --}}
    <x-admin.page-header
        title="Classes"
        subtitle="Manage Classes, Roster, Classroom and Sections"
        school-year="{{ $schoolYear ?? $activeSchoolYear }}"
    />

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Card Header --}}
        <div class="flex items-center gap-2 px-6 py-5 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:clock-circle-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Class List</h2>
            <span class="ml-1 text-xs text-slate-400">Auto-generated from Subject Allocation</span>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <form method="GET" action="{{ route('admin.classes.list') }}">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-3">
                    {{-- School Year --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">School Year</label>
                        <div class="relative">
                            <select name="school_year" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                @foreach($schoolYears as $sy)
                                <option value="{{ $sy->name }}" {{ $schoolYear===$sy->name?'selected':'' }}>SY {{ $sy->name }}</option>
                                @endforeach
                                @if($schoolYears->isEmpty())
                                <option value="{{ $schoolYear }}" selected>SY {{ $schoolYear }}</option>
                                @endif
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Grade and Section --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Grade and Section</label>
                        <div class="relative">
                            <select name="grade_section" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All</option>
                                @foreach($sections as $sec)
                                <option value="{{ $sec->grade_level }}" {{ request('grade_section')===$sec->grade_level?'selected':'' }}>
                                    {{ $sec->display_name }}
                                </option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Subject</label>
                        <div class="relative">
                            <select name="subject_id" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}" {{ request('subject_id')==$sub->id?'selected':'' }}>{{ $sub->subject_name }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>

                    {{-- Teacher --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-medium text-slate-500 dark:text-slate-400">Teacher</label>
                        <div class="relative">
                            <select name="teacher_id" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 pr-8">
                                <option value="">All Teachers</option>
                                @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ request('teacher_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>
                                @endforeach
                            </select>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="13" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon> Apply
                    </button>
                    <a href="{{ route('admin.classes.list') }}" class="rounded-lg border border-slate-200 dark:border-dark-border px-5 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 transition-colors">Clear All</a>
                </div>
            </form>
        </div>

        {{-- Table Controls --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between px-6 py-3 border-b border-slate-100 dark:border-dark-border">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <span>Show</span>
                <select class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-2 py-1 text-xs focus:outline-none">
                    <option>10</option><option>25</option><option>50</option>
                </select>
                <span>Entries</span>
            </div>
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" width="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></iconify-icon>
                <input type="text" id="class-search" placeholder="Search class.."
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white pl-8 pr-4 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:960px">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-dark-border text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <th class="px-4 py-3 whitespace-nowrap">Class ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">School Year</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3 whitespace-nowrap">Grade & Section</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Hrs/Wk</th>
                        <th class="px-4 py-3">Schedule</th>
                        <th class="px-4 py-3">Room</th>
                        <th class="px-4 py-3">Teacher</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border" id="class-tbody">
                @forelse($classes as $class)
                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors class-row"
                    data-name="{{ strtolower($class->subject_name . ' ' . ($class->section?->display_name ?? '') . ' ' . ($class->teacher?->name ?? '')) }}">

                    <td class="px-4 py-3 text-xs font-mono text-slate-400 whitespace-nowrap">#{{ str_pad($class->id, 6, '0', STR_PAD_LEFT) }}</td>

                    <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">{{ $class->school_year }}</td>

                    <td class="px-4 py-3">
                        <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $class->subject_name }}</p>
                        <p class="text-xs text-slate-400 font-mono">{{ $class->subject_code }}</p>
                    </td>

                    <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300 whitespace-nowrap font-medium">
                        {{ $class->section?->display_name ?? '—' }}
                    </td>

                    <td class="px-4 py-3 text-xs text-center font-semibold text-slate-700 dark:text-slate-300">
                        {{ $class->hours_per_week }}
                    </td>

                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                        @if($class->schedules->isEmpty())
                            <span class="text-slate-300 italic">No schedule set</span>
                        @else
                            @foreach($class->schedules as $slot)
                            <div class="whitespace-nowrap leading-5">
                                <span class="font-medium text-slate-600 dark:text-slate-300">{{ $slot->day_of_week }}</span>
                                <span class="text-slate-400 ml-1">
                                    {{ \Carbon\Carbon::parse($slot->time_start)->format('g:i A') }}–{{ \Carbon\Carbon::parse($slot->time_end)->format('g:i A') }}
                                </span>
                            </div>
                            @endforeach
                        @endif
                    </td>

                    <td class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                        @if($class->schedules->isNotEmpty())
                            {{ $class->schedules->pluck('room')->filter()->unique()->implode(', ') ?: '—' }}
                        @else
                            —
                        @endif
                    </td>

                    <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-300 whitespace-nowrap">
                        {{ $class->teacher?->name ?? '—' }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <div x-data="{ open: false }" class="relative inline-block">
                            <button @click="open=!open"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-300 transition-colors shadow-sm">
                                Select
                                <iconify-icon icon="solar:alt-arrow-down-linear" width="12"
                                    :class="open ? 'rotate-180' : ''" class="transition-transform duration-200"></iconify-icon>
                            </button>
                            <div x-show="open" @click.outside="open=false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 z-20 mt-1 w-40 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-dark-card shadow-lg py-1">

                                {{-- View: shows full class detail modal --}}
                                <button type="button" @click="open=false"
                                    onclick="viewClass({{ $class->id }})"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:eye-bold" width="14" class="text-amber-500"></iconify-icon>
                                    View Details
                                </button>

                                {{-- Edit: redirect to subject allocation for this section --}}
                                <a href="{{ route('admin.academic.subjects') }}?section_id={{ $class->section_id }}&school_year={{ $class->school_year }}&open_allocation=1"
                                    @click="open=false"
                                    class="flex w-full items-center gap-2 px-3 py-2 text-xs text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                    <iconify-icon icon="solar:pen-bold" width="14" class="text-blue-500"></iconify-icon>
                                    Edit Allocation
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <iconify-icon icon="solar:calendar-search-bold" width="36" class="text-slate-300"></iconify-icon>
                            <p class="text-sm font-medium text-slate-500">No classes found.</p>
                            <p class="text-xs text-slate-400">Classes are created automatically when subjects are assigned to sections in
                                <a href="{{ route('admin.academic.subjects') }}" class="text-blue-600 hover:underline">Subject Allocation →</a>
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border">
            <p class="text-xs text-slate-400">
                Showing {{ $classes->firstItem() ?? 0 }}–{{ $classes->lastItem() ?? 0 }} of {{ $classes->total() }}
            </p>
            {{ $classes->links() }}
        </div>

    </div>
    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══ VIEW CLASS MODAL ══ --}}
<div id="view-class-modal" class="fixed inset-0 z-50 items-center justify-center" style="display:none">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeClassModal()"></div>
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl" style="max-height:90vh;overflow-y:auto">

        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between sticky top-0 z-10">
            <h3 id="class-modal-title" class="text-white text-sm font-bold">CLASS SCHEDULE</h3>
            <button onclick="closeClassModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        <div id="class-modal-content" class="p-6">
            <div class="py-8 text-center">
                <iconify-icon icon="solar:loading-bold" width="24" class="text-slate-300 animate-spin block mx-auto mb-2"></iconify-icon>
                <p class="text-xs text-slate-400">Loading...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const BASE = '{{ url("/admin/classes/list") }}';

function closeClassModal() {
    document.getElementById('view-class-modal').style.display = 'none';
    document.body.style.overflow = '';
}

// Live search
document.getElementById('class-search')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.class-row').forEach(row =>
        row.style.display = (!q || (row.dataset.name||'').includes(q)) ? '' : 'none'
    );
});

// View class modal
function viewClass(id) {
    document.getElementById('view-class-modal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    document.getElementById('class-modal-content').innerHTML = `
        <div class="py-8 text-center">
            <iconify-icon icon="solar:loading-bold" width="24" class="text-slate-300 animate-spin block mx-auto mb-2"></iconify-icon>
            <p class="text-xs text-slate-400">Loading...</p>
        </div>`;

    fetch(`${BASE}/${id}`)
        .then(r => r.json())
        .then(({ class: c }) => {
            document.getElementById('class-modal-title').textContent = c.subject_name + ' — ' + c.display_name;

            const scheduleRows = c.schedules.length
                ? c.schedules.map(s => `
                    <tr class="border-b border-slate-100 dark:border-slate-700 last:border-0">
                        <td class="py-2 pr-4 text-xs font-medium text-slate-700 dark:text-slate-300">${s.day}</td>
                        <td class="py-2 pr-4 text-xs text-slate-500">${s.time_start} – ${s.time_end}</td>
                        <td class="py-2 text-xs text-slate-500">${s.room}</td>
                    </tr>`).join('')
                : `<tr><td colspan="3" class="py-4 text-center text-xs text-slate-400 italic">No schedule set yet.</td></tr>`;

            const shsBlock = (c.track || c.strand) ? `
                <div class="flex gap-6 pt-2">
                    <div><p class="text-[10px] text-slate-400 uppercase tracking-wide mb-0.5">Track</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">${c.track||'—'}</p></div>
                    <div><p class="text-[10px] text-slate-400 uppercase tracking-wide mb-0.5">Strand</p><p class="text-xs font-semibold text-slate-700 dark:text-slate-300">${c.strand||'—'}</p></div>
                </div>` : '';

            document.getElementById('class-modal-content').innerHTML = `
            <div class="space-y-5">

                {{-- Class Information --}}
                <div class="rounded-xl border border-slate-200 dark:border-dark-border p-4 space-y-3 text-xs">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Class Information</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div><p class="text-slate-400 mb-0.5">Subject Code</p><p class="font-mono font-semibold text-slate-700 dark:text-slate-300">${c.subject_code}</p></div>
                        <div><p class="text-slate-400 mb-0.5">Subject</p><p class="font-semibold text-slate-700 dark:text-slate-300">${c.subject_name}</p></div>
                        <div><p class="text-slate-400 mb-0.5">Grade Level</p><p class="font-semibold text-slate-700 dark:text-slate-300">${c.grade_level}</p></div>
                        <div><p class="text-slate-400 mb-0.5">Section</p><p class="font-semibold text-slate-700 dark:text-slate-300">${c.section_name}</p></div>
                        <div><p class="text-slate-400 mb-0.5">Program Level</p><p class="font-semibold text-slate-700 dark:text-slate-300">${c.program_level}</p></div>
                        <div><p class="text-slate-400 mb-0.5">Hours / Week</p><p class="font-semibold text-slate-700 dark:text-slate-300">${c.hours_per_week}</p></div>
                        <div class="col-span-2"><p class="text-slate-400 mb-0.5">Teacher</p><p class="font-semibold text-slate-700 dark:text-slate-300">${c.teacher}</p></div>
                    </div>
                    ${shsBlock}
                </div>

                {{-- Schedule --}}
                <div class="rounded-xl border border-slate-200 dark:border-dark-border overflow-hidden">
                    <div class="bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5 border-b border-slate-200 dark:border-dark-border">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Weekly Schedule (${c.schedules.length} slot${c.schedules.length !== 1 ? 's' : ''})</p>
                    </div>
                    <div class="px-4 py-2">
                        <table class="w-full">
                            <thead>
                                <tr class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">
                                    <th class="py-1.5 pr-4 text-left">Day</th>
                                    <th class="py-1.5 pr-4 text-left">Time</th>
                                    <th class="py-1.5 text-left">Room</th>
                                </tr>
                            </thead>
                            <tbody>${scheduleRows}</tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer actions --}}
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-dark-border">
                    <a href="{{ route('admin.academic.subjects') }}?section_id=${c.section_id}&school_year=${c.school_year}&open_allocation=1"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                        <iconify-icon icon="solar:pen-bold" width="14"></iconify-icon>
                        EDIT ALLOCATION
                    </a>
                    <button onclick="closeClassModal()"
                        class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                        CLOSE
                    </button>
                </div>
            </div>`;
        })
        .catch(() => {
            document.getElementById('class-modal-content').innerHTML =
                '<p class="text-xs text-red-500 text-center py-8">Failed to load class details.</p>';
        });
}
</script>
@endpush
@endsection