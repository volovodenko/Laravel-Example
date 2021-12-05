<?php

declare(strict_types = 1);

namespace App\Enums;

/**
 * @method static OrderStatus ADD_DELIVERY_METHOD_STAGE()
 * @method static OrderStatus ADD_PAYMENT_METHOD_STAGE()
 * @method static OrderStatus NEW()
 * @method static OrderStatus PAID()
 * @method static OrderStatus READY_TO_SHIP()
 * @method static OrderStatus SHIPPED()
 * @method static OrderStatus RECEIVED()
 * @method static OrderStatus CANCELED()
 * @method static OrderStatus REJECTED()
 */
final class OrderStatus extends BaseEnum
{
    public const ADD_DELIVERY_METHOD_STAGE = 'ADD_DELIVERY_METHOD_STAGE';
    public const ADD_PAYMENT_METHOD_STAGE  = 'ADD_PAYMENT_METHOD_STAGE';
    public const NEW                       = 'NEW';
    public const PAID                      = 'PAID';
    public const READY_TO_SHIP             = 'READY_TO_SHIP';
    public const SHIPPED                   = 'SHIPPED';
    public const RECEIVED                  = 'RECEIVED';
    public const CANCELED                  = 'CANCELED';
    public const REJECTED                  = 'REJECTED';

    public function translateDescription(): string
    {
        return trans($this->getDescriptionTranslationPrefix() . $this->value);
    }

    protected static function getTranslationPrefix(): string
    {
        return 'order.status.';
    }

    protected static function getDescriptionTranslationPrefix(): string
    {
        return 'order.status_description.';
    }
}
