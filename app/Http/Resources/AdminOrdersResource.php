<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminOrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'reference_id' => $this->reference_id,
            'status'       => $this->statusObject,
            'user'         => [
                'fullname' => $this->user->firstname.' '.$this->user->lastname,
                'mobile'    => $this->user->mobile,
            ],
            'qty' => $this->items->sum('qty'),
            'total' => $this->total,
            'shipping_cost' => $this->shipping_cost,
            'original_total' => $this->original_total,
            'discount_total' => $this->discount_total,
            'created_at' => $this->createdAtObject,
            'url' => route('admin.orders.edit',$this->id),
        ];
    }
}
