<?php
// App\Http\Resources\Api\V1\CompanyResource

namespace App\Http\Resources\Api\V1;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'type' => $this->type,
            'cnpj' => $this->cnpj,
            'trade_name' => $this->trade_name,
            'company_name' => $this->company_name,
            'state_registration' => $this->state_registration,
            'municipal_registration' => $this->municipal_registration,
            'address' => [
                'street' => $this->street,
                'number' => $this->number,
                'neighborhood' => $this->neighborhood,
                'zip_code' => $this->zip_code,
                'city' => $this->whenLoaded('city', function () {
                    return $this->city ? [
                        'id' => $this->city->id,
                        'name' => $this->city->name,
                    ] : null;
                }),
                'state' => $this->whenLoaded('state', function () {
                    return $this->state ? [
                        'id' => $this->state->id,
                        'name' => $this->state->name,
                        'uf' => $this->state->uf,
                    ] : null;
                }),
                'full_address' => $this->full_address,
            ],
            'logo' => $this->logo,
            'cnae' => $this->cnae,
            'opening_date' => $this->opening_date,
            'is_headquarters' => $this->is_headquarters,
            'headquarters_code' => $this->headquarters_code,
            'is_branch' => $this->is_branch,
            'branch_code' => $this->branch_code,
            'category' => $this->whenLoaded('category'),
            'subcategory' => $this->whenLoaded('subcategory'),
            'tax_regime' => $this->tax_regime,
            //'contacts' => $this->contacts ? json_decode($this->contacts, true) : [],
            'contacts' => $this->contacts ?: [],
            'financial' => [
                'credit_limit' => (float) $this->credit_limit,
                'used_credit' => (float) $this->used_credit,
                'available_credit' => (float) $this->credit_limit - $this->used_credit,
            ],
            'status' => [
                'activated' => $this->activated,
                'situation' => $this->situation,
                'status' => $this->status,
                'archived' => $this->archived,
                'archived_at' => $this->archived_at,
            ],
            'custom_fields' => [
                'custom_field1' => $this->custom_field1,
                'custom_field2' => $this->custom_field2,
                'custom_field3' => $this->custom_field3,
            ],
            'notes' => $this->notes,
            'audit' => [
                'created_by' => $this->created_by,
                'updated_by' => $this->updated_by,
                'archived_by' => $this->archived_by,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}