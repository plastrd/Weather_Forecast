<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class ForecastController extends Controller
{
    //Returns only the actine ones
    public function locations()
    {
        return Location::where('is_active', true)->get();
    }

    public function forecasts(Request $request, Location $location)
{
    $type = $request->query('type', 'daily');

    // The last one
    $fetches = $location->fetches()
        ->where('status', 'success')
        ->when($request->query('provider'), fn ($q, $provider) => $q->where('provider', $provider))
        ->when($request->query('issued_on'), fn ($q, $date) => $q->where('issued_on', $date))
        ->orderByDesc('issued_on')
        ->get()
        ->unique('provider');

    $providers = [];

    foreach ($fetches as $fetch) {
        if ($type === 'hourly') {
            $data = $fetch->hourly()
                ->orderBy('forecast_time')
                ->get(['forecast_time', 'temperature_c', 'precipitation_mm']);
        } else {
            $data = $fetch->daily()
                ->orderBy('forecast_date')
                ->get(['forecast_date', 'temp_min_c', 'temp_max_c', 'precipitation_mm']);
        }

        $providers[$fetch->provider] = [
            'issued_on' => $fetch->issued_on->toDateString(),
            'data'      => $data,
        ];
    }

    return [
        'location'   => $location,
        'type' => $type === 'hourly' ? 'hourly' : 'daily',
        'providers'  => $providers,
    ];
}
}