<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class FinancialTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            // Identificação básica
            'id' => $this->id,
            'client_id' => $this->client_id,
            'type' => $this->whenLoaded('type', function () {
                return [
                    'id' => $this->type_id,
                    'name' => $this->type_name,
                    'code' => $this->type_code,
                ];
            }, $this->type_id),
            
            // Informações do documento
            'fiscal_document' => $this->fiscal_document,
            'cost_center' => $this->cost_center,
            'description' => $this->description,
            
            // Categorias
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? new CategoryResource($this->category) : null;
            }),
            'subcategory_id' => $this->subcategory_id,
            'subcategory' => $this->whenLoaded('subcategory', function () {
                return $this->subcategory ? new CategoryResource($this->subcategory) : null;
            }),
                      
            
            // Pessoa/empresa
            'person_type' => $this->person_type,
            'person_type_name' => $this->person_type == 1 ? 'individual' : 'company',
            
            'individual' => $this->whenLoaded('individual', function () {
                return $this->individual ? new PersonResource($this->individual) : null;
            }),
            'company' => $this->whenLoaded('company', function () {
                return $this->company ? new CompanyResource($this->company) : null;
            }),
            'individual_id' => $this->individual_id,
            'company_id' => $this->company_id,
            
            // Pessoa (baseada no tipo)
            'person' => $this->when($this->person_type == 1 && $this->individual, function () {
                return new PersonResource($this->individual);
            }, function () {
                return $this->when($this->person_type == 2 && $this->company, function () {
                    return new CompanyResource($this->company);
                });
            }),
            
            // Datas e valores
            'due_date' => $this->due_date->format('Y-m-d'),
            'due_date_formatted' => $this->due_date->format('d/m/Y'),
            'amount' => (float) $this->amount,
            'amount_formatted' => number_format($this->amount, 2, ',', '.'),
            
            // Status
            'status' => $this->whenLoaded('status', function () {
                return $this->status ? [
                    'id' => $this->status_id,
                    'name' => $this->status->name,
                    'color' => $this->status->color,
                    'css_class' => $this->status->css_class,
                ] : null;
            }, $this->status_id),
            'status_id' => $this->status_id,
            
            // Informações de pagamento
            'boleto_url' => $this->boleto_url,
            'paid_at' => $this->paid_at?->format('Y-m-d'),
            'paid_at_formatted' => $this->paid_at?->format('d/m/Y'),
            'paid_amount' => $this->paid_amount ? (float) $this->paid_amount : null,
            'paid_amount_formatted' => $this->paid_amount ? number_format($this->paid_amount, 2, ',', '.') : null,
            
            'bank' => $this->whenLoaded('bank', function () {
                return $this->bank ? [
                    'id' => $this->bank_id,
                    'name' => $this->bank->name,
                    'code' => $this->bank->code,
                ] : null;
            }),
            'bank_id' => $this->bank_id,
            
            'payment_method' => $this->whenLoaded('paymentMethod', function () {
                return $this->paymentMethod ? [
                    'id' => $this->payment_method_id,
                    'name' => $this->paymentMethod->name,
                ] : null;
            }),
            'payment_method_id' => $this->payment_method_id,
            
            // Parcelas
            'installment' => $this->installment,
            'total_installments' => $this->total_installments,
            'transaction_key' => $this->transaction_key,
            'is_installment' => $this->total_installments > 1,
            'installment_info' => $this->total_installments > 1 ? 
                "{$this->installment}/{$this->total_installments}" : null,
            
            // Anexos
            'receipt_url' => $this->receipt_url,
            
            // Campos customizados e notas
            'custom_field1' => $this->custom_field1,
            'custom_field2' => $this->custom_field2,
            'custom_field3' => $this->custom_field3,
            'notes' => $this->notes,
            'css_class' => $this->css_class,
            
            // Calculados/accessors
            'is_overdue' => $this->is_overdue,
            'remaining_amount' => (float) $this->remaining_amount,
            'remaining_amount_formatted' => number_format($this->remaining_amount, 2, ',', '.'),
            'is_paid' => !is_null($this->paid_at),
            'payment_status' => $this->getPaymentStatus(),
            
            // Cliente
            'client' => $this->whenLoaded('client', function () {
                return $this->client ? new UserResource($this->client) : null;
            }),
            
            // Audit
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
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'archived' => $this->archived,
            'archived_by' => $this->archived_by,
            'archived_at' => $this->archived_at?->format('Y-m-d H:i:s'),
            
            // Timestamps
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
            
            // Links (HATEOAS)
            'links' => [
                'self' => route('api.v1.financial-transactions.show', $this->id),
                'mark_paid' => $this->is_paid ? null : route('api.v1.financial-transactions.mark-as-paid', $this->id),
                'receipt' => $this->receipt_url ? route('api.v1.financial-transactions.receipt', $this->id) : null,
            ],
        ];
    }
    
    /**
     * Método auxiliar para determinar o status do pagamento
     */
    private function getPaymentStatus(): string
    {
        if (!is_null($this->paid_at)) {
            return 'paid';
        }
        
        if ($this->is_overdue) {
            return 'overdue';
        }
        
        return 'pending';
    }
}