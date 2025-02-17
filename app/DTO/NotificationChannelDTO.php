<?php

namespace App\DTO;

use App\Enums\NotificationChannelType;

readonly class NotificationChannelDTO
{
    public function __construct(
        public NotificationChannelType $channel,
        public string $value
    ) {
    }

    public static function fromRequest(array $validated): self
    {
        return new self(
            channel: NotificationChannelType::from($validated['channel']),
            value: $validated['value']
        );
    }
}
