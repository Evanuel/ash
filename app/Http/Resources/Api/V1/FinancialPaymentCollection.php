<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FinancialPaymentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total_payments' => $this->collection->count(),
                'total_amount' => $this->collection->sum('amount'),
            ],
        ];
    }
}
