<?php

namespace App\Services;

use App\DTO\UserPreferenceDTO;
use App\Models\User;
use App\Repositories\UserPreferenceRepository;
use Illuminate\Database\Eloquent\Collection;

readonly class UserPreferenceService
{

    public function __construct(
        private UserPreferenceRepository $userPreferenceRepository,
        private TrackingWeatherParameterService $trackingWeatherParameterService,
        private WeatherService $weatherService
    ) {
    }

    public function getUserPreferences(User $user): Collection
    {
        return $this->userPreferenceRepository->getUserPreferences($user);
    }

    public function addCity(User $user, UserPreferenceDTO $dto)
    {
        $userPreference = $this->userPreferenceRepository->addCity($user, $dto->city_id);
        $this->trackingWeatherParameterService->createAllDefaultPreferences($userPreference->id);
        $this->weatherService->getWeather($userPreference->city_id);

        return $userPreference;
    }

    public function removeCity(User $user, UserPreferenceDTO $dto)
    {
        return $this->userPreferenceRepository->removeCity($user, $dto->city_id);
    }
}
