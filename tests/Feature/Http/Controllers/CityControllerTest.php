<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use Tests\Feature\TestCase;

class CityControllerTest extends TestCase
{
    private const GET_COUNTRIES_ROUTE = 'get-countries';
    private const GET_CITIES_ROUTE = 'get-cities-by-country';

    public function testGetCountries(): void
    {
        $country = Country::factory()->create();

        $response = $this->get(route(self::GET_COUNTRIES_ROUTE));

        $this->assertSuccessfulJsonResponse($response, [
            [
                'id' => $country->id,
                'name' => $country->name,
            ],
        ]);
    }

    public function testGetCitiesByCountry(): void
    {
        $country = Country::factory()->create();
        $city = City::factory()->create(['country_id' => $country->id]);

        $response = $this->post(route(self::GET_CITIES_ROUTE), [
            'country' => $country->id,
        ]);

        $this->assertSuccessfulJsonResponse($response, [
            [
                'id' => $city->id,
                'name' => $city->name,
            ],
        ]);
    }

    public function testCannotGetCitiesWithoutCountry(): void
    {
        $response = $this->post(route(self::GET_CITIES_ROUTE), []);

        $response->assertStatus(302)
            ->assertInvalid(['country' => ['The country field is required.']]);
    }

    public function testCannotGetCitiesForNonexistentCountry(): void
    {
        $response = $this->post(route(self::GET_CITIES_ROUTE), ['country' => 999]);

        $response->assertStatus(302)
            ->assertInvalid(['country' => ['The selected country is invalid.']]);
    }

    private function assertSuccessfulJsonResponse($response, array $expectedFragments): void
    {
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name'],
            ]);

        foreach ($expectedFragments as $fragment) {
            $response->assertJsonFragment($fragment);
        }
    }
}
