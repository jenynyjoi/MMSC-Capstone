<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PostLoginOtpController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    // Show OTP form
    public function show(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('post_login_user_id')) {
            return redirect()->route('login');
        }

        $email = $request->session()->get('post_login_otp_email');

        // Mask email for display
        [$local, $domain] = explode('@', $email) + ['', ''];
        $masked = substr($local, 0, 1) . str_repeat('*', max(strlen($local) - 1, 3)) . '@' . $domain;

        return view('auth.login-otp', [
            'maskedEmail' => $masked,
        ]);
    }

    // Verify OTP and complete login
    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['otp' => ['required', 'digits:6']]);

        $userId   = $request->session()->get('post_login_user_id');
        $remember = $request->session()->get('post_login_remember', false);
        $email    = $request->session()->get('post_login_otp_email');

        if (!$userId || !$email) {
            return redirect()->route('login');
        }

        $valid = $this->otpService->verify($email, 'email', $request->otp);

        if (!$valid) {
            $remaining = $this->otpService->remainingAttempts($email, 'email');

            if ($remaining === 0) {
                $request->session()->forget(['post_login_user_id', 'post_login_remember', 'post_login_otp_email', 'needs_post_login_otp']);
                return redirect()->route('login')
                    ->withErrors(['otp' => 'Too many failed attempts. Please log in again.']);
            }

            return back()->withErrors([
                'otp' => "Invalid or expired OTP. {$remaining} attempt(s) remaining.",
            ]);
        }

        // OTP verified — complete login
        $user = User::findOrFail($userId);
        $request->session()->forget(['post_login_user_id', 'post_login_remember', 'post_login_otp_email', 'needs_post_login_otp']);

        $request->session()->regenerate();
        Auth::login($user, $remember);

        // Role-based redirect
        if ($user->hasRole('super_admin')) return redirect()->route('superadmin.dashboard');
        if ($user->hasRole('admin'))       return redirect()->route('admin.dashboard');
        if ($user->hasRole('teacher'))     return redirect()->route('teacher.dashboard');
        if ($user->hasRole('student'))     return redirect()->route('student.dashboard');
        if ($user->hasRole('parent'))      return redirect()->route('parent.dashboard');

        return redirect('/');
    }

    // Resend OTP
    public function resend(Request $request): RedirectResponse
    {
        $email = $request->session()->get('post_login_otp_email');

        if (!$email) {
            return redirect()->route('login');
        }

        $cooldown = $this->otpService->resendCooldownSeconds($email, 'email');
        if ($cooldown > 0) {
            return back()->withErrors(['otp' => "Please wait {$cooldown} seconds before resending."]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) return redirect()->route('login');

        $otp = $this->otpService->generate($email, 'email');

        try {
            \Illuminate\Support\Facades\Mail::to($email)
                ->send(new \App\Mail\PasswordResetOtpMail($otp, $user->name));
        } catch (\Throwable) {}

        return back()->with('status', 'A new OTP has been sent to your email.');
    }
}
