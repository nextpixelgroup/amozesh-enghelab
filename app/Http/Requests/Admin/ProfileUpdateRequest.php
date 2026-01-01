<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
        $userId = $this->user()->id;
        
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $userId],
            'gender' => ['required', 'in:male,female'],
            'tel' => ['nullable', 'string', 'max:20'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $userId
            ],
            'national_code' => [
                'required',
                'string',
                'size:10',
                'unique:users,national_code,' . $userId
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required' => 'وارد کردن نام اجباری است.',
            'firstname.string' => 'نام باید به صورت متن باشد.',
            'firstname.max' => 'نام نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            
            'lastname.required' => 'وارد کردن نام خانوادگی اجباری است.',
            'lastname.string' => 'نام خانوادگی باید به صورت متن باشد.',
            'lastname.max' => 'نام خانوادگی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            
            'username.required' => 'وارد کردن نام کاربری اجباری است.',
            'username.string' => 'نام کاربری باید به صورت متن باشد.',
            'username.max' => 'نام کاربری نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            'username.unique' => 'این نام کاربری قبلا ثبت شده است.',
            
            'gender.required' => 'انتخاب جنسیت اجباری است.',
            'gender.in' => 'جنسیت انتخاب شده معتبر نیست.',
            
            'tel.string' => 'شماره تلفن باید به صورت متن باشد.',
            'tel.max' => 'شماره تلفن نمی‌تواند بیشتر از ۲۰ کاراکتر باشد.',
            
            'email.required' => 'وارد کردن ایمیل اجباری است.',
            'email.string' => 'ایمیل باید به صورت متن باشد.',
            'email.email' => 'فرمت ایمیل وارد شده معتبر نیست.',
            'email.max' => 'ایمیل نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            'email.unique' => 'این ایمیل قبلا ثبت شده است.',
            
            'national_code.required' => 'وارد کردن کد ملی اجباری است.',
            'national_code.string' => 'کد ملی باید به صورت متن باشد.',
            'national_code.size' => 'کد ملی باید ۱۰ رقمی باشد.',
            'national_code.unique' => 'این کد ملی قبلا ثبت شده است.',
        ];
    }
}
