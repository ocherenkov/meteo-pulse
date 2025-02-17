<?php

namespace App\Http\Controllers;

use App\DTO\GetCityDTO;
use App\Http\Requests\GetCityRequest;
use App\Services\CityService;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function getCountries(CityService $cityService): JsonResponse
    {
        return response()->json($cityService->getCountries());
    }

    public function getCitiesByCountry(GetCityRequest $request, CityService $cityService): JsonResponse
    {
        $dto = $request->toDTO();
        return response()->json($cityService->getCities($dto));
    }
}
