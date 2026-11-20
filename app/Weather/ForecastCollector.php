<?php

namespace App\Weather;

use App\Models\ForecastFetch;
use App\Models\Location;

class ForecastCollector
{
   public function collect(Location $location, WeatherProvider $provider): void
{
    $key = [
        'location_id' => $location->id,
        'provider'    => $provider->name(),
        'issued_on'   => today()->toDateString(),
    ];

    // if was suscessfully fetched today, skip
    if (ForecastFetch::where($key)->where('status', 'success')->exists()) {
        return;
    }

    try {
        $data = $provider->fetch($location);
    } catch (\Throwable $e) {
        ForecastFetch::updateOrCreate($key, [
            'status' => 'failed', 'error' => $e->getMessage(), 'fetched_at' => now(),
        ]);
        return;
    }

    $fetch = ForecastFetch::updateOrCreate($key, [
        'status' => 'success', 'error' => null, 'fetched_at' => now(),
    ]);

    $fetch->hourly()->createMany($data['hourly']);
    $fetch->daily()->createMany($data['daily']);
}
}