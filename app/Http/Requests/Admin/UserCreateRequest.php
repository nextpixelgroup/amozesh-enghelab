<?php

namespace App\Http\Requests\Admin;

use App\Enums\GenderEnum;
use App\Enums\UserGenderEnum;
use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Rules\IsMobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserCreateRequest extends FormRequest
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
            'firstname'     => 'required|string|min:2|max:255',
            'lastname'      => 'nullable|string|min:2|max:255',
            'gender'        => 'nullable|in:'.collect(GenderEnum::cases())->pluck('name')->implode(','),
            'national_code' => 'nullable|string|digits:10|unique:users,national_code',
            'mobile'        => ['required', 'unique:users,mobile', new IsMobile],
            'email'         => 'nullable|email|unique:users,email',
            'address'       => 'nullable|string|min:5|max:255',
            'postal_code'   => 'nullable|numeric',
            'birth_day'     => 'nullable|numeric',
            'birth_month'   => 'nullable|numeric',
            'birth_year'    => 'nullable|numeric',
            'company'       => 'nullable|string|min:2|max:255',
            'status'        => 'required|in:'.collect(UserStatusEnum::cases())->pluck('name')->implode(','),
            'role'          => 'required|in:'.User::allRoles()->pluck('value')->implode(','),
            'username' => [
                Rule::requiredIf(fn () => in_array($this->input('role'), ['admin', 'content-manager'])),
                'nullable',
                'string',
                'min:3',
                'max:255',
                Rule::unique('users', 'username')->ignore($this->user?->id)
            ],
            'password' => [
                Rule::requiredIf(fn () => $this->input('role') === 'client'),
                'nullable',
                'string',
                'min:6',
            ],
            'slug' => [
                Rule::requiredIf(fn () => $this->input('role') === 'teacher'),
                'nullable',
                'string',
                'min:3',
                'max:100',
                Rule::unique('users', 'slug')->ignore($this->user?->id)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Firstname
            'firstname.required' => 'وارد کردن نام الزامی است.',
            'firstname.string' => 'نام باید به صورت متن باشد.',
            'firstname.min' => 'نام باید حداقل ۲ کاراکتر باشد.',
            'firstname.max' => 'نام نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            // Lastname
            'lastname.string' => 'نام خانوادگی باید به صورت متن باشد.',
            'lastname.min' => 'نام خانوادگی باید حداقل ۲ کاراکتر باشد.',
            'lastname.max' => 'نام خانوادگی نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',

            // Gender
            'gender.in' => 'جنسیت انتخاب شده معتبر نیست.',

            // National Code
            'national_code.digits' => 'کد ملی باید ۱۰ رقم باشد.',
            'national_code.unique' => 'این کد ملی قبلاً ثبت شده است.',

            // Mobile
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.unique' => 'این شماره موبایل قبلاً ثبت شده است.',

            // Email
            'email.email' => 'لطفا یک ایمیل معتبر وارد کنید.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',

            // Address
            'address.min' => 'آدرس باید حداقل :min کاراکتر باشد.',
            'address.max' => 'آدرس نمی‌تواند بیشتر از :max کاراکتر باشد.',

            // Postal Code
            'postal_code.numeric' => 'کد پستی باید عددی باشد.',
            'postal_code.digits' => 'کد پستی باید :digits رقم باشد.',

            // Birth Date
            'birth_day.numeric' => 'روز تولد باید عدد باشد.',
            'birth_month.numeric' => 'ماه تولد باید عدد باشد.',
            'birth_year.numeric' => 'سال تولد باید عدد باشد.',

            // Company
            'company.min' => 'نام شرکت باید حداقل :min کاراکتر باشد.',
            'company.max' => 'نام شرکت نمی‌تواند بیشتر از :max کاراکتر باشد.',

            // Status
            'status.required' => 'انتخاب وضعیت الزامی است.',
            'status.in' => 'وضعیت انتخاب شده معتبر نیست.',

            // Role
            'role.required' => 'انتخاب نقش الزامی است.',
            'role.in' => 'نقش انتخاب شده معتبر نیست.',

            // Username
            'username.required' => 'برای نقش انتخاب شده، نام کاربری الزامی است.',
            'username.string' => 'نام کاربری باید به صورت متن باشد.',
            'username.min' => 'نام کاربری باید حداقل :min کاراکتر باشد.',
            'username.max' => 'نام کاربری نمی‌تواند بیشتر از :max کاراکتر باشد.',
            'username.unique' => 'این نام کاربری قبلاً ثبت شده است.',

            // Password
            'password.required' => 'برای کاربران عادی، تعیین رمز عبور الزامی است.',
            'password.string' => 'رمز عبور باید به صورت متن باشد.',
            'password.min' => 'رمز عبور باید حداقل :min کاراکتر باشد.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',

            //slug
            'slug.required' => 'برای مدرس، وارد کردن نامک الزامی است.',
            'slug.string' => 'نامک حتما باید کاراکتری باشد',
            'slug.min' => 'نامک باید حداقل :min کاراکتر باشد.',
            'slug.max' => 'نامک نمی‌تواند بیشتر از :max کاراکتر باشد.',
            'unique' => 'این نامک قبلا استفاده شده است'
        ];
    }

}
