<?php

namespace Database\Factories;

use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackingWeatherParameterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_preference_id' => UserPreference::factory(),
            'parameter' => $this->faker->randomElement(['temperature', 'humidity', 'wind_speed', 'pressure']),
            'min_value' => $this->faker->randomFloat(1, -30, 20),
            'max_value' => $this->faker->randomFloat(1, 21, 40),
        ];
    }
}
