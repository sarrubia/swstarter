<?php

namespace App\Services;

use App\Models\ComputedStats;

class StatsService
{

    public function __construct() {}

    /**
     * Fetch from DB all the metrics and returns a formated value
     * @return array
     */
    public function getCalculatedStats(): array {
        $data = [];
        foreach (ComputedStats::all() as $measure) {
            // the format value at this line it's not production ready. Maybe a field TYPE is required to
            // identify the metric data type and apply specific value formater per metric type
            $data[$measure->name] = number_format((float)$measure->value, 2, '.', '');
        };

        return $data;
    }
}
