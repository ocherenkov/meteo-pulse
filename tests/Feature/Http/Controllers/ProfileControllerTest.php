<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\NotificationChannelType;
use App\Enums\WeatherParameterType;
use App\Models\City;
use App\Models\NotificationChannel;
use App\Models\User;
use App\Models\UserPreference;
use Tests\Feature\TestCase;

class ProfileControllerTest extends TestCase
{
    private const ROUTE_PROFILE_INDEX = 'profile.index';
    private const ROUTE_AUTH_LOGIN = 'auth.login-form';
    private const ROUTE_PROFILE_ADD_CITY = 'profile.add-city';
    private const ROUTE_PROFILE_REMOVE_CITY = 'profile.remove-city';
    private const ROUTE_PROFILE_ADD_CHANNEL = 'profile.add-channel';
    private const ROUTE_PROFILE_REMOVE_CHANNEL = 'profile.remove-channel';
    private const ROUTE_PROFILE_UPDATE_TRACKING_PARAMETER = 'profile.set-tracking-parameter';
    private const ROUTE_PROFILE_PAUSE_NOTIFICATIONS = 'profile.pause-notifications';
    private const ROUTE_PROFILE_RESUME_NOTIFICATIONS = 'profile.resume-notifications';
    private const TEST_EMAIL = 'test@example.com';

    private function loginUserAndReturn(): User
    {
        return $this->createAndLoginUser();
    }

    public function testGuestCannotAccessProfile(): void
    {
        $response = $this->get(route(self::ROUTE_PROFILE_INDEX));
        $response->assertRedirect(route(self::ROUTE_AUTH_LOGIN));
    }

    public function testUserCanViewProfile(): void
    {
        $this->loginUserAndReturn();
        $response = $this->get(route(self::ROUTE_PROFILE_INDEX));
        $response->assertStatus(200)
            ->assertViewIs('profile.index')
            ->assertViewHas(['preferences', 'channels', 'weatherData']);
    }

    public function testUserCanAddCity(): void
    {
        $user = $this->loginUserAndReturn();
        $city = City::factory()->create();

        $response = $this->post(route(self::ROUTE_PROFILE_ADD_CITY), ['city_id' => $city->id]);
        $response->assertRedirect(route(self::ROUTE_PROFILE_INDEX))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'city_id' => $city->id,
        ]);
    }

    public function testUserCanRemoveCity(): void
    {
        $user = $this->loginUserAndReturn();
        $city = City::factory()->create();
        $preference = UserPreference::factory()->create(['user_id' => $user->id, 'city_id' => $city->id]);

        $response = $this->post(route(self::ROUTE_PROFILE_REMOVE_CITY), ['city_id' => $city->id]);
        $response->assertRedirect(route(self::ROUTE_PROFILE_INDEX))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('user_preferences', ['id' => $preference->id]);
    }

    public function testUserCanAddNotificationChannel(): void
    {
        $user = $this->loginUserAndReturn();

        $response = $this->post(route(self::ROUTE_PROFILE_ADD_CHANNEL), [
            'channel' => NotificationChannelType::EMAIL->value,
            'value' => self::TEST_EMAIL,
        ]);
        $response->assertRedirect(route(self::ROUTE_PROFILE_INDEX))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('notification_channels', [
            'user_id' => $user->id,
            'channel' => NotificationChannelType::EMAIL->value,
            'value' => self::TEST_EMAIL,
        ]);
    }

    public function testUserCannotRemoveLastNotificationChannel(): void
    {
        $user = $this->loginUserAndReturn();
        $channel = NotificationChannel::factory()->create(
            ['user_id' => $user->id, 'channel' => NotificationChannelType::EMAIL]
        );

        $response = $this->delete(route(self::ROUTE_PROFILE_REMOVE_CHANNEL, ['channel' => $channel->channel->value]));
        $response->assertRedirect(route(self::ROUTE_PROFILE_INDEX))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('notification_channels', ['id' => $channel->id]);
    }

    public function testUserCanRemoveNotificationChannel(): void
    {
        $user = $this->loginUserAndReturn();
        $channelToRemove = NotificationChannel::factory()->create(
            ['user_id' => $user->id, 'channel' => NotificationChannelType::EMAIL]
        );
        $remainingChannel = NotificationChannel::factory()->create(
            ['user_id' => $user->id, 'channel' => NotificationChannelType::TELEGRAM]
        );

        $response = $this->delete(route(self::ROUTE_PROFILE_REMOVE_CHANNEL, ['channel' => $channelToRemove->channel->value]));
        $response->assertRedirect(route(self::ROUTE_PROFILE_INDEX))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('notification_channels', ['id' => $channelToRemove->id]);
        $this->assertDatabaseHas('notification_channels', ['id' => $remainingChannel->id]);
    }

    public function testUserCanUpdateTrackingParameter(): void
    {
        $user = $this->loginUserAndReturn();
        $preference = UserPreference::factory()->create(['user_id' => $user->id]);

        $response = $this->post(route(self::ROUTE_PROFILE_UPDATE_TRACKING_PARAMETER, ['userPreference' => $preference->id]), [
            'name' => WeatherParameterType::UV_INDEX->value,
            'threshold' => 1,
        ]);
        $response->assertRedirect(route(self::ROUTE_PROFILE_INDEX))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tracking_weather_parameters', [
            'user_preference_id' => $preference->id,
            'name' => WeatherParameterType::UV_INDEX->value,
            'threshold' => 1,
        ]);
    }

    public function testUserCanPauseNotifications(): void
    {
        $user = $this->loginUserAndReturn();
        $hours = 2;

        $response = $this->post(route('profile.pause-notifications'), [
            'hours' => $hours,
        ]);

        $response->assertRedirect(route(self::ROUTE_PROFILE_INDEX))
            ->assertSessionHas('success', __('messages.profile.messages.notifications_paused', ['hours' => $hours]));

        $this->assertNotNull($user->refresh()->notifications_paused_until);
    }

    public function testUserCanResumeNotifications(): void
    {
        $user = $this->loginUserAndReturn();
        $user->update(['notifications_paused_until' => now()->addHour()]);

        $response = $this->post(route(self::ROUTE_PROFILE_RESUME_NOTIFICATIONS));

        $response->assertRedirect(route(self::ROUTE_PROFILE_INDEX))
            ->assertSessionHas('success', __('messages.profile.messages.notifications_resumed'));

        $this->assertNull($user->refresh()->notifications_paused_until);
    }

    public function testGuestCannotPauseNotifications(): void
    {
        $response = $this->post(route(self::ROUTE_PROFILE_PAUSE_NOTIFICATIONS), [
            'hours' => 2,
        ]);

        $response->assertRedirect(route(self::ROUTE_AUTH_LOGIN));
    }

    public function testGuestCannotResumeNotifications(): void
    {
        $response = $this->post(route(self::ROUTE_PROFILE_RESUME_NOTIFICATIONS));

        $response->assertRedirect(route(self::ROUTE_AUTH_LOGIN));
    }

    public function testUserCannotPauseNotificationsWithInvalidHours(): void
    {
        $this->loginUserAndReturn();

        $response = $this->post(route(self::ROUTE_PROFILE_PAUSE_NOTIFICATIONS), [
            'hours' => 25,
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['hours']);
    }
}
