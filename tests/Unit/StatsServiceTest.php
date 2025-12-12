<?php

use App\Models\ComputedStats;
use App\Services\StatsService;

beforeEach(function () {
    $this->service = new StatsService();
});

describe('StatsService::getCalculatedStats', function () {
    
    it('returns all computed stats with formatted values', function () {
        ComputedStats::create([
            'name' => 'request_timing_average',
            'value' => 125.5678,
            'uri' => '/api/people'
        ]);
        
        ComputedStats::create([
            'name' => 'request_timing_min',
            'value' => 50.1234,
            'uri' => '/api/films'
        ]);

        $result = $this->service->getCalculatedStats();

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(2)
            ->and($result[0]['value'])->toBe('125.57')
            ->and($result[1]['value'])->toBe('50.12');
    });

    it('returns empty array when no stats exist', function () {
        $result = $this->service->getCalculatedStats();

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('formats values with 2 decimal places', function () {
        ComputedStats::create([
            'name' => 'test_metric',
            'value' => 100,
            'uri' => '/api/test'
        ]);

        $result = $this->service->getCalculatedStats();

        expect($result[0]['value'])->toBe('100.00');
    });

    it('includes name, value and uri in response', function () {
        ComputedStats::create([
            'name' => 'request_timing_average',
            'value' => 125.50,
            'uri' => '/api/people'
        ]);

        $result = $this->service->getCalculatedStats();

        expect($result[0])->toHaveKeys(['name', 'value', 'uri'])
            ->and($result[0]['name'])->toBe('request_timing_average')
            ->and($result[0]['uri'])->toBe('/api/people');
    });
});

describe('StatsService::getTop5RequestTiming', function () {
    
    it('returns top 5 request timings ordered by value descending', function () {
        // Create 7 records
        for ($i = 1; $i <= 7; $i++) {
            ComputedStats::create([
                'name' => 'request_timing_min',
                'value' => $i * 100,
                'uri' => "/api/test/{$i}"
            ]);
        }

        $result = $this->service->getTop5RequestTiming();

        expect($result)->toHaveCount(5)
            ->and($result[0]['value'])->toBe('700.00') // Highest
            ->and($result[4]['value'])->toBe('300.00'); // 5th highest
    });

    it('returns less than 5 if fewer records exist', function () {
        ComputedStats::create([
            'name' => 'request_timing_min',
            'value' => 100,
            'uri' => '/api/test/1'
        ]);
        
        ComputedStats::create([
            'name' => 'request_timing_min',
            'value' => 200,
            'uri' => '/api/test/2'
        ]);

        $result = $this->service->getTop5RequestTiming();

        expect($result)->toHaveCount(2);
    });

    it('only returns request_timing_min metrics', function () {
        ComputedStats::create([
            'name' => 'request_timing_min',
            'value' => 100,
            'uri' => '/api/test/1'
        ]);
        
        ComputedStats::create([
            'name' => 'request_timing_average',
            'value' => 200,
            'uri' => '/api/test/2'
        ]);

        $result = $this->service->getTop5RequestTiming();

        expect($result)->toHaveCount(1)
            ->and($result[0]['name'])->toBe('request_timing_min');
    });

    it('formats values with 2 decimal places', function () {
        ComputedStats::create([
            'name' => 'request_timing_min',
            'value' => 123.456789,
            'uri' => '/api/test'
        ]);

        $result = $this->service->getTop5RequestTiming();

        expect($result[0]['value'])->toBe('123.46');
    });
});

describe('StatsService::getOverallRequestTiming', function () {
    
    it('returns request timing averages ordered by value ascending', function () {
        ComputedStats::create([
            'name' => 'request_timing_average',
            'value' => 300,
            'uri' => '/api/test/3'
        ]);
        
        ComputedStats::create([
            'name' => 'request_timing_average',
            'value' => 100,
            'uri' => '/api/test/1'
        ]);
        
        ComputedStats::create([
            'name' => 'request_timing_average',
            'value' => 200,
            'uri' => '/api/test/2'
        ]);

        $result = $this->service->getOverallRequestTiming();

        expect($result)->toHaveCount(3)
            ->and($result[0]['value'])->toBe('100.00') // Lowest
            ->and($result[1]['value'])->toBe('200.00')
            ->and($result[2]['value'])->toBe('300.00'); // Highest
    });

    it('only returns request_timing_average metrics', function () {
        ComputedStats::create([
            'name' => 'request_timing_average',
            'value' => 100,
            'uri' => '/api/test/1'
        ]);
        
        ComputedStats::create([
            'name' => 'request_timing_min',
            'value' => 200,
            'uri' => '/api/test/2'
        ]);

        $result = $this->service->getOverallRequestTiming();

        expect($result)->toHaveCount(1)
            ->and($result[0]['name'])->toBe('request_timing_average');
    });

    it('returns empty array when no matching stats exist', function () {
        ComputedStats::create([
            'name' => 'other_metric',
            'value' => 100,
            'uri' => '/api/test'
        ]);

        $result = $this->service->getOverallRequestTiming();

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('formats values with 2 decimal places', function () {
        ComputedStats::create([
            'name' => 'request_timing_average',
            'value' => 99.999,
            'uri' => '/api/test'
        ]);

        $result = $this->service->getOverallRequestTiming();

        expect($result[0]['value'])->toBe('100.00');
    });
});
