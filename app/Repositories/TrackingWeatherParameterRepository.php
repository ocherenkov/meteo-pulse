<?php

namespace App\Repositories;

use App\Enums\WeatherParameterType;
use App\Models\TrackingWeatherParameter;

class TrackingWeatherParameterRepository
{
    public function getPreferencesForCity(int $user_preference_id)
    {
        return TrackingWeatherParameter::query()->where('user_preference_id', $user_preference_id)->get();
    }

    public function upsertPreference(int $user_preference_id, WeatherParameterType $name, float $threshold)
    {
        return TrackingWeatherParameter::query()->updateOrCreate(
            ['user_preference_id' => $user_preference_id, 'name' => $name],
            ['threshold' => $threshold]
        );
    }

    public function removePreference(int $user_preference_id, WeatherParameterType $name)
    {
        return TrackingWeatherParameter::query()->where('user_preference_id', $user_preference_id)
            ->where('name', $name)
            ->delete();
    }
}
