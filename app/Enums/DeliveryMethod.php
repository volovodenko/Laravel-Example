<?php

declare(strict_types = 1);

namespace App\Enums;

/**
 * @method static DeliveryMethod NEW_POST_DEPARTMENT_DELIVERY()
 * @method static DeliveryMethod NEW_POST_COURIER_DELIVERY()
 * @method static DeliveryMethod DELIVERY_DEPARTMENT_DELIVERY()
 * @method static DeliveryMethod DELIVERY_COURIER_DELIVERY()
 * @method static DeliveryMethod AGRO_DB_DELIVERY()
 * @method static DeliveryMethod EXW()
 */
final class DeliveryMethod extends BaseEnum
{
    public const NEW_POST_DEPARTMENT_DELIVERY = 'NEW_POST_DEPARTMENT_DELIVERY';
    public const NEW_POST_COURIER_DELIVERY    = 'NEW_POST_COURIER_DELIVERY';
    public const DELIVERY_DEPARTMENT_DELIVERY = 'DELIVERY_DEPARTMENT_DELIVERY';
    public const DELIVERY_COURIER_DELIVERY    = 'DELIVERY_COURIER_DELIVERY';
    public const AGRO_DB_DELIVERY             = 'AGRO_DB_DELIVERY';
    public const EXW                          = 'EXW';

    protected static function getTranslationPrefix(): string
    {
        return 'order.delivery_method.';
    }
}
