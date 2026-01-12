<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseController extends Controller
{
    /**
     * Model Eloquent associada
     */
    protected Model $model;

    /**
     * Resource associada
     */
    protected string $resource;

    /**
     * Permissões++
     * 
     */
    protected string $permissionView   = '';
    protected string $permissionCreate = '';
    protected string $permissionUpdate = '';
    protected string $permissionDelete = '';

    /**
     * Filtros permitidos
     */
    protected array $allowedFilters = [];

    /**
     * Campos pesquisáveis
     */
    protected array $searchable = [];

    /**
     * Ordenação padrão
     */
    protected string $defaultSort = 'id';
    protected string $defaultOrder = 'desc';

    /**
     * Paginação
     */
    protected int $perPage = 15;

    /* ==========================================================
     | CRUD METHODS
     |==========================================================*/

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

        return $this->resource::collection($data);
    }

    public function show(int|string $id)
    {
        $this->authorizeOrFail($this->permissionView);

        $item = $this->model->findOrFail($id);

        return new $this->resource($item);
    }

    public function store(Request $request)
    {
        $this->authorizeOrFail($this->permissionCreate);

        $item = $this->model->create($request->validated());

        return new $this->resource($item);
    }

    public function update(Request $request, int|string $id)
    {
        $this->authorizeOrFail($this->permissionUpdate);

        $item = $this->model->findOrFail($id);
        $item->update($request->validated());

        return new $this->resource($item);
    }

    public function destroy(int|string $id)
    {
        $this->authorizeOrFail($this->permissionDelete);

        $item = $this->model->findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro removido com sucesso'
        ]);
    }

    /* ==========================================================
     | AUTHORIZATION
     |==========================================================*/

    protected function authorizeOrFail(string $permission): void
    {
        if (empty($permission)) {
            return;
        }

        $user = auth()->user();

        if (! $user) {
            abort(401, 'Não autenticado');
        }

        $allowed = app(PermissionService::class)
            ->has($user, $permission);

        if (! $allowed) {
            abort(403, 'Acesso negado');
        }
    }

    /* ==========================================================
     | QUERY HELPERS
     |==========================================================*/

    protected function applyFilters($query, Request $request): void
    {
        foreach ($this->allowedFilters as $filter) {
            if (! $request->has($filter)) {
                continue;
            }

            $value = $request->query($filter);

            if (is_bool($value) || $value === 'true' || $value === 'false') {
                $query->where($filter, filter_var($value, FILTER_VALIDATE_BOOLEAN));
            } else {
                $query->where($filter, $value);
            }
        }
    }

    protected function applySearch($query, Request $request): void
    {
        if (! $request->filled('search') || empty($this->searchable)) {
            return;
        }

        $search = $request->query('search');

        $query->where(function ($q) use ($search) {
            foreach ($this->searchable as $field) {
                $q->orWhere($field, 'LIKE', "%{$search}%");
            }
        });
    }

    protected function applySorting($query, Request $request): void
    {
        $sort  = $request->query('sort', $this->defaultSort);
        $order = $request->query('order', $this->defaultOrder);

        if (! in_array($order, ['asc', 'desc'])) {
            $order = $this->defaultOrder;
        }

        $query->orderBy($sort, $order);
    }
}