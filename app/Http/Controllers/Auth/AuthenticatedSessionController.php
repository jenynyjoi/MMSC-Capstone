<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // If LoginRequest stored OTP intent, redirect there instead
        if ($request->session()->pull('needs_post_login_otp')) {
            return redirect()->route('login.otp');
        }

        $request->session()->regenerate();

        $user = auth()->user();
        $user->update(['last_login_at' => now()]);
        session()->flash('success', 'Welcome back, ' . $user->name . '!');

        if ($user->hasRole('super_admin')) return redirect()->intended(route('superadmin.dashboard'));
        if ($user->hasRole('admin'))       return redirect()->intended(route('admin.dashboard'));
        if ($user->hasRole('teacher'))     return redirect()->intended(route('teacher.dashboard'));
        if ($user->hasRole('student'))     return redirect()->intended(route('student.dashboard'));
        if ($user->hasRole('parent'))      return redirect()->intended(route('parent.dashboard'));

        return redirect('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
