<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;

    protected $table = 'people';
    
    protected $fillable = [
        'client_id',
        'type',
        'cpf',
        'first_name',
        'last_name',
        'rg',
        'street',
        'number',
        'neighborhood',
        'zip_code',
        'state_id',
        'city_id',
        'birthdate',
        'category_id',
        'subcategory_id',
        'email',
        'phone',
        'credit_limit',
        'used_credit',
        'activated',
        'situation',
        'status',
        'custom_field1',
        'custom_field2',
        'custom_field3',
        'notes',
        'created_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'activated' => 'boolean',
        'status' => 'boolean',
        'archived' => 'boolean',
        'credit_limit' => 'decimal:2',
        'used_credit' => 'decimal:2',
        'birthdate' => 'date',
        'archived_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'individual_id');
    }

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getFullAddressAttribute()
    {
        $address = [];
        if ($this->street) $address[] = $this->street;
        if ($this->number) $address[] = $this->number;
        if ($this->neighborhood) $address[] = $this->neighborhood;
        if ($this->city) $address[] = $this->city->name . '/' . $this->state->uf;
        if ($this->zip_code) $address[] = $this->zip_code;
        
        return implode(', ', $address);
    }
}