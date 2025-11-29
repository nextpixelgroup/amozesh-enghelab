<?php

namespace App\Http\Requests\Web;

use App\Rules\IsMobile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayRequest extends FormRequest
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
            'firstname' => ['required', 'string', 'min:2', 'max:50'],
            'lastname' => ['required', 'string', 'min:2', 'max:50'],
            'address' => ['required', 'string', 'min:10', 'max:255'],
            'postal_code' => ['required', 'numeric', 'digits:10'],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'note' => ['nullable', 'min:3', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'لطفا نام را وارد نمایید',
            'firstname.min' => 'نام باید حداقل :min کاراکتر باشد',
            'firstname.max' => 'نام باید حداکثر :max کاراکتر باشد',
            'lastname.required' => 'لطفا نام خانوادگی را وارد نمایید',
            'lastname.min' => 'نام خانوادگی باید حداقل :min کاراکتر باشد',
            'lastname.max' => 'نام خانوادگی باید حداکثر :max کاراکتر باشد',
            'address.required' => 'لطفا آدرس را وارد نمایید',
            'address.min' => 'آدرس باید حداقل :min کاراکتر باشد',
            'address.max' => 'آدرس باید حداکثر :max کاراکتر باشد',
            'postal_code.required' => 'لطفا کدپستی را وارد نمایید',
            'postal_code.numeric' => 'کدپستی شامل عدد می‌باشد',
            'postal_code.digits' => 'کدپستی باید :digits رقم باشد',
            'email' => 'لطفا ایمیل را به درستی وارد نمایید',
            'email.unique' => 'این ایمیل قبلاً در سامانه ثبت شده است؛ لطفاً ایمیل دیگری وارد نمایید.',
            'note.min' => 'یادداشت باید حداقل :min کاراکتر باشد',
            'note.max' => 'یادداشت باید حداکثر :max کاراکتر باشد',
        ];
    }
}
