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
            $data[] = [
                'name' => $measure->name,
                'value' => number_format((float)$measure->value, 2, '.', ''),
                'uri' => $measure->uri,
            ];
        };

        return $data;
    }

    public function getTop5RequestTiming(): array {

        $top5RequestTiming = ComputedStats::query()
            ->select()
            ->where(['name' => 'request_timing_min'])
            ->orderBy('value', 'desc')
            ->limit(5)
            ->get();

        $data = [];
        foreach ( $top5RequestTiming as $measure) {
            $data[] = [
                'name' => $measure->name,
                'value' => number_format((float)$measure->value, 2, '.', ''),
                'uri' => $measure->uri,
            ];
        };

        return $data;
    }

    public function getOverallRequestTiming(): array {

        $overallRequestTiming = ComputedStats::query()
            ->select()
            ->where(['name' => 'request_timing_average'])
            ->orderBy('value', 'asc')
            ->get();


        $data = [];
        foreach ( $overallRequestTiming as $measure) {
            $data[] = [
                'name' => $measure->name,
                'value' => number_format((float)$measure->value, 2, '.', ''),
                'uri' => $measure->uri,
            ];
        };

        return $data;
    }
}
