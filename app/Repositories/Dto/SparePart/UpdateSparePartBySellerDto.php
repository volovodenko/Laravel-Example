<?php

declare(strict_types = 1);

namespace App\Repositories\Dto\SparePart;

use App\Models\SparePart;
use App\Repositories\Dto\Contracts\UpdateSparePart;

class UpdateSparePartBySellerDto implements UpdateSparePart
{
    private const FIELDS_MAP = [
        'vendor_name'  => 'vendorName',
        'vendor_code'  => 'vendorCode',
        'city'         => 'city',
        'public_name'  => 'publicName',
        'condition'    => 'condition',
        'description'  => 'description',
        'weight'       => 'weight',
        'height'       => 'height',
        'width'        => 'width',
        'depth'        => 'depth',
        'is_vat'       => 'isVat',
        'is_oversized' => 'isOversized',
    ];

    public function __construct(
        private SparePart $sparePart,
        private string $vendor_code,
        private string $city,
        private string $condition,
        private ?string $vendor_name = null,
        private ?string $public_name = null,
        private ?string $weight = null,
        private ?string $height = null,
        private ?string $width = null,
        private ?string $depth = null,
        private ?string $description = null,
        private ?string $is_vat = null,
        private ?string $is_oversized = null,
        private ?array $photos = null,
    ) {
    }

    public function vendorName(): ?string
    {
        return $this->vendor_name;
    }

    public function vendorCode(): string
    {
        return $this->vendor_code;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function publicName(): ?string
    {
        return $this->public_name;
    }

    public function condition(): string
    {
        return $this->condition;
    }

    public function description(): ?string
    {
        return $this->description ? $this->description : null;
    }

    public function weight(): ?int
    {
        return $this->weight ? (int) $this->weight : null;
    }

    public function height(): ?int
    {
        return $this->height ? (int) $this->height : null;
    }

    public function width(): ?int
    {
        return $this->width ? (int) $this->width : null;
    }

    public function depth(): ?int
    {
        return $this->width ? (int) $this->depth : null;
    }

    public function isVat(): bool
    {
        return (bool) $this->is_vat;
    }

    public function isOversized(): bool
    {
        return (bool) $this->is_oversized;
    }

    public function photos(): array
    {
        return $this->photos ?? [];
    }

    public function sparePart(): SparePart
    {
        return $this->sparePart;
    }

    public function fields(): array
    {
        $fields = [];

        foreach (self::FIELDS_MAP as $field => $method) {
            $fields[$field] = $this->{$method}();
        }

        return $fields;
    }
}
