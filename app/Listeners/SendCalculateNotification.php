<?php

namespace App\Listeners;

use App\Events\CalculateStatsMetric;
use App\Jobs\ComputeStats;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCalculateNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Event listener that triggers the ComputeStats job.
     */
    public function handle(CalculateStatsMetric $event): void
    {
        ComputeStats::dispatch();
    }
}
