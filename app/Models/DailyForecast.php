<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyForecast extends Model
{
    protected $fillable = ['forecast_fetch_id', 'forecast_date', 'temp_min_c', 'temp_max_c', 'precipitation_mm'];

protected $casts = [
    'forecast_date'    => 'date:Y-m-d',
    'temp_min_c'       => 'float',
    'temp_max_c'       => 'float',
    'precipitation_mm' => 'float',
];

public function fetch()
{
    return $this->belongsTo(ForecastFetch::class, 'forecast_fetch_id');
}
}
