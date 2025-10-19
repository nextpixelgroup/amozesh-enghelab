<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
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
            'body' => ['required', 'string', 'min:3', 'max:1000'],
            'commentable_type' => ['required', 'string', Rule::in(['course', 'book'])],
            'commentable_id' => ['required', 'integer', 'min:1'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'body.required' => 'لطفا متن نظر را وارد کنید.',
            'body.min' => 'متن نظر باید حداقل ۳ کاراکتر باشد.',
            'body.max' => 'متن نظر نباید بیشتر از ۱۰۰۰ کاراکتر باشد.',
            'commentable_type.in' => 'نوع کامنت نامعتبر است.',
            'commentable_id.min' => 'شناسه معتبر نیست.',
            'parent_id.exists' => 'نظر والد معتبر نیست.',
        ];
    }
}
