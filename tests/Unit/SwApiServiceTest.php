<?php

use App\Services\SwApi\SwApiService;
use App\Services\SwApi\Exceptions\SwApiRequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->service = new SwApiService();
    Cache::flush();
});

describe('SwApiService::getFilms', function () {
    
    it('fetches films by title successfully', function () {
        Http::fake([
            '*' => Http::response([
                'result' => [
                    [
                        'uid' => '1',
                        'properties' => [
                            'title' => 'A New Hope',
                            'director' => 'George Lucas',
                            'producer' => 'Gary Kurtz',
                            'release_date' => '1977-05-25',
                            'opening_crawl' => 'It is a period of civil war...',
                            'episode_id' => 4
                        ]
                    ]
                ]
            ], 200),
        ]);

        $result = $this->service->getFilms('hope');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1)
            ->and($result[0])->toHaveKey('uid', '1')
            ->and($result[0])->toHaveKey('title', 'A New Hope');
    });

    it('throws exception when API request fails', function () {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $this->service->getFilms('hope');
    })->throws(SwApiRequestException::class);

    it('returns empty array when no films match', function () {
        Http::fake([
            '*' => Http::response([
                'result' => []
            ], 200),
        ]);

        $result = $this->service->getFilms('nonexistent');

        expect($result)->toBeArray()->toBeEmpty();
    });

    it('removes SWAPI endpoint from character URLs', function () {
        Http::fake([
            '*' => Http::response([
                'result' => [
                    [
                        'uid' => '1',
                        'properties' => [
                            'title' => 'A New Hope',
                            'characters' => [
                                'https://www.swapi.tech/api/people/1',
                                'https://www.swapi.tech/api/people/2'
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $result = $this->service->getFilms('hope');

        expect($result[0]['characters'])->toEqual([
            '/api/people/1',
            '/api/people/2'
        ]);
    });
});

describe('SwApiService::getFilmById', function () {
    
    it('fetches film by id successfully', function () {
        Http::fake([
            '*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'A New Hope',
                        'director' => 'George Lucas',
                        'producer' => 'Gary Kurtz',
                        'release_date' => '1977-05-25',
                        'opening_crawl' => 'It is a period of civil war...',
                        'episode_id' => 4,
                        'characters' => []
                    ]
                ]
            ], 200),
        ]);

        $result = $this->service->getFilmById('1');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('uid', '1')
            ->and($result)->toHaveKey('title', 'A New Hope')
            ->and($result)->toHaveKey('director', 'George Lucas');
    });

    it('caches film data after first request', function () {
        Http::fake([
            '*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'A New Hope',
                        'director' => 'George Lucas',
                        'characters' => []
                    ]
                ]
            ], 200),
        ]);

        // First request
        $result1 = $this->service->getFilmById('1');
        
        // Verify cache was set
        expect(Cache::has('films_1'))->toBeTrue();
        
        // Second request should use cache
        $result2 = $this->service->getFilmById('1');
        
        expect($result1)->toBe($result2);
    });

    it('throws exception when API request fails', function () {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $this->service->getFilmById('1');
    })->throws(SwApiRequestException::class);
});

describe('SwApiService::getFilmByIdWithDecoration', function () {
    
    it('decorates film with character details', function () {
        Http::fake(function ($request) {
            if (str_contains($request->url(), '/films/')) {
                return Http::response([
                    'result' => [
                        'properties' => [
                            'title' => 'A New Hope',
                            'characters' => [
                                'https://www.swapi.tech/api/people/1',
                                'https://www.swapi.tech/api/people/2'
                            ]
                        ]
                    ]
                ], 200);
            }
            
            if (str_contains($request->url(), '/people/1')) {
                return Http::response([
                    'result' => [
                        'properties' => [
                            'name' => 'Luke Skywalker',
                            'films' => []
                        ]
                    ]
                ], 200);
            }
            
            if (str_contains($request->url(), '/people/2')) {
                return Http::response([
                    'result' => [
                        'properties' => [
                            'name' => 'C-3PO',
                            'films' => []
                        ]
                    ]
                ], 200);
            }
            
            return Http::response([], 404);
        });

        $result = $this->service->getFilmByIdWithDecoration('1');

        expect($result['characters'])->toBeArray()
            ->and($result['characters'])->toHaveCount(2)
            ->and($result['characters'][0])->toHaveKey('name', 'Luke Skywalker')
            ->and($result['characters'][1])->toHaveKey('name', 'C-3PO');
    });
});

describe('SwApiService::getPeople', function () {
    
    it('fetches people by name successfully', function () {
        Http::fake([
            '*' => Http::response([
                'result' => [
                    [
                        'uid' => '1',
                        'properties' => [
                            'name' => 'Luke Skywalker',
                            'gender' => 'male',
                            'height' => '172'
                        ]
                    ]
                ]
            ], 200),
        ]);

        $result = $this->service->getPeople('luke');

        expect($result)->toBeArray()
            ->and($result)->toHaveCount(1)
            ->and($result[0])->toHaveKey('uid', '1')
            ->and($result[0])->toHaveKey('name', 'Luke Skywalker');
    });

    it('throws exception when API request fails', function () {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $this->service->getPeople('luke');
    })->throws(SwApiRequestException::class);
});

describe('SwApiService::getPersonById', function () {
    
    it('fetches person by id successfully', function () {
        Http::fake([
            '*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'Luke Skywalker',
                        'gender' => 'male',
                        'height' => '172',
                        'films' => []
                    ]
                ]
            ], 200),
        ]);

        $result = $this->service->getPersonById('1');

        expect($result)->toBeArray()
            ->and($result)->toHaveKey('uid', '1')
            ->and($result)->toHaveKey('name', 'Luke Skywalker');
    });

    it('caches person data after first request', function () {
        Http::fake([
            '*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'Luke Skywalker',
                        'films' => []
                    ]
                ]
            ], 200),
        ]);

        // First request
        $this->service->getPersonById('1');
        
        // Verify cache was set
        expect(Cache::has('people_1'))->toBeTrue();
    });
});

describe('SwApiService::getPersonByIdWithDecoration', function () {
    
    it('decorates person with film details', function () {
        Http::fake(function ($request) {
            if (str_contains($request->url(), '/people/1')) {
                return Http::response([
                    'result' => [
                        'properties' => [
                            'name' => 'Luke Skywalker',
                            'films' => [
                                'https://www.swapi.tech/api/films/1',
                                'https://www.swapi.tech/api/films/2'
                            ]
                        ]
                    ]
                ], 200);
            }
            
            if (str_contains($request->url(), '/films/1')) {
                return Http::response([
                    'result' => [
                        'properties' => [
                            'title' => 'A New Hope',
                            'episode_id' => 4,
                            'characters' => []
                        ]
                    ]
                ], 200);
            }
            
            if (str_contains($request->url(), '/films/2')) {
                return Http::response([
                    'result' => [
                        'properties' => [
                            'title' => 'The Empire Strikes Back',
                            'episode_id' => 5,
                            'characters' => []
                        ]
                    ]
                ], 200);
            }
            
            return Http::response([], 404);
        });

        $result = $this->service->getPersonByIdWithDecoration('1');

        expect($result['films'])->toBeArray()
            ->and($result['films'])->toHaveCount(2)
            ->and($result['films'][0])->toHaveKey('title', 'A New Hope')
            ->and($result['films'][1])->toHaveKey('title', 'The Empire Strikes Back');
    });
});
