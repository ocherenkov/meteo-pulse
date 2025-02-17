<?php

namespace App\Http\Requests;

use App\DTO\GetCityDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetCityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'country' => ['required', 'integer', Rule::Exists('countries', 'id')],
        ];
    }

    public function toDTO(): GetCityDTO
    {
        return GetCityDTO::fromRequest($this->validated());
    }
}
