<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPageDetailsResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'slug' => $this->slug,
            'type' => $this->type,
            'status' => $this->statusObject,
            'meta' => $this->metaArray,
            'thumbnail' => [
                'id'    => $this->thumbnail_id ?? null,
                'url'   => $this->thumbnail?->url,
            ]
        ];
    }
}
