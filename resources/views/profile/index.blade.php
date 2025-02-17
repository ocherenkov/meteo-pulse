@extends('layouts.home')
@section('content')
    <div class="container profile-page">
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
                <h4>{{ __('messages.profile.titles.current_channels') }}</h4>
                <ul class="list-group mb-3">
                    @forelse ($channels as $channel)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ ucfirst($channel->channel->value) }} ({{ $channel->value }})
                            @if (count($channels) > 1)
                                <form action="{{ route('profile.remove-channel', $channel->channel) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger btn-sm">{{ __('messages.profile.buttons.delete') }}</button>
                                </form>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item">{{ __('messages.profile.messages.no_channels') }}</li>
                    @endforelse
                </ul>

                <h4>{{ __('messages.profile.titles.notification_control') }}</h4>
                @if (auth()->user()->notifications_paused_until)
                    <div class="alert alert-info">
                        {{ __('messages.profile.messages.notifications_paused_until', ['time' => auth()->user()->notifications_paused_until->format('H:i')]) }}
                        <form action="{{ route('profile.resume-notifications') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 ms-2">{{ __('messages.profile.buttons.resume') }}</button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('profile.pause-notifications') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="number" name="hours" class="form-control" min="1" max="24" value="2">
                            <span class="input-group-text">hours</span>
                            <button type="submit" class="btn btn-warning">{{ __('messages.profile.buttons.pause') }}</button>
                        </div>
                    </form>
                @endif

                <h4>{{ __('messages.profile.titles.add_channel') }}</h4>
                <form action="{{ route('profile.add-channel') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="channel" class="form-control">
                            <option value="email">Email</option>
                            <option value="telegram">Telegram</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="value" class="form-control"
                               placeholder="{{ __('messages.profile.placeholders.contact') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('messages.profile.buttons.add') }}</button>
                </form>
                <h2>{{ __('messages.profile.titles.notification_settings') }}</h2>
                <h4>{{ __('messages.profile.titles.your_cities') }}</h4>
                <ul class="list-group mb-3">
                    @forelse ($preferences as $preference)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $preference->city->name }}
                            <form action="{{ route('profile.remove-city') }}" method="POST">
                                @csrf
                                <input type="hidden" name="city_id" value="{{ $preference->city->id }}">
                                <button type="submit"
                                        class="btn btn-danger btn-sm">{{ __('messages.profile.buttons.delete') }}</button>
                            </form>
                        </li>
                    @empty
                        <li class="list-group-item">{{ __('messages.profile.messages.no_cities') }}</li>
                    @endforelse
                </ul>

                <h4>{{ __('messages.profile.titles.add_city') }}</h4>
                <form action="{{ route('profile.add-city') }}" method="POST" id="addCityForm">
                    @csrf
                    <div class="mb-3">
                        <label for="countrySelect"
                               class="form-label">{{ __('messages.profile.selects.country') }}</label>
                        <select name="country" id="countrySelect" class="form-select" required>
                            <option value="">{{ __('messages.profile.placeholders.select_country') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="citySelect" class="form-label">{{ __('messages.profile.selects.city') }}</label>
                        <select name="city_id" id="citySelect" class="form-select" required disabled>
                            <option value="">{{ __('messages.profile.placeholders.select_city') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('messages.profile.buttons.add_city') }}</button>
                </form>
                <h4>{{ __('messages.profile.titles.weather_parameters') }}</h4>
                @foreach ($preferences as $preference)
                    <h5>{{ $preference->city->name }}</h5>
                    <ul class="list-group mb-3">
                        @foreach ($preference->trackingParameters as $parameter)
                            <li class="list-group-item">
                                <form action="{{ route('profile.set-tracking-parameter', $preference) }}" method="POST"
                                      class="d-flex justify-content-between">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ $parameter->name->value }}">

                                    <div>
                                        <strong>{{ ucfirst($parameter->name->value) }}</strong>:
                                        <input type="number" step="0.1" name="threshold"
                                               value="{{ $parameter->threshold }}"
                                               class="form-control d-inline w-25">
                                    </div>

                                    <button type="submit"
                                            class="btn btn-primary btn-sm">{{ __('messages.profile.buttons.update') }}</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
            <div class="col-md-6">
                <h4>{{ __('messages.profile.titles.current_weather') }}</h4>
                @if ($weatherData->isEmpty())
                    <p>{{ __('messages.profile.messages.no_weather_data') }}</p>
                @else
                    <ul class="list-group">
                        @foreach ($weatherData as $weather)
                            <li class="list-group-item">
                                <strong>{{ $weather->city->name }}</strong>:
                                {{ __('messages.profile.weather.temperature', ['value' => $weather->data['temp_c']]) }},
                                {{ __('messages.profile.weather.precipitation', ['value' => $weather->data[App\Enums\WeatherParameterType::PRECIPITATION->value]]) }}
                                ,
                                {{ __('messages.profile.weather.uv_index', ['value' => $weather->data[App\Enums\WeatherParameterType::UV_INDEX->value]]) }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
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
