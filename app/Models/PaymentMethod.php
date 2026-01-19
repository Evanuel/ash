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
        'code',
        'description',
        'active',
        'requires_bank',
        'requires_card',
        'created_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'requires_bank' => 'boolean',
        'requires_card' => 'boolean',
        'archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'payment_method_id');
    }

    // Scope para métodos ativos
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    // Scope por tipo (requer banco ou não)
    public function scopeRequiresBank($query, $requires = true)
    {
        return $query->where('requires_bank', $requires);
    }

    // Scope por código
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    // Accessor para status
    public function getStatusAttribute()
    {
        return $this->active ? 'Ativo' : 'Inativo';
    }
}
