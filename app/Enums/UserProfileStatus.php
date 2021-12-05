<?php

declare(strict_types = 1);

namespace App\Enums;

/**
 * @method static UserProfileStatus ACTIVE()
 * @method static UserProfileStatus MODERATION_PENDING()
 * @method static UserProfileStatus REJECTED()
 */
final class UserProfileStatus extends BaseEnum
{
    public const ACTIVE             = 'ACTIVE';
    public const MODERATION_PENDING = 'MODERATION_PENDING';
    public const REJECTED           = 'REJECTED';

    protected static function getTranslationPrefix(): string
    {
        return 'profile.status.many.';
    }

    protected static function getOneTranslationPrefix(): string
    {
        return 'profile.status.one.';
    }

    public function translateOne(): string
    {
        return trans($this->getOneTranslationPrefix() . $this->value);
    }
}
