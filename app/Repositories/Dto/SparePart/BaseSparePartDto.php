<?php

declare(strict_types = 1);

namespace App\Repositories\Dto\SparePart;

abstract class BaseSparePartDto
{
    public function __construct(
        private string $article_number,
        private string $private_name,
        private string $price,
        private string $quantity,
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
        private ?string $is_checked = null,
        private ?array $photos = null,
    ) {
    }

    public function articleNumber(): string
    {
        return $this->article_number;
    }

    public function photos(): array
    {
        return $this->photos ?? [];
    }

    public function fields(): array
    {
        return [
            'article_number' => $this->article_number,
            'private_name'   => $this->private_name,
            'price'          => $this->price,
            'quantity'       => $this->quantity,
            'vendor_code'    => $this->vendor_code,
            'city'           => $this->city,
            'condition'      => $this->condition,
            'vendor_name'    => $this->vendor_name,
            'public_name'    => $this->public_name,
            'weight'         => $this->weight,
            'height'         => $this->height,
            'width'          => $this->width,
            'depth'          => $this->depth,
            'description'    => $this->description,
            'is_vat'         => $this->is_vat,
            'is_oversized'   => $this->is_oversized,
            'is_checked'     => $this->is_checked,
        ];
    }
}
