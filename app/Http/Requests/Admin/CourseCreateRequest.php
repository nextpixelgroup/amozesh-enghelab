<?php

namespace App\Http\Requests\Admin;

use App\Enums\CourseStatusEnum;
use App\Rules\TeacherRole;
use Illuminate\Foundation\Http\FormRequest;

class CourseCreateRequest extends FormRequest
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
            'title'                                                     => 'required|min:3|max:100',
            "category"                                                  => 'nullable|exists:categories,id',
            'teacher'                                                   => ['required', 'exists:users,id', new TeacherRole],
            "description"                                               => 'nullable',
            "requirements"                                              => 'nullable',
            "must_complete_quizzes"                                     => 'required|boolean',
            "status"                                                    => 'required|in:'.implode(',', enumNames(CourseStatusEnum::cases())),
            'seasons'                                                   => 'required|array|min:1',
            'seasons.*.title'                                           => 'required|string|min:3|max:255',
            'seasons.*.description'                                     => 'nullable|string',
            'seasons.*.is_active'                                       => 'required|boolean',
            'seasons.*.lessons'                                         => 'required|array|min:1',
            'seasons.*.lessons.*.title'                                 => 'required|string|min:3|max:255',
            'seasons.*.lessons.*.duration'                              => 'required|numeric',
            'seasons.*.lessons.*.description'                           => 'nullable|string',
            'seasons.*.lessons.*.is_active'                             => 'required|boolean',
            'seasons.*.lessons.*.quiz'                                  => 'nullable|array',
            'seasons.*.lessons.*.quiz.title' => [
                'nullable',
                'string',
                'min:3',
                'max:255',
                'required_if:seasons.*.lessons.*.has_quiz,true'
            ],
            'seasons.*.lessons.*.quiz.description'                      => 'nullable|string',
            'seasons.*.lessons.*.quiz.is_active'                        => [
                'boolean',
                'required_if:seasons.*.lessons.*.has_quiz,true'
            ],
            'seasons.*.lessons.*.quiz.questions'                        => 'required_with:seasons.*.lessons.*.quiz|array|min:1',
            'seasons.*.lessons.*.quiz.questions.*.question'             => [
                'nullable',
                'string',
                'min:3',
                'required_if:seasons.*.lessons.*.has_quiz,true'
            ],
            //'seasons.*.lessons.*.quiz.questions.*.type'                 => 'required|in:true_false,multiple_option,descriptive',
            'seasons.*.lessons.*.quiz.questions.*.explanation'          => 'nullable|string',
            'seasons.*.lessons.*.quiz.questions.*.options'              => 'required_if:seasons.*.lessons.*.quiz.questions.*.type,true_false,multiple_option|array|min:2',
            'seasons.*.lessons.*.quiz.questions.*.options.*.option'     => 'required|string',
            'seasons.*.lessons.*.quiz.questions.*.options.*.is_correct' => 'required|boolean',
            //"image"                 => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        $messages = [
            // Course fields
            'title.required' => 'عنوان دوره الزامی است.',
            'title.min' => 'عنوان دوره باید حداقل ۳ کاراکتر باشد.',
            'title.max' => 'عنوان دوره نمی‌تواند بیشتر از ۱۰۰ کاراکتر باشد.',
            'slug.unique' => 'این آدرس قبلا استفاده شده است.',
            'teacher.required' => 'انتخاب مدرس الزامی است.',
            'teacher.exists' => 'مدرس انتخاب شده معتبر نیست.',
            'published_at.required' => 'تاریخ انتشار الزامی است.',
            'published_at.date' => 'فرمت تاریخ معتبر نیست.',
            'price.required' => 'قیمت دوره الزامی است.',
            'price.numeric' => 'قیمت باید عددی باشد.',
            'price.min' => 'قیمت نمی‌تواند منفی باشد.',
            'must_complete_quizzes.required' => 'وضعیت اجباری بودن آزمون‌ها الزامی است.',
            'must_complete_quizzes.boolean' => 'وضعیت اجباری بودن آزمون‌ها معتبر نیست.',
            'status.required' => 'وضعیت دوره الزامی است.',
            'status.in' => 'وضعیت انتخاب شده معتبر نیست.',

            // Seasons (عمومی)
            'seasons.required' => 'حداقل یک فصل برای دوره الزامی است.',
            'seasons.array' => 'فصل‌ها باید به صورت آرایه ارسال شوند.',
            'seasons.min' => 'حداقل یک فصل برای دوره الزامی است.',
            'seasons.*.is_active.required' => 'وضعیت فعال/غیرفعال کردن فصل الزامی است.',
            'seasons.*.is_active.boolean' => 'وضعیت فعال/غیرفعال کردن فصل معتبر نیست.',

            // Lessons (عمومی)
            'seasons.*.lessons.*.duration' => 'زمان ویدیو را وارد نمایید.',
            'seasons.*.lessons.*.title.min' => 'عنوان درس باید حداقل ۳ کاراکتر باشد.',
            'seasons.*.lessons.*.title.max' => 'عنوان درس نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            'seasons.*.lessons.*.is_active.required' => 'وضعیت فعال/غیرفعال کردن درس الزامی است.',
            'seasons.*.lessons.*.is_active.boolean' => 'وضعیت فعال/غیرفعال کردن درس معتبر نیست.',

            // Quiz
            'seasons.*.lessons.*.quiz.array' => 'آزمون باید به صورت آرایه ارسال شود.',
            'seasons.*.lessons.*.quiz.title.required_if' => 'عنوان آزمون الزامی است.',
            'seasons.*.lessons.*.quiz.title.min' => 'عنوان آزمون باید حداقل ۳ کاراکتر باشد.',
            'seasons.*.lessons.*.quiz.title.max' => 'عنوان آزمون نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.',
            'seasons.*.lessons.*.quiz.is_active.required_with' => 'وضعیت فعال/غیرفعال کردن آزمون الزامی است.',
            'seasons.*.lessons.*.quiz.is_active.boolean' => 'وضعیت فعال/غیرفعال کردن آزمون معتبر نیست.',
            'seasons.*.lessons.*.quiz.questions.required_with' => 'حداقل یک سوال برای آزمون الزامی است.',
            'seasons.*.lessons.*.quiz.questions.array' => 'سوالات باید به صورت آرایه ارسال شوند.',
            'seasons.*.lessons.*.quiz.questions.min' => 'حداقل یک سوال برای آزمون الزامی است.',

            // Questions
            'seasons.*.lessons.*.quiz.questions.*.question.required_if' => 'متن سوال الزامی است.',
            'seasons.*.lessons.*.quiz.questions.*.question.min' => 'متن سوال باید حداقل ۳ کاراکتر باشد.',
            'seasons.*.lessons.*.quiz.questions.*.type.in' => 'نوع سوال معتبر نیست.',

            // Options
            'seasons.*.lessons.*.quiz.questions.*.options.required_if' => 'برای سوالات چندگزینه‌ای و صحیح/غلط گزینه‌ها الزامی هستند.',
            'seasons.*.lessons.*.quiz.questions.*.options.array' => 'گزینه‌ها باید به صورت آرایه ارسال شوند.',
            'seasons.*.lessons.*.quiz.questions.*.options.min' => 'حداقل ۲ گزینه برای سوال الزامی است.',
            'seasons.*.lessons.*.quiz.questions.*.options.*.option.required' => 'متن گزینه الزامی است.',
            'seasons.*.lessons.*.quiz.questions.*.options.*.is_correct.required' => 'وضعیت درستی/نادرستی گزینه الزامی است.',
            'seasons.*.lessons.*.quiz.questions.*.options.*.is_correct.boolean' => 'وضعیت درستی/نادرستی گزینه معتبر نیست.',

            // Image
            'image.required' => 'تصویر دوره الزامی است.',
            'image.image' => 'فایل ارسالی باید تصویر باشد.',
            'image.mimes' => 'فرمت تصویر باید یکی از موارد jpeg, png, jpg, gif, svg باشد.',
            'image.max' => 'حجم تصویر نباید بیشتر از ۲ مگابایت باشد.',
        ];

        $seasons = $this->input('seasons', []);

        foreach ($seasons as $seasonIndex => $season) {
            $seasonNumber = $seasonIndex + 1;

            // پیام‌های اختصاصی فصل
            $messages["seasons.{$seasonIndex}.title.required"] = "عنوان فصل {$seasonNumber} الزامی است.";
            $messages["seasons.{$seasonIndex}.title.min"] = "عنوان فصل {$seasonNumber} باید حداقل ۳ کاراکتر باشد.";
            $messages["seasons.{$seasonIndex}.title.max"] = "عنوان فصل {$seasonNumber} نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.";

            $messages["seasons.{$seasonIndex}.lessons.required"] = "حداقل یک درس برای فصل {$seasonNumber} الزامی است.";
            $messages["seasons.{$seasonIndex}.lessons.array"] = "درس‌های فصل {$seasonNumber} باید به صورت آرایه ارسال شوند.";
            $messages["seasons.{$seasonIndex}.lessons.min"] = "حداقل یک درس برای فصل {$seasonNumber} الزامی است.";

            // پیام‌های اختصاصی درس
            foreach (($season['lessons'] ?? []) as $lessonIndex => $lesson) {
                $lessonNumber = $lessonIndex + 1;

                $messages["seasons.{$seasonIndex}.lessons.{$lessonIndex}.title.required"]
                    = "عنوان درس {$lessonNumber} از فصل {$seasonNumber} الزامی است.";
                $messages["seasons.{$seasonIndex}.lessons.{$lessonIndex}.title.min"]
                    = "عنوان درس {$lessonNumber} از فصل {$seasonNumber} باید حداقل ۳ کاراکتر باشد.";
                $messages["seasons.{$seasonIndex}.lessons.{$lessonIndex}.title.max"]
                    = "عنوان درس {$lessonNumber} از فصل {$seasonNumber} نمی‌تواند بیشتر از ۲۵۵ کاراکتر باشد.";
            }
        }

        return $messages;
    }

}
