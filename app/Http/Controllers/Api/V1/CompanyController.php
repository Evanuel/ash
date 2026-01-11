<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Http\Resources\Api\V1\CompanyResource;

class CompanyController extends BaseController {
    
    /**
     * Model Eloquent associada
     */
    protected Model $model;

    /**
     * Resource associada
     */

    protected string $resource = CompanyResource::class;

    /**
     * Permissões
     */
    protected string $permissionView = 'company.view';
    protected string $permissionCreate = 'company.create';
    protected string $permissionUpdate = 'company.update';
    protected string $permissionDelete = 'company.delete';

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

    public function __construct()
    {
        $this->model = new Company;
    }

}
