<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'username'       => $this->username,
            'firstname'      => $this->firstname,
            'lastname'       => $this->lastname,
            'fullname'       => $this->firstname.' '.$this->lastname,
            'email'          => $this->email,
            'mobile'         => $this->mobile,
            'tel'            => $this->tel,
            'gender'         => $this->genderObject,
            'national_code'  => $this->national_code,
            'address'        => $this->address,
            'postal_code'    => $this->postal_code,
            'birth_date'     => $this->birthDateObject,
            'company'        => $this->company,
            'role'           => $this->roleObject,
            'status'         => $this->status(),
            'slug'           => $this->slug,
            'institution_id' => $this->institution_id,
            'teacherDetails' => $this->teacherDetails ? new TeacherDetailsResource($this->teacherDetails) : [],
            'educations'     => $this->educationals ? EducationalResource::collection($this->educationals) : [],
            'avatar'         => [
                'id' => $this->avatar_id ?? null,
                'url' => $this->avatar?->url ?? null,
            ],
            'national_card_image'         => [
                'id' => $this->national_card_image_id ?? null,
                'url' => $this->nationalCardImage?->url ?? null,
            ]
        ];
    }
}
