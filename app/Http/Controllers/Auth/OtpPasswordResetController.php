<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OtpPasswordResetController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    // ── STEP 1: Show forgot-password form ─────────────────────
    public function showRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    // ── STEP 2: Send OTP via email ────────────────────────────
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Always redirect to verify to prevent user enumeration
            $request->session()->put('otp_email', $request->email);
            $request->session()->save();
            return redirect()->route('password.verify');
        }

        $sent = $this->sendEmailOtp($user);

        if (!$sent) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Failed to send OTP. Please try again later.']);
        }

        $request->session()->put('otp_email', $user->email);
        $request->session()->save();

        return redirect()->route('password.verify');
    }

    // ── STEP 3: Show OTP entry form ───────────────────────────
    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('otp_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Please enter your email to receive an OTP.']);
        }

        $email = $request->session()->get('otp_email');
        [$local, $domain] = explode('@', $email) + ['', ''];
        $masked = substr($local, 0, 1) . str_repeat('*', max(strlen($local) - 1, 3)) . '@' . $domain;

        return view('auth.otp-verify', [
            'maskedIdentifier' => $masked,
        ]);
    }

    // ── STEP 4: Verify OTP — returns the reset form directly ──
    public function verifyOtp(Request $request): View|RedirectResponse
    {
        $request->validate(['otp' => ['required', 'digits:6']]);

        $email = $request->session()->get('otp_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please enter your email again.']);
        }

        $valid = $this->otpService->verify($email, 'email', $request->otp);

        if (!$valid) {
            $remaining = $this->otpService->remainingAttempts($email, 'email');

            if ($remaining === 0) {
                $request->session()->forget('otp_email');
                return redirect()->route('password.request')
                    ->withErrors(['email' => 'Too many failed attempts. Please request a new OTP.']);
            }

            return back()->withErrors([
                'otp' => "Invalid or expired OTP. {$remaining} attempt(s) remaining.",
            ]);
        }

        // ✅ OTP verified — write session, then render the form directly.
        // We do NOT redirect here because the redirect-then-GET causes a
        // session read on a new request where the file may not be flushed yet,
        // silently bouncing the user back to forgot-password.
        $request->session()->forget('otp_email');
        $request->session()->put('otp_verified_email', $email);
        $request->session()->save();

        return view('auth.otp-new-password');
    }

    // ── STEP 5: Show new password form (direct URL / browser back) ──
    public function showResetForm(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('otp_verified_email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please request a new OTP.']);
        }

        return view('auth.otp-new-password');
    }

    // ── STEP 6: Reset password ────────────────────────────────
    public function resetPassword(Request $request): RedirectResponse
    {
        $email = $request->session()->get('otp_verified_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please request a new OTP.']);
        }

        $request->validate([
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one number, and one special character.',
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Account not found. Please try again.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        $this->otpService->consume($email);
        $request->session()->forget('otp_verified_email');

        return redirect()->route('login')
            ->with('status', 'Password reset successfully. You can now log in.');
    }

    // ── Resend OTP ────────────────────────────────────────────
    public function resendOtp(Request $request): RedirectResponse
    {
        $email = $request->session()->get('otp_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Session expired. Please enter your email again.']);
        }

        $cooldown = $this->otpService->resendCooldownSeconds($email, 'email');
        if ($cooldown > 0) {
            return back()->withErrors(['otp' => "Please wait {$cooldown} seconds before resending."]);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request');
        }

        $this->sendEmailOtp($user);

        return back()->with('status', 'A new OTP has been sent to your email.');
    }

    // ── Helper ────────────────────────────────────────────────
    private function sendEmailOtp(User $user): bool
    {
        try {
            $otp = $this->otpService->generate($user->email, 'email');
            Mail::to($user->email)->send(new PasswordResetOtpMail($otp, $user->name));
            return true;
        } catch (\Throwable $e) {
            Log::error('OtpPasswordResetController: email OTP failed', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
