<?php

namespace App\Enums;

enum WeatherParameterType: string
{
    case PRECIPITATION = 'precipitation';
    case UV_INDEX = 'uv_index';
}
