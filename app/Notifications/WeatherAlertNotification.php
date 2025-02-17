<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class WeatherAlertNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $weatherData)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['mail'];

        if ($notifiable->routeNotificationForTelegram()) {
            $channels[] = TelegramChannel::class;
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('messages.notifications.weather.subject'))
            ->view('email.weather-notification', ['name' => $notifiable->name, 'weatherData' => $this->weatherData]);
    }

    /**
     * Get the array representation of the notification.
     *
     */
    public function toTelegram(object $notifiable): TelegramMessage
    {
        $content = $this->formattedTelegramMessage($notifiable->name, $this->weatherData);
        return TelegramMessage::create()
            ->content($content);
    }

    /**
     * Format the weather data for a Telegram message.
     *
     * @param string $name
     * @param array $weatherData
     * @return string
     */
    private function formattedTelegramMessage(string $name, array $weatherData): string
    {
        if (empty($weatherData)) {
            return sprintf(
                "%s\n%s\n\n%s",
                __('messages.notifications.weather.greeting', ['name' => $name]),
                __('messages.notifications.weather.no_data'),
                __('messages.notifications.weather.thanks')
            );
        }

        $message = __('messages.notifications.weather.greeting', ['name' => $name]) . "\n";
        $message .= __('messages.notifications.weather.update_title') . "\n";

        foreach ($weatherData as $city => $weather) {
            if (!isset($weather['parameters']) || empty($weather['parameters'])) {
                continue;
            }

            $message .= "{$city}:\n";

            foreach ($weather['parameters'] as $parameter => $value) {
                $formattedValue = is_float($value) ? number_format($value, 1) : $value;
                $message .= "{$parameter}: {$formattedValue}\n";
            }
        }

        $message .= "\n" . __('messages.notifications.weather.thanks');

        return $message;
    }
}
