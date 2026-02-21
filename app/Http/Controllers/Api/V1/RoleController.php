<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Role\StoreRoleRequest;
use App\Http\Requests\Api\V1\Role\UpdateRoleRequest;
use App\Http\Resources\Api\V1\RoleResource;
use App\Http\Resources\Api\V1\RoleCollection;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->model = new Role();
        $this->resource = RoleResource::class;
        $this->collection = RoleCollection::class;

        $this->permissionView = 'roles.manage';
        $this->permissionCreate = 'roles.manage';
        $this->permissionUpdate = 'roles.manage';
        $this->permissionDelete = 'roles.manage';

        $this->allowedFilters = [
            'active',
            'client_id',
        ];

        $this->searchable = [
            'name',
            'description',
        ];

        $this->defaultSort = 'level';
        $this->defaultOrder = 'desc';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorizeOrFail($this->permissionView);

        $query = $this->model->newQuery();

        $this->applyFilters($query, $request);
        $this->applySearch($query, $request);
        $this->applySorting($query, $request);

        $data = $query->paginate(
            $request->integer('per_page', $this->perPage)
        );

        return new RoleCollection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $this->authorizeOrFail($this->permissionCreate);

        $validated = $request->validated();
        $role = Role::create($validated);

        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->authorizeOrFail($this->permissionView);

        $role = Role::findOrFail($id);

        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $this->authorizeOrFail($this->permissionUpdate);

        $role = Role::findOrFail($id);
        $validated = $request->validated();
        $role->update($validated);

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return parent::destroy($id);
    }

    /**
     * Restore a deleted role.
     */
    public function restore($id)
    {
        $this->authorizeOrFail($this->permissionUpdate);

        $role = Role::withTrashed()->findOrFail($id);

        if (!$role->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta role não está excluída.'
            ], 400);
        }

        $role->restore();

        return response()->json([
            'success' => true,
            'message' => 'Role restaurada com sucesso.',
            'data' => new RoleResource($role)
        ]);
    }
}