<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'level' => $this->level,
            'permissions' => $this->permissions,
            'active' => $this->active,
            'permission_count' => $this->permission_count,
            'is_admin' => $this->isAdmin(),
            'is_super_admin' => $this->isSuperAdmin(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            // Relacionamentos
            'client' => $this->whenLoaded('client', function () {
                return new UserResource($this->client);
            }),
            'users_count' => $this->whenCounted('users'),
        ];
    }
}
