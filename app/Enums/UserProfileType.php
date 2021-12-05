<?php

declare(strict_types = 1);

namespace App\Enums;

/**
 * @method static UserProfileType PHYSICAL_PERSON()
 * @method static UserProfileType INDIVIDUAL_ENTREPRENEUR()
 * @method static UserProfileType LEGAL_PERSON()
 */
class UserProfileType extends BaseEnum
{
    public const PHYSICAL_PERSON         = 'PHYSICAL_PERSON';
    public const INDIVIDUAL_ENTREPRENEUR = 'INDIVIDUAL_ENTREPRENEUR';
    public const LEGAL_PERSON            = 'LEGAL_PERSON';

    protected static function getTranslationPrefix(): string
    {
        return 'profile.type.';
    }
}
