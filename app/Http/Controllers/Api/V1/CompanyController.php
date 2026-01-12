<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Company\StoreCompanyRequest;
use App\Http\Requests\Api\V1\Company\UpdateCompanyRequest;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Http\Resources\Api\V1\CompanyResource;

class CompanyController extends BaseController
{

    protected Model $model;
    protected string $resource = CompanyResource::class;
    protected string $storeRequestClass = StoreCompanyRequest::class;
    protected string $updateRequestClass = UpdateCompanyRequest::class;

    protected string $permissionView = 'view.company';
    protected string $permissionCreate = 'create.company';
    protected string $permissionUpdate = 'edit.company';
    protected string $permissionDelete = 'delete.company';

    /**
     * Filtros permitidos
     */
    protected array $allowedFilters = [
        'client_id',
        'type',
        'cnpj',
        'trade_name',
        'company_name',
        'state_registration',
        'municipal_registration',
        'street',
        'number',
        'neighborhood',
        'zip_code',
        'state_id',
        'city_id',
        'cnae',
    ];

    /**
     * Campos pesquisáveis
     */
    protected array $searchable = [
        'type',
        'cnpj',
        'trade_name',
        'company_name',
        'cnae'
    ];

    /**
     * Ordenação padrão
     */
    protected string $defaultSort = 'trade_name';
    protected string $defaultOrder = 'asc';

    public function __construct(Company $company)
    {
        $this->model = $company;
    }

    public function store(StoreCompanyRequest $request) {
        $this->authorizeOrFail($this->permissionCreate);

        $item = $this->model->create($request->validated());

        return new $this->resource($item);
    }

    public function update(UpdateCompanyRequest $request, int|string $id) {
        $this->authorizeOrFail($this->permissionUpdate);

        $item = $this->model->findOrFail($id);
        $item->update($request->validated());

        return new $this->resource($item);
    }

}
