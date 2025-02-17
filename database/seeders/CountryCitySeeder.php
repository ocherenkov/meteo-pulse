<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CountryCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // TODO refactoring, added full country & city list
    public function run(): void
    {
        $countries = [
            'USA' => ['New York', 'Los Angeles', 'Chicago'],
            'UK' => ['London', 'Manchester', 'Birmingham'],
            'Germany' => ['Berlin', 'Munich', 'Hamburg'],
            'France' => ['Paris', 'Lyon', 'Marseille'],
            'Italy' => ['Rome', 'Milan', 'Naples'],
            'Spain' => ['Madrid', 'Barcelona', 'Valencia'],
            'Portugal' => ['Lisbon', 'Porto', 'Faro'],
            'Netherlands' => ['Amsterdam', 'Rotterdam', 'Utrecht'],
            'Sweden' => ['Stockholm', 'Gothenburg', 'Malmo'],
            'Ukraine' => ['Kyiv', 'Kharkiv', 'Odesa'],
        ];

        foreach ($countries as $countryName => $cities) {
            $country = Country::query()->createOrFirst(['name' => $countryName]);

            foreach ($cities as $city) {
                City::query()->createOrFirst([
                    'name' => $city,
                    'country_id' => $country->id,
                ]);
            }
        }
    }
}
