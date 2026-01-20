<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class PersonCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user();
        
        // Estatísticas
        $activeCount = $this->collection->where('status', true)
            ->where('archived', false)
            ->count();
            
        $inactiveCount = $this->collection->where('status', false)
            ->where('archived', false)
            ->count();
            
        $archivedCount = $this->collection->where('archived', true)->count();
        
        $withCreditCount = $this->collection->where('credit_limit', '>', 0)
            ->where('archived', false)
            ->count();
        
        $totalCreditLimit = $this->collection->where('archived', false)
            ->sum('credit_limit');
            
        $totalUsedCredit = $this->collection->where('archived', false)
            ->sum('used_credit');
            
        $availableCredit = $totalCreditLimit - $totalUsedCredit;
        
        return [
            'meta' => [
                'pagination' => [
                    'total' => $this->total(),
                    'count' => $this->count(),
                    'per_page' => $this->perPage(),
                    'current_page' => $this->currentPage(),
                    'total_pages' => $this->lastPage(),
                ],
                'statistics' => [
                    'active_count' => $activeCount,
                    'inactive_count' => $inactiveCount,
                    'archived_count' => $archivedCount,
                    'with_credit_count' => $withCreditCount,
                    'total_credit_limit' => $totalCreditLimit,
                    'total_used_credit' => $totalUsedCredit,
                    'available_credit' => $availableCredit,
                ],
                'links' => [
                    'self' => $this->url($this->currentPage()),
                    'first' => $this->url(1),
                    'last' => $this->url($this->lastPage()),
                    'prev' => $this->previousPageUrl(),
                    'next' => $this->nextPageUrl(),
                ],
                'client_id' => $user->client_id,
            ],
            'data' => PersonResource::collection($this->collection),
        ];
    }
}