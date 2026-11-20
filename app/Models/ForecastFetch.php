<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastFetch extends Model
{
   protected $fillable = ['location_id', 'provider', 'issued_on', 'status', 'error', 'fetched_at'];

protected $casts = [
    'issued_on'  => 'date',
    'fetched_at' => 'datetime',
];

public function location()
{
    return $this->belongsTo(Location::class);
}

public function hourly()
{
    return $this->hasMany(HourlyForecast::class);
}

public function daily()
{
    return $this->hasMany(DailyForecast::class);
} 
}
