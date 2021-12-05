<?php

declare(strict_types = 1);

namespace App\Enums\Contracts;

interface Enum
{
    public static function keys(): array;

    public static function values(): array;

    public function value(): string;

    public function equals(Enum $enum): bool;

    public function translate(): string;

    public static function except(array | string $values): array;

    public static function getSelectArray(array $onlyKeys = []): array;
}
