<?php

declare(strict_types = 1);

namespace App\Repositories\Dto\User;

class UserDto
{
    public function __construct(
        private string $email,
        private int $default_commission,
        private int $vip_commission,
        private ?string $password = null,
        private ?string $emailVerifiedAt = null,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'email'              => $this->email,
            'default_commission' => $this->default_commission,
            'vip_commission'     => $this->vip_commission,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        if ($this->emailVerifiedAt) {
            $data['email_verified_at'] = $this->emailVerifiedAt;
        }

        return $data;
    }
}
