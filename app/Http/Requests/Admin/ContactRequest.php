<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'mobile' => ['required', 'string', 'regex:/^09[0-9]{9}$/'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.regex' => 'فرمت شماره موبایل معتبر نیست. مثال: 09123456789',
            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'email.email' => 'ایمیل معتبر وارد نمایید.',
            'email.max' => 'حداکثر طول ایمیل :max کاراکتر است.',
            'subject.required' => 'وارد کردن موضوع الزامی است.',
            'subject.max' => 'حداکثر طول موضوع :max کاراکتر است.',
            'message.max' => 'حداکثر طول متن پیام :max کاراکتر است.',
        ];
    }
}
