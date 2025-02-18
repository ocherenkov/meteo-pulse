<?php

namespace App\Services;

use App\DTO\NotificationChannelDTO;
use App\Enums\NotificationChannelType;
use App\Models\NotificationChannel;
use App\Models\User;
use App\Repositories\NotificationChannelRepository;
use Illuminate\Database\Eloquent\Collection;

readonly class NotificationChannelService
{

    public function __construct(private NotificationChannelRepository $notificationChannelRepository)
    {
    }

    /**
     * Returns the notification channels for the given user.
     *
     * @param User $user
     * @return Collection<NotificationChannel> The notification channels for the user
     */
    public function getUserChannels(User $user): Collection
    {
        return $this->notificationChannelRepository->getUserChannels($user);
    }

    /**
     * Upserts a notification channel for the user.
     *
     * @param User $user
     * @param NotificationChannelDTO $dto
     * @return ?NotificationChannel The upserted notification channel, or null if no channel was added
     */
    public function upsertChannel(User $user, NotificationChannelDTO $dto): ?NotificationChannel
    {
        return $this->notificationChannelRepository->upsertChannel($user, $dto->channel, $dto->value);
    }

    /**
     * Removes a notification channel from the user.
     *
     * @param User $user
     * @param NotificationChannelType $channel
     * @return false|int false if the user has only one channel left, int number of removed channels otherwise
     */
    public function removeChannel(User $user, NotificationChannelType $channel): false|int
    {
        return $this->notificationChannelRepository->removeChannel($user, $channel);
    }
}
