<?php

namespace App\Http\Requests;

use App\DTO\NotificationPauseDTO;
use Illuminate\Foundation\Http\FormRequest;

class PauseNotificationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'hours' => ['required', 'integer', 'min:1', 'max:24'],
        ];
    }

    public function toDTO(): NotificationPauseDTO
    {
        return NotificationPauseDTO::fromRequest($this->validated());
    }
}
