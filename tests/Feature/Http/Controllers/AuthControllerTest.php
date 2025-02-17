<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\City;
use App\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\Feature\TestCase;

class AuthControllerTest extends TestCase
{
    private const TEST_EMAIL = 'test@example.com';
    private const TEST_PASSWORD = 'password';

    protected function createTestUser(array $overrides = []): User
    {
        return $this->createUser(
            array_merge([
                'email' => self::TEST_EMAIL,
                'password' => bcrypt(self::TEST_PASSWORD),
            ], $overrides)
        );
    }

    protected function login(array $credentials = []): TestResponse
    {
        return $this->post(
            route('auth.login'),
            array_merge([
                'email' => self::TEST_EMAIL,
                'password' => self::TEST_PASSWORD,
            ], $credentials)
        );
    }

    public function testUserCanViewLoginForm(): void
    {
        $this->get(route('auth.login-form'))
            ->assertStatus(200)
            ->assertViewIs('auth.login');
    }

    public function testUserCanLoginWithCorrectCredentials(): void
    {
        $user = $this->createTestUser();

        $this->login()
            ->assertRedirect(route('profile.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function testUserCannotLoginWithIncorrectCredentials(): void
    {
        $this->createTestUser();

        $this->login(['password' => 'wrong-password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function testUserCanLogout(): void
    {
        $this->createAndLoginUser();

        $this->post(route('auth.logout'))
            ->assertRedirect(route('auth.login-form'));

        $this->assertGuest();
    }

    public function testUserCanViewRegisterForm(): void
    {
        $this->get(route('auth.register-form'))
            ->assertStatus(200)
            ->assertViewIs('auth.register');
    }

    public function testUserCanRegister(): void
    {
        $city = City::factory()->create();

        $this->post(route('auth.register'), [
            'name' => 'Test User',
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
            'password_confirmation' => self::TEST_PASSWORD,
            'city' => $city->id,
        ])->assertRedirect(route('auth.login-form'));

        $user = User::where('email', self::TEST_EMAIL)->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => self::TEST_EMAIL,
            'name' => 'Test User',
        ]);
        $this->assertDatabaseHas('user_preferences', [
            'city_id' => $city->id,
            'user_id' => $user->id,
        ]);
    }

    public function testUserCannotRegisterWithExistingEmail(): void
    {
        $city = City::factory()->create();
        $this->createTestUser();

        $this->post(route('auth.register'), [
            'name' => 'Test User',
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
            'password_confirmation' => self::TEST_PASSWORD,
            'city_id' => $city->id,
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('user_preferences', 0);
    }

    public function testUserCannotRegisterWithoutCity(): void
    {
        $this->post(route('auth.register'), [
            'name' => 'Test User',
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
            'password_confirmation' => self::TEST_PASSWORD,
        ])->assertSessionHasErrors('city');

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('user_preferences', 0);
    }

    public function testUserCannotRegisterWithNonexistentCity(): void
    {
        $this->post(route('auth.register'), [
            'name' => 'Test User',
            'email' => self::TEST_EMAIL,
            'password' => self::TEST_PASSWORD,
            'password_confirmation' => self::TEST_PASSWORD,
            'city' => 999,
        ])->assertSessionHasErrors('city');

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('user_preferences', 0);
    }
}
