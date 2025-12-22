<?php

namespace App\Http\Resources;

use App\Services\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PanelProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'firstname'     => $this->firstname,
            'lastname'      => $this->lastname,
            'gender'        => $this->gender,
            'mobile'        => $this->mobile,
            'email'         => $this->email,
            'birth_date'    => $this->birthDateObject,
            'national_code' => $this->national_code,
            'address'       => $this->address,
            'postal_code'   => $this->postal_code,
            'company'       => $this->company,
            'province'      => [
                'value' => $this->province ?? '',
                'title' => $this->province ? City::getLabel($this->province, 'province') : '',
            ],
            'city' => [
                'value' => $this->city ?? '',
                'title' => $this->city ? City::getLabel($this->city, 'city') : '',
            ],
            'national_card_image' => [
                'id' => $this->national_card_image_id ?? null,
                'url' => $this->nationalCardImage?->url ?? null,
            ],
            'educations'     => $this->educationals ? EducationalResource::collection($this->educationals) : [],
        ];
        return $data;
    }
}
