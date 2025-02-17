<?php

namespace App\DTO;

use App\Enums\WeatherParameterType;

readonly class TrackingWeatherParameterDTO
{
    public function __construct(
        public WeatherParameterType $name,
        public float $threshold
    ) {
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            name: WeatherParameterType::from($validated['name']),
            threshold: $validated['threshold']
        );
    }
}
