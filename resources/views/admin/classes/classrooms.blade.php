@extends('layouts.admin_layout')
@section('title', 'Classroom Management')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4 pb-28"
     x-data="classroomPage()">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Classroom Management</h1>
            <p class="mt-0.5 text-sm text-slate-400">Manage school classrooms and facilities</p>
        </div>
        <button @click="openModal()"
            class="flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 hover:bg-blue-100 dark:border-blue-700 dark:bg-blue-900/20 px-4 py-2 text-xs font-semibold text-blue-700 dark:text-blue-400 transition-colors mt-2 sm:mt-0 self-start">
            <iconify-icon icon="solar:add-circle-linear" width="15"></iconify-icon>
            Add Classroom
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['blue',   'solar:buildings-bold',      $total,       'Total Rooms'],
            ['green',  'solar:check-circle-bold',    $available,   'Available'],
            ['amber',  'solar:clock-circle-bold',    $occupied,    'Occupied'],
            ['red',    'solar:hammer-bold',          $underRepair, 'Under Repair'],
        ] as [$c, $icon, $count, $label])
        <div class="flex items-center gap-3 rounded-xl border border-{{ $c }}-200 bg-{{ $c }}-50 dark:border-{{ $c }}-900/30 dark:bg-{{ $c }}-900/10 px-4 py-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-{{ $c }}-100 dark:bg-{{ $c }}-900/30">
                <iconify-icon icon="{{ $icon }}" width="20" class="text-{{ $c }}-600 dark:text-{{ $c }}-400"></iconify-icon>
            </div>
            <div>
                <p class="text-xl font-bold text-slate-800 dark:text-white leading-none">{{ $count }}</p>
                <p class="text-xs text-{{ $c }}-600 dark:text-{{ $c }}-400 mt-1">{{ $label }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.classes.classrooms') }}"
              class="grid grid-cols-2 sm:grid-cols-4 gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border bg-slate-50/50 dark:bg-white/[0.01]">
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Room Type</label>
                <select name="type" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    @foreach(['Regular','Lab','Auditorium','Music Room','Art Room'] as $t)
                        <option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Availability</label>
                <select name="avail_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="available"   {{ request('avail_status')==='available'?'selected':'' }}>Available</option>
                    <option value="occupied"    {{ request('avail_status')==='occupied'?'selected':'' }}>Occupied</option>
                    <option value="under_repair"{{ request('avail_status')==='under_repair'?'selected':'' }}>Under Repair</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Room Status</label>
                <select name="room_status" class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="active"             {{ request('room_status')==='active'?'selected':'' }}>Active</option>
                    <option value="inactive"           {{ request('room_status')==='inactive'?'selected':'' }}>Inactive</option>
                    <option value="under_maintenance"  {{ request('room_status')==='under_maintenance'?'selected':'' }}>Under Maintenance</option>
                </select>
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Search</label>
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Room number…"
                        class="flex-1 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card dark:text-white px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="rounded-lg bg-[#0d4c8f] hover:bg-blue-800 px-3 py-2 text-xs font-semibold text-white transition-colors">
                        <iconify-icon icon="solar:filter-bold" width="13"></iconify-icon>
                    </button>
                    @if(request()->hasAny(['type','avail_status','room_status','search']))
                    <a href="{{ route('admin.classes.classrooms') }}" class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-2 text-xs text-slate-500 transition-colors">✕</a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm" style="min-width:900px">
                <thead class="border-b border-slate-100 dark:border-dark-border text-[11px] font-semibold uppercase tracking-wide text-slate-500 bg-slate-50/70 dark:bg-white/[0.02]">
                    <tr>
                        <th class="px-5 py-3">Room</th>
                        <th class="px-5 py-3 text-center">Capacity</th>
                        <th class="px-5 py-3">Type</th>
                        <th class="px-5 py-3">Grade Level</th>
                        <th class="px-5 py-3">Assigned Section</th>
                        <th class="px-5 py-3">Availability</th>
                        <th class="px-5 py-3">Room Status</th>
                        <th class="px-5 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-dark-border">
                    @forelse($classrooms as $room)
                    @php
                        $availBadge = match($room->availability_status) {
                            'available'   => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                            'occupied'    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                            'under_repair'=> 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                            default       => 'bg-slate-100 text-slate-600',
                        };
                        $statusBadge = match($room->room_status) {
                            'active'            => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                            'inactive'          => 'bg-slate-100 text-slate-600 dark:bg-slate-800/40 dark:text-slate-400',
                            'under_maintenance' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                            default             => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    @php $secKey = strtoupper(trim($room->room_number)); $assignedSec = $sectionsByRoom[$secKey] ?? null; @endphp
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="px-5 py-3 font-semibold text-slate-800 dark:text-white">{{ $room->room_number }}</td>
                        <td class="px-5 py-3 text-center text-xs text-slate-500">{{ $room->capacity }}</td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ $room->room_type }}</td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ $room->grade_level_type ?? '—' }}</td>
                        <td class="px-5 py-3 text-xs text-slate-500">
                            @if($assignedSec)
                                <span class="font-medium text-slate-700 dark:text-slate-200">{{ $assignedSec['display_name'] }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $availBadge }}">
                                {{ ucwords(str_replace('_',' ', $room->availability_status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $statusBadge }}">
                                {{ ucwords(str_replace('_',' ', $room->room_status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-center">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open" @click.outside="open = false"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-sm">
                                    Select <iconify-icon icon="solar:alt-arrow-down-linear" width="12" :class="open?'rotate-180':''" class="transition-transform"></iconify-icon>
                                </button>
                                <div x-show="open" x-transition
                                     class="absolute right-0 top-full mt-1 w-44 rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-lg z-50 overflow-hidden"
                                     style="display:none">
                                    @if($assignedSec)
                                    <button @click="open=false; viewSection(@js($assignedSec))"
                                        class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-slate-600 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:eye-linear" width="14" class="text-slate-400"></iconify-icon> View Section
                                    </button>
                                    @endif
                                    <button @click="open=false; editRoom({{ $room->id }}, @js($room))"
                                        class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-blue-600 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:pen-linear" width="14"></iconify-icon> Edit
                                    </button>
                                    <button @click="open=false; deleteRoom({{ $room->id }}, '{{ $room->room_number }}')"
                                        class="flex w-full items-center gap-2 px-4 py-2.5 text-xs text-red-600 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <iconify-icon icon="solar:trash-bin-trash-linear" width="14"></iconify-icon> Delete
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-sm text-slate-400"> {{-- 8 cols: room, capacity, type, grade, assigned section, availability, status, action --}}
                            <iconify-icon icon="solar:buildings-bold" width="32" class="mx-auto mb-2 opacity-30 block"></iconify-icon>
                            No classrooms found. <button @click="openModal()" class="text-blue-600 hover:underline font-medium">Add one now.</button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($classrooms->hasPages())
        <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50">
            <p class="text-xs text-slate-500">Showing {{ $classrooms->firstItem() }}–{{ $classrooms->lastItem() }} of {{ $classrooms->total() }}</p>
            <div class="flex items-center gap-1">{{ $classrooms->links() }}</div>
        </div>
        @endif
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>

    {{-- ── Classroom Modal ──────────────────────────────────────── --}}
    <div x-show="modalOpen" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden max-h-[92vh] flex flex-col" @click.stop>

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 bg-[#0d4c8f] flex-shrink-0">
                <h3 class="text-sm font-bold text-white" x-text="editId ? 'Edit Classroom' : 'Add Classroom'"></h3>
                <button @click="closeModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">
                    <iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon>
                </button>
            </div>

            {{-- Body --}}
            <div class="overflow-y-auto flex-1 p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-500">Room Number <span class="text-red-500">*</span></label>
                        <input type="text" x-model="form.room_number" placeholder="e.g. 101"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-500">Capacity <span class="text-red-500">*</span></label>
                        <input type="number" x-model.number="form.capacity" placeholder="30" min="1"
                            class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-500">Room Type <span class="text-red-500">*</span></label>
                        <select x-model="form.room_type"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['Regular','Lab','Auditorium','Music Room','Art Room'] as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-500">Grade Level Type</label>
                        <select x-model="form.grade_level_type"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">— Any Level —</option>
                            @foreach(['Grade 1-3','Grade 4-6','Grade 7-10','Grade 11-12','All Levels'] as $gl)
                                <option value="{{ $gl }}">{{ $gl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-slate-500">Room Status <span class="text-red-500">*</span></label>
                        <select x-model="form.room_status"
                            class="w-full appearance-none rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="under_maintenance">Under Maintenance</option>
                        </select>
                    </div>
                </div>

                {{-- Maintenance notice --}}
                <div x-show="form.room_status === 'under_maintenance'"
                     class="flex items-center gap-2 rounded-xl bg-red-50 border border-red-200 px-4 py-2.5">
                    <iconify-icon icon="solar:danger-triangle-bold" width="14" class="text-red-500 shrink-0"></iconify-icon>
                    <p class="text-[11px] text-red-700">Availability will be set to <strong>Under Repair</strong> automatically.</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-slate-500">Notes <span class="text-slate-400 font-normal">(optional)</span></label>
                    <textarea x-model="form.notes" rows="2" placeholder="Any remarks about this room…"
                        class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                </div>

                {{-- Error display --}}
                <div x-show="errors.length" class="rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                    <template x-for="e in errors" :key="e">
                        <p class="text-xs text-red-700" x-text="e"></p>
                    </template>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 dark:border-dark-border bg-slate-50/50 flex-shrink-0">
                <button @click="closeModal()"
                    class="rounded-lg border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button @click="saveRoom()"
                    :disabled="saving"
                    :class="saving ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700'"
                    class="flex items-center gap-2 rounded-lg bg-[#0d4c8f] px-5 py-2 text-xs font-semibold text-white transition-colors shadow-sm">
                    <iconify-icon x-show="saving" icon="solar:spinner-line-duotone" width="14" class="animate-spin"></iconify-icon>
                    <iconify-icon x-show="!saving" icon="solar:check-circle-bold" width="14"></iconify-icon>
                    <span x-text="saving ? 'Saving…' : (editId ? 'Update Classroom' : 'Save Classroom')"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Toast --}}
    <div x-show="toast.show" x-transition
         :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'"
         class="fixed top-5 right-5 z-[9999] rounded-xl px-4 py-3 text-xs font-semibold text-white shadow-lg max-w-sm"
         style="display:none"
         x-text="toast.msg"></div>

    {{-- Section Details Modal --}}
    <div x-show="sectionModal.open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="sectionModal.open = false"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 bg-[#0d4c8f]">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:target-bold" width="16" class="text-white/80"></iconify-icon>
                    <h3 class="text-sm font-bold text-white">SECTION DETAILS</h3>
                </div>
                <button @click="sectionModal.open = false" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30">
                    <iconify-icon icon="solar:close-circle-linear" width="16"></iconify-icon>
                </button>
            </div>
            <template x-if="sectionModal.data">
                <div class="px-6 py-5 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2 flex flex-col gap-0.5">
                            <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Section</span>
                            <span class="text-base font-bold text-slate-800 dark:text-white" x-text="sectionModal.data.display_name"></span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Grade Level</span>
                            <span class="text-sm text-slate-700 dark:text-slate-300" x-text="sectionModal.data.grade_level"></span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">School Year</span>
                            <span class="text-sm text-slate-700 dark:text-slate-300" x-text="sectionModal.data.school_year"></span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Enrollment</span>
                            <span class="text-sm text-slate-700 dark:text-slate-300" x-text="sectionModal.data.current_enrollment + ' / ' + sectionModal.data.capacity"></span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Homeroom Adviser</span>
                            <span class="text-sm text-slate-700 dark:text-slate-300" x-text="sectionModal.data.homeroom_adviser_name"></span>
                        </div>
                        <template x-if="sectionModal.data.track || sectionModal.data.strand">
                            <div class="col-span-2 grid grid-cols-2 gap-3">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Track</span>
                                    <span class="text-sm text-slate-700 dark:text-slate-300" x-text="sectionModal.data.track || '—'"></span>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Strand</span>
                                    <span class="text-sm text-slate-700 dark:text-slate-300" x-text="sectionModal.data.strand || '—'"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="flex items-center gap-2 pt-1 border-t border-slate-100 dark:border-dark-border">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                            :class="sectionModal.data.availability === 'available' ? 'bg-green-100 text-green-700' : sectionModal.data.availability === 'full' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'"
                            x-text="(sectionModal.data.availability || '—').replace('_',' ').replace(/\b\w/g, c => c.toUpperCase())"></span>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                            :class="sectionModal.data.adviser_status === 'assigned' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                            x-text="sectionModal.data.adviser_status === 'assigned' ? 'Adviser Assigned' : 'No Adviser'"></span>
                    </div>
                    <div class="flex justify-end">
                        <a :href="'/admin/classes/sections?search=' + encodeURIComponent(sectionModal.data.section_name)"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-4 py-2 text-xs font-semibold text-white transition-colors">
                            <iconify-icon icon="solar:arrow-right-linear" width="13"></iconify-icon>
                            Go to Section
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>

@push('scripts')
<script>
const _sectionsByRoom = @json($sectionsByRoom);

function classroomPage() {
    return {
        modalOpen: false,
        saving: false,
        editId: null,
        errors: [],
        toast: { show: false, msg: '', type: 'success' },
        form: {
            room_number: '', capacity: 30, room_type: 'Regular',
            grade_level_type: '', room_status: 'active', notes: '',
        },
        sectionModal: { open: false, data: null },

        openModal() {
            this.editId = null;
            this.errors = [];
            this.form = { room_number:'', capacity:30, room_type:'Regular', grade_level_type:'', room_status:'active', notes:'' };
            this.modalOpen = true;
        },

        editRoom(id, data) {
            this.editId = id;
            this.errors = [];
            this.form = {
                room_number:      data.room_number,
                capacity:         data.capacity,
                room_type:        data.room_type,
                grade_level_type: data.grade_level_type || '',
                room_status:      data.room_status,
                notes:            data.notes || '',
            };
            this.modalOpen = true;
        },

        viewSection(data) {
            this.sectionModal = { open: true, data };
        },

        closeModal() {
            this.modalOpen = false;
        },

        async saveRoom() {
            if (!this.form.room_number.trim()) { this.errors = ['Room number is required.']; return; }
            this.saving = true;
            this.errors = [];
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const url  = this.editId
                ? `/admin/classes/classrooms/${this.editId}`
                : '/admin/classes/classrooms';
            const method = this.editId ? 'PUT' : 'POST';

            try {
                const r = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify(this.form),
                });
                const d = await r.json();
                if (r.ok && d.success) {
                    this.closeModal();
                    this.showToast(d.message, 'success');
                    setTimeout(() => location.reload(), 800);
                } else {
                    this.errors = d.errors
                        ? Object.values(d.errors).flat()
                        : [d.message || 'Failed to save.'];
                }
            } catch(e) {
                this.errors = ['An unexpected error occurred.'];
            }
            this.saving = false;
        },

        async deleteRoom(id, roomNumber) {
            if (!confirm(`Delete Room ${roomNumber}? This cannot be undone.`)) return;
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const r = await fetch(`/admin/classes/classrooms/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                });
                const d = await r.json();
                if (d.success) {
                    this.showToast(d.message, 'success');
                    setTimeout(() => location.reload(), 800);
                } else {
                    this.showToast(d.message || 'Delete failed.', 'error');
                }
            } catch(e) {
                this.showToast('An error occurred.', 'error');
            }
        },

        showToast(msg, type = 'success') {
            this.toast = { show: true, msg, type };
            setTimeout(() => this.toast.show = false, 3500);
        },
    };
}
</script>
@endpush

@endsection
