<?php

use App\Http\Middleware\RequestStatsMiddleware;
use App\Models\Metrics;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

beforeEach(function () {
    $this->middleware = new RequestStatsMiddleware();
});

describe('RequestStatsMiddleware', function () {
    
    it('saves request timing metric to database', function () {
        $request = Request::create('/api/people?name=luke', 'GET');
        
        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        expect($response->getStatusCode())->toBe(200);
        
        $this->assertDatabaseHas('metrics', [
            'name' => 'request_timing',
            'uri' => '/api/people?name=luke'
        ]);
    });

    it('records timing in milliseconds', function () {
        $request = Request::create('/api/films?title=hope', 'GET');
        
        $this->middleware->handle($request, function ($req) {
            usleep(10000); // Sleep for 10ms
            return new Response('OK', 200);
        });

        $metric = Metrics::where('name', 'request_timing')->first();
        
        expect($metric->value)->toBeGreaterThanOrEqual(10)
            ->and($metric->value)->toBeLessThan(1000); // Should be less than 1 second
    });

    it('stores uri path without query string when query is empty', function () {
        $request = Request::create('/api/people', 'GET');
        
        $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $metric = Metrics::where('name', 'request_timing')->first();
        
        expect($metric->uri)->toBe('/api/people');
    });

    it('stores uri path with query string when query exists', function () {
        $request = Request::create('/api/people?name=luke&age=23', 'GET');
        
        $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $metric = Metrics::where('name', 'request_timing')->first();
        
        expect($metric->uri)->toBe('/api/people?name=luke&age=23');
    });

    it('continues request lifecycle and returns response', function () {
        $request = Request::create('/api/test', 'GET');
        
        $response = $this->middleware->handle($request, function ($req) {
            return new Response('Test Response', 201);
        });

        expect($response->getStatusCode())->toBe(201)
            ->and($response->getContent())->toBe('Test Response');
    });

    it('saves metric even when controller throws exception', function () {
        $request = Request::create('/api/error', 'GET');
        
        try {
            $this->middleware->handle($request, function ($req) {
                throw new \Exception('Controller error');
            });
        } catch (\Exception $e) {
            // Exception is expected
        }

        $this->assertDatabaseHas('metrics', [
            'name' => 'request_timing',
            'uri' => '/api/error'
        ]);
    });

    it('records different timings for different requests', function () {
        $request1 = Request::create('/api/fast', 'GET');
        $request2 = Request::create('/api/slow', 'GET');
        
        $this->middleware->handle($request1, function ($req) {
            return new Response('OK', 200);
        });
        
        $this->middleware->handle($request2, function ($req) {
            usleep(20000); // Sleep for 20ms
            return new Response('OK', 200);
        });

        $metric1 = Metrics::where('uri', '/api/fast')->first();
        $metric2 = Metrics::where('uri', '/api/slow')->first();
        
        expect($metric2->value)->toBeGreaterThan($metric1->value);
    });

    it('stores metric name as request_timing', function () {
        $request = Request::create('/api/test', 'GET');
        
        $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        $metric = Metrics::latest()->first();
        
        expect($metric->name)->toBe('request_timing');
    });
});
