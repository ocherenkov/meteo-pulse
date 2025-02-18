<?php

namespace App\Services;

use App\DTO\UserPreferenceDTO;
use App\Exceptions\WeatherApiException;
use App\Models\User;
use App\Models\UserPreference;
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

    /**
     * Get user preferences.
     *
     * @param User $user User to fetch preferences for.
     * @return Collection<UserPreference> Collection of user preferences.
     */
    public function getUserPreferences(User $user): Collection
    {
        return $this->userPreferenceRepository->getUserPreferences($user);
    }

    /**
     * Adds a city to user preferences.
     *
     * Also creates default tracking parameters and fetches the latest weather data for the city.
     *
     * @param User $user User to add city preference to.
     * @param UserPreferenceDTO $dto DTO containing city ID to be added.
     * @return UserPreference The newly created or existing user preference.
     * @throws WeatherApiException
     */
    public function addCity(User $user, UserPreferenceDTO $dto): UserPreference
    {
        $userPreference = $this->userPreferenceRepository->addCity($user, $dto->city_id);
        $this->trackingWeatherParameterService->createAllDefaultPreferences($userPreference->id);
        $this->weatherService->getWeather($userPreference->city_id);

        return $userPreference;
    }

    /**
     * Removes a city from user preferences.
     *
     * @param User $user User to remove city preference from.
     * @param UserPreferenceDTO $dto DTO containing city ID to be removed.
     * @return int Number of affected rows.
     */
    public function removeCity(User $user, UserPreferenceDTO $dto): int
    {
        return $this->userPreferenceRepository->removeCity($user, $dto->city_id);
    }
}
