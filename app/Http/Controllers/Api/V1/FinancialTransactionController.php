<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\FinancialTransactionResource;
use App\Models\FinancialTransaction;
use Illuminate\Database\Eloquent\Model;

class FinancialTransactionController extends BaseController {
    
    protected Model $model;

    protected string $resource = FinancialTransactionResource::class;

    protected string $permissionView   = 'view.financial-transaction';
    protected string $permissionCreate = 'create.financial-transaction';
    protected string $permissionUpdate = 'update.financial-transaction';
    protected string $permissionDelete = 'delete.financial-transaction';

    protected array $allowedFilters = [
        'active',
        'role_id'
    ];

    protected array $searchable = [
        'name',
        'email'
    ];

    public function __construct(FinancialTransaction $financialTransaction)
    {
        $this->model = $financialTransaction;
    }
}
