<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => CategoryResource::collection($this->collection),
            'meta' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'types' => $this->getTypesSummary(),
                'active_count' => $this->collection->where('active', true)->count(),
                'archived_count' => $this->collection->where('archived', true)->count(),
                'links' => [
                    'self' => $this->url($this->currentPage()),
                    'first' => $this->url(1),
                    'last' => $this->url($this->lastPage()),
                    'prev' => $this->previousPageUrl(),
                    'next' => $this->nextPageUrl(),
                ],
            ],
        ];
    }
    
    /**
     * Retorna um sumário dos tipos presentes na coleção
     */
    private function getTypesSummary(): array
    {
        return $this->collection
            ->groupBy('type')
            ->map(function ($items, $type) {
                return [
                    'type' => $type,
                    'count' => $items->count(),
                    'active_count' => $items->where('active', true)->count(),
                ];
            })
            ->values()
            ->toArray();
    }
}