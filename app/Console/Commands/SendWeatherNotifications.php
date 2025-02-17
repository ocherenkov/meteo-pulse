<?php

namespace App\Console\Commands;

use App\Services\WeatherNotificationService;
use Illuminate\Console\Command;

class SendWeatherNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weather notifications';

    /**
     * Execute the console command.
     */
    public function handle(WeatherNotificationService $weatherNotificationService): void
    {
        $weatherNotificationService->sendWeatherNotifications();
        $this->info('Notifications sent');
    }
}
