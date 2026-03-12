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
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = auth()->user();

    // ── Flash success message ──
    session()->flash('success', 'Welcome back, ' . $user->name . '! 👋');

    if ($user->hasRole('super_admin')) {
        return redirect()->intended(route('superadmin.dashboard'));
    }
    if ($user->hasRole('admin')) {
        return redirect()->intended(route('admin.dashboard'));
    }
    if ($user->hasRole('teacher')) {
        return redirect()->intended(route('teacher.dashboard'));
    }
    if ($user->hasRole('student')) {
        return redirect()->intended(route('student.dashboard'));
    }
    if ($user->hasRole('parent')) {
        return redirect()->intended(route('parent.dashboard'));
    }

    return redirect('/');
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
