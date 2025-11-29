<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case pending   = 'درانتظار'; // درانتظار پرداخت
    case initiated = 'درحال پرداخت'; // پرداخت شروع شده و هنوز وریفای نشده
    case paid      = 'پرداخت شده'; // پرداخت انجام شده و وریفای شده
    case failed    = 'ناموفق'; // برگشت ناموفق از درگاه
    case refunded  = 'برگشت'; // برگشت پول به کاربر

    public static function fromKey(string $key): ?self
    {
        $cases = (new \ReflectionEnum(self::class))->getCases();
        foreach ($cases as $case) {
            if ($case->getName() === strtolower($key)) {
                return $case->getValue();
            }
        }
        return null;
    }

    public static function fromValue(string $value): ?self
    {
        $cases = (new \ReflectionEnum(self::class))->getCases();
        foreach ($cases as $case) {
            if ($case->getValue()->value === $value) {
                return $case->getValue();
            }
        }
        return null;
    }
}
