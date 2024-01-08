<?php

declare(strict_types=1);

namespace App\Enum;

enum PreferredTheme: string
{
    case Light = 'light';
    case Dark = 'dark';

    public static function isLight(string $theme): bool
    {
        return self::tryFrom($theme) == self::Light;
    }

    public static function isDark(string $theme): bool
    {
        return self::tryFrom($theme) == self::Dark;
    }
}