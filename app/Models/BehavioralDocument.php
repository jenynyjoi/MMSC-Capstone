<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BehavioralDocument extends Model
{
    protected $table = 'behavioral_documents';

    protected $fillable = [
        'behavioral_record_id', 'file_name', 'file_path',
        'file_type', 'file_size', 'description', 'uploaded_by',
    ];

    public function record()
    {
        return $this->belongsTo(BehavioralRecord::class, 'behavioral_record_id');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}