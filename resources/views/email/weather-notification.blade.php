<!DOCTYPE html>
<html>
<head>
    <title>{{ __('messages.notifications.weather.title') }}</title>
</head>
<body>
<p>{{ __('messages.notifications.weather.greeting', ['name' => $name]) }}</p>
<p>{{ __('messages.notifications.weather.update_title') }}</p>
@foreach ($weatherData as $city => $weather)
    <p><strong>{{ $city }}</strong>:
        @foreach($weather['parameters'] as $parameter => $value)
            {{ $parameter }}: {{ $value }},
        @endforeach
    </p>
@endforeach

<p>{{ __('messages.notifications.weather.thanks') }}</p>
</body>
</html>
