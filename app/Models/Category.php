<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'type',
        'order',
        'active',
        'client_id',
        'metadata',
        'created_by',
        'updated_by',
        'archived',
        'archived_by',
        'archived_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'archived' => 'boolean',
        'metadata' => 'array',
        'order' => 'integer',
        'archived_at' => 'datetime',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'category_id');
    }

    public function subcategoryCompanies()
    {
        return $this->hasMany(Company::class, 'subcategory_id');
    }

    public function people()
    {
        return $this->hasMany(Person::class, 'category_id');
    }

    public function subcategoryPeople()
    {
        return $this->hasMany(Person::class, 'subcategory_id');
    }

    public function financialTransactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'category_id');
    }

    public function subcategoryTransactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'subcategory_id');
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($category) {
            if (auth()->check()) {
                if (!$category->client_id) {
                    $category->client_id = auth()->user()->client_id;
                }
                if (!$category->created_by) {
                    $category->created_by = auth()->id();
                }
            }

            if (!$category->slug && $category->name) {
                $category->slug = \Illuminate\Support\Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if (auth()->check() && !$category->isDirty('updated_by')) {
                $category->updated_by = auth()->id();
            }
        });
    }
}