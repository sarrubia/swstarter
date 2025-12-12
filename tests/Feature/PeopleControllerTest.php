<?php

use Illuminate\Support\Facades\Http;
use function Pest\Laravel\getJson;

beforeEach(function () {
    // Base setup without mocks - each test will define its own
});

describe('GET /api/people', function () {

    it('returns people when valid name is provided', function () {
        Http::fake([
            '*people*' => Http::response([
                'result' => [
                    [
                        'uid' => '1',
                        'properties' => [
                            'name' => 'Luke Skywalker',
                            'gender' => 'male',
                            'skin_color' => 'fair',
                            'hair_color' => 'blond',
                            'height' => '172',
                            'eye_color' => 'blue',
                            'mass' => '77',
                            'birth_year' => '19BBY',
                            'films' => []
                        ]
                    ]
                ]
            ], 200),
        ]);

        $response = getJson('/api/people?name=luke');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'uid',
                    'name',
                    'gender',
                    'skin_color',
                    'hair_color',
                    'height',
                    'eye_color',
                    'mass',
                    'birth_year'
                ]
            ]);
    });

    it('returns 400 when name query parameter is missing', function () {
        $response = getJson('/api/people');

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'The query string "name" is required'
            ]);
    });

    it('returns 400 when name query parameter is empty', function () {
        $response = getJson('/api/people?name=');

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'The query string "name" is required'
            ]);
    });

    it('returns 500 when external API fails', function () {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $response = getJson('/api/people?name=luke');

        $response->assertStatus(500);
    });

    it('stores request metrics in database', function () {
        Http::fake([
            '*people*' => Http::response([
                'result' => [
                    [
                        'uid' => '1',
                        'properties' => [
                            'name' => 'Luke Skywalker',
                            'gender' => 'male',
                            'skin_color' => 'fair',
                            'hair_color' => 'blond',
                            'height' => '172',
                            'eye_color' => 'blue',
                            'mass' => '77',
                            'birth_year' => '19BBY',
                            'films' => []
                        ]
                    ]
                ]
            ], 200),
        ]);

        $response = getJson('/api/people?name=luke');

        $this->assertDatabaseHas('metrics', [
            'name' => 'request_timing',
            'uri' => '/api/people?name=luke'
        ]);
    });
});

describe('GET /api/people/{id}', function () {

    it('returns a person by id with decorated films', function () {
        Http::fake([
            '*people/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'Luke Skywalker',
                        'gender' => 'male',
                        'skin_color' => 'fair',
                        'hair_color' => 'blond',
                        'height' => '172',
                        'eye_color' => 'blue',
                        'mass' => '77',
                        'birth_year' => '19BBY',
                        'films' => [
                            'https://www.swapi.tech/api/films/1',
                            'https://www.swapi.tech/api/films/2'
                        ]
                    ]
                ]
            ], 200),
            '*films/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'A New Hope',
                        'director' => 'George Lucas',
                        'episode_id' => 4,
                        'characters' => []
                    ]
                ]
            ], 200),
            '*films/2*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'The Empire Strikes Back',
                        'director' => 'Irvin Kershner',
                        'episode_id' => 5,
                        'characters' => []
                    ]
                ]
            ], 200),
        ]);

        $response = getJson('/api/people/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'uid',
                'name',
                'gender',
                'skin_color',
                'hair_color',
                'height',
                'eye_color',
                'mass',
                'birth_year',
                'films' => [
                    '*' => [
                        'uid',
                        'link',
                        'title'
                    ]
                ]
            ]);
    });

    it('returns 500 when external API fails', function () {
        Http::fake([
            '*' => Http::response([], 500),
        ]);

        $response = getJson('/api/people/1');

        $response->assertStatus(500);
    });

    it('stores request metrics in database', function () {
        Http::fake([
            '*people/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'Luke Skywalker',
                        'gender' => 'male',
                        'skin_color' => 'fair',
                        'hair_color' => 'blond',
                        'height' => '172',
                        'eye_color' => 'blue',
                        'mass' => '77',
                        'birth_year' => '19BBY',
                        'films' => [
                            'https://www.swapi.tech/api/films/1',
                            'https://www.swapi.tech/api/films/2'
                        ]
                    ]
                ]
            ], 200),
            '*films/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'A New Hope',
                        'director' => 'George Lucas',
                        'episode_id' => 4,
                        'characters' => []
                    ]
                ]
            ], 200),
            '*films/2*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'The Empire Strikes Back',
                        'director' => 'Irvin Kershner',
                        'episode_id' => 5,
                        'characters' => []
                    ]
                ]
            ], 200),
        ]);

        $response = getJson('/api/people/1');

        $this->assertDatabaseHas('metrics', [
            'name' => 'request_timing',
            'uri' => '/api/people/1'
        ]);
    });

    it('caches person data after first request', function () {
        // First request mock
        Http::fake([
            '*people/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'Luke Skywalker',
                        'gender' => 'male',
                        'skin_color' => 'fair',
                        'hair_color' => 'blond',
                        'height' => '172',
                        'eye_color' => 'blue',
                        'mass' => '77',
                        'birth_year' => '19BBY',
                        'films' => [
                            'https://www.swapi.tech/api/films/1',
                            'https://www.swapi.tech/api/films/2'
                        ]
                    ]
                ]
            ], 200),
            '*films/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'A New Hope',
                        'director' => 'George Lucas',
                        'episode_id' => 4,
                        'characters' => []
                    ]
                ]
            ], 200),
            '*films/2*' => Http::response([
                'result' => [
                    'properties' => [
                        'title' => 'The Empire Strikes Back',
                        'director' => 'Irvin Kershner',
                        'episode_id' => 5,
                        'characters' => []
                    ]
                ]
            ], 200),
        ]);

        // First request
        getJson('/api/people/1')->assertStatus(200);

        // Change the mock to return different data
        Http::fake([
            '*people/1*' => Http::response([
                'result' => [
                    'properties' => [
                        'name' => 'Different Name',
                        'gender' => 'female',
                        'films' => []
                    ]
                ]
            ], 200),
        ]);

        // Second request should return cached data (Luke Skywalker)
        $response = getJson('/api/people/1');
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Luke Skywalker']);
    });
});
