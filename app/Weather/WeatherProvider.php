<?php

namespace App\Weather;

use App\Models\Location;

interface WeatherProvider
{
    public function name(): string;

    public function fetch(Location $location): array;
}