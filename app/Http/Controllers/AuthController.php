<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{

    public function __construct(private readonly AuthService $authService)
    {
    }

    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request): RedirectResponse
    {
        $dto = $request->toDTO();
        $this->authService->register($dto);

        return to_route('auth.login-form')
            ->with('success', __('messages.auth.registration_success'));
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginUserRequest $request): RedirectResponse
    {
        $dto = $request->toDTO();
        $this->authService->login($dto);

        return to_route('profile.index')->with('success', __('messages.auth.login_success'));
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return to_route('auth.login-form')->with('success', __('messages.auth.logout_success'));
    }
}
