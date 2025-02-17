<?php

namespace App\DTO;

readonly class NotificationPauseDTO
{
    public function __construct(
        public int $hours
    ) {
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            hours: $validated['hours']
        );
    }
}
