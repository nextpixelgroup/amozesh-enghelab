<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avatar = asset('assets/img/default-teacher.svg');
        if($this->user_id){
            $avatar = $this->user?->avatar?->url ?? $avatar;
        }

        $data = [
            'id' => $this->id,
            'avatar' => $avatar,
            'name' => $this->user_id ? $this->user->firstname.' '.$this->user->lastname : $this->name,
            'body' => $this->body,
            'created_at' => verta()->instance($this->created_at)->format('Y/m/d-H:i'),
            'replies' => WebCommentResource::collection($this->replies),
        ];
        return $data;
    }
}
