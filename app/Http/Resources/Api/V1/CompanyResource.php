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
            'type' => $this->type,
            'cnpj' => $this->cnpj,
            'trade_name' => $this->trade_name,
            'company_name'=> $this->company_name,
            'state_registration'=> $this->state_registration,
            'municipal_registration'=> $this->municipal_registration,
            'street'=> $this->street,
            'number'=> $this->number,
            'neighborhood'=> $this->neighborhood,
            'zip_code'=> $this->zip_code,
            'state_id'=> $this->state_id,
            'city_id'=> $this->city_id,
            'logo'=> $this->logo,
            'cnae'=> $this->cnae,
            'opening_date'=> $this->opening_date,
            'is_headquarters'=> $this->is_headquarters,
            'headquarters_code'=> $this->headquarters_code,
            'is_branch'=> $this->is_branch,
            'branch_code'=> $this->branch_code,
            'category_id'=> $this->category_id,
            'subcategory_id'=> $this->subcategory_id,
            'tax_regime'=> $this->tax_regime,
            'contacts'=> $this->contacts,
            'credit_limit'=> $this->credit_limit,
            'used_credit'=> $this->used_credit,
            'activated'=> $this->activated,
            'situation'=> $this->situation,
            'status'=> $this->status,
            'custom_field1'=> $this->custom_field1,
            'custom_field2'=> $this->custom_field2,
            'custom_field3'=> $this->custom_field3,
            'notes'=> $this->notes,
            'created_by'=> $this->created_by,
            'updated_by'=> $this->updated_by,
            'archived'=> $this->archived,
            'archived_by'=> $this->archived_by,
            'archived_at'=> $this->archived_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}