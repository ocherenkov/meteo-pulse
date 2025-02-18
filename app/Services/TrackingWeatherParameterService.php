<?php

namespace App\Services;

use App\DTO\TrackingWeatherParameterDTO;
use App\Enums\WeatherParameterType;
use App\Models\TrackingWeatherParameter;
use App\Repositories\TrackingWeatherParameterRepository;
use Illuminate\Database\Eloquent\Collection;

readonly class TrackingWeatherParameterService
{
    public function __construct(private TrackingWeatherParameterRepository $trackingWeatherParameterRepository)
    {
    }

    /**
     * Retrieves all tracking weather parameters for the given user preference.
     *
     * @param int $user_preference_id The ID of the user preference.
     * @return Collection<TrackingWeatherParameter> A collection of tracking weather parameters.
     */
    public function getPreferencesForCity(int $user_preference_id): Collection
    {
        return $this->trackingWeatherParameterRepository->getPreferencesForCity($user_preference_id);
    }

    /**
     * Upserts a tracking weather parameter for the given user preference.
     *
     * @param int $user_preference_id The ID of the user preference.
     * @param TrackingWeatherParameterDTO $dto The data for the tracking weather parameter.
     * @return ?TrackingWeatherParameter The upserted tracking weather parameter, or null if none was created (if the parameter already exists).
     */
    public function upsertPreference(
        int $user_preference_id,
        TrackingWeatherParameterDTO $dto
    ): ?TrackingWeatherParameter {
        return $this->trackingWeatherParameterRepository->upsertPreference(
            $user_preference_id,
            $dto->name,
            $dto->threshold
        );
    }

    /**
     * Removes a tracking weather parameter from the given user preference.
     *
     * @param int $user_preference_id The ID of the user preference.
     * @param WeatherParameterType $name The name of the tracking weather parameter to remove.
     * @return int The number of records deleted.
     */
    public function removePreference(int $user_preference_id, WeatherParameterType $name): int
    {
        return $this->trackingWeatherParameterRepository->removePreference($user_preference_id, $name);
    }

    /**
     * Creates all default tracking weather parameters for the given user preference.
     *
     * @param int $user_preference_id The ID of the user preference.
     */
    public function createAllDefaultPreferences(int $user_preference_id): void
    {
        foreach (WeatherParameterType::cases() as $parameterType) {
            $trackingDTO = new TrackingWeatherParameterDTO($parameterType, 0);

            $this->upsertPreference($user_preference_id, $trackingDTO);
        }
    }
}
