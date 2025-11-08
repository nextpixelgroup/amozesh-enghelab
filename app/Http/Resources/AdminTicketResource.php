<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'user'       => new UserResource($this->user),
            'subject'    => $this->subject,
            'message'    => $this->message,
            'read_at'    => $this->readAtObject,
            'created_at' => $this->createdAtObject,
        ];
    }
}
