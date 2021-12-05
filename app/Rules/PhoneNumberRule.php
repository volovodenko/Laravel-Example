<?php

declare(strict_types = 1);

namespace App\Rules;

use Illuminate\Validation\Validator;

class PhoneNumberRule
{
    public function validate($attribute, $value, $parameters, Validator $validator): bool
    {
        return (bool) preg_match('/^380[0-9]{9}$/', $value);
    }
}
