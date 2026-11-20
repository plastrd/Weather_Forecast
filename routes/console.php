<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('forecasts:fetch')->dailyAt('06:00')->timezone('Europe/Athens');
