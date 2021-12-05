<?php

declare(strict_types = 1);

namespace App\Repositories\Dto\SparePart;

use App\Models\User;
use App\Repositories\Dto\Contracts\CreateOrUpdateSparePart;

class CreateSparePartByAdminDto extends BaseSparePartDto implements CreateOrUpdateSparePart
{
    public function __construct(
        private User $seller,
        string $article_number,
        string $private_name,
        string $price,
        string $quantity,
        string $vendor_code,
        string $city,
        string $condition,
        ?string $vendor_name = null,
        ?string $public_name = null,
        ?string $weight = null,
        ?string $height = null,
        ?string $width = null,
        ?string $depth = null,
        ?string $description = null,
        ?string $is_vat = null,
        ?string $is_oversized = null,
        ?string $is_checked = null,
        ?array $photos = null,
    ) {
        parent::__construct(
            $article_number,
            $private_name,
            $price,
            $quantity,
            $vendor_code,
            $city,
            $condition,
            $vendor_name,
            $public_name,
            $weight,
            $height,
            $width,
            $depth,
            $description,
            $is_vat,
            $is_oversized,
            $is_checked,
            $photos,
        );
    }

    public function seller(): User
    {
        return $this->seller;
    }
}
