<?php

namespace App\Observers;

use App\Models\City;
use App\Services\CityService;

class CityObserver
{

    public function __construct(private readonly CityService $cityService) {}
    /**
     * Handle the City "created" event.
     */
    public function created(City $city): void
    {
        $this->cityService->clearCache($city->country);
    }

    /**
     * Handle the City "updated" event.
     */
    public function updated(City $city): void
    {
        //
    }

    /**
     * Handle the City "deleted" event.
     */
    public function deleted(City $city): void
    {
        //
    }

    /**
     * Handle the City "restored" event.
     */
    public function restored(City $city): void
    {
        //
    }

    /**
     * Handle the City "force deleted" event.
     */
    public function forceDeleted(City $city): void
    {
        //
    }
}
