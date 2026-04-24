<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPaymentMonth extends Model
{
    protected $table = 'student_payment_months';

    protected $fillable = [
        'student_finance_id', 'month_name', 'month_number',
        'month_year', 'due_date', 'amount_due', 'amount_paid',
        'status', 'paid_date', 'notes',
    ];

    protected $casts = [
        'due_date'   => 'date',
        'paid_date'  => 'date',
        'amount_due' => 'decimal:2',
        'amount_paid'=> 'decimal:2',
    ];

    public function finance()
    {
        return $this->belongsTo(StudentFinance::class, 'student_finance_id');
    }
}
