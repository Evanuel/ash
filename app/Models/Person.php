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

    protected $appends = [
        'full_name',
        'full_address',
        'available_credit',
    ];

    /**
     * Get the client that owns the person.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the state that owns the person.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the city that owns the person.
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the category that owns the person.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the subcategory that owns the person.
     */
    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    /**
     * Get the users associated with the person.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'individual_id');
    }

    /**
     * Get the financial transactions for the person.
     */
    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    /**
     * Scope a query to only include active persons.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include inactive persons.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    /**
     * Scope a query to only include archived persons.
     */
    public function scopeArchived($query)
    {
        return $query->where('archived', true);
    }

    /**
     * Scope a query to only include non-archived persons.
     */
    public function scopeNotArchived($query)
    {
        return $query->where('archived', false);
    }

    /**
     * Scope a query to only include persons with credit.
     */
    public function scopeWithCredit($query)
    {
        return $query->where('credit_limit', '>', 0);
    }

    /**
     * Scope a query to only include persons by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include persons by situation.
     */
    public function scopeBySituation($query, $situation)
    {
        return $query->where('situation', $situation);
    }

    /**
     * Scope a query to only include persons by client.
     */
    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Scope a query to only include persons with available credit.
     */
    public function scopeWithAvailableCredit($query, $minAmount = 0)
    {
        return $query->whereRaw('(credit_limit - used_credit) >= ?', [$minAmount]);
    }

    /**
     * Get the person's full name.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the person's full address.
     */
    public function getFullAddressAttribute()
    {
        $address = [];
        if ($this->street) $address[] = $this->street;
        if ($this->number) $address[] = $this->number;
        if ($this->neighborhood) $address[] = $this->neighborhood;
        
        if ($this->city && $this->state) {
            $address[] = $this->city->name . '/' . $this->state->uf;
        } elseif ($this->city) {
            $address[] = $this->city->name;
        } elseif ($this->state) {
            $address[] = $this->state->uf;
        }
        
        if ($this->zip_code) $address[] = 'CEP: ' . $this->zip_code;
        
        return implode(', ', $address);
    }

    /**
     * Get the person's available credit.
     */
    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - $this->used_credit;
    }

    /**
     * Check if person has available credit.
     */
    public function hasAvailableCredit($amount)
    {
        return $this->available_credit >= $amount;
    }

    /**
     * Use credit from person.
     */
    public function useCredit($amount)
    {
        if (!$this->hasAvailableCredit($amount)) {
            throw new \Exception('Crédito insuficiente.');
        }
        
        $this->increment('used_credit', $amount);
        return $this;
    }

    /**
     * Release credit from person.
     */
    public function releaseCredit($amount)
    {
        if ($amount > $this->used_credit) {
            throw new \Exception('Valor a liberar maior que o crédito utilizado.');
        }
        
        $this->decrement('used_credit', $amount);
        return $this;
    }

    /**
     * Update credit limit.
     */
    public function updateCreditLimit($amount)
    {
        $this->credit_limit = $amount;
        $this->save();
        return $this;
    }

    /**
     * Archive the person.
     */
    public function archive()
    {
        $this->update([
            'archived' => true,
            'archived_at' => now(),
            'archived_by' => auth()->id(),
        ]);
        
        return $this;
    }

    /**
     * Unarchive the person.
     */
    public function unarchive()
    {
        $this->update([
            'archived' => false,
            'archived_at' => null,
            'archived_by' => null,
        ]);
        
        return $this;
    }

    /**
     * Activate the person.
     */
    public function activate()
    {
        $this->update([
            'status' => true,
            'activated' => true,
        ]);
        
        return $this;
    }

    /**
     * Deactivate the person.
     */
    public function deactivate()
    {
        $this->update([
            'status' => false,
        ]);
        
        return $this;
    }

    /**
     * Get age of person.
     */
    public function getAgeAttribute()
    {
        if (!$this->birthdate) {
            return null;
        }
        
        return $this->birthdate->age;
    }

    /**
     * Search persons by various criteria.
     */
    public static function search($search, $clientId = null)
    {
        $query = self::query();
        
        if ($clientId) {
            $query->where('client_id', $clientId);
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('cpf', 'LIKE', "%{$search}%")
              ->orWhere('first_name', 'LIKE', "%{$search}%")
              ->orWhere('last_name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhere('rg', 'LIKE', "%{$search}%")
              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
        });
    }
}