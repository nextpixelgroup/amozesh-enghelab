<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRestrictionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class RestrictionCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'in:'.collect(UserRestrictionTypeEnum::cases())->pluck('name')->implode(','),
            'reason' => 'required|min:3',
            'days' => [
                'required_if:type,temporary',
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages()
    {
        return [
            'type.in' => 'نوع محدودیت معتبر نیست',
            'reason.required' => 'توضیحات الزامی است',
            'reason.min' => 'توضیحات حداقل 3 کاراکتر باید باشد',
            'days.required' => 'تعداد روز الزامی است',
            'days.numeric' => 'تعداد روز باید عددی باشد',
        ];
    }
}
