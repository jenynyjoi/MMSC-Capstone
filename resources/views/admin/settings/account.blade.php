@extends('layouts.admin_layout')
@section('title', 'Account Settings')

@section('content')
<div class="flex-1 overflow-y-auto lg:p-8 dark:bg-dark-bg bg-slate-50/50 p-4">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
        <iconify-icon icon="solar:check-circle-bold" width="16" class="text-green-600"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between flex-wrap">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900 dark:text-white">Settings</h1>
            <p class="mt-0.5 text-sm text-slate-400 dark:text-slate-500">Manage Account &amp; System</p>
        </div>
    </div>

    {{-- Settings Nav Tabs --}}
    <div class="flex gap-1 mb-6 border-b border-slate-200 dark:border-dark-border">
        <a href="{{ route('admin.settings.account') }}"
           class="px-4 py-2 text-sm font-medium text-[#0d4c8f] dark:text-blue-400 border-b-2 border-[#0d4c8f] dark:border-blue-400">
            Account
        </a>
        <a href="{{ route('admin.settings.general') }}"
           class="px-4 py-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors border-b-2 border-transparent">
            General
        </a>
    </div>

    {{-- Main Card --}}
    <div class="rounded-2xl border border-slate-200 dark:border-dark-border bg-white dark:bg-dark-card shadow-sm overflow-hidden">

        {{-- Account Settings Header --}}
        <div class="px-7 py-4 border-b border-slate-100 dark:border-dark-border flex items-center gap-2">
            <iconify-icon icon="solar:user-id-bold" width="20" class="text-slate-600 dark:text-slate-300"></iconify-icon>
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Account Settings</h2>
        </div>

        {{-- Profile Banner --}}
        <div class="px-9 py-8 flex flex-col sm:flex-row items-start sm:items-center gap-6">

            {{-- Avatar with photo upload --}}
            <div class="relative shrink-0">
                <form id="photo-form" method="POST" action="{{ route('admin.settings.account.photo') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="photo-input" name="profile_photo" accept="image/*" class="hidden" onchange="submitPhotoForm()">
                </form>

                <div class="h-28 w-28 rounded-full bg-[#0d4c8f] flex items-center justify-center overflow-hidden border-4 border-white dark:border-slate-600 shadow-lg">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile photo"
                            class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl font-bold text-white select-none">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strstr($user->name, ' ') ?: ' ', 1, 1)) }}
                        </span>
                    @endif
                </div>

                {{-- Camera button triggers file input --}}
                <button type="button" onclick="document.getElementById('photo-input').click()"
                    class="absolute bottom-1 right-1 flex h-8 w-8 items-center justify-center rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 shadow-md text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors"
                    title="Change profile photo">
                    <iconify-icon icon="solar:camera-bold" width="15"></iconify-icon>
                </button>
            </div>

            {{-- Name / Role / Email / Last Login --}}
            <div class="flex-1">
                <p class="text-[11px] font-bold tracking-widest text-[#0d4c8f] dark:text-blue-400 uppercase mb-1">
                    {{ ucfirst(str_replace('_', ' ', $user->getRoleNames()->first() ?? 'Admin')) }}
                </p>
                <h3 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">{{ $user->name }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-0.5">{{ $user->email }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500">
                    Last Login:
                    @if($user->last_login_at)
                        {{ $user->last_login_at->format('F j, Y · g:i A') }}
                    @else
                        —
                    @endif
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-3 mt-5">
                    <button onclick="openEditProfileModal()"
                        class="flex items-center gap-2 rounded-lg border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                        <iconify-icon icon="solar:pen-linear" width="14"></iconify-icon>
                        Edit Profile
                    </button>
                    <button onclick="openChangePasswordModal()"
                        class="flex items-center gap-2 rounded-lg border border-slate-300 dark:border-dark-border bg-white dark:bg-dark-card hover:bg-slate-50 dark:hover:bg-white/5 px-5 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors shadow-sm">
                        <iconify-icon icon="solar:key-linear" width="14"></iconify-icon>
                        Change Password
                    </button>
                </div>
            </div>
        </div>

        <hr class="border-slate-100 dark:border-dark-border">

        {{-- Profile Information --}}
        <div class="px-6 py-6">
            <div class="flex items-center gap-2 mb-6">
                <iconify-icon icon="solar:user-bold" width="18" class="text-slate-600 dark:text-slate-300"></iconify-icon>
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Profile Information</h3>
            </div>

            <div class="space-y-4 max-w-2xl">
                <div class="flex items-center gap-4">
                    <label class="w-40 shrink-0 text-sm text-slate-500 dark:text-slate-400">Name:</label>
                    <div class="flex-1 rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5">
                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $user->name }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 shrink-0 text-sm text-slate-500 dark:text-slate-400">Username:</label>
                    <div class="flex-1 rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5">
                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $user->username ?? '—' }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 shrink-0 text-sm text-slate-500 dark:text-slate-400">Email:</label>
                    <div class="flex-1 rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5">
                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $user->email }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <label class="w-40 shrink-0 text-sm text-slate-500 dark:text-slate-400">Contact Number:</label>
                    <div class="flex-1 rounded-lg border border-slate-200 dark:border-dark-border bg-slate-50 dark:bg-slate-800/40 px-4 py-2.5">
                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $user->phone ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-slate-100 dark:border-dark-border">

        {{-- Footer --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 py-5">
            <p class="text-xs text-slate-400 dark:text-slate-500">
                Last Login:
                @if($user->last_login_at)
                    {{ $user->last_login_at->format('F j, Y · g:i A') }}
                @else
                    —
                @endif
            </p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 rounded-lg border-2 border-red-600 dark:border-red-500 bg-white dark:bg-dark-card hover:bg-red-50 dark:hover:bg-red-900/10 px-6 py-2.5 text-sm font-semibold text-red-500 dark:text-red-400 transition-colors shadow-sm">
                    <iconify-icon icon="solar:logout-3-linear" width="16"></iconify-icon>
                    Log Out
                </button>
            </form>
        </div>

    </div>

    <p class="mt-6 text-center text-xs text-slate-400">© 2026 My Messiah School of Cavite. All rights reserved.</p>
</div>

{{-- ══════ EDIT PROFILE MODAL ══════ --}}
<div id="edit-profile-modal" class="fixed inset-0 z-50 flex items-center justify-center" style="display:none!important">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditProfileModal()"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">

        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:pen-bold" width="16" class="text-white/80"></iconify-icon>
                <h3 class="text-white text-sm font-bold tracking-wide">EDIT PROFILE</h3>
            </div>
            <button onclick="closeEditProfileModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.settings.account.profile') }}" class="px-6 py-5 space-y-4">
            @csrf
            @method('PATCH')

            @if($errors->has('name') || $errors->has('username') || $errors->has('email') || $errors->has('phone'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach(['name','username','email','phone'] as $f)
                        @error($f)<li class="text-xs text-red-500">{{ $message }}</li>@enderror
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                    placeholder="e.g. ADMIN_JENNY"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Contact Number</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    placeholder="e.g. 09123456789"
                    class="rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-dark-border">
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                    SAVE CHANGES
                </button>
                <button type="button" onclick="closeEditProfileModal()"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    CANCEL
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════ CHANGE PASSWORD MODAL ══════ --}}
<div id="change-password-modal" class="fixed inset-0 z-50 flex items-center justify-center" style="display:none!important">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeChangePasswordModal()"></div>
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-dark-card shadow-2xl overflow-hidden">

        <div class="bg-[#0d4c8f] px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="solar:key-bold" width="16" class="text-white/80"></iconify-icon>
                <h3 class="text-white text-sm font-bold tracking-wide">CHANGE PASSWORD</h3>
            </div>
            <button onclick="closeChangePasswordModal()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 text-sm">✕</button>
        </div>

        <form method="POST" action="{{ route('admin.settings.account.password') }}" class="px-6 py-5 space-y-4">
            @csrf
            @method('PATCH')

            @if($errors->has('current_password') || $errors->has('password'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                <ul class="list-disc list-inside space-y-0.5">
                    @error('current_password')<li class="text-xs text-red-500">{{ $message }}</li>@enderror
                    @error('password')<li class="text-xs text-red-500">{{ $message }}</li>@enderror
                </ul>
            </div>
            @endif

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Current Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="current_password" id="cur-pwd" required
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="togglePwd('cur-pwd', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <iconify-icon icon="solar:eye-linear" width="16"></iconify-icon>
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">New Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="password" id="new-pwd" required minlength="8"
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="togglePwd('new-pwd', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <iconify-icon icon="solar:eye-linear" width="16"></iconify-icon>
                    </button>
                </div>
                <p class="text-xs text-slate-400">Minimum 8 characters.</p>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Confirm New Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="conf-pwd" required
                        class="w-full rounded-lg border border-slate-200 dark:border-dark-border dark:bg-dark-card dark:text-white px-3 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="togglePwd('conf-pwd', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <iconify-icon icon="solar:eye-linear" width="16"></iconify-icon>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-dark-border">
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#0d4c8f] hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                    <iconify-icon icon="solar:check-circle-bold" width="14"></iconify-icon>
                    UPDATE PASSWORD
                </button>
                <button type="button" onclick="closeChangePasswordModal()"
                    class="px-6 py-2.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                    CANCEL
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function submitPhotoForm() {
    const input = document.getElementById('photo-input');
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 2 * 1024 * 1024) {
        alert('Image must be 2 MB or smaller.');
        input.value = '';
        return;
    }
    document.getElementById('photo-form').submit();
}

function openEditProfileModal()    { document.getElementById('edit-profile-modal').style.display = 'flex'; }
function closeEditProfileModal()   { document.getElementById('edit-profile-modal').style.display = 'none'; }
function openChangePasswordModal() { document.getElementById('change-password-modal').style.display = 'flex'; }
function closeChangePasswordModal(){ document.getElementById('change-password-modal').style.display = 'none'; }

function togglePwd(id, btn) {
    const input = document.getElementById(id);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    btn.querySelector('iconify-icon').setAttribute('icon', isHidden ? 'solar:eye-closed-linear' : 'solar:eye-linear');
}

// Re-open modal if validation failed
@if($errors->has('name') || $errors->has('username') || $errors->has('email') || $errors->has('phone'))
    document.addEventListener('DOMContentLoaded', () => openEditProfileModal());
@endif
@if($errors->has('current_password') || $errors->has('password'))
    document.addEventListener('DOMContentLoaded', () => openChangePasswordModal());
@endif
</script>
@endsection
