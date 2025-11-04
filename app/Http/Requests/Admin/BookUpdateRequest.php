<?php

namespace App\Http\Requests\Admin;

use App\Enums\BookStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
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
            'title'          => 'required|min:2',
            'subtitle'       => 'nullable|min:3',
            'slug'           => 'nullable',
            'summary'        => 'nullable|min:10',
            'content'        => 'nullable|min:10',
            'thumbnail_id'   => 'nullable',
            'publisher'      => 'nullable',
            'price'          => 'nullable|numeric',
            'special_price'  => 'nullable|numeric',
            'is_stock'       => 'required|string',
            'stock'          => 'nullable|numeric',
            'max_order'      => 'required|numeric',
            'year_published' => 'nullable',
            'edition'        => 'nullable',
            'author'         => 'nullable',
            'status'         => 'required|in:'.implode(',', enumNames(BookStatusEnum::cases())),
        ];
    }


    public function messages()
    {
        return [
            // Title messages
            'title.required' => 'عنوان کتاب الزامی است.',
            'title.max' => 'عنوان نمی‌تواند بیشتر از 255 کاراکتر باشد.',

            // Subtitle messages
            'subtitle.required' => 'زیرعنوان الزامی است.',
            'subtitle.unique' => 'این زیرعنوان قبلا ثبت شده است.',
            'subtitle.max' => 'زیرعنوان نمی‌تواند بیشتر از 255 کاراکتر باشد.',

            // summary messages
            'summary.min' => 'خلاصه توضیحات باید حداقل 10 کاراکتر باشد.',

            // Content messages
            'content.min' => 'محتوا باید حداقل 10 کاراکتر باشد.',

            // Price messages
            'price.numeric' => 'قیمت باید عددی باشد.',
            'price.min' => 'قیمت نمی‌تواند منفی باشد.',

            // Special price messages
            'special_price.numeric' => 'قیمت ویژه باید عددی باشد.',
            'special_price.min' => 'قیمت ویژه نمی‌تواند منفی باشد.',

            // Stock messages
            'is_stock.required' => 'وضعیت موجودی الزامی است.',

            'stock.numeric' => 'تعداد موجودی باید عددی باشد.',
            'stock.min' => 'تعداد موجودی نمی‌تواند منفی باشد.',

            // Max order messages
            'max_order.required' => 'حداکثر تعداد سفارش الزامی است.',
            'max_order.numeric' => 'حداکثر تعداد سفارش باید عددی باشد.',
            'max_order.min' => 'حداقل تعداد سفارش 1 می‌باشد.',

            // Status messages
            'status.required' => 'وضعیت انتشار الزامی است.',
            'status.in' => 'وضعیت انتخابی معتبر نیست.',

            // Category messages
            'category.required' => 'حداقل یک دسته‌بندی الزامی است.',
            'category.array' => 'فرمت دسته‌بندی‌ها نامعتبر است.',
            'category.*.exists' => 'یکی از دسته‌بندی‌های انتخاب شده معتبر نیست.',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'عنوان',
            'subtitle' => 'زیرعنوان',
            'slug' => 'نامک',
            'summary' => 'خلاصه توضیحات',
            'content' => 'محتوا',
            'thumbnail_id' => 'تصویر شاخص',
            'publisher' => 'ناشر',
            'price' => 'قیمت',
            'special_price' => 'قیمت ویژه',
            'is_stock' => 'وضعیت موجودی',
            'stock' => 'تعداد موجودی',
            'max_order' => 'حداکثر تعداد سفارش',
            'year_published' => 'سال انتشار',
            'edition' => 'ویرایش',
            'status' => 'وضعیت',
            'category' => 'دسته‌بندی',
            'category.*' => 'دسته‌بندی',
        ];
    }
}
