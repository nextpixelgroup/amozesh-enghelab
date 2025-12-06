<?php

namespace App\Enums;

enum MenuTypeEnum: string
{
    case header = 'هدر';
    case submenu = 'زیرمنو';
    case footer1 = 'فوتر ۱';
    case footer2 = 'فوتر ۲';
    case footer3 = 'فوتر ۳';

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
