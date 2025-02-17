<?php

namespace App\DTO;

readonly class RegisterUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $city,
    ) {
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            email: $validated['email'],
            password: bcrypt($validated['password']),
            city: $validated['city'],
        );
    }
}
