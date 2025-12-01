<?php

namespace App\Http\Requests\Web;

use App\Rules\IsMobile;
use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
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
            'name' => ['required', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'mobile' => ['required', new IsMobile],
            'message' => ['required', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'لطفا نام خود را وارد کنید',
            'name.min' => 'نام باید حداقل :min کاراکتر باشد',
            'name.max' => 'نام باید حداکثر :max کاراکتر باشد',
            'email.required' => 'لطفا ایمیل خود را وارد کنید',
            'email.email' => 'ایمیل معتبر نیست',
            'email.max' => 'ایمیل باید حداکثر :max کاراکتر باشد',
            'mobile.required' => 'لطفا شماره موبایل خود را وارد کنید',
            'mobile.is_mobile' => 'لطفا شمراه موبایل معتبر وارد نمایید',
            'message.required' => 'لطفا پیام خود را وارد کنید',
            'message.min' => 'پیام باید حداقل :min کاراکتر باشد',
            'message.max' => 'پیام باید حداکثر :max کاراکتر باشد',
        ];
    }
}
