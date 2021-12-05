<?php

declare(strict_types = 1);

namespace App\Enums;

/**
 * @method static SparePartIsChecked YES()
 * @method static SparePartIsChecked NO()
 */
class SparePartIsChecked extends BaseEnum
{
    public const YES = 'YES';
    public const NO  = 'NO';

    protected static function getTranslationPrefix(): string
    {
        return 'spare_part.filter.is_checked.';
    }
}
