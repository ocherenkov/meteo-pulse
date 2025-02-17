<?php

namespace App\Http\Requests;

use App\DTO\TrackingWeatherParameterDTO;
use App\Enums\WeatherParameterType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrackingWeatherParameterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::enum(WeatherParameterType::class)],
            'threshold' => ['required', 'numeric'],
        ];
    }

    public function toDTO(): TrackingWeatherParameterDTO
    {
        return TrackingWeatherParameterDTO::fromRequest($this->validated());
    }
}
