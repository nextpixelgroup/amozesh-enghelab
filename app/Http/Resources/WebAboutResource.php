<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebAboutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $meta = $this->metaArray;

        if (!empty($meta['institutions'])) {
            $meta['institutions'] = User::query()
                ->whereIn('id', $meta['institutions'])
                ->with('avatar') // حل مشکل N+1 (اگر آواتار ریلیشن است)
                ->get()
                ->map(fn($user) => [
                    'title'  => $user->firstname,
                    'avatar' => $user->avatar?->url,
                ])
                ->toArray();
        }
        if (!empty($meta['teachers'])) {
            $meta['teachers'] = User::query()
                ->whereIn('id', $meta['teachers'])
                ->with('avatar') // حل مشکل N+1 (اگر آواتار ریلیشن است)
                ->get()
                ->map(fn($user) => [
                    'title'  => $user->firstname.' '.$user->lastname,
                    'avatar' => $user->avatar?->url,
                    'url' => route('web.teachers.show',$user->slug),
                ])
                ->toArray();
        }
        return [
            'title'   => $this->title,
            'content' => $this->content,
            'thumbnail' => [
                'id'    => $this->thumbnail_id ?? null,
                'url'   => $this->thumbnail?->url,
            ],
            'meta'    => $meta,
        ];
    }
}
