<?php

use App\Services\SwApi\SwApiService;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\getJson;

beforeEach(function () {
    // Base setup without mocks - each test will define its own
});

describe('GET /api/films', function () {

    it('returns films when valid title is provided', function () {
        Http::fake([
            '*films*' => Http::response([
                'result' => [
                    [
                        'uid' => '1',
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
                ]
            ], 200),
        ]);

        $response = getJson('/api/films?title=hope');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'uid',
                    'title',
                    'director',
                    'producer',
                    'release_date',
                    'opening_crawl',
                    'episode_id'
                ]
            ]);
    });

    it('returns 400 when title query parameter is missing', function () {
        $response = getJson('/api/films');

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'The query string "title" is required'
            ]);
    });

    it('returns 400 when title query parameter is empty', function () {
        $response = getJson('/api/films?title=');

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'The query string "title" is required'
            ]);
    });

    it('returns 500 when external API fails', function () {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $response = getJson('/api/films?title=hope');

        $response->assertStatus(500);
    });

    it('stores request metrics in database', function () {
        Http::fake([
            '*films*' => Http::response([
                'result' => [
                    [
                        'uid' => '1',
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
                ]
            ], 200),
        ]);

        $response = getJson('/api/films?title=hope');

        $this->assertDatabaseHas('metrics', [
            'name' => 'request_timing',
            'uri' => '/api/films?title=hope'
        ]);
    });
});

describe('GET /api/films/{id}', function () {

    beforeEach(function () {
        Http::fake([
            '*films/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'A New Hope',
                        'director' => 'George Lucas',
                        'producer' => 'Gary Kurtz',
                        'release_date' => '1977-05-25',
                        'opening_crawl' => 'It is a period of civil war...',
                        'episode_id' => 4,
                        'characters' => [
                            'https://www.swapi.tech/api/people/1',
                            'https://www.swapi.tech/api/people/2'
                        ]
                    ]
                ]
            ], 200),
            '*people/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'Luke Skywalker',
                        'gender' => 'male',
                        'films' => []
                    ]
                ]
            ], 200),
            '*people/2*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'C-3PO',
                        'gender' => 'n/a',
                        'films' => []
                    ]
                ]
            ], 200),
        ]);
    });

    it('returns a film by id with decorated characters', function () {
        $response = getJson('/api/films/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'uid',
                'title',
                'director',
                'producer',
                'release_date',
                'opening_crawl',
                'episode_id',
                'characters' => [
                    '*' => [
                        'uid',
                        'name',
                        'link'
                    ]
                ]
            ]);
    });

    it('returns 500 when external API fails', function () {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $response = getJson('/api/films/2');

        $response->assertStatus(500);
    });

    it('stores request metrics in database', function () {
        Http::fake([
            '*films/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'A New Hope',
                        'director' => 'George Lucas',
                        'producer' => 'Gary Kurtz',
                        'release_date' => '1977-05-25',
                        'opening_crawl' => 'It is a period of civil war...',
                        'episode_id' => 4,
                        'characters' => [
                            'https://www.swapi.tech/api/people/1',
                            'https://www.swapi.tech/api/people/2'
                        ]
                    ]
                ]
            ], 200),
            '*people/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'Luke Skywalker',
                        'gender' => 'male',
                        'films' => []
                    ]
                ]
            ], 200),
            '*people/2*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'C-3PO',
                        'gender' => 'n/a',
                        'films' => []
                    ]
                ]
            ], 200),
        ]);

        $response = getJson('/api/films/1');

        $this->assertDatabaseHas('metrics', [
            'name' => 'request_timing',
            'uri' => '/api/films/1'
        ]);
    });
});
