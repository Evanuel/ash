<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $table = 'payment_methods';
    
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }
}