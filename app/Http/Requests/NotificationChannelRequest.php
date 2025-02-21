<?php

namespace App\Http\Requests;

use App\DTO\NotificationChannelDTO;
use App\Enums\NotificationChannelType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationChannelRequest extends FormRequest
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
            'channel' => ['required', 'string', Rule::enum(NotificationChannelType::class)],
            'value' => ['required', 'string', 'max:255']
        ];
    }

    public function toDTO(): NotificationChannelDTO
    {
        return NotificationChannelDTO::fromRequest($this->validated());
    }
}
