<?php

namespace App\Services;

use App\Models\PasswordResetOtp;
use Carbon\Carbon;

class OtpService
{
    const EXPIRY_MINUTES   = 10;
    const MAX_ATTEMPTS     = 5;
    const RESEND_COOLDOWN  = 60; // seconds

    /**
     * Generate a new 6-digit OTP and store it.
     * Deletes any previous unverified OTP for the same identifier+type.
     */
    public function generate(string $identifier, string $type): string
    {
        // Remove old OTPs for this identifier/type
        PasswordResetOtp::where('identifier', $identifier)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::create([
            'identifier' => $identifier,
            'type'       => $type,
            'otp_code'   => $code,
            'attempts'   => 0,
            'expires_at' => Carbon::now()->addMinutes(self::EXPIRY_MINUTES),
        ]);

        return $code;
    }

    /**
     * Verify an OTP code.
     * Returns true on success, false otherwise.
     * Also increments attempt count on failure.
     */
    public function verify(string $identifier, string $type, string $code): bool
    {
        $otp = PasswordResetOtp::where('identifier', $identifier)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$otp) return false;
        if ($otp->isExpired()) return false;
        if ($otp->isMaxAttemptsReached()) return false;

        if (!hash_equals($otp->otp_code, $code)) {
            $otp->increment('attempts');
            return false;
        }

        $otp->update(['verified_at' => Carbon::now()]);
        return true;
    }

    /**
     * Check whether a verified (but not yet used) OTP exists for this identifier.
     */
    public function hasVerified(string $identifier, string $type): bool
    {
        return PasswordResetOtp::where('identifier', $identifier)
            ->where('type', $type)
            ->whereNotNull('verified_at')
            ->where('expires_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Delete all OTPs for an identifier after password is reset.
     */
    public function consume(string $identifier): void
    {
        PasswordResetOtp::where('identifier', $identifier)->delete();
    }

    /**
     * Check how many seconds remain before a resend is allowed.
     * Returns 0 if resend is allowed.
     */
    public function resendCooldownSeconds(string $identifier, string $type): int
    {
        $otp = PasswordResetOtp::where('identifier', $identifier)
            ->where('type', $type)
            ->latest()
            ->first();

        if (!$otp) return 0;

        $elapsed = Carbon::now()->diffInSeconds($otp->created_at, false);
        // diffInSeconds with false → negative means "in the past"
        $elapsed = abs($elapsed);

        return max(0, self::RESEND_COOLDOWN - $elapsed);
    }

    /**
     * Get remaining attempts for an OTP.
     */
    public function remainingAttempts(string $identifier, string $type): int
    {
        $otp = PasswordResetOtp::where('identifier', $identifier)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$otp) return self::MAX_ATTEMPTS;
        return max(0, self::MAX_ATTEMPTS - $otp->attempts);
    }
}
