<?php

namespace App\Enums;

enum QuestionTypeEnum: string
{
    case multipleOption = 'چند گزینه‌ای';
    case trueFalse = 'درست و غلط';
    case shortAnswer = 'پاسخ کوتاه';

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
