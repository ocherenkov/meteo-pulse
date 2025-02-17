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

    public function upsertChannel(User $user, NotificationChannelType $channel, string $value)
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

    public function removeChannel(User $user, NotificationChannelType $channel)
    {
        // Must have at least one channel
        if (NotificationChannel::query()->where('user_id', $user->id)->count() <= 1) {
            return false;
        }

        return NotificationChannel::query()->where('user_id', $user->id)->where('channel', $channel)->delete();
    }
}
