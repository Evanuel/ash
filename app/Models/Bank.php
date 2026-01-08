<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;

    protected $table = 'banks';
    
    protected $fillable = [
        'code',
        'ispb',
        'name',
        'short_name',
        'compe_code',
        'document_number',
        'url',
        'logo',
        'type',
        'is_public',
        'is_foreign',
        'active',
        'participates_on_pix',
        'start_date',
        'end_date',
        'phone',
        'email',
        'address_street',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_zip_code',
        'client_id',
        'created_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_foreign' => 'boolean',
        'active' => 'boolean',
        'participates_on_pix' => 'boolean',
        'archived' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'archived_at' => 'datetime',
    ];

    // Bank types
    const TYPE_COMMERCIAL = 'commercial';
    const TYPE_INVESTMENT = 'investment';
    const TYPE_DEVELOPMENT = 'development';
    const TYPE_SAVINGS = 'savings';
    const TYPE_COOPERATIVE = 'cooperative';
    const TYPE_PAYMENT = 'payment';

    public static $types = [
        self::TYPE_COMMERCIAL => 'Comercial',
        self::TYPE_INVESTMENT => 'Investimento',
        self::TYPE_DEVELOPMENT => 'Desenvolvimento',
        self::TYPE_SAVINGS => 'Poupança',
        self::TYPE_COOPERATIVE => 'Cooperativo',
        self::TYPE_PAYMENT => 'Pagamento',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeCommercial($query)
    {
        return $query->where('type', self::TYPE_COMMERCIAL);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeParticipatesOnPix($query)
    {
        return $query->where('participates_on_pix', true);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('client_id');
    }

    // Helper methods
    public function isActive()
    {
        return $this->active && !$this->archived;
    }

    public function getTypeLabelAttribute()
    {
        return self::$types[$this->type] ?? $this->type;
    }

    public function getFullAddressAttribute()
    {
        $address = [];
        if ($this->address_street) $address[] = $this->address_street;
        if ($this->address_number) $address[] = $this->address_number;
        if ($this->address_complement) $address[] = $this->address_complement;
        if ($this->address_neighborhood) $address[] = $this->address_neighborhood;
        if ($this->address_city) $address[] = $this->address_city;
        if ($this->address_state) $address[] = $this->address_state;
        if ($this->address_zip_code) $address[] = $this->address_zip_code;
        
        return implode(', ', array_filter($address));
    }

    public function getFormattedCodeAttribute()
    {
        return str_pad($this->code, 3, '0', STR_PAD_LEFT);
    }

    public function getFormattedDocumentAttribute()
    {
        if (!$this->document_number) return null;
        
        $cnpj = preg_replace('/\D/', '', $this->document_number);
        if (strlen($cnpj) === 14) {
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
        }
        
        return $this->document_number;
    }
}