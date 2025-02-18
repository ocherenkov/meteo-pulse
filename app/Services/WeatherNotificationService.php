<?php

namespace App\Services;

use App\Enums\WeatherParameterType;
use App\Models\User;
use App\Models\UserPreference;
use App\Notifications\WeatherAlertNotification;

readonly class WeatherNotificationService
{
    public function __construct(
        private WeatherService $weatherService,
        private TrackingWeatherParameterService $trackingWeatherParameterService,
        private NotificationPauseService $notificationPauseService
    ) {
    }

    /**
     * Sends weather notifications to all users.
     *
     * This method loops through all users and their preferences, checks if notifications
     * are paused for the user, if the user has any preferences, if the weather data exists
     * for the user's preferences, and if any weather parameters have exceeded their thresholds.
     * If all conditions are met, a notification is sent to the user.
     */
    public function sendWeatherNotifications(): void
    {
        $users = User::with('notificationChannels')->get();

        foreach ($users as $user) {
            if ($this->notificationPauseService->isNotificationsPaused($user)) {
                continue;
            }

            $userPreferences = $user->preferences;
            if ($userPreferences->isEmpty()) {
                continue;
            }

            $weatherData = $this->getProcessedWeatherData($user);
            if (empty($weatherData)) {
                continue;
            }

            foreach ($userPreferences as $preference) {
                $exceededParameters = $this->getExceededParameters($weatherData, $preference);

                if (!empty($exceededParameters)) {
                    $this->sendNotification($user, $exceededParameters);
                }
            }
        }
    }

    /**
     * Retrieves the weather data for the given user's preferences, processed into a format
     * suitable for sending as a notification.
     *
     * @param User $user
     * @return array
     */
    private function getProcessedWeatherData(User $user): array
    {
        return $this->weatherService->getWeatherForUserPreferences($user)
            ->map(fn($weatherItem) => $this->mapWeatherData($weatherItem))
            ->toArray();
    }

    /**
     * Maps the given weather item data to a format suitable for sending as a notification.
     *
     * @param object $weatherItem
     * @return array
     */
    private function mapWeatherData(object $weatherItem): array
    {
        $parameters = [];
        foreach (WeatherParameterType::cases() as $type) {
            $parameters[$type->value] = $weatherItem->data[$type->value] ?? null;
        }

        return array_merge(['city' => $weatherItem->city->name], $parameters);
    }

    /**
     * Retrieves parameters that have exceeded the given threshold for the given user preference.
     *
     * @param array $weatherData
     * @param UserPreference $preference
     * @return array
     */
    private function getExceededParameters(array $weatherData, UserPreference $preference): array
    {
        $trackingPreferences = $this->trackingWeatherParameterService->getPreferencesForCity($preference->id);
        $exceededParameters = [];

        foreach ($trackingPreferences as $trackingPreference) {
            $type = $trackingPreference->name->value;
            $threshold = $trackingPreference->threshold;

            foreach ($weatherData as $data) {
                if (isset($data[$type]) && $data[$type] >= $threshold) {
                    $exceededParameters[$data['city']]['parameters'][$type] = $data[$type];
                }
            }
        }
        return $exceededParameters;
    }

    /**
     * Sends a weather alert notification to the given user.
     *
     * @param User $user
     * @param array $exceededParameters
     */
    private function sendNotification(User $user, array $exceededParameters): void
    {
        $user->notify(new WeatherAlertNotification($exceededParameters));
    }
}
