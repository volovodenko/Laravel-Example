<?php

declare(strict_types = 1);

use App\Models\User;

if (!function_exists('format_to_money')) {
    function format_to_money($value)
    {
        return number_format(round($value / 100, 2), 2, '.', '');
    }
}

if (!function_exists('unique_filename')) {
    function unique_filename(string $prefix, string $extension)
    {
        return str_replace('.', '_', uniqid($prefix, false))
            . \Str::start($extension, '.');
    }
}

if (!function_exists('mb_ucFirst')) {
    function mb_ucFirst(string $value, bool $lower = false)
    {
        return mb_strtoupper(mb_substr($value, 0, 1))
            . (
                $lower
                ? mb_strtolower(mb_substr($value, 1))
                : mb_substr($value, 1)
            );
    }
}
