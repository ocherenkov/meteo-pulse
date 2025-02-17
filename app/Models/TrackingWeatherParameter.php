<?php

namespace App\Models;

use App\Enums\WeatherParameterType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingWeatherParameter extends Model
{
    use HasFactory;

    protected $fillable = ['user_preference_id', 'name', 'threshold'];

    protected $casts = [
        'name' => WeatherParameterType::class,
    ];

    public function userPreference(): BelongsTo
    {
        return $this->belongsTo(UserPreference::class);
    }
}
