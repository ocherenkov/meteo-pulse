<?php

namespace App\DTO;

readonly class GetCityDTO
{
    public function __construct(public int $country)
    {
    }

    public static function fromRequest(array $validated): self
    {
        return new self(country: $validated['country']);
    }
}
