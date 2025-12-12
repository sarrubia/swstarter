<?php

use App\Models\ComputedStats;
use function Pest\Laravel\getJson;

beforeEach(function () {
    // Seed test data for computed stats
    ComputedStats::create([
        'name' => 'request_timing_average',
        'value' => 125.50,
        'uri' => '/api/people'
    ]);
    
    ComputedStats::create([
        'name' => 'request_timing_average',
        'value' => 200.75,
        'uri' => '/api/films'
    ]);
    
    ComputedStats::create([
        'name' => 'request_timing_min',
        'value' => 500.00,
        'uri' => '/api/people/1'
    ]);
    
    ComputedStats::create([
        'name' => 'request_timing_min',
        'value' => 450.25,
        'uri' => '/api/films/1'
    ]);
    
    ComputedStats::create([
        'name' => 'request_timing_min',
        'value' => 600.00,
        'uri' => '/api/people/2'
    ]);
});

describe('GET /api/stats', function () {
    
    it('returns stats with correct structure', function () {
        $response = getJson('/api/stats');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'average' => [
                        '*' => [
                            'name',
                            'value',
                            'uri'
                        ]
                    ],
                    'top5' => [
                        '*' => [
                            'name',
                            'value',
                            'uri'
                        ]
                    ]
                ]
            ]);
    });

    it('returns overall request timing averages ordered by value', function () {
        $response = getJson('/api/stats');
        
        $data = $response->json();
        $averages = $data[0]['average'];
        
        // Should be ordered by value ascending
        expect($averages[0]['value'])->toBe('125.50')
            ->and($averages[1]['value'])->toBe('200.75');
    });

    it('returns top 5 request timings ordered by value descending', function () {
        $response = getJson('/api/stats');
        
        $data = $response->json();
        $top5 = $data[0]['top5'];
        
        // Should be ordered by value descending
        expect($top5[0]['value'])->toBe('600.00')
            ->and($top5[1]['value'])->toBe('500.00')
            ->and($top5[2]['value'])->toBe('450.25');
    });

    it('formats metric values with 2 decimal places', function () {
        $response = getJson('/api/stats');
        
        $data = $response->json();
        $averages = $data[0]['average'];
        
        foreach ($averages as $metric) {
            expect($metric['value'])->toMatch('/^\d+\.\d{2}$/');
        }
    });

    it('returns empty arrays when no stats exist', function () {
        ComputedStats::truncate();
        
        $response = getJson('/api/stats');
        
        $response->assertStatus(200)
            ->assertJson([
                [
                    'average' => [],
                    'top5' => []
                ]
            ]);
    });

    it('limits top 5 to maximum of 5 results', function () {
        // Add more records
        for ($i = 3; $i <= 10; $i++) {
            ComputedStats::create([
                'name' => 'request_timing_min',
                'value' => 100 * $i,
                'uri' => "/api/test/{$i}"
            ]);
        }
        
        $response = getJson('/api/stats');
        
        $data = $response->json();
        $top5 = $data[0]['top5'];
        
        expect(count($top5))->toBeLessThanOrEqual(5);
    });
});
