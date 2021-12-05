<?php

declare(strict_types = 1);

namespace App\Enums;

/**
 * @method static UserProfileContractType PAPER()
 * @method static UserProfileContractType DIGITAL()
 */
final class UserProfileContractType extends BaseEnum
{
    public const PAPER   = 'PAPER';
    public const DIGITAL = 'DIGITAL';
}
