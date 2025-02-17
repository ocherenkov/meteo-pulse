<?php

namespace App\Services;

use App\DTO\NotificationChannelDTO;
use App\Enums\NotificationChannelType;
use App\Models\User;
use App\Repositories\NotificationChannelRepository;
use Illuminate\Database\Eloquent\Collection;

readonly class NotificationChannelService
{

    public function __construct(private NotificationChannelRepository $notificationChannelRepository)
    {
    }

    public function getUserChannels(User $user): Collection
    {
        return $this->notificationChannelRepository->getUserChannels($user);
    }

    public function upsertChannel(User $user, NotificationChannelDTO $dto)
    {
        return $this->notificationChannelRepository->upsertChannel($user, $dto->channel, $dto->value);
    }

    public function removeChannel(User $user, NotificationChannelType $channel)
    {
        return $this->notificationChannelRepository->removeChannel($user, $channel);
    }
}
