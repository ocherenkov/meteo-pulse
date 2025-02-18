<?php

namespace App\Services;

use App\DTO\WeatherDataDTO;
use App\Exceptions\WeatherApiException;
use App\Models\City;
use App\Models\User;
use App\Models\UserPreference;
use App\Models\WeatherData;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

readonly class WeatherService
{
    /**
     * Get weather data for all user's preferred cities
     *
     * @param User $user
     * @return Collection<WeatherData>
     */
    public function getWeatherForUserPreferences(User $user): Collection
    {
        $preferences = UserPreference::where('user_id', $user->id)->pluck('city_id');

        return WeatherData::with('city')
            ->whereIn('city_id', $preferences)
            ->recent()
            ->get();
    }

    /**
     * Get weather data for a specific city
     *
     * @param int $cityId
     * @return array
     * @throws WeatherApiException
     */
    public function getWeather(int $cityId): array
    {
        $weather = WeatherData::query()
            ->where('city_id', $cityId)
            ->recent()
            ->first();

        return $weather ? $weather->data : $this->fetchAndStoreWeather($cityId);
    }

    /**
     * Fetch fresh weather data from the API and store it
     *
     * @param int $cityId
     * @return array
     * @throws WeatherApiException
     */
    private function fetchAndStoreWeather(int $cityId): array
    {
        $city = City::findOrFail($cityId);

        try {
            $response = Http::get(config('services.weatherapi.url'), [
                'key' => config('services.weatherapi.key'),
                'q' => $city->name,
            ]);

            if ($response->failed()) {
                throw new WeatherApiException(
                    __('messages.api.errors.fetch_failed', [
                        'city' => $city->name,
                        'message' => $response->body()
                    ])
                );
            }

            $weatherData = $response->json();

            if (!isset($weatherData['current'])) {
                throw new WeatherApiException(__('messages.api.errors.invalid_response'));
            }

            $weatherDTO = WeatherDataDTO::fromArray($weatherData['current']);

            WeatherData::updateOrCreate(
                ['city_id' => $cityId],
                ['data' => $weatherDTO->toArray()]
            );

            return $weatherDTO->toArray();
        } catch (WeatherApiException $e) {
            Log::error('Weather API error', [
                'city_id' => $cityId,
                'city_name' => $city->name,
                'error' => $e->getMessage()
            ]);
            throw $e;
        } catch (Exception $e) {
            Log::error('Unexpected error while fetching weather data', [
                'city_id' => $cityId,
                'city_name' => $city->name,
                'error' => $e->getMessage()
            ]);
            throw new WeatherApiException(__('messages.api.errors.unexpected'), 500, $e);
        }
    }
}
