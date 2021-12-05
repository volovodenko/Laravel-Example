<?php

declare(strict_types = 1);

namespace App\Filters\Public;

use App\Enums\SparePartCondition;
use App\Enums\SparePartIsChecked;
use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class SearchFilter extends BaseFilter
{
    private const ALL_ITEMS = 'ALL';

    private int $minSellerRating;

    private int $maxSellerRating;

    public function __construct()
    {
        parent::__construct();

        $this->minSellerRating = config('settings.seller.rating.min');
        $this->maxSellerRating = config('settings.seller.rating.max');
    }

    /**
     * Return an associative array where key is a method name and value is query alias.
     */
    public function filterMap(): array
    {
        return [
            'text',
            'city',
            'condition',
            'vendorName'       => 'vendor_name',
            'isChecked'        => 'is_checked',
            'sellerRatingFrom' => 'seller_rating_from',
            'sellerRatingTo'   => 'seller_rating_to',
        ];
    }

    public function text($searchText)
    {
        if (!$this->isValidString($searchText)) {
            return;
        }

        $this->query->where(
            fn (Builder $query) => $query
                ->whereRaw('public_name ilike ?', ['%' . mb_strtolower($searchText) . '%'])
                ->orWhereRaw('vendor_code ilike ?', ['%' . mb_strtolower($searchText) . '%'])
        );
    }

    public function vendorName($name)
    {
        if (!$this->isValidString($name)) {
            return;
        }

        $this->query->whereRaw('LOWER(vendor_name) ilike ?', ['%' . mb_strtolower($name) . '%']);
    }

    public function city($city)
    {
        if (!$this->isValidString($city)) {
            return;
        }

        $this->query->whereRaw('LOWER(city) ilike ?', ['%' . mb_strtolower($city) . '%']);
    }

    public function condition($condition)
    {
        if (!$this->isValidString($condition) || self::ALL_ITEMS === $condition || !SparePartCondition::isValidValue($condition)) {
            return;
        }

        $this->query->where('condition', $condition);
    }

    public function isChecked($isChecked)
    {
        if (!$this->isValidString($isChecked) || self::ALL_ITEMS === $isChecked || !SparePartIsChecked::isValidValue($isChecked)) {
            return;
        }

        $this->query->where('is_checked', SparePartIsChecked::YES === $isChecked);
    }

    public function sellerRatingFrom($fromRating)
    {
        if (!$this->isValidRating($fromRating)) {
            return;
        }

        $this->query->whereHas('seller', fn (Builder $query) => $query->where('seller_rating', '>=', $fromRating));
    }

    public function sellerRatingTo($toRating)
    {
        if (!$this->isValidRating($toRating)) {
            return;
        }

        $this->query->whereHas('seller', fn (Builder $query) => $query->where('seller_rating', '<=', $toRating));
    }

    private function isValidRating($rating): bool
    {
        return is_int(+$rating) && +$rating >= $this->minSellerRating && +$rating <= $this->maxSellerRating;
    }
}
