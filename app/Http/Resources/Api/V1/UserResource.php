<?php
// app/Http/Resources/Api/V1/UserResource.php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'client_id' => $this->client_id,
            'role_id' => $this->role_id,
            'role' => $this->whenLoaded('role', function () {
                return [
                    'id' => $this->role->id,
                    'name' => $this->role->name,
                    'level' => $this->role->level,
                ];
            }),
            'branch_id' => $this->branch_id,
            'supervisor_id' => $this->supervisor_id,
            'company_id' => $this->company_id,
            'people_id' => $this->people_id,
            'profile_image' => $this->profile_image,
            'active' => $this->active,
            'archived' => $this->archived,
            'archived_at' => $this->archived_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'links' => [
                'self' => route('api.v1.users.show', $this->id),
                'role' => $this->role_id ? route('api.v1.roles.show', $this->role_id) : null,
            ],
        ];
    }

    /**
     * Customize the response for a request.
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
                'timestamp' => now()->toISOString(),
            ],
        ];
    }
}