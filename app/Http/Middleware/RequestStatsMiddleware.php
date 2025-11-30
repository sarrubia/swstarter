<?php

namespace App\Http\Middleware;

use App\Models\Metrics;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestStatsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // getting the time now before calling the controller
        $startTime = microtime(true);
        $resp = $next($request); // calling next middleware

        // calculating the elapsed time after controller returns the response
        $endTime = microtime(true) - $startTime;
        $milliseconds = intval($endTime * 1000);

        // writing metric in database
        $metrics = new Metrics();
        $metrics->name = "request_timing";
        $metrics->value = $milliseconds;
        $metrics->save();

        // returning response to continue the request life cycle
        return $resp;
    }
}
