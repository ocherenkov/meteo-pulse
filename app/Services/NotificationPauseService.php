<?php

namespace App\Services;

use App\DTO\NotificationPauseDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;

readonly class NotificationPauseService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * Pause notifications for the given user.
     *
     * @param User $user The user to pause notifications for.
     * @param NotificationPauseDTO $dto The pause duration in hours.
     * @return void
     */
    public function pauseNotifications(User $user, NotificationPauseDTO $dto): void
    {
        $this->userRepository->updateNotificationsPausedUntil(
            $user,
            now()->addHours($dto->hours)
        );
    }

    /**
     * Resume notifications for the given user.
     *
     * @param User $user
     * @return void
     */
    public function resumeNotifications(User $user): void
    {
        $this->userRepository->updateNotificationsPausedUntil($user, null);
    }

    /**
     * Checks if the user's notifications are paused.
     *
     * @param User $user
     * @return bool
     */
    public function isNotificationsPaused(User $user): bool
    {
        $pausedUntil = $this->userRepository->getNotificationsPausedUntil($user);

        if (!$pausedUntil) {
            return false;
        }

        return now()->lt($pausedUntil);
    }

    /**
     * Returns the remaining time until the notifications are unpaused, or null if they are not paused.
     *
     * @param User $user
     * @return string|null A string in the format 'H:i', or null if the notifications are not paused.
     */
    public function getRemainingPauseTime(User $user): ?string
    {
        $pausedUntil = $this->userRepository->getNotificationsPausedUntil($user);

        if (!$pausedUntil || !$this->isNotificationsPaused($user)) {
            return null;
        }

        return Carbon::createFromTimeString($pausedUntil)->format('H:i');
    }
}
