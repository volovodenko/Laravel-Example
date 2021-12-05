<?php

declare(strict_types = 1);

namespace App\Models\Contracts;

interface File
{
    public function path(): string;

    public function storage(): string;

    public function fileName(): string;
}
