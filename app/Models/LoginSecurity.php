<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginSecurity extends Model
{
    protected $table      = 'login_security';
    public $incrementing  = false;
    protected $primaryKey = 'email';
    protected $keyType    = 'string';
    public $timestamps    = false;

    protected $fillable = [
        'email', 'attempts', 'locked_until',
        'alert_sent', 'requires_otp', 'updated_at',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
        'alert_sent'   => 'boolean',
        'requires_otp' => 'boolean',
    ];

    public static function forEmail(string $email): self
    {
        return static::firstOrNew(['email' => strtolower($email)]);
    }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function lockSecondsRemaining(): int
    {
        if (!$this->isLocked()) return 0;
        return (int) now()->diffInSeconds($this->locked_until);
    }
}
