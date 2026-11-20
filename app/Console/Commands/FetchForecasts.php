<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Location;
use App\Weather\ForecastCollector;
use App\Weather\OpenMeteoProvider;
use App\Weather\WeatherApiProvider;

#[Signature('forecasts:fetch')]
#[Description('Fetch forecasts from all providers')]
class FetchForecasts extends Command
{
    /**
     * Execute the console command.
     */
    
    public function handle(): void
    {
        $providers = [
            new OpenMeteoProvider(),
            new WeatherApiProvider(),
        ];

        $collector = new ForecastCollector();

        foreach (Location::where('is_active', true)->get() as $location) {
            foreach ($providers as $provider) {
                $this->info("Fetching {$provider->name()} for {$location->name}...");
                $collector->collect($location, $provider);
            }
        }

        $this->info('Done.');
    }
}
