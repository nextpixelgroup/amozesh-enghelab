<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'       => $this->title,
            'description' => $this->description,
            'slug'        => $this->slug,
            'isActive'    => $this->isActiveObject,
            'createdAt'   => $this->createdAtObject,
        ];
    }
}
