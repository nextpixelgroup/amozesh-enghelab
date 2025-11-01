<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestrictionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->typeObject,
            'reason'     => $this->reason,
            'expires_at' => $this->expiresAtObject,
            'created_at' => $this->createdAtObject,
            'updated_at' => $this->updatedAtObject,
        ];
    }
}
