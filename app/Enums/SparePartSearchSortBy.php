<?php

declare(strict_types = 1);

namespace App\Enums;

/**
 * @method static SparePartSearchSortBy PRICE()
 * @method static SparePartSearchSortBy SELLER_RATING()
 * @method static SparePartSearchSortBy CONDITION()
 */
final class SparePartSearchSortBy extends BaseEnum
{
    public const PRICE         = 'price';
    public const SELLER_RATING = 'seller_rating';
    public const CONDITION     = 'condition';
}
