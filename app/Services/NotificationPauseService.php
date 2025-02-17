<?php

namespace App\Services;

use App\DTO\NotificationPauseDTO;
use App\Models\User;
use App\Repositories\UserRepository;

readonly class NotificationPauseService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function pauseNotifications(User $user, NotificationPauseDTO $dto): void
    {
        $this->userRepository->updateNotificationsPausedUntil(
            $user,
            now()->addHours($dto->hours)
        );
    }

    public function resumeNotifications(User $user): void
    {
        $this->userRepository->updateNotificationsPausedUntil($user, null);
    }

    public function isNotificationsPaused(User $user): bool
    {
        $pausedUntil = $this->userRepository->getNotificationsPausedUntil($user);
        
        if (!$pausedUntil) {
            return false;
        }

        return now()->lt($pausedUntil);
    }

    public function getRemainingPauseTime(User $user): ?int
    {
        $pausedUntil = $this->userRepository->getNotificationsPausedUntil($user);

        if (!$pausedUntil || !$this->isNotificationsPaused($user)) {
            return null;
        }

        return now()->diffInMinutes($pausedUntil);
    }
}
