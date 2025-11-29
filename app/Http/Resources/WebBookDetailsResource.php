<?php

namespace App\Http\Resources;

use App\Models\Book;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebBookDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        $qty = 1;
        $cartItemId = null;
        if($user){
            $cartItem = CartItem::query()->where('item_id', $this->id)->where('item_type', Book::class)->whereHas('cart', fn($q) => $q->where('user_id', $user->id))->first();
            if($cartItem) {
                $qty = $cartItem->qty;
                $cartItemId = $cartItem->id;
            }
        }
        return [
            'id'             => $this->id,
            'slug'           => $this->slug,
            'title'          => $this->title,
            'subtitle'       => $this->subtitle,
            'summary'        => $this->summary,
            'content'        => $this->content,
            'rate'           => number_format($this->rate,1),
            'user_rate'      => $user ? $this->ratings()->where('user_id',$user->id)->first()?->rate : null,
            'thumbnail'      => $this->thumbnail->url ?? '/assets/img/default.svg',
            'publisher'      => $this->publisher,
            'year_published' => $this->year_published,
            'edition'        => $this->edition,
            'size'           => $this->size,
            'author'         => $this->author,
            'price'          => number_format($this->price),
            'special_price'  => $this->special_price ? number_format($this->special_price) : 0,
            'is_stock'       => $this->is_stock ? true : false,
            'stock'          => $this->stock,
            'max_order'      => $this->max_order,
            'category'       => $this->categories?->first()?->title,
            'url'            => route('web.books.show',$this->slug),
            'qty'            => $qty,
            'cartItemId'     => $cartItemId,
        ];
    }
}
