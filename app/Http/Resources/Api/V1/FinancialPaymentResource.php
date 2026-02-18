<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class FinancialPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'financial_transaction_id' => $this->financial_transaction_id,
            'payment_date' => $this->payment_date->format('Y-m-d'),
            'payment_date_formatted' => $this->payment_date->format('d/m/Y'),
            'amount' => (float) $this->amount,
            'amount_formatted' => number_format($this->amount, 2, ',', '.'),
            'payment_method_id' => $this->payment_method_id,
            'payment_method' => $this->whenLoaded('paymentMethod', function () {
                return $this->paymentMethod ? [
                    'id' => $this->payment_method_id,
                    'name' => $this->paymentMethod->name,
                ] : null;
            }),
            'bank_id' => $this->bank_id,
            'bank' => $this->whenLoaded('bank', function () {
                return $this->bank ? [
                    'id' => $this->bank_id,
                    'name' => $this->bank->name,
                    'code' => $this->bank->code,
                ] : null;
            }),
            'is_manual' => $this->is_manual,
            'notes' => $this->notes,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
