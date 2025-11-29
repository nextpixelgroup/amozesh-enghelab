<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case pending    = 'درانتظار';
    case paid       = 'پرداخت شده';
    case processing = 'درحال پردازش';
    case completed  = 'تکمیل';
    case canceled   = 'لغو شده';
    case refunded   = 'برگشت';

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
