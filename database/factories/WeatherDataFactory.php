<?php

namespace Database\Factories;

use App\Enums\WeatherParameterType;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeatherDataFactory extends Factory
{
    public function definition(): array
    {
        return [
            'city_id' => City::factory(),
            'data' => [
                WeatherParameterType::PRECIPITATION->value => $this->faker->randomFloat(1, 0, 1),
                WeatherParameterType::UV_INDEX->value => $this->faker->randomFloat(1, 0, 1),
            ],
        ];
    }
}
