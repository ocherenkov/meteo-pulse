<?php

namespace App\Services;

use App\DTO\LoginUserDTO;
use App\DTO\RegisterUserDTO;
use App\Enums\NotificationChannelType;
use App\Exceptions\WeatherApiException;
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

    /**
     * Registers a new user in the system.
     *
     * @param RegisterUserDTO $dto Data for the new user.
     * @return User The newly created user.
     * @throws ValidationException|WeatherApiException If the email is already taken.
     */
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

    /**
     * Login a user into the application.
     *
     * @param LoginUserDTO $dto
     * @return bool
     * @throws ValidationException
     */
    public function login(LoginUserDTO $dto): bool
    {
        if (!Auth::attempt(['email' => $dto->email, 'password' => $dto->password], $dto->remember)) {
            throw ValidationException::withMessages(['email' => __('messages.auth.errors.invalid_credentials')]);
        }

        return true;
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
