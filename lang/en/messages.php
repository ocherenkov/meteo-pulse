<?php

return [
    'home' => [
        'title' => 'Stay Updated with Weather Alerts',
        'subtitle' => 'Get real-time weather notifications for your favorite cities',
        'features' => [
            'real_time' => [
                'title' => 'Real-Time Updates',
                'description' => 'Receive instant notifications about weather changes in your cities',
            ],
            'multi_channel' => [
                'title' => 'Multiple Channels',
                'description' => 'Choose between email and Telegram notifications',
            ],
            'customizable' => [
                'title' => 'Customizable Alerts',
                'description' => 'Set your own thresholds for precipitation, and UV index',
            ],
        ],
        'cta' => [
            'register' => 'Register Now',
            'login' => 'Login',
            'start' => 'Start Getting Weather Alerts',
        ],
    ],
    'auth' => [
        'registration_success' => 'Registration completed successfully. Please sign in.',
        'login_success' => 'You have successfully logged in!',
        'logout_success' => 'You have been logged out.',
        'no_account' => 'Don\'t have an account?',
        'has_account' => 'Already have an account?',
        'register_link' => 'Sign up',
        'login_link' => 'Sign in',
        'form' => [
            'name' => 'Name',
            'name_placeholder' => 'Enter your name',
            'email' => 'Email address',
            'email_placeholder' => 'name@example.com',
            'password' => 'Password',
            'password_placeholder' => 'Enter your password',
            'password_confirm' => 'Confirm Password',
            'password_confirm_placeholder' => 'Confirm your password',
            'country' => 'Country',
            'country_placeholder' => 'Select your country',
            'city' => 'City',
            'city_placeholder' => 'Select your city',
            'remember_me' => 'Remember me',
            'welcome_register' => 'Create your account',
            'welcome_login' => 'Welcome back!',
            'subtitle_register' => 'Get started with weather notifications',
            'subtitle_login' => 'Sign in to continue',
        ],
        'errors' => [
            'email_taken' => 'This email is already registered.',
            'invalid_credentials' => 'Invalid email or password.',
        ],
    ],
    'notifications' => [
        'weather' => [
            'subject' => 'Weather Alert Notification',
            'title' => '🌦️ Weather Notification',
            'greeting' => 'Hi :name,',
            'no_data' => 'No weather data available at the moment.',
            'update_title' => 'Weather update for your cities:',
            'thanks' => 'Thanks for using our service!',
            'stay_updated' => 'Stay updated with the weather! 🌤️',
            'weather_info' => [
                'temperature' => 'Temperature: :value°C',
                'humidity' => 'Humidity: :value%',
                'wind' => 'Wind: :value m/s',
            ],
        ],
    ],
    'navbar' => [
        'home' => 'Home',
        'profile' => 'Profile',
        'logout' => 'Logout',
    ],
    'profile' => [
        'errors' => [
            'cannot_delete_last_channel' => 'Cannot delete the last notification channel.',
        ],
        'titles' => [
            'current_channels' => 'Current Notification Channels:',
            'add_channel' => 'Add/Update Channel:',
            'notification_settings' => 'Notification Settings',
            'your_cities' => 'Your Cities for Notifications:',
            'current_weather' => 'Current Weather in Your Cities:',
            'add_city' => 'Add New City:',
            'weather_parameters' => 'Weather Parameters Settings for Cities:',
            'notification_control' => 'Notification Control',
        ],
        'messages' => [
            'no_channels' => 'You have no notification channels.',
            'no_weather_data' => 'No weather data. It will be updated within an hour.',
            'no_cities' => 'You have not added any cities.',
            'channel_deleted' => 'Channel deleted successfully.',
            'channel_added' => 'Channel added successfully.',
            'city_added' => 'City added successfully.',
            'city_deleted' => 'City deleted successfully.',
            'threshold_updated' => 'Threshold updated successfully.',
            'notifications_paused' => 'Notifications have been paused for :hours hours.',
            'notifications_resumed' => 'Notifications have been resumed.',
            'notifications_paused_until' => 'Notifications are paused until :time',
        ],
        'placeholders' => [
            'contact' => 'Enter email or Telegram ID',
            'select_country' => 'Select a country',
            'select_city' => 'Select a city',
        ],
        'selects' => [
            'country' => 'Country',
            'city' => 'City',
        ],
        'buttons' => [
            'delete' => 'Delete',
            'add' => 'Add',
            'add_city' => 'Add City',
            'update' => 'Update',
            'pause' => 'Pause Notifications',
            'resume' => 'Resume Now',
        ],
        'weather' => [
            'temperature' => 'Temperature: :value°C',
            'precipitation' => 'Precipitation: :value',
            'uv_index' => 'UV Index: :value',
        ],
    ],
    'api' => [
        'errors' => [
            'general' => 'Weather API error occurred',
            'fetch_failed' => 'Failed to fetch weather data for city :city: :message',
            'invalid_response' => 'Invalid response format: missing current weather data',
            'unexpected' => 'Unexpected error while fetching weather data',
        ],
    ],
];
