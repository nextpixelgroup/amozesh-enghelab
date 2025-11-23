<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebBooksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug'     => $this->slug,
            'title'    => $this->title,
            'subtitle' => $this->subtitle,
            'thumbnail' => $this->thumbnail ? $this->thumbnail->url : asset('assets/img/default.svg'),
            'price' => $this->price,
            'special_price' => $this->special_price,
            'url' => route('web.books.show',$this->slug),
            'rate' => $this->rate,
        ];
    }
}
