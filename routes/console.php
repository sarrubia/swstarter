<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ComputeStats;
use \App\Events\CalculateStatsMetric;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// The ComputeStats job can be called directly via scheduler.
//Schedule::job(new ComputeStats())->everyFiveMinutes();

// Dispatching event to run stats calculator job (ComputeStats)
Schedule::call(function () {
        CalculateStatsMetric::dispatch();
    })->everyMinute(); //->everyFiveMinutes()
