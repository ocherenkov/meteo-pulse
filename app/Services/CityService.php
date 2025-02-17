<?php

namespace App\Services;

use App\DTO\GetCityDTO;
use App\Models\Country;
use App\Repositories\CityRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

readonly class CityService
{
    public function __construct(private CityRepository $cityRepository)
    {
    }

    public function getCountries(): Collection
    {
        return cache()->rememberForever('countries_list', function () {
            return $this->cityRepository->getCountries();
        });
    }

    public function getCities(GetCityDTO $getCityDTO): Collection
    {
        return cache()->rememberForever("cities_list_{$getCityDTO->country}", function () use ($getCityDTO) {
            return $this->cityRepository->getCitiesByCountry($getCityDTO->country);
        });
    }

    public function clearCache(Country $country): void
    {
        Cache::forget('countries_list');
        Cache::forget("cities_list_{$country->id}");
    }
}
