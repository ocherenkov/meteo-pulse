<?php

namespace App\Services;

use App\DTO\TrackingWeatherParameterDTO;
use App\Enums\WeatherParameterType;
use App\Repositories\TrackingWeatherParameterRepository;
use Illuminate\Database\Eloquent\Collection;

readonly class TrackingWeatherParameterService
{
    public function __construct(private TrackingWeatherParameterRepository $trackingWeatherParameterRepository)
    {
    }

    public function getPreferencesForCity(int $user_preference_id): Collection
    {
        return $this->trackingWeatherParameterRepository->getPreferencesForCity($user_preference_id);
    }

    public function upsertPreference(int $user_preference_id, TrackingWeatherParameterDTO $dto)
    {
        return $this->trackingWeatherParameterRepository->upsertPreference(
            $user_preference_id,
            $dto->name,
            $dto->threshold
        );
    }

    public function removePreference(int $user_preference_id, WeatherParameterType $name)
    {
        return $this->trackingWeatherParameterRepository->removePreference($user_preference_id, $name);
    }

    public function createAllDefaultPreferences(int $user_preference_id): void
    {
        foreach (WeatherParameterType::cases() as $parameterType) {
            $trackingDTO = new TrackingWeatherParameterDTO($parameterType, 0);

            $this->upsertPreference($user_preference_id, $trackingDTO);
        }
    }
}
