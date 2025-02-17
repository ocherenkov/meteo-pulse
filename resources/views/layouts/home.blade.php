<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/additional.css') }}" rel="stylesheet">
    <title>@yield('title', config('app.name'))</title>
</head>
<body>
    <div class="toast-container position-fixed top-0 end-0 p-3">
        @if (session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif
    </div>
    @auth
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('home')]) href="{{ route('home') }}">
                                <i class="bi bi-house-door"></i> {{ __('messages.navbar.home') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('profile.*')]) href="{{ route('profile.index') }}">
                                <i class="bi bi-person"></i> {{ __('messages.navbar.profile') }}
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent">
                                    <i class="bi bi-box-arrow-right"></i> {{ __('messages.navbar.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth
    
    @yield('content')
    <footer class="footer py-3 bg-light mt-auto">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('js')
</body>
</html>
