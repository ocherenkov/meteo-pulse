@extends('layouts.home')
@section('content')
    <main class="text-center form-signin">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('auth.register') }}" method="post">
                    @csrf
                    <h1 class="h3 mb-2 fw-normal text-primary">{{ __('messages.auth.form.welcome_register') }}</h1>
                    <p class="text-muted mb-4">{{ __('messages.auth.form.subtitle_register') }}</p>

                    <div class="form-floating mb-3">
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               placeholder="{{ __('messages.auth.form.name_placeholder') }}"
                               value="{{ old('name') }}">
                        <label for="name">{{ __('messages.auth.form.name') }}</label>
                        @error('name')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>

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

                    <div class="form-floating mb-3">
                        <input type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password-confirmation" 
                               name="password_confirmation" 
                               placeholder="{{ __('messages.auth.form.password_confirm_placeholder') }}">
                        <label for="password-confirmation">{{ __('messages.auth.form.password_confirm') }}</label>
                        @error('password_confirmation')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select @error('country') is-invalid @enderror" 
                                id="countrySelect" 
                                name="country">
                            <option selected disabled value="">{{ __('messages.auth.form.country_placeholder') }}</option>
                        </select>
                        <label for="countrySelect">{{ __('messages.auth.form.country') }}</label>
                        @error('country')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-4">
                        <select class="form-select @error('city') is-invalid @enderror" 
                                id="citySelect" 
                                name="city" 
                                disabled>
                            <option selected disabled value="">{{ __('messages.auth.form.city_placeholder') }}</option>
                        </select>
                        <label for="citySelect">{{ __('messages.auth.form.city') }}</label>
                        @error('city')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="w-100 btn btn-lg btn-primary mb-3" type="submit">
                        {{ __('messages.auth.register_link') }}
                    </button>

                    <p class="text-muted mb-0">
                        {{ __('messages.auth.has_account') }}
                        <a href="{{ route('auth.login-form') }}" class="text-decoration-none">{{ __('messages.auth.login_link') }}</a>
                    </p>
                </form>
            </div>
        </div>
    </main>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const countrySelect = document.getElementById('countrySelect');
            const citySelect = document.getElementById('citySelect');

            fetch('{{ route("get-countries") }}')
                .then(response => response.json())
                .then(data => {
                    data.forEach(country => {
                        const option = new Option(country.name, country.id);
                        countrySelect.add(option);
                    });
                });

            countrySelect.addEventListener('change', function () {
                const country = this.value;
                citySelect.innerHTML = `<option value="">{{ __('messages.profile.placeholders.select_city') }}</option>`;

                if (country) {
                    fetch('{{ route("get-cities-by-country") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({country: country})
                    })
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(city => {
                                const option = new Option(city.name, city.id);
                                citySelect.add(option);
                            });
                            citySelect.disabled = false;
                        });
                }
            });
        });
    </script>
@endpush
