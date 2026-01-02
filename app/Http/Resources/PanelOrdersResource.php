<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanelOrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reference_id' => $this->reference_id,
            'products' => $this->items->map(function ($item){

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'thumbnail' => $item?->item->thumbnail->url ?? asset('/assets/img/default.svg'),
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'discount' => $item->discount,
                    'sale_price' => $item->sale_price,
                ];
            }),
            'total' => $this->total,
            'shipping_cost' => $this->shipping_cost,
            'original_total' => $this->original_total,
            'discount_total' => $this->discount_total,
            'status' => $this->statusObject,
            'created_at' => $this->createdAtObject,
        ];
    }
}
