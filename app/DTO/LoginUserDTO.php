<?php

namespace App\DTO;

readonly class LoginUserDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember
    ) {
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            email: $validated['email'],
            password: $validated['password'],
            remember: $validated['remember'] ?? false
        );
    }
}
