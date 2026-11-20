<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HourlyForecast extends Model
{
    protected $fillable = ['forecast_fetch_id', 'forecast_time', 'temperature_c', 'precipitation_mm'];

protected $casts = [
    'forecast_time'    => 'datetime',
    'temperature_c'    => 'float',
    'precipitation_mm' => 'float',
];

public function fetch()
{
    return $this->belongsTo(ForecastFetch::class, 'forecast_fetch_id');
}
}
