<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'financial_transactions';

    protected $fillable = [
        'client_id',
        'type_id',
        'fiscal_document',
        'cost_center',
        'description',
        'category_id',
        'subcategory_id',
        'person_type',
        'individual_id',
        'company_id',
        'due_date',
        'amount',
        'status_id',
        'boleto_url',
        'paid_at',
        'paid_amount',
        'bank_id',
        'payment_method_id',
        'installment',
        'total_installments',
        'transaction_key',
        'receipt_url',
        'custom_field1',
        'custom_field2',
        'custom_field3',
        'notes',
        'css_class',
        'created_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'type_id' => 'integer',
        'person_type' => 'integer',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_at' => 'date',
        'paid_amount' => 'decimal:2',
        'installment' => 'integer',
        'total_installments' => 'integer',
        'archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    // Constants for person_type (mantemos essas)
    const PERSON_INDIVIDUAL = 1;
    const PERSON_COMPANY = 2;

    // Relações
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function individual()
    {
        return $this->belongsTo(Person::class, 'individual_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
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

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    public function person()
    {
        if ($this->person_type == self::PERSON_INDIVIDUAL) {
            return $this->individual();
        } else {
            return $this->company();
        }
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('description', 'like', '%' . $search . '%')
              ->orWhere('fiscal_document', 'like', '%' . $search . '%')
              ->orWhere('transaction_key', 'like', '%' . $search . '%')
              ->orWhere('cost_center', 'like', '%' . $search . '%');
        });
    }

    // Scopes para type (baseado no type_id)
    public function scopeReceivable($query)
    {
        return $query->whereHas('type', function ($q) {
            $q->where('name', 'like', '%receivable%')->orWhere('code', 'R');
        });
    }

    public function scopePayable($query)
    {
        return $query->whereHas('type', function ($q) {
            $q->where('name', 'like', '%payable%')->orWhere('code', 'P');
        });
    }

    public function scopePending($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('name', 'like', '%pendente%')->orWhere('is_default', true);
        });
    }

    public function scopePaid($query)
    {
        return $query->whereNotNull('paid_at');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNull('paid_at')
            ->whereHas('status', function ($q) {
                $q->where('name', 'not like', '%pago%');
            });
    }

    // Accessors
    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() && !$this->paid_at && $this->status->name != 'Pago';
    }


    public function getRemainingAmountAttribute()
    {
        return $this->amount - ($this->paid_amount ?? 0);
    }

    public function getTypeNameAttribute()
    {
        return $this->type ? $this->type->name : null;
    }

    public function getTypeCodeAttribute()
    {
        return $this->type ? $this->type->code : null;
    }

    // Métodos
    public function markAsPaid($amount = null, $date = null, $paymentMethodId = null)
    {
        $this->paid_at = $date ?? now();
        $this->paid_amount = $amount ?? $this->amount;

        if ($paymentMethodId) {
            $this->payment_method_id = $paymentMethodId;
        }

        // Find and set paid status
        $paidStatus = Status::where('type', 'account')
            ->where('name', 'like', '%pago%')
            ->orWhere('name', 'like', '%paid%')
            ->first();

        if ($paidStatus) {
            $this->status_id = $paidStatus->id;
        }

        $this->save();
    }
}