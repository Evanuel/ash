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
        'fiscal_document_id',
        'cost_center',
        'cost_center_id',
        'description',
        'competency_date',
        'category_id',
        'subcategory_id',
        'person_type',
        'individual_id',
        'company_id',
        'due_date',
        'amount',
        'status_id',
        'boleto_url',
        'installment',
        'total_installments',
        'transaction_key',
        'receipt_url',
        'custom_field1',
        'custom_field2',
        'custom_field3',
        'notes',
        'css_class',

        // Approval
        'approval_status',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',

        // Financial adjustments
        'interest_amount',
        'fine_amount',
        'discount_amount',

        // Reconciliation
        'is_fully_reconciled',
        'reconciled_total',

        // Payment control
        'paid_total',
        'is_fully_paid',

        // Origin
        'origin',

        // Audit
        'created_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_at',

        'metadata',
        'history',
    ];

    protected $casts = [
        'type_id' => 'integer',
        'person_type' => 'integer',
        'due_date' => 'date',
        'competency_date' => 'date',
        'amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'fine_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'reconciled_total' => 'decimal:2',
        'paid_total' => 'decimal:2',
        'installment' => 'integer',
        'total_installments' => 'integer',
        'is_fully_reconciled' => 'boolean',
        'is_fully_paid' => 'boolean',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'archived' => 'boolean',
        'archived_at' => 'datetime',
        'metadata' => 'array',
        'history' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | CONSTANTS
    |--------------------------------------------------------------------------
    */

    const PERSON_INDIVIDUAL = 1;
    const PERSON_COMPANY = 2;

    const TYPE_RECEIVABLE = 1;
    const TYPE_PAYABLE = 2;

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
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

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function person()
    {
        return $this->person_type === self::PERSON_INDIVIDUAL
            ? $this->individual()
            : $this->company();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
                ->orWhere('fiscal_document', 'like', "%{$search}%")
                ->orWhere('transaction_key', 'like', "%{$search}%")
                ->orWhere('cost_center', 'like', "%{$search}%");
        });
    }

    public function scopeReceivable($query)
    {
        return $query->where('type_id', self::TYPE_RECEIVABLE);
    }

    public function scopePayable($query)
    {
        return $query->where('type_id', self::TYPE_PAYABLE);
    }

    public function scopePending($query)
    {
        return $query->where('is_fully_paid', false);
    }

    public function scopePaid($query)
    {
        return $query->where('is_fully_paid', true);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('is_fully_paid', false);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() && !$this->is_fully_paid;
    }

    public function getRemainingAmountAttribute()
    {
        return (
            $this->amount
            + $this->interest_amount
            + $this->fine_amount
            - $this->discount_amount
            - $this->paid_total
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS METHODS
    |--------------------------------------------------------------------------
    */

    public function registerPayment(float $amount)
    {
        $this->paid_total += $amount;

        $totalDue = (
            $this->amount
            + $this->interest_amount
            + $this->fine_amount
            - $this->discount_amount
        );

        if ($this->paid_total >= $totalDue) {
            $this->is_fully_paid = true;
        }

        $this->save();
    }

    public function reconcile(float $amount)
    {
        $this->reconciled_total += $amount;

        if ($this->reconciled_total >= $this->paid_total) {
            $this->is_fully_reconciled = true;
        }

        $this->save();
    }
}
