<?php

namespace App\DTO;

readonly class UserPreferenceDTO
{
    public function __construct(
        public int $city_id
    ) {
    }

    public static function fromRequest(array $validated): self
    {
        return new self(city_id: $validated['city_id']);
    }
}
