<?php

namespace App\Http\Controllers;

use App\Enums\NotificationChannelType;
use App\Http\Requests\NotificationChannelRequest;
use App\Http\Requests\PauseNotificationsRequest;
use App\Http\Requests\TrackingWeatherParameterRequest;
use App\Http\Requests\UserPreferenceRequest;
use App\Models\UserPreference;
use App\Services\NotificationChannelService;
use App\Services\NotificationPauseService;
use App\Services\TrackingWeatherParameterService;
use App\Services\UserPreferenceService;
use App\Services\WeatherService;
use App\Traits\WithAuthUser;
use App\Traits\WithFlashMessages;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    use WithAuthUser;
    use WithFlashMessages;

    private const ROUTE_PROFILE = 'profile.index';

    public function __construct(
        private readonly UserPreferenceService $userPreferenceService,
        private readonly NotificationChannelService $notificationChannelService,
        private readonly TrackingWeatherParameterService $trackingWeatherParameterService,
        private readonly WeatherService $weatherService,
        private readonly NotificationPauseService $notificationPauseService
    ) {
    }

    public function index(): View
    {
        $user = $this->getAuthUser();

        return view('profile.index', [
            'preferences' => $this->userPreferenceService->getUserPreferences($user),
            'channels' => $this->notificationChannelService->getUserChannels($user),
            'weatherData' => $this->weatherService->getWeatherForUserPreferences($user),
        ]);
    }

    public function addCity(UserPreferenceRequest $request): RedirectResponse
    {
        $this->userPreferenceService->addCity($this->getAuthUser(), $request->toDTO());

        return $this->successResponse(self::ROUTE_PROFILE, 'messages.profile.messages.city_added');
    }

    public function removeCity(UserPreferenceRequest $request): RedirectResponse
    {
        $this->userPreferenceService->removeCity($this->getAuthUser(), $request->toDTO());

        return $this->successResponse(self::ROUTE_PROFILE, 'messages.profile.messages.city_deleted');
    }

    public function addNotificationChannel(NotificationChannelRequest $request): RedirectResponse
    {
        $this->notificationChannelService->upsertChannel($this->getAuthUser(), $request->toDTO());

        return $this->successResponse(self::ROUTE_PROFILE, 'messages.profile.messages.channel_added');
    }

    public function removeNotificationChannel(NotificationChannelType $channel): RedirectResponse
    {
        if (!$this->notificationChannelService->removeChannel($this->getAuthUser(), $channel)) {
            return $this->errorResponse(self::ROUTE_PROFILE, 'messages.profile.errors.cannot_delete_last_channel');
        }

        return $this->successResponse(self::ROUTE_PROFILE, 'messages.profile.messages.channel_deleted');
    }

    public function updateTrackingParameter(
        UserPreference $userPreference,
        TrackingWeatherParameterRequest $request
    ): RedirectResponse {
        $this->trackingWeatherParameterService->upsertPreference($userPreference->id, $request->toDTO());

        return $this->successResponse(self::ROUTE_PROFILE, 'messages.profile.messages.threshold_updated');
    }

    public function pauseNotifications(PauseNotificationsRequest $request): RedirectResponse
    {
        $dto = $request->toDTO();
        $this->notificationPauseService->pauseNotifications($this->getAuthUser(), $dto);

        return $this->successResponse(
            self::ROUTE_PROFILE,
            'messages.profile.messages.notifications_paused',
            ['hours' => $dto->hours]
        );
    }

    public function resumeNotifications(): RedirectResponse
    {
        $this->notificationPauseService->resumeNotifications($this->getAuthUser());

        return $this->successResponse(self::ROUTE_PROFILE, 'messages.profile.messages.notifications_resumed');
    }
}
