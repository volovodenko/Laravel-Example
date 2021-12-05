<?php

declare(strict_types = 1);

namespace App\Filters\Admin;

use App\Enums\SparePartCondition;
use App\Filters\BaseFilter;

class SparePartFilter extends BaseFilter
{
    /**
     * Return an associative array where key is a method name and value is query alias.
     */
    public function filterMap(): array
    {
        return [
            'name',
            'city',
            'condition',
            'oversized',
            'priceFrom'     => 'price_from',
            'priceTo'       => 'price_to',
            'articleNumber' => 'article_number',
            'privateName'   => 'private_name',
            'vendorCode'    => 'vendor_code',
        ];
    }

    public function name($name)
    {
        if (!$this->isValidString($name)) {
            return;
        }

        $this->query->whereHas(
            'sellerProfile',
            fn ($query) => $query->whereRaw(
                "LOWER(concat(first_name, ' ', last_name)) ilike ?",
                ['%' . strtolower($name) . '%']
            )
        );
    }

    public function articleNumber($articleNumber)
    {
        if (!$this->isValidString($articleNumber)) {
            return;
        }

        $this->query->where('article_number', $articleNumber);
    }

    public function vendorCode($code)
    {
        if (!$this->isValidString($code)) {
            return;
        }

        $this->query->where('vendor_code', $code);
    }

    public function privateName($privateName)
    {
        if (!$this->isValidString($privateName)) {
            return;
        }

        $this->query->whereRaw('private_name ilike ?', ['%' . $privateName . '%']);
    }

    public function city($city)
    {
        if (!$this->isValidString($city)) {
            return;
        }

        $this->query->whereRaw('city ilike ?', ['%' . $city . '%']);
    }

    public function condition($condition)
    {
        if (!$this->isValidString($condition) || !SparePartCondition::isValidValue($condition)) {
            return;
        }

        $this->query->where('condition', $condition);
    }

    public function oversized($oversized)
    {
        if (!$this->isValidInt($oversized)) {
            return;
        }

        $this->query->where('is_oversized', '0' !== $oversized);
    }

    public function priceFrom($priceFrom)
    {
        if (!$this->isValidInt($priceFrom) || +$priceFrom < 0) {
            return;
        }

        $this->query->where('price', '>=', +$priceFrom * 100);
    }

    public function priceTo($priceTo)
    {
        if (!$this->isValidInt($priceTo) || +$priceTo < 0) {
            return;
        }

        $this->query->where('price', '<=', +$priceTo * 100);
    }
}
