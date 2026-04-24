<?php

namespace App\Http\Requests\Auth;

use App\Mail\LoginAlertMail;
use App\Models\LoginSecurity;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public const ALERT_AT  = 3;   // show captcha + send email
    public const LOCK_AT   = 5;   // lock account
    public const LOCK_MINS = 12;  // lock duration in minutes

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    // ── Main authenticate method ──────────────────────────────
    public function authenticate(): void
    {
        $email    = strtolower($this->string('email'));
        $security = LoginSecurity::forEmail($email);

        // ── 1. Account locked? ────────────────────────────────
        if ($security->isLocked()) {
            $mins = (int) ceil($security->lockSecondsRemaining() / 60);
            throw ValidationException::withMessages([
                'email' => "Your account is temporarily locked. Please try again in {$mins} minute(s).",
            ]);
        }

        // ── 2. Captcha required but not cleared? ─────────────
        if (($security->attempts ?? 0) >= self::ALERT_AT && !session('captcha_cleared')) {
            session()->put('show_captcha', true);
            throw ValidationException::withMessages([
                'email' => 'Please complete the security verification before continuing.',
            ]);
        }

        // ── 3. Validate credentials ───────────────────────────
        $user = User::where('email', $email)->first();

        if (!$user || !Auth::validate($this->only('email', 'password'))) {
            $this->handleFailedAttempt($security, $email, $user);
        }

        // ── 4. Success — check if OTP required after lockout ──
        $security->attempts   = 0;
        $security->alert_sent = false;

        if ($security->requires_otp) {
            $security->requires_otp = false;
            $security->updated_at   = now();
            $security->save();

            RateLimiter::clear($this->throttleKey());
            session()->forget(['captcha_cleared', 'show_captcha', 'captcha_text', 'captcha_show_image']);

            // Store intent; OTP already sent in CaptchaController flow
            $otp = app(OtpService::class)->generate($user->email, 'email');
            try {
                Mail::to($user->email)->send(new \App\Mail\PasswordResetOtpMail($otp, $user->name));
            } catch (\Throwable) {}

            session()->put([
                'post_login_user_id'   => $user->id,
                'post_login_remember'  => $this->boolean('remember'),
                'post_login_otp_email' => $user->email,
            ]);
            session()->put('needs_post_login_otp', true);
            return;
        }

        // ── 5. Normal login ───────────────────────────────────
        $security->updated_at = now();
        $security->save();

        RateLimiter::clear($this->throttleKey());
        session()->forget(['captcha_cleared', 'show_captcha', 'captcha_text', 'captcha_show_image']);

        Auth::login($user, $this->boolean('remember'));
    }

    // ── Handle a failed attempt ───────────────────────────────
    private function handleFailedAttempt(LoginSecurity $security, string $email, ?User $user): never
    {
        RateLimiter::hit($this->throttleKey());

        $security->attempts   = ($security->attempts ?? 0) + 1;
        $security->updated_at = now();

        // Lock at threshold
        if ($security->attempts >= self::LOCK_AT) {
            $security->locked_until = now()->addMinutes(self::LOCK_MINS);
            $security->requires_otp = true;
            $security->updated_at   = now();
            $security->save();

            session()->forget(['captcha_cleared', 'show_captcha', 'captcha_text', 'captcha_show_image']);

            throw ValidationException::withMessages([
                'email' => 'Your account has been temporarily locked. Please try again in ' . self::LOCK_MINS . ' minutes.',
            ]);
        }

        // At alert threshold: send email once + require captcha
        if ($security->attempts >= self::ALERT_AT) {
            if (!$security->alert_sent && $user) {
                $security->alert_sent = true;
                try {
                    Mail::to($user->email)->send(new LoginAlertMail(
                        $user->name,
                        $this->ip() ?? 'unknown',
                        now()->format('F j, Y \a\t g:i A'),
                    ));
                } catch (\Throwable) {}
            }

            // Require captcha on next attempt — clear any old cleared state
            session()->put('show_captcha', true);
            session()->forget(['captcha_cleared', 'captcha_text', 'captcha_show_image']);
        }

        $security->save();

        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 10)) return;

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
