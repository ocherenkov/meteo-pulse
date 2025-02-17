<?php

namespace App\Traits;

use Illuminate\Http\RedirectResponse;

trait WithFlashMessages
{
    protected function successResponse(string $route, string $message, array $replace = []): RedirectResponse
    {
        return to_route($route)->with('success', __($message, $replace));
    }

    protected function errorResponse(string $route, string $message, array $replace = []): RedirectResponse
    {
        return to_route($route)->with('error', __($message, $replace));
    }
}
