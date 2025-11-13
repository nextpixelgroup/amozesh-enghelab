<?php

namespace App\Enums;

enum DegreeEnum: string
{
    case pre_diploma = 'زیردیپلم';
    case diploma = 'دیپلم';
    case associate = 'کاردانی';
    case bachelor = 'کارشناسی';
    case master = 'کارشناسی ارشد';
    case phd = 'دکتری';

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
