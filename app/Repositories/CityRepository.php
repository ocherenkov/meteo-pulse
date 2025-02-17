<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

class CityRepository
{

    public function getCountries(): Collection
    {
        return Country::query()->get(['id', 'name']);
    }

    public function getCitiesByCountry(int $countryId): Collection
    {
        return City::query()->where('country_id', $countryId)->get(['id', 'name']);
    }
}
