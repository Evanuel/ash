<?php 

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // Identificação básica
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'parent' => $this->whenLoaded('parent', function () {
                return $this->parent ? new CategoryResource($this->parent) : null;
            }),

            // Informações da categoria
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'type_label' => $this->getTypeLabel(),
            'order' => $this->order,

            // Status e visibilidade
            'active' => $this->active,
            'active_label' => $this->active ? 'active' : 'inactive',
            'archived' => $this->archived,
            'archived_label' => $this->archived ? 'archived' : 'unarchived',
            'archived_at' => $this->archived_at?->format('Y-m-d H:i:s'),

            // Client
            'client_id' => $this->client_id,
            'client' => $this->whenLoaded('client', function () {
                return $this->client ? new UserResource($this->client) : null;
            }),

            // Metadados
            'metadata' => $this->metadata,
            
            // Hierarquia (children)
            'children' => $this->whenLoaded('children', function () {
                return CategoryResource::collection($this->children);
            }),
            'children_count' => $this->whenLoaded('children', function () {
                return $this->children->count();
            }),
            'all_children' => $this->whenLoaded('allChildren', function () {
                return CategoryResource::collection($this->allChildren);
            }),
            'has_children' => $this->whenLoaded('children', function () {
                return $this->children->count() > 0;
            }),
            'is_parent' => !is_null($this->parent_id),
            'is_child' => !is_null($this->parent_id),
            'level' => $this->calculateLevel(),
            
            // Relações (opcionais - carregar sob demanda)
            'companies_count' => $this->when($request->has('with_counts'), 
                fn() => $this->companies()->count()
            ),
            'people_count' => $this->when($request->has('with_counts'),
                fn() => $this->people()->count()
            ),
            'financial_transactions_count' => $this->when($request->has('with_counts'),
                fn() => $this->financialTransactions()->count()
            ),
            'subcategory_financial_transactions_count' => $this->when($request->has('with_counts'),
                fn() => $this->subcategoryTransactions()->count()
            ),
            
            // Audit
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'archived_by' => $this->archived_by,
            'created_by_user' => $this->whenLoaded('createdBy', function () {
                return $this->createdBy ? [
                    'id' => $this->created_by,
                    'name' => $this->createdBy->name,
                ] : null;
            }),
            'updated_by_user' => $this->whenLoaded('updatedBy', function () {
                return $this->updatedBy ? [
                    'id' => $this->updated_by,
                    'name' => $this->updatedBy->name,
                ] : null;
            }),
            'archived_by_user' => $this->whenLoaded('archivedBy', function () {
                return $this->archivedBy ? [
                    'id' => $this->archived_by,
                    'name' => $this->archivedBy->name,
                ] : null;
            }),
            
            // Timestamps
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
            
            // Links (HATEOAS)
            'links' => [
                'self' => route('api.v1.categories.show', $this->id),
                'parent' => $this->parent_id ? route('api.v1.categories.show', $this->parent_id) : null,
                'children' => route('api.v1.categories.index', ['parent_id' => $this->id]),
                'companies' => route('api.v1.companies.index', ['category_id' => $this->id]),
                'people' => route('api.v1.people.index', ['category_id' => $this->id]),
                'financial_transactions' => route('api.v1.financial-transactions.index', ['category_id' => $this->id]),
            ],
            
            // Permissions (se necessário)
            'permissions' => $this->when($request->has('with_permissions'), function () {
                return [
                    'can_edit' => auth()->user() ? auth()->user()->can('edit', $this->resource) : false,
                    'can_delete' => auth()->user() ? auth()->user()->can('delete', $this->resource) : false,
                    'can_archive' => auth()->user() ? auth()->user()->can('archive', $this->resource) : false,
                ];
            }),
        ];
    }

    /**
     * Retorna o label do tipo de categoria
     */
    private function getTypeLabel(): string{
        $types = [
            'account' => 'Conta',
            'product' => 'Produto',
            'client' => 'Cliente',
            'financial' => 'Financeira',
            'transaction' => 'Transação',
            'company' => 'Empresa',
            'person' => 'Pessoa',
        ];
        return $types[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Calcula o nível hierárquico da categoria
     */
    function calculateLevel(): int
    {
        $level = 0;
        $category = $this;
        while ($category->parent_id) {
            $level++;
            $category = $category->parent;
        }
        return $level;
    }
}