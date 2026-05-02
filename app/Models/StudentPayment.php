<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    protected $table = 'student_payments';

    protected $fillable = [
        'student_finance_id', 'receipt_number', 'amount',
        'payment_date', 'payment_method', 'online_reference',
        'month_ids', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'month_ids'    => 'array',
        'amount'       => 'decimal:2',
    ];

    public function finance()
    {
        return $this->belongsTo(StudentFinance::class, 'student_finance_id');
    }

    public static function generateReceiptNumber(): string
    {
        $year  = date('Y');
        $month = date('m');
        $count = static::whereYear('created_at', $year)->whereMonth('created_at', $month)->count() + 1;
        return 'RCP-' . $year . $month . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
