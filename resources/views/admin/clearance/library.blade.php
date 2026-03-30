@extends('layout.admin_layout')
@section('title', 'Clearance')
@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 bg-slate-50/50 p-4">
    <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div><h1 class="text-2xl font-semibold text-slate-900 dark:text-white">Clearance</h1><p class="mt-1 text-sm text-slate-500">Manage student clearance status.</p></div>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-base font-semibold text-slate-900">Clearance List</h3>
            <input type="text" placeholder="Search..." class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead><tr class="border-b border-slate-100 text-xs font-medium text-slate-500"><th class="px-6 py-3">Student</th><th class="px-6 py-3">Level</th><th class="px-6 py-3">Library</th><th class="px-6 py-3">Finance</th><th class="px-6 py-3">Adviser</th><th class="px-6 py-3">Overall</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach([['Ana Gonzales','Grade 8','Cleared','Cleared','Pending','Pending'],['Ben Torres','Grade 11','Cleared','Cleared','Cleared','Cleared']] as $r)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3 font-medium text-slate-700">{{ $r[0] }}</td>
                        <td class="px-6 py-3 text-slate-500">{{ $r[1] }}</td>
                        @foreach(array_slice($r, 2) as $status)
                        <td class="px-6 py-3"><span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $status === 'Cleared' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">{{ $status }}</span></td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <p class="mt-8 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection