<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;
use App\Http\Requests\Api\V1\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Resources\Api\V1\CategoryResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{

    /**
     * Model Eloquent associada
     */
    protected Model $model;

    /**
     * Resource associada
     */
    protected string $resource = CategoryResource::class;

    /**
     * Permissões
     */
    protected string $permissionView = 'category.view';
    protected string $permissionCreate = 'category.create';
    protected string $permissionUpdate = 'category.update';
    protected string $permissionDelete = 'category.delete';

    /**
     * Filtros permitidos
     */
    protected array $allowedFilters = [
        'type',
        'active',
        'archived',
        'client_id',
        'parent_id'
    ];

    /**
     * Campos pesquisáveis
     */
    protected array $searchable = [
        'name',
        'description',
        'slug'
    ];

    /**
     * Ordenação padrão
     */
    protected string $defaultSort = 'order';
    protected string $defaultOrder = 'asc';

    public function __construct()
    {
        $this->model = new Category;
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->authorizeOrFail($this->permissionCreate);

        $validated = $request->validated();
        $category = Category::create($validated);

        $category->load(['parent', 'children']);

        return new CategoryResource($category);
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $this->authorizeOrFail($this->permissionUpdate);

        $category = Category::findOrFail($id);
        $validated = $request->validated();
        $category->update($validated);

        $category->load(['parent', 'children']);

        return new CategoryResource($category);
    }

    /**
     * Restore a deleted category.
     */
    public function restore($id)
    {
        $this->authorizeOrFail($this->permissionUpdate);

        $category = Category::withTrashed()->findOrFail($id);

        if (!$category->trashed()) {
            return response()->json([
                'message' => 'Esta categoria não está excluída.'
            ], 400);
        }

        $category->restore();

        return response()->json([
            'success' => true,
            'message' => 'Categoria restaurada com sucesso.',
            'data' => new CategoryResource($category)
        ]);
    }

    /**
     * Endpoint específico para árvore hierárquica
     */
    public function tree(Request $request)
    {
        $this->authorizeOrFail($this->permissionView);

        $query = $this->model->newQuery()
            ->whereNull('parent_id')
            ->with('allChildren')
            ->active();

        if ($request->has('type')) {
            $query->byType($request->type);
        }

        if ($request->has('client_id')) {
            $query->byClient($request->client_id);
        }

        $categories = $query->orderBy('order')->get();

        return CategoryResource::collection($categories);
    }
}
