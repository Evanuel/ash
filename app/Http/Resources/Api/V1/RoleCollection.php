<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class RoleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user();

        return [
            'meta' => [
                'pagination' => [
                    'total' => $this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator ? $this->resource->total() : $this->collection->count(),
                    'count' => $this->collection->count(),
                    'per_page' => $this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator ? $this->resource->perPage() : $this->collection->count(),
                    'current_page' => $this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator ? $this->resource->currentPage() : 1,
                    'total_pages' => $this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator ? $this->resource->lastPage() : 1,
                ],
                'statistics' => [
                    'total_roles' => $this->collection->count(),
                    'active_roles' => $this->collection->where('active', true)->count(),
                    'admin_roles' => $this->collection->filter(fn($role) => $role->level >= 90)->count(),
                ],
                'client_id' => $user ? $user->client_id : null,
            ],
            'data' => RoleResource::collection($this->collection),
        ];
    }
}
