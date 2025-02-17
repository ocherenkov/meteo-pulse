@extends('layouts.home')

@section('content')
    <div class="container-fluid px-0">
        <!-- Hero Section -->
        <div class="bg-primary text-white py-5 mb-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="display-4 fw-bold mb-3">{{ __('messages.home.title') }}</h1>
                        <p class="lead mb-4">{{ __('messages.home.subtitle') }}</p>
                        @guest
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="{{ route('auth.register-form') }}"
                                   class="btn btn-warning btn-lg px-4 me-md-2">{{ __('messages.home.cta.register') }}</a>
                                <a href="{{ route('auth.login-form') }}"
                                   class="btn btn-outline-warning btn-lg px-4">{{ __('messages.home.cta.login') }}</a>
                            </div>
                        @else
                            <a href="{{ route('profile.index') }}"
                               class="btn btn-warning btn-lg px-4">{{ __('messages.home.cta.start') }}</a>
                        @endguest
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset('assets/images/weather-illustration.svg') }}" alt="Weather Illustration"
                             class="img-fluid">
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="container mb-5">
            <div class="row g-4">
                <!-- Real-Time Updates -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-clock text-primary fs-2"></i>
                            </div>
                            <h3 class="fs-4 mb-3">{{ __('messages.home.features.real_time.title') }}</h3>
                            <p class="text-muted mb-0">{{ __('messages.home.features.real_time.description') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Multiple Channels -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-bell text-primary fs-2"></i>
                            </div>
                            <h3 class="fs-4 mb-3">{{ __('messages.home.features.multi_channel.title') }}</h3>
                            <p class="text-muted mb-0">{{ __('messages.home.features.multi_channel.description') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Customizable Alerts -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="bi bi-sliders text-primary fs-2"></i>
                            </div>
                            <h3 class="fs-4 mb-3">{{ __('messages.home.features.customizable.title') }}</h3>
                            <p class="text-muted mb-0">{{ __('messages.home.features.customizable.description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
