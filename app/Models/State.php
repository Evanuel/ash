<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;

    protected $table = 'states';
    
    protected $fillable = [
        'code',
        'name',
        'uf',
        'country_id',
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

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'state_code', 'code');
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