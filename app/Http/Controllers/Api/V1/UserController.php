<?php
// app/Http/Controllers/Api/V1/UserController.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserController extends BaseController
{
    protected Model $model;

    protected string $resource = UserResource::class;

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
