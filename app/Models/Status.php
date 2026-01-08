<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    protected $table = 'statuses';
    
    protected $fillable = [
        'client_id',
        'name',
        'description',
        'color',
        'icon',
        'color_class',
        'text_class',
        'bg_class',
        'type',
        'order',
        'is_default',
        'created_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'archived' => 'boolean',
        'order' => 'integer',
        'archived_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('archived', false);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}