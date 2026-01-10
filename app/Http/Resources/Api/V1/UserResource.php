<?php
// app/Http/Resources/Api/V1/UserResource.php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'avatar_url' => $this->avatar_url,
            'is_active' => $this->is_active,
            'is_archived' => $this->is_archived,
            'role' => $this->whenLoaded('role', function () {
                return $this->role ? [
                    'id' => $this->role->id,
                    'name' => $this->role->name,
                    'level' => $this->role->level,
                ] : null;
            }),
            'supervisor' => $this->whenLoaded('supervisor', function () {
                return $this->supervisor ? [
                    'id' => $this->supervisor->id,
                    'name' => $this->supervisor->name,
                    'email' => $this->supervisor->email,
                ] : null;
            }),
            'company' => $this->whenLoaded('company'),
            'person' => $this->whenLoaded('person'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}