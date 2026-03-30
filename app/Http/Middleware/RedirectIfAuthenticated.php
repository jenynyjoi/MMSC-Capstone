<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * If already logged in, redirect to their role dashboard instead of login page.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user->hasRole('super_admin')) {
                    return redirect()->route('superadmin.dashboard');
                }
                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                }
                if ($user->hasRole('teacher')) {
                    return redirect()->route('teacher.dashboard');
                }
                if ($user->hasRole('student')) {
                    return redirect()->route('student.dashboard');
                }
                if ($user->hasRole('parent')) {
                    return redirect()->route('parent.dashboard');
                }

                return redirect('/');
            }
        }

        return $next($request);
    }
}