<?php
// App\Http\Resources\Api\V1\CostCenterResource

namespace App\Http\Resources\Api\V1;
use Illuminate\Http\Resources\Json\JsonResource;

class CostCenterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'css_class' => $this->css_class,
            'icon' => $this->icon,
        ];
    }
}