<?php

namespace App\Http\Requests\Panel;

use App\Enums\DegreeEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProfileStoreRequest extends FormRequest
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
        $userId = auth()->id();

        return [
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'gender' => 'nullable|in:male,female',
            'email' => 'nullable|email|unique:users,email,' . $userId,
            'birth_day' => 'nullable|integer|between:1,31',
            'birth_month' => 'nullable|integer|between:1,12',
            'birth_year' => 'nullable|integer|min:1300|max:' . date('Y'),
            'national_code' => [
                'required',
                'string',
                'size:10',
                'regex:/^[0-9]{10}$/',
                'unique:users,national_code,' . $userId,
            ],
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|regex:/^[0-9]{10}$/',
            'province' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'national_card_image_id' => 'nullable|exists:media,id',
            'educations' => 'nullable|array',
            'educations.*.university' => 'required|string|max:255',
            'educations.*.city' => 'required|string|max:100',
            'educations.*.field_of_study' => 'required|string|max:255',
            'educations.*.degree' => 'required|in:' . implode(',', enumNames(DegreeEnum::cases())),
            'educations.*.start_year' => 'required|integer|min:1300|max:' . (date('Y')),
            'educations.*.start_month' => 'required|integer|between:1,12',
            'educations.*.end_year' => 'required_if:educations.*.is_studying,false|nullable|integer|min:1300|max:' . (date('Y')) . '|gte:educations.*.start_year',
            'educations.*.end_month' => 'required_if:educations.*.is_studying,false|nullable|integer|between:1,12',
            'educations.*.is_studying' => 'boolean',
            'educations.*.description' => 'nullable|string|max:1000',

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
            'firstname.required' => 'وارد کردن نام الزامی است.',
            'firstname.max' => 'نام نباید بیشتر از ۵۰ کاراکتر باشد.',

            'lastname.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'lastname.max' => 'نام خانوادگی نباید بیشتر از ۵۰ کاراکتر باشد.',

            'gender.in' => 'جنسیت انتخاب شده معتبر نیست.',

            'email.email' => 'فرمت ایمیل وارد شده معتبر نیست.',
            'email.unique' => 'این ایمیل قبلا ثبت شده است.',

            'birth_day.between' => 'روز تولد باید بین ۱ تا ۳۱ باشد.',

            'birth_month.between' => 'ماه تولد باید بین ۱ تا ۱۲ باشد.',

            'birth_year.min' => 'سال تولد باید حداقل ۱۳۰۰ باشد.',
            'birth_year.max' => 'سال تولد باید حداکثر ' . date('Y') . ' باشد.',

            'national_code.required' => 'وارد کردن کد ملی الزامی است.',
            'national_code.size' => 'کد ملی باید ۱۰ رقمی باشد.',
            'national_code.regex' => 'فرمت کد ملی معتبر نیست (فقط اعداد مجاز است).',
            'national_code.unique' => 'این کد ملی قبلا ثبت شده است.',

            'address.max' => 'آدرس نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'postal_code.regex' => 'فرمت کد پستی معتبر نیست (باید ۱۰ رقمی باشد).',

            'national_card_image_id.exists' => 'تصویر کارت ملی معتبر نیست.',

            'educations.array' => 'فرمت سوابق تحصیلی نامعتبر است.',

            'educations.*.university.required' => 'نام دانشگاه یا موسسه الزامی است.',
            'educations.*.university.max' => 'نام دانشگاه نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'educations.*.city.required' => 'شهر محل تحصیل الزامی است.',
            'educations.*.city.max' => 'شهر محل تحصیل نباید بیشتر از ۱۰۰ کاراکتر باشد.',

            'educations.*.field_of_study.required' => 'رشته تحصیلی الزامی است.',
            'educations.*.field_of_study.max' => 'رشته تحصیلی نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'educations.*.degree.required' => 'انتخاب مقطع تحصیلی الزامی است.',
            'educations.*.degree.in' => 'مقطع تحصیلی انتخاب شده معتبر نیست.',

            'educations.*.start_year.required' => 'سال شروع تحصیل الزامی است.',
            'educations.*.start_year.min' => 'سال شروع باید حداقل ۱۳۰۰ باشد.',
            'educations.*.start_year.max' => 'سال شروع نمی‌تواند از ' . (date('Y')) . ' بیشتر باشد.',

            'educations.*.start_month.required' => 'ماه شروع تحصیل الزامی است.',
            'educations.*.start_month.between' => 'ماه شروع باید بین ۱ تا ۱۲ باشد.',

            'educations.*.end_year.required_if' => 'سال پایان تحصیل الزامی است مگر در صورت در حال تحصیل بودن.',
            'educations.*.end_year.min' => 'سال پایان باید حداقل ۱۳۰۰ باشد.',
            'educations.*.end_year.max' => 'سال پایان نمی‌تواند از ' . (date('Y')) . ' بیشتر باشد.',
            'educations.*.end_year.gte' => 'سال پایان باید مساوی یا بزرگتر از سال شروع باشد.',

            'educations.*.end_month.required_if' => 'ماه پایان تحصیل الزامی است مگر در صورت در حال تحصیل بودن.',
            'educations.*.end_month.between' => 'ماه پایان باید بین ۱ تا ۱۲ باشد.',

            'educations.*.is_studying.boolean' => 'وضعیت تحصیل نامعتبر است.',

            'educations.*.description.max' => 'توضیحات نباید بیشتر از ۱۰۰۰ کاراکتر باشد.',
        ];
    }
}
