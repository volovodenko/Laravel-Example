<?php

declare(strict_types = 1);

namespace App\Repositories\Dto\Contracts;

use App\Models\SparePart;

interface UpdateSparePart extends SparePartFields, SparePartPhotos
{
    public function sparePart(): SparePart;
}
