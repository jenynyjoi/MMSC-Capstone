<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // ← add this

class User extends Authenticatable
{
    use Notifiable, HasRoles; // ← add HasRoles here

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'profile_photo',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

}

