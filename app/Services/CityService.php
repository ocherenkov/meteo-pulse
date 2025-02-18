<?php

namespace App\Services;

use App\DTO\GetCityDTO;
use App\Models\City;
use App\Models\Country;
use App\Repositories\CityRepository;
use Illuminate\Support\Collection;

readonly class CityService
{
    public function __construct(private CityRepository $cityRepository)
    {
    }

    /**
     * Returns a collection of countries.
     *
     * The result is cached in the default cache store with the key "countries_list".
     *
     * @return Collection<Country>
     */
    public function getCountries(): Collection
    {
        return cache()->rememberForever('countries_list', function () {
            return $this->cityRepository->getCountries();
        });
    }

    /**
     * Get cities for a given country.
     *
     * The cities are cached for improved performance.
     *
     * @param GetCityDTO $getCityDTO
     * @return Collection<int, City>
     */
    public function getCities(GetCityDTO $getCityDTO): Collection
    {
        return cache()->rememberForever("cities_list_{$getCityDTO->country}", function () use ($getCityDTO) {
            return $this->cityRepository->getCitiesByCountry($getCityDTO->country);
        });
    }

    /**
     * Clears the cache for countries and cities related to the given country.
     *
     * @param Country $country
     */
    public function clearCache(Country $country): void
    {
        cache()->forget('countries_list');
        cache()->forget("cities_list_{$country->id}");
    }
}
