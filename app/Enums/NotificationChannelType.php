<?php

namespace App\Enums;

enum NotificationChannelType: string
{
    case EMAIL = 'email';
    case TELEGRAM = 'telegram';
}
