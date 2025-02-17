<?php

namespace Feature\Services;

use App\DTO\WeatherDataDTO;
use App\Exceptions\WeatherApiException;
use App\Models\City;
use App\Models\WeatherData;
use App\Services\WeatherService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    use RefreshDatabase;

    private WeatherService $weatherService;
    private const FAKE_API_KEY = 'test-api-key';
    private const FAKE_API_URL = 'http://api.weatherapi.com/v1/current.json';

    protected function setUp(): void
    {
        parent::setUp();

        $this->weatherService = new WeatherService();

        Config::set('services.weatherapi.key', self::FAKE_API_KEY);
        Config::set('services.weatherapi.url', self::FAKE_API_URL);
    }

    public function testGetWeatherReturnsExistingData(): void
    {
        $city = City::factory()->create();
        $weatherData = WeatherData::factory()->create([
            'city_id' => $city->id,
            'data' => WeatherDataDTO::fromArray([])->toArray(),
        ]);

        $result = $this->weatherService->getWeather($city->id);

        $this->assertEquals($weatherData->data, $result);
    }

    public function testGetWeatherThrowsExceptionOnApiFailure(): void
    {
        $city = City::factory()->create();

        Http::fake([
            self::FAKE_API_URL . '*' => Http::response(null, 500)
        ]);

        $this->expectException(WeatherApiException::class);
        $this->expectExceptionMessage(
            __('messages.api.errors.fetch_failed', ['city' => $city->name, 'message' => ''])
        );

        $this->weatherService->getWeather($city->id);
    }

    public function testGetWeatherThrowsExceptionOnInvalidResponse(): void
    {
        $city = City::factory()->create();

        Http::fake([
            self::FAKE_API_URL . '*' => Http::response(['invalid' => 'response'])
        ]);

        $this->expectException(WeatherApiException::class);
        $this->expectExceptionMessage(__('messages.api.errors.invalid_response'));

        $this->weatherService->getWeather($city->id);
    }

    public function testGetWeatherThrowsExceptionOnNonexistentCity(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->weatherService->getWeather(999);
    }
}
