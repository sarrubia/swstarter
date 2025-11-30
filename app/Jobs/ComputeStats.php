<?php

namespace App\Jobs;

use App\Models\ComputedStats;
use App\Models\Metrics;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComputeStats implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *  Note: this code is not production ready. If the Database contains ton of metrics all the hard work
     *  has been delegated to the database which will face performance issues.
     *
     *  This approach has been choosen only for this demo purpose.
     */
    public function handle(): void
    {
        Log::info("Running computed stats");
        Metrics::query()
            ->select(DB::raw('name, AVG(value) as average'))
            ->groupBy('name')
            ->get() // getting all logged metrics and its average
            ->each(function ($metric) { // foreach calculated metric save it in the compute_stats table
                $statName = $metric->name . "_average";
                $record = ComputedStats::find($statName);
                if ($record === null) { // creates if not exists
                    $record = new ComputedStats();
                    $record->name = $metric->name . "_average";
                }
                $record->value = $metric->average;
                $record->save();
            });
    }
}
