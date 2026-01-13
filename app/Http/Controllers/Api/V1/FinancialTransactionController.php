<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\FinancialTransaction\StoreFinancialTransactionRequest;
use App\Http\Requests\Api\V1\FinancialTransaction\UpdateFinancialTransactionRequest;
use App\Http\Resources\Api\V1\FinancialTransactionResource;
use App\Models\FinancialTransaction;
use Illuminate\Database\Eloquent\Model;

class FinancialTransactionController extends BaseController {
    
    protected Model $model;

    protected string $resource = FinancialTransactionResource::class;

    protected string $permissionView   = 'financial-transaction.view';
    protected string $permissionCreate = 'financial-transaction.create';
    protected string $permissionUpdate = 'financial-transaction.update';
    protected string $permissionDelete = 'financial-transaction.delete';

    protected array $allowedFilters = [
        'active',
        'role_id'
    ];

    protected array $searchable = [
        'name',
        'email'
    ];

    /**
     * Ordenação padrão
     */
    protected string $defaultSort = 'due_date';
    protected string $defaultOrder = 'asc';

    public function __construct(FinancialTransaction $financialTransaction)
    {
        $this->model = $financialTransaction;
    }

    public function store(StoreFinancialTransactionRequest $request) {
        $this->authorizeOrFail($this->permissionCreate);

        $item = $this->model->create($request->validated());

        return new $this->resource($item);
    }

    public function update(UpdateFinancialTransactionRequest $request, int|string $id) {
        $this->authorizeOrFail($this->permissionUpdate);

        $item = $this->model->findOrFail($id);
        $item->update($request->validated());

        return new $this->resource($item);
    }
}
