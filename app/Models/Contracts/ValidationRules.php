<?php

declare(strict_types = 1);

namespace App\Models\Contracts;

interface ValidationRules
{
    /**
     * Array of validation rules.
     */
    public function validationRules(): array;
}
