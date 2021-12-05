<?php

declare(strict_types = 1);

namespace App\Repositories\Dto\Contracts;

interface CreateOrUpdateSparePart extends SparePartUnique, SparePartFields, SparePartPhotos
{
}
