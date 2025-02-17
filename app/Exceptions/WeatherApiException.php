<?php

namespace App\Exceptions;

use Exception;

class WeatherApiException extends Exception
{
    public function __construct(
        string $message = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message ?? __('messages.api.errors.general'), $code, $previous);
    }
}
