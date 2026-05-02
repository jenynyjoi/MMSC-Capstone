<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\CaptchaController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OtpPasswordResetController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PostLoginOtpController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

// ── CAPTCHA (no auth required, no guest restriction) ─────────
Route::get('captcha/image',   [CaptchaController::class, 'image'])  ->name('captcha.image');
Route::post('captcha/verify', [CaptchaController::class, 'verify']) ->name('captcha.verify')
            ->middleware('throttle:10,1');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // ── OTP-based password reset ──────────────────────────────
    Route::get('forgot-password', [OtpPasswordResetController::class, 'showRequestForm'])
                ->name('password.request');

    Route::post('forgot-password/send-otp', [OtpPasswordResetController::class, 'sendOtp'])
                ->name('password.send-otp')
                ->middleware('throttle:6,1');

    Route::get('forgot-password/verify', [OtpPasswordResetController::class, 'showVerifyForm'])
                ->name('password.verify');

    Route::post('forgot-password/verify', [OtpPasswordResetController::class, 'verifyOtp'])
                ->name('password.verify.store');

    Route::post('forgot-password/resend', [OtpPasswordResetController::class, 'resendOtp'])
                ->name('password.resend')
                ->middleware('throttle:3,1');

    Route::get('forgot-password/reset', [OtpPasswordResetController::class, 'showResetForm'])
                ->name('password.otp-reset');

    Route::post('forgot-password/reset', [OtpPasswordResetController::class, 'resetPassword'])
                ->name('password.otp-reset.store');

    // ── Post-login OTP (after account unlock) ────────────────
    Route::get('login/verify',    [PostLoginOtpController::class, 'show'])   ->name('login.otp');
    Route::post('login/verify',   [PostLoginOtpController::class, 'verify']) ->name('login.otp.verify');
    Route::post('login/resend',   [PostLoginOtpController::class, 'resend']) ->name('login.otp.resend')
                ->middleware('throttle:3,1');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
