<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class TeacherRole implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::find($value);
        if (!$user) {
            $fail('کاربر مورد نظر یافت نشد.');
            return;
        }

        if (!$user->hasRole('teacher')) {
            $fail('کاربر انتخاب شده مدرس نمی‌باشد.');
        }
    }
}
