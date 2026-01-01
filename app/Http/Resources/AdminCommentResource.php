<?php

namespace App\Http\Resources;

use App\Models\Book;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCommentResource extends JsonResource
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
        $route = $this->commentable_type === Course::class ? 'web.courses.show' : 'web.books.show';
        $data = [
            'id' => $this->id,
            'user' => [
                'is_user'     => $this->user_id ? true : false,
                'name'        => $this->user ? $this->user->firstname.' '.$this->user->lastname : $this->name,
                'email'       => $this->user ? '' : $this->email,
                'avatar'      => $avatar,
                'institution' => $this->user?->institution?->firstname,
            ],
            'model' => [
                'type' => class_exists($this->commentable_type) ? $this->commentable_type::label() : 'نامشخص',
                'title' => $this->commentable->title,
                'url' => route($route,$this->commentable->slug ).'#comments'
            ],
            'comment' => $this->body,
            'is_approved' => $this->is_approved,
            'date' => verta()->instance($this->created_at)->format('Y/m/d'),
            'time' => verta()->instance($this->created_at)->format('H:i'),
        ];
        //dd($data);
        return $data;
    }
}
