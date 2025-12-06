<?php

namespace App\Http\Resources;

use App\Enums\MenuTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMenusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        // Since we're receiving a collection of items grouped by type
        return $this->map(function ($items, $type) {
            return [
                'type' => [
                    'key' => $type,
                    'value' => MenuTypeEnum::fromKey($type)->value,
                ],
                'items' => $items->map(function ($menu) {
                    return [
                        'id' => $menu->id,
                        'title' => $menu->title,
                        'icon' => $menu->icon,
                        'url' => $menu->url,
                        'target' => $menu->target,
                        'children' => $menu->children->map(function ($child) {
                            return [
                                'id' => $child->id,
                                'title' => $child->title,
                                'icon' => $child->icon,
                                'url' => $child->url,
                                'target' => $child->target,
                            ];
                        })
                    ];
                })
            ];
        })->values();
    }
}
