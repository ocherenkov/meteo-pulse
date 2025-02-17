<?php

namespace Database\Factories;

use App\Enums\NotificationChannelType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationChannelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'channel' => $this->faker->randomElement(NotificationChannelType::cases()),
            'value' => $this->faker->email,
        ];
    }

    public function email(): self
    {
        return $this->state(fn(array $attributes) => [
            'channel' => NotificationChannelType::EMAIL,
            'value' => $this->faker->email,
        ]);
    }

    public function telegram(): self
    {
        return $this->state(fn(array $attributes) => [
            'channel' => NotificationChannelType::TELEGRAM,
            'value' => $this->faker->userName,
        ]);
    }
}
