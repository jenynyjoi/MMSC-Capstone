<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'body',
        'viewers',
        'importance',
        'attachment',
        'posted_by',
        'school_year',
    ];

    protected $casts = [
        'viewers' => 'array',
    ];
}
