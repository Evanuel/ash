<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $table = 'cities';
    
    protected $fillable = [
        'code',
        'name',
        'uf',
        'state_code',
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

    public function state()
    {
        return $this->belongsTo(State::class, 'state_code', 'code');
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function people()
    {
        return $this->hasMany(Person::class);
    }
}