<?php

declare(strict_types = 1);

namespace App\Enums;

/**
 * @method static UserProfileDocumentType SPARE_PARTS_UPLOAD_XLS()
 * @method static UserProfileDocumentType REGISTRATION_CERTIFICATE()
 * @method static UserProfileDocumentType SYSTEM_FILE()
 */
final class UserProfileDocumentType extends BaseEnum
{
    public const SPARE_PARTS_UPLOAD_XLS   = 'SPARE_PARTS_UPLOAD_XLS';
    public const REGISTRATION_CERTIFICATE = 'REGISTRATION_CERTIFICATE';
    public const SYSTEM_FILE              = 'SYSTEM_FILE';

    protected static function getTranslationPrefix(): string
    {
        return 'profile.document.type.';
    }
}
