<?php

namespace App\Weather;

use App\Models\Location;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

class OpenMeteoProvider implements WeatherProvider
{
    public function name(): string
    {
        return 'open_meteo';
    }

    public function fetch(Location $location): array
    {
        $data = Http::timeout(10)
            ->retry(3, 500)
            ->get(config('services.open_meteo.base_url') . '/forecast', [
                'latitude'      => $location->lat,
                'longitude'     => $location->lon,
                'hourly'        => 'temperature_2m,precipitation',
                'daily'         => 'temperature_2m_min,temperature_2m_max,precipitation_sum',
                'timezone'      => $location->timezone,
                'forecast_days' => 7,
            ])
            ->throw()
            ->json();

        $hourly = [];
        foreach ($data['hourly']['time'] as $i => $time) {
            $hourly[] = [
                'forecast_time'    => CarbonImmutable::parse($time, $location->timezone)->utc(),
                'temperature_c'    => $data['hourly']['temperature_2m'][$i] ?? null,
                'precipitation_mm' => $data['hourly']['precipitation'][$i] ?? null,
            ];
        }

        $daily = [];
        foreach ($data['daily']['time'] as $i => $date) {
            $daily[] = [
                'forecast_date'    => $date,
                'temp_min_c'       => $data['daily']['temperature_2m_min'][$i] ?? null,
                'temp_max_c'       => $data['daily']['temperature_2m_max'][$i] ?? null,
                'precipitation_mm' => $data['daily']['precipitation_sum'][$i] ?? null,
            ];
        }

        return ['hourly' => $hourly, 'daily' => $daily];
    }
}