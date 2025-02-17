<?php

namespace App\Services;

use App\DTO\LoginUserDTO;
use App\DTO\RegisterUserDTO;
use App\Enums\NotificationChannelType;
use App\Models\User;
use App\Repositories\NotificationChannelRepository;
use App\Repositories\UserPreferenceRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

readonly class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPreferenceRepository $userPreferenceRepository,
        private NotificationChannelRepository $notificationChannelRepository,
        private TrackingWeatherParameterService $trackingWeatherParameterService,
        private WeatherService $weatherService
    ) {
    }

    public function register(RegisterUserDTO $dto): User
    {
        if ($this->userRepository->findByEmail($dto->email)) {
            throw ValidationException::withMessages(['email' => __('messages.auth.errors.email_taken')]);
        }

        $user = $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        $userPreference = $this->userPreferenceRepository->addCity($user, $dto->city);

        $this->weatherService->getWeather($userPreference->city_id);

        $this->notificationChannelRepository->upsertChannel($user, NotificationChannelType::EMAIL, $user->email);

        $this->trackingWeatherParameterService->createAllDefaultPreferences($userPreference->id);

        return $user;
    }

    public function login(LoginUserDTO $dto): bool
    {
        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password], $dto->remember)) {
            throw ValidationException::withMessages(['email' => __('messages.auth.errors.invalid_credentials')]);
        }

        return true;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
