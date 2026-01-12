<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Company\StoreCompanyRequest;
use App\Http\Requests\Api\V1\Company\UpdateCompanyRequest;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Http\Resources\Api\V1\CompanyResource;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $storeRequestClass = StoreCompanyRequest::createFrom($request);
        $storeRequestClass->setContainer(app());
        $storeRequestClass->validateResolved();

        $this->authorizeOrFail($this->permissionCreate);
        $validated = $storeRequestClass->validated();
        $company = $this->model->create($validated);
        return new $this->resource($company);
    }

    public function update(Request $request, $id)
    {
        $updateRequestClass = UpdateCompanyRequest::createFrom($request);
        $updateRequestClass->setContainer(app());
        $updateRequestClass->validateResolved();

        $this->authorizeOrFail($this->permissionUpdate);
        $company = $this->model->findOrFail($id);
        $validated = $request->validated();
        $company->update($validated);
        return new $this->resource($company);
    }
}
