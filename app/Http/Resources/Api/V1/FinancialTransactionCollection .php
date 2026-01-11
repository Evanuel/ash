<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FinancialTransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => FinancialTransactionResource::collection($this->collection),
            'meta' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
                'links' => [
                    'self' => $this->url($this->currentPage()),
                    'first' => $this->url(1),
                    'last' => $this->url($this->lastPage()),
                    'prev' => $this->previousPageUrl(),
                    'next' => $this->nextPageUrl(),
                ],
            ],
            'summary' => $this->when($request->has('summary'), function () {
                return [
                    'total_amount' => $this->collection->sum('amount'),
                    'total_paid' => $this->collection->sum('paid_amount'),
                    'total_remaining' => $this->collection->sum('remaining_amount'),
                    'overdue_count' => $this->collection->where('is_overdue', true)->count(),
                ];
            }),
        ];
    }
}