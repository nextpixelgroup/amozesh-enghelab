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
            'id'            => $this->id,
            'username'      => $this->username,
            'firstname'     => $this->firstname,
            'lastname'      => $this->lastname,
            'fullname'      => $this->firstname.' '.$this->lastname,
            'email'         => $this->email,
            'mobile'        => $this->mobile,
            'gender'        => $this->genderObject,
            'national_code' => $this->national_code,
            'address'       => $this->address,
            'postal_code'   => $this->postal_code,
            'birth_date'    => $this->birthDateObject,
            'company'       => $this->company,
            'role'          => $this->roleObject,
            'status'        => $this->status(),
            'slug'          => $this->slug,
        ];
    }
}
