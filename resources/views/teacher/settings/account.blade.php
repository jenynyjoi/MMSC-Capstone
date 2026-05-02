@extends('layouts.teacher_layout')
@section('title', 'My Account')
@section('page_title', 'My Account')
@section('page_subtitle', 'Manage your profile and password')

@section('content')
<div class="p-6 max-w-2xl">

    @if(session('success'))
    <div class="mb-4 flex items-center gap-2 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-300">
        <iconify-icon icon="solar:check-circle-bold" width="16"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif

    {{-- Profile Info --}}
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm mb-5">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:user-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Profile Information</h2>
        </div>
        <form method="POST" action="{{ route('teacher.settings.account.update') }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            @csrf @method('PATCH')

            <div class="flex items-center gap-4 mb-4">
                @if(auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                        class="h-16 w-16 rounded-full object-cover border-2 border-slate-200 dark:border-dark-border">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#0d4c8f]/10 text-[#0d4c8f] dark:bg-[#0d4c8f]/20 dark:text-blue-300 text-xl font-bold border-2 border-slate-200 dark:border-dark-border">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Profile Photo</label>
                    <input type="file" name="profile_photo" accept="image/*"
                        class="text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:rounded-lg file:border-0 file:bg-[#0d4c8f]/10 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-[#0d4c8f] hover:file:bg-[#0d4c8f]/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:diskette-linear" width="13"></iconify-icon>Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="rounded-xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-dark-border">
            <iconify-icon icon="solar:lock-password-bold" width="18" class="text-[#0d4c8f] dark:text-blue-300"></iconify-icon>
            <h2 class="text-sm font-semibold text-slate-900 dark:text-white">Change Password</h2>
        </div>
        <form method="POST" action="{{ route('teacher.settings.account.password') }}" class="px-6 py-5 space-y-4">
            @csrf @method('PATCH')

            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Current Password</label>
                <input type="password" name="current_password"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('current_password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">New Password</label>
                <input type="password" name="password"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation"
                    class="w-full rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 dark:text-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="flex items-center gap-1.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 px-5 py-2 text-xs font-semibold text-white transition-colors">
                    <iconify-icon icon="solar:lock-password-bold" width="13"></iconify-icon>Update Password
                </button>
            </div>
        </form>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© {{ date('Y') }} My Messiah School of Cavite. All rights reserved.</p>
</div>
@endsection
