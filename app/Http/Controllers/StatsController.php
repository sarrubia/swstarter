<?php

namespace App\Http\Controllers;

use App\Http\Dtos\MetricDto;
use App\Services\StatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * StatsController exposes the API url to get calculated stats
 */
class StatsController extends Controller
{

    /**
     * @var StatsService
     */
    private StatsService $statsService;

    /**
     * @param StatsService $statsService
     */
    public function __construct(StatsService $statsService) {
        $this->statsService = $statsService;
    }

    /**
     * Returns the calculated metrics
     * @param Request $request
     * @return JsonResponse the format output is [{"metric_name":"some_metric_name","metric_value":"some-metric_value"}]
     */
    public function get(Request $request): JsonResponse {

        $requestTimingAverage = [];
        foreach ($this->statsService->getOverallRequestTiming() as $item) {
            $requestTimingAverage[] = MetricDto::fromArray($item)->toArray();
        }

        $requestTimingTop5 = [];
        foreach ($this->statsService->getTop5RequestTiming() as $item) {
            $requestTimingTop5[] = MetricDto::fromArray($item)->toArray();
        }

        $data[] = [
            'average' => $requestTimingAverage,
            'top5' => $requestTimingTop5,
        ];

        return response()->json($data, JsonResponse::HTTP_OK);
    }
}
