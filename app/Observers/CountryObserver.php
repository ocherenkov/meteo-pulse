<?php

namespace App\Observers;

use App\Models\Country;
use App\Services\CityService;

class CountryObserver
{
    public function __construct(private readonly CityService $cityService) {}
    /**
     * Handle the Country "created" event.
     */
    public function created(Country $country): void
    {
        $this->cityService->clearCache($country);
    }

    /**
     * Handle the Country "updated" event.
     */
    public function updated(Country $country): void
    {
        $this->cityService->clearCache($country);
    }

    /**
     * Handle the Country "deleted" event.
     */
    public function deleted(Country $country): void
    {
        $this->cityService->clearCache($country);
    }

    /**
     * Handle the Country "restored" event.
     */
    public function restored(Country $country): void
    {
        //
    }

    /**
     * Handle the Country "force deleted" event.
     */
    public function forceDeleted(Country $country): void
    {
        //
    }
}
