<?php

use App\Enums\GenderEnum;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Str;

if (!function_exists('enumNames')) {
    function enumNames($enumCase)
    {
        return collect($enumCase)->pluck('name')->toArray();
    }
}

if (!function_exists('enumFormated')) {
    function enumFormated($enumCase)
    {
        return collect($enumCase)->map(function ($item) {
            return [
                'title' => $item->value,
                'value' => $item->name,
            ];
        });
    }
}

if (!function_exists('sendJson')) {
    function sendJson($status = 'success', $message = '', $data = null)
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($result)->header('Content-type', 'application/json');
    }
}

if (!function_exists('redirectMessage')) {
    function redirectMessage($status, $message = null, $data = null, $redirect = null)
    {
        if ($status == 'success') {
            return $redirect === null
                ? back()->with($status, $message)
                : redirect()->intended($redirect)->with($status, $message);
        } else {
            return $redirect === null
                ? back()->withErrors($message)
                : redirect()->intended($redirect)->with($status, $message);
        }

    }

}
if (!function_exists('responseJSon')) {
    function responseJSon($status, $message, $data = [], $state = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $state);

    }
}
if (!function_exists('convertDigits')) {
    function convertDigits($string)
    {
        if ($string === null)
            return null;
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

        $num = range(0, 9);
        $convertedPersianNums = str_replace($persian, $num, $string);
        $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

        return $englishNumbersOnly;
    }
}

if (!function_exists('isValidDate')) {
    function isValidDate($date)
    {
        // الگوی پذیرش تاریخ به دو فرمت: yyyy/mm/dd و yyyy-mm-dd
        $pattern = '/^(13|14)\d{2}[\/-](0[1-9]|1[0-2])[\/-](0[1-9]|[12][0-9]|3[01])$/';
        // بررسی تطابق با الگو
        if (preg_match($pattern, $date)) {
            // جداسازی اجزای تاریخ برای بررسی صحت آن (مانند ماه‌های 30 و 31 روزه)
            $separator = strpos($date, '/') !== false ? '/' : '-';
            list($year, $month, $day) = explode($separator, $date);

            // بررسی صحت مقادیر سال، ماه و روز
            return checkdate((int)$month, (int)$day, (int)$year);
        }

        return false; // اگر تطابق نداشته باشد
    }
}

if (!function_exists('months')) {
    function months()
    {
        return [
            [
                'title' => 'فروردین',
                'value' => 1,
            ],
            [
                'title' => 'اردیبهشت',
                'value' => 2,
            ],
            [
                'title' => 'خرداد',
                'value' => 3,
            ],
            [
                'title' => 'تیر',
                'value' => 4,
            ],
            [
                'title' => 'مرداد',
                'value' => 5,
            ],
            [
                'title' => 'شهریور',
                'value' => 6,
            ],
            [
                'title' => 'مهر',
                'value' => 7,
            ],
            [
                'title' => 'آبان',
                'value' => 8,
            ],
            [
                'title' => 'آذر',
                'value' => 9,
            ],
            [
                'title' => 'دی',
                'value' => 10,
            ],
            [
                'title' => 'بهمن',
                'value' => 11,
            ],
            [
                'title' => 'اسفند',
                'value' => 12,
            ],
        ];
    }
}

if (!function_exists('dayes')) {
    function days()
    {
        $days = [];
        for ($i = 1; $i <= 31; $i++) {
            $days[] = [
                'title' => $i,
                'value' => $i,
            ];
        }
        return $days;
    }
}

if (!function_exists('hours')) {
    function hours()
    {
        $hours = [];
        for ($i = 0; $i <= 23; $i++) {
            $hours[] = [
                'title' => $i,
                'value' => $i,
            ];
        }
        return $hours;
    }
}


if (!function_exists('minutes')) {
    function minutes()
    {
        $minutes = [];
        for ($i = 0; $i <= 59; $i++) {
            $minutes[] = [
                'title' => $i,
                'value' => $i,
            ];
        }
        return $minutes;
    }
}

if (!function_exists('enumNames')) {
    function enumNames($enumCase)
    {
        return collect($enumCase)->pluck('name')->toArray();
    }
}

if (!function_exists('enumFormated')) {
    function enumFormated($enumCase)
    {
        return collect($enumCase)->map(function ($item) {
            return [
                'label' => $item->value,
                'value' => $item->name,
            ];
        });
    }
}

if (!function_exists('base62Encode')) {
    function base62Encode($data)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($characters);
        $result = '';
        $num = gmp_init(bin2hex($data), 16); // استفاده از gmp برای کار با اعداد بزرگ
        while (gmp_cmp($num, 0) > 0) {
            $remainder = gmp_mod($num, $base);
            $result = $characters[gmp_intval($remainder)] . $result;
            $num = gmp_div_q($num, $base);
        }
        return $result;
    }
}

if (!function_exists('base62Decode')) {
    function base62Decode($data)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base = strlen($characters);
        $num = gmp_init(0, 10); // استفاده از gmp برای مقداردهی اولیه عدد صفر

        // پردازش هر کاراکتر از رشته Base62
        for ($i = 0, $len = strlen($data); $i < $len; $i++) {
            $num = gmp_add(gmp_mul($num, $base), strpos($characters, $data[$i]));
        }
        // تبدیل عدد به باینری
        $hex = gmp_strval($num, 16); // تبدیل عدد به رشته هگزادسیمال
        if (strlen($hex) % 2 != 0) {
            $hex = '0' . $hex; // اضافه کردن صفر برای تبدیل صحیح به باینری
        }

        return hex2bin($hex);
    }
}


if (!function_exists('paymentCrypt')) {
    function paymentCrypt($type, $id)
    {
        $rand = Str::random(4);
        return base62Encode($type . '-' . $rand . '-' . $id);
    }
}

if (!function_exists('classPath')) {
    function classPath($className)
    {
        $className = ucfirst($className);
        return 'App\\Models\\' . $className;
    }
}

if (!function_exists('years')) {
    function years($incremental = false, $startYear = null)
    {
        if ($startYear) {
            $now = $startYear;
        } else {
            $now = verta()->now()->format('Y');
        }
        $years = [];
        if ($incremental) {
            for ($i = $now; $i < $now + 100; $i++) {
                $years[] = [
                    'value' => $i,
                    'title' => $i,
                ];
            }
        } else {
            for ($i = $now; $i > $now - 100; $i--) {
                $years[] = [
                    'value' => $i,
                    'title' => $i,
                ];
            }
        }
        return $years;
    }
}

if (!function_exists('genders')) {
    function genders()
    {
        return collect(GenderEnum::cases())->map(function ($item) {
            return [
                'label' => $item->value,
                'value' => $item->name,
            ];
        });
    }
}

if (!function_exists('removeFirstZeros')) {
    function removeFirstZeros($number)
    {
        return ltrim($number, '0') ?: '0';
    }
}

if (!function_exists('getMimeTypeTitle')) {
    function getMimeTypeTitle($mimeType)
    {
        $types = [
            'image/jpeg' => 'تصویر',
            'image/png' => 'تصویر',
            'image/gif' => 'تصویر',
            'image/webp' => 'تصویر',
            'application/pdf' => 'PDF',
            'application/msword' => 'فایل Word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'فایل Word',
            'application/vnd.ms-excel' => 'فایل Excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'فایل Excel',
            'text/plain' => 'فایل متنی',
            'video/mp4' => 'ویدئو',
            'audio/mpeg' => 'فایل صوتی',
            'application/zip' => 'فایل فشرده',
            'application/x-rar-compressed' => 'فایل فشرده',
        ];

        return $types[$mimeType] ?? 'فرمت ناشناخته';
    }
}

if (!function_exists('formatSize')) {
    function formatSize($byte)
    {
        if ($byte < 1024) {
            return $byte . ' بایت';
        } elseif ($byte < 1048576) { // کمتر از یک مگابایت
            return round($byte / 1024, 2) . ' کیلوبایت';
        } elseif ($byte < 1073741824) { // کمتر از یک گیگابایت
            return round($byte / 1048576, 2) . ' مگابایت';
        } else { // بیشتر از یک گیگابایت
            return round($byte / 1073741824, 2) . ' گیگابایت';
        }
    }
}


if (!function_exists('categories')) {
    function categories($modelType)
    {
        $categories = Category::where('model_type', $modelType)->get()->map(function ($category) {
            return [
                'label' => $category->name,
                'value' => $category->id,
            ];
        });
        return $categories;

    }
}

if (!function_exists('jobTypesObject')) {
    function jobTypesObject($types)
    {
        $data = null;
        if ($types) {
            foreach ($types as $item) {
                $data[] = [
                    'value' => $item->id,
                    'label' => $item->label,
                ];
            }
        }
        return $data;
    }
}

if (!function_exists('perPages')) {
    function perPages()
    {
        return [20, 50, 100, 150, 300, 1000];
    }
}

if (!function_exists('generateRandomDigit')) {
    function generateRandomDigit($len = 10): string
    {
        return str_pad(strval(random_int(0, 9999999999)), $len, '0', STR_PAD_LEFT);
    }
}
if (!function_exists('generateTransactionId')) {
    function generateTransactionId()
    {
        return Str::random(3) . (now()->getTimestampMs() * mt_rand(100, 2000));
    }
}


function makeSlugUnique(string $slug, string $modelClass, int $count = 0): string
{
    $newSlug = $count === 0 ? $slug : $slug . '-' . $count;

    if (class_exists($modelClass)) {
        if ($modelClass::where('slug', $newSlug)->exists()) {
            return makeSlugUnique($slug, $modelClass, $count + 1);
        }
    } else {
        throw new \InvalidArgumentException("Invalid model class: {$modelClass}");
    }

    return $newSlug;
}

function createSlug($string, $separator = '-')
{
    // حذف فاصله‌های اضافی و trim
    $string = trim($string);

    // جایگزینی فاصله با جداکننده
    $string = preg_replace('/[\s]+/u', $separator, $string);

    // حذف کاراکترهای غیرمجاز (به جز حروف فارسی، اعداد، و جداکننده)
    $string = preg_replace('/[^آ-ی۰-۹a-zA-Z0-9\-_]+/u', '', $string);

    // حذف جداکننده‌های تکراری
    $string = preg_replace('/' . preg_quote($separator, '/') . '{2,}/', $separator, $string);

    // حذف جداکننده از ابتدا و انتها
    $string = trim($string, $separator);

    return $string;
}



if (!function_exists('isMobile')) {
    function isMobile(string $mobile): bool
    {
        // Remove any non-digit characters
        $mobile = preg_replace('/[^0-9]/', '', $mobile);

        // Check if the number starts with 09 and is 11 digits long
        return (bool)preg_match('/^09[0-9]{9}$/', $mobile);
    }
}
