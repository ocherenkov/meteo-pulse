<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait WithAuthUser
{
    protected function getAuthUser(): User
    {
        return Auth::user();
    }
}
