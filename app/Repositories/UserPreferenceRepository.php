<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Collection;

class UserPreferenceRepository
{
    public function getUserPreferences(User $user): Collection
    {
        return UserPreference::query()->with(['city', 'trackingParameters'])->where('user_id', $user->id)->get();
    }

    public function addCity(User $user, int $city_id)
    {
        return UserPreference::query()->create([
            'user_id' => $user->id,
            'city_id' => $city_id,
        ]);
    }

    public function removeCity(User $user, int $city_id)
    {
        return UserPreference::query()->where(['user_id' => $user->id, 'city_id' => $city_id])->delete();
    }
}
