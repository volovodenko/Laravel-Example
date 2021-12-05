<?php

declare(strict_types = 1);

namespace App\Enums;

/**
 * @method static SparePartCondition NEW()
 * @method static SparePartCondition USED()
 */
class SparePartCondition extends BaseEnum
{
    public const NEW  = 'NEW';
    public const USED = 'USED';

    protected static function getTranslationPrefix(): string
    {
        return 'spare_part.condition.';
    }
}
