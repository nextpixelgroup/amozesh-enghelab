<?php

namespace App\Enums;

enum VideoStatusEnum: string
{

    case pending = 'درانتظار';
    case recording = 'درحال ضبط';
    case pending_process = 'درحال پردازش';
    case completed = 'تکمیل شده';
    case failed = 'ناموفق';
    case review = 'درحال‌بررسی';
    case approved = 'تایید شده';
    case rejected = 'رد شده';

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
