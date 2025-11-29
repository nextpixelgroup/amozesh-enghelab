<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $item = $this->item;
        $type = class_basename($this->item_type);

        // پیش‌فرض‌ها
        $valid = true;
        $message = null;
        $warning = false;   // اخطار max_order

        // فقط برای Book بررسی موجودی لازم است
        if ($type === 'Book') {

            // حالت 1: is_stock = false  → موجودی صفر مطلق
            if ($item->is_stock == false || $item->status !== 'publish') {
                $valid = false;
                $message = 'این کتاب ناموجود است و به صورت خودکار از سبد خرید شما حذف می گردد.';
            } // حالت 2: is_stock = true و stock = null → نامحدود
            elseif ($item->is_stock == true && $item->stock === null) {
                $valid = true; // مشکلی نیست
            } // حالت 3: is_stock = true و stock > 0 → موجودی محدود
            elseif ($item->is_stock == true && $item->stock > 0) {
                if ($this->qty > $item->stock) {
                    $valid = false;
                    $message = "تعداد موجودی این کتاب فقط {$item->stock} عدد است و به صورت خودکار از سبد خرید شما حذف می‌گردد. لطفا دوباره سفارش دهید.";
                }
            }
            // 4) بررسی max_order (هشدار — زرد)
            if ($valid === true && $item->max_order !== null) {
                if ($this->qty > $item->max_order) {

                    // کاهش qty → آپدیت
                    $this->update(['qty' => $item->max_order]);

                    $warning = true;
                    $message = "سفارش شما ویرایش شد. حداکثر تعداد قابل سفارش برای این کتاب {$item->max_order} عدد است.";
                }
            }
        }

        // اگر معتبر نبود، آیتم را حذف کن اما یکبار برای کلاینت نمایش بده
        if ($valid === false) {
            // حذف از دیتابیس
            $this->delete();
        }

        return [
            'id' => $this->id,
            'qty' => $this->qty,
            'type' => $type,
            'valid' => $valid,
            'warning' => $warning,
            'message' => $message,

            'data' => match ($type) {
                'Book' => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'price' => $item->price,
                    'special_price' => $item->special_price > 0 ? (int)$item->special_price : null,
                    'sale_price' => $item->special_price > 0 ? (int)$item->special_price : (int)$item->price,
                    'max_order' => $item->max_order,
                    'thumbnail' => $item->thumbnail ? $item->thumbnail->url : '/assets/img/default.svg',
                    'url'       => route('web.books.show',$item->slug),
                ],

                default => null
            }
        ];
    }

}
