<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'region', 'country', 'continent', 'lat', 'lon', 'timezone', 'altitude_m', 'is_active'];

    protected $casts = [
        'lat' => 'float',
        'lon' => 'float',
        'altitude_m' => 'integer',
        'is_active' => 'boolean',
    ];
    
    public function fetches()
    {
        return $this->hasMany(ForecastFetch::class);
    }
}