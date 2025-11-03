<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'subtitle'       => $this->subtitle,
            'summary'        => $this->summary,
            'content'        => $this->content,
            'publisher'      => $this->publisher,
            'status'         => $this->statusObject,
            'price'          => $this->price,
            'special_price'  => $this->special_price,
            'is_stock'       => $this->is_stock,
            'stock'          => $this->stock,
            'max_order'      => $this->max_order,
            'year_published' => $this->year_published,
            'edition'        => $this->edition,
            'author'         => $this->author,
            'size'           => $this->size,
            'thumbnail' => [
                'id'    => $this->thumbnail_id,
                'url'   => $this->thumbnail->url,
            ],
            'categories' => $this->categories
        ];
    }
}
