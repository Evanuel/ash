<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'type' => $this->type,
            'cpf' => $this->cpf,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'rg' => $this->rg,
            'street' => $this->street,
            'number' => $this->number,
            'neighborhood' => $this->neighborhood,
            'zip_code' => $this->zip_code,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'birthdate' => $this->birthdate,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'credit_limit' => $this->credit_limit,
            'used_credit' => $this->used_credit,
            'available_credit' => $this->credit_limit - $this->used_credit,
            'activated' => $this->activated,
            'situation' => $this->situation,
            'status' => $this->status,
            'custom_field1' => $this->custom_field1,
            'custom_field2' => $this->custom_field2,
            'custom_field3' => $this->custom_field3,
            'notes' => $this->notes,
            'full_address' => $this->full_address,
            'archived' => $this->archived,
            'archived_at' => $this->archived_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            
            // Relacionamentos
            'client' => $this->whenLoaded('client', function () {
                return new UserResource($this->client);
            }),
            'state' => $this->whenLoaded('state', function () {
                return new StateResource($this->state);
            }),
            'city' => $this->whenLoaded('city', function () {
                return new CityResource($this->city);
            }),
            'category' => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),
            'subcategory' => $this->whenLoaded('subcategory', function () {
                return new CategoryResource($this->subcategory);
            }),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'financial_transactions' => FinancialTransactionResource::collection($this->whenLoaded('financialTransactions')),
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}