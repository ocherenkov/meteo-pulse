<?php

namespace App\DTO;

use App\Enums\WeatherParameterType;

readonly class WeatherDataDTO
{
    public function __construct(
        public float $precipitation,
        public float $uvIndex,
        public float $temperature,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            precipitation: $data['precip_mm'] ?? 0.0,
            uvIndex: $data['uv'] ?? 0.0,
            temperature: $data['temp_c'] ?? 0.0
        );
    }

    public function toArray(): array
    {
        return [
            WeatherParameterType::PRECIPITATION->value => $this->precipitation,
            WeatherParameterType::UV_INDEX->value => $this->uvIndex,
            'temp_c' => $this->temperature,
        ];
    }
}
