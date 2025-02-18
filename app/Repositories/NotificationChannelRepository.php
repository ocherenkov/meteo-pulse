<?php

namespace App\Repositories;

use App\Enums\NotificationChannelType;
use App\Models\NotificationChannel;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class NotificationChannelRepository
{
    public function getUserChannels(User $user): Collection
    {
        return NotificationChannel::query()->where('user_id', $user->id)->get(['channel', 'value']);
    }

    public function upsertChannel(User $user, NotificationChannelType $channel, string $value): ?NotificationChannel
    {
        return NotificationChannel::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'channel' => $channel,
            ],
            [
                'value' => $value,
            ]
        );
    }

    public function removeChannel(User $user, NotificationChannelType $channel): int|false
    {
        if ($this->hasOnlyOneChannel($user)) {
            return false;
        }

        return $user->notificationChannels()
            ->where('channel', $channel)
            ->delete();
    }

    private function hasOnlyOneChannel(User $user): bool
    {
        return $user->notificationChannels()->count() <= 1;
    }
}
