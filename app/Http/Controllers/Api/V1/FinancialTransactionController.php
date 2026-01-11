<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FinancialTransactionResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FinancialTransactionController extends BaseController {
    
    protected Model $model;

    protected string $resource = FinancialTransactionResource::class;

    protected string $permissionView   = 'user.view';
    protected string $permissionCreate = 'user.create';
    protected string $permissionUpdate = 'user.update';
    protected string $permissionDelete = 'user.delete';

    protected array $allowedFilters = [
        'active',
        'role_id'
    ];

    protected array $searchable = [
        'name',
        'email'
    ];

    public function __construct(User $user)
    {
        $this->model = $user;
    }
}
