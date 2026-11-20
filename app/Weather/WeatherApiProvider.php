<?php

namespace App\Weather;

use App\Models\Location;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;

class WeatherApiProvider implements WeatherProvider
{
    public function name(): string
    {
        return 'weatherapi';
    }

    public function fetch(Location $location): array
    {
        $data = Http::timeout(10)
            ->retry(3, 500)
            ->get(config('services.weatherapi.base_url') . '/forecast.json', [
                'key'  => config('services.weatherapi.key'),
                'q'    => $location->lat . ',' . $location->lon,
                'days' => 3,
            ])
            ->throw()
            ->json();

        $days = $data['forecast']['forecastday'];

        $hourly = [];
        foreach ($days as $day) {
            foreach ($day['hour'] as $hour) {
                $hourly[] = [
                    'forecast_time'    => CarbonImmutable::createFromTimestampUTC($hour['time_epoch']),
                    'temperature_c'    => $hour['temp_c'] ?? null,
                    'precipitation_mm' => $hour['precip_mm'] ?? null,
                ];
            }
        }

        $daily = [];
        foreach ($days as $day) {
            $daily[] = [
                'forecast_date'    => $day['date'],
                'temp_min_c'       => $day['day']['mintemp_c'] ?? null,
                'temp_max_c'       => $day['day']['maxtemp_c'] ?? null,
                'precipitation_mm' => $day['day']['totalprecip_mm'] ?? null,
            ];
        }

        return ['hourly' => $hourly, 'daily' => $daily];
    }
}