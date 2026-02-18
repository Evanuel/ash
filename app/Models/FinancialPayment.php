<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialPayment extends Model
{
    protected $fillable = [
        'client_id',
        'financial_transaction_id',
        'payment_date',
        'amount',
        'payment_method_id',
        'bank_id',
        'is_manual',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'is_manual' => 'boolean',
        'financial_transaction_id' => 'integer',
        'payment_method_id' => 'integer',
        'bank_id' => 'integer',
        'created_by' => 'integer',
    ];

    public function financialTransaction()
    {
        return $this->belongsTo(FinancialTransaction::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
