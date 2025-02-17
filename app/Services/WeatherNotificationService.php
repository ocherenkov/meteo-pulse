<?php

namespace App\Services;

use App\Enums\WeatherParameterType;
use App\Models\User;
use App\Notifications\WeatherAlertNotification;

readonly class WeatherNotificationService
{
    public function __construct(
        private WeatherService $weatherService,
        private TrackingWeatherParameterService $trackingWeatherParameterService,
        private NotificationPauseService $notificationPauseService
    ) {
    }

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

    private function getProcessedWeatherData(User $user): array
    {
        return $this->weatherService->getWeatherForUserPreferences($user)
            ->map(fn($weatherItem) => $this->mapWeatherData($weatherItem))
            ->toArray();
    }

    private function mapWeatherData(object $weatherItem): array
    {
        $parameters = [];
        foreach (WeatherParameterType::cases() as $type) {
            $parameters[$type->value] = $weatherItem->data[$type->value] ?? null;
        }

        return array_merge(['city' => $weatherItem->city->name], $parameters);
    }

    private function getExceededParameters(array $weatherData, $preference): array
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

    private function sendNotification(User $user, array $exceededParameters): void
    {
        $user->notify(new WeatherAlertNotification($exceededParameters));
    }
}
