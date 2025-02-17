@extends('layouts.home')
@section('content')
    <main class="text-center form-signin">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('auth.login') }}" method="post">
                    @csrf
                    <h1 class="h3 mb-2 fw-normal text-primary">{{ __('messages.auth.form.welcome_login') }}</h1>
                    <p class="text-muted mb-4">{{ __('messages.auth.form.subtitle_login') }}</p>

                    <div class="form-floating mb-3">
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               placeholder="{{ __('messages.auth.form.email_placeholder') }}" 
                               value="{{ old('email') }}">
                        <label for="email">{{ __('messages.auth.form.email') }}</label>
                        @error('email')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="{{ __('messages.auth.form.password_placeholder') }}">
                        <label for="password">{{ __('messages.auth.form.password') }}</label>
                        @error('password')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3 text-start">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                        <label class="form-check-label" for="remember">
                            {{ __('messages.auth.form.remember_me') }}
                        </label>
                    </div>

                    <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">
                        {{ __('messages.auth.login_link') }}
                    </button>

                    <p class="text-muted mb-0">
                        {{ __('messages.auth.no_account') }}
                        <a href="{{ route('auth.register-form') }}" class="text-decoration-none">{{ __('messages.auth.register_link') }}</a>
                    </p>
                </form>
            </div>
        </div>
    </main>
@endsection
