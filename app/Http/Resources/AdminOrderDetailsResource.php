<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminOrderDetailsResource extends JsonResource
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
            'reference_id' => $this->reference_id,
            'user' => [
                'fullname' => $this->user->firstname.' '.$this->user->lastname,
                'type' => $this->institution_id ? 'دانشگاهی' : 'عادی',
            ],
            'fullname' => $this->fullname,
            'mobile' => $this->mobile,
            'postal_code' => $this->postal_code,
            'address' => $this->address,
            'user_note' => $this->user_note,
            'products' => $this->items->map(function ($item){
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'discount' => $item->discount,
                    'sale_price' => $item->sale_price,
                ];
            }),
            'payment' => [
                'transaction_id' => $this->payment ? $this->payment->transaction_id : '-',
                'reference_id' => $this->payment ? $this->payment->reference_id : '-',
            ],
            'shipping' => '-',
            'total' => $this->total,
            'shipping_cost' => $this->shipping_cost,
            'original_total' => $this->original_total,
            'discount_total' => $this->discount_total,
            'status' => $this->status,
            'created_at' => $this->createdAtObject,
            'notes' => $this->notes()->orderBy('created_at', 'desc')->get()->map(function ($note){
                return [
                    'message' => $note->message,
                    'creator' => $note->creator->firstname.' '.$note->creator->lastname,
                    'created_at' => $note->createdAtObject,
                ];
            }),
        ];
    }
}
