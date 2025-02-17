<?php

namespace App\Console\Commands;

use App\Models\UserPreference;
use App\Services\WeatherService;
use Illuminate\Console\Command;

class UpdateWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update weather data';

    /**
     * Execute the console command.
     */
    public function handle(WeatherService $weatherService): void
    {
        $cities = UserPreference::query()->select('city_id')->distinct()->get(['city_id']);

        foreach ($cities as $city) {
            $weatherService->getWeather($city->city_id);
            $this->info("Updated weather data for city: {$city->city_id}");
        }
    }
}
