<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function updateNotificationsPausedUntil(User $user, ?string $pausedUntil): bool
    {
        return $user->update(['notifications_paused_until' => $pausedUntil]);
    }

    public function getNotificationsPausedUntil(User $user): ?string
    {
        return $user->notifications_paused_until;
    }
}
