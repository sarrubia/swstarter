<?php

namespace App\Services\SwApi;

use App\Lib\Utils\ArrayUtils;
use App\Services\SwApi\Exceptions\SwApiRequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SwApiService handle requests to SwAPI resources.
 */
class SwApiService
{
    const SWAPI_ENDPOINT = 'https://www.swapi.tech/api/';

    const SWAPI_RESOURCE_PEOPLE = 'people';
    const SWAPI_RESOURCE_FILMS = 'films';

    public function __construct() {}

    /**
     * @param string $title
     * @return array ['uid' => string, 'properties'=> [[
     *           'name' => string,
     *           'gender' => string
     *           'skin_color' => string
     *           'hair_color' => string
     *           'height' => string
     *           'eye_color' => string
     *           'mass' => string
     *           'birth_year' => string
     *       ]]]
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \App\Services\SwApi\Exceptions\SwApiRequestException
     */
    public function getFilms(string $title): array {
        $response = Http::withUrlParameters([
            'endpoint' => self::SWAPI_ENDPOINT,
            'resource' => self::SWAPI_RESOURCE_FILMS,
        ])->get('{+endpoint}/{resource}', ['title' => $title]);

        if ($response->failed()) {
            Log::error('error fetching info from swapi.tech. statusCode={status} body={body}',
                ['status' => $response->getStatusCode(), 'body' => $response->getBody()]);

            throw new SwApiRequestException('error fetching info from swapi.tech');
        }

        return $this->fetchItems($response->json('result', []));
    }

    /**
     * getFilmById returns a film given an ID
     *
     * @param string $id
     * @return array [  'uid' => string, 'producer' => string, 'title' => string, 'director' => string,
     *                  'release_date' => string, 'opening_crawl' => string, 'episode_id' => string]
     * @throws \Illuminate\Http\Client\ConnectionException
     * @throws \App\Services\SwApi\Exceptions\SwApiRequestException
     */
    public function getFilmById(string $id): array {
        $response = Http::withUrlParameters([
            'endpoint' => self::SWAPI_ENDPOINT,
            'resource' => self::SWAPI_RESOURCE_FILMS,
            'id' => $id,
        ])->get('{+endpoint}/{resource}/{id}');

        if ($response->failed()) {
            Log::error('error fetching film by id from swapi.tech. id={} statusCode={status} body={body}',
                ['id' => $id, 'status' => $response->getStatusCode(), 'body' => $response->getBody()]);

            throw new SwApiRequestException('error fetching film by id from swapi.tech');
        }

        return ['uid' => $id, ...$response->json('result.properties', [])];
    }

    /**
     * Fetch people from the StarWars API given a person name
     * @param string $name
     * @return array
     * @throws SwApiRequestException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function getPeople(string $name): array {
        $response = Http::withUrlParameters([
            'endpoint' => self::SWAPI_ENDPOINT,
            'resource' => self::SWAPI_RESOURCE_PEOPLE,
        ])->get('{+endpoint}/{resource}', ['name' => $name]);

        if ($response->failed()) {
            Log::error('error fetching info from swapi.tech. statusCode={status} body={body}',
                ['status' => $response->getStatusCode(), 'body' => $response->getBody()]);

            throw new SwApiRequestException('error fetching info from swapi.tech');
        }

        return $this->fetchItems($response->json('result', []));
    }

    /**
     * Fetch Person by id
     * @param string $id
     * @return array
     * @throws SwApiRequestException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function getPersonById(string $id): array {
        $response = Http::withUrlParameters([
            'endpoint' => self::SWAPI_ENDPOINT,
            'resource' => self::SWAPI_RESOURCE_PEOPLE,
            'id' => $id,
        ])->get('{+endpoint}/{resource}/{id}');

        if ($response->failed()) {
            Log::error('error fetching person by id from swapi.tech. id={} statusCode={status} body={body}',
                ['id' => $id, 'status' => $response->getStatusCode(), 'body' => $response->getBody()]);

            throw new SwApiRequestException('error fetching person by id from swapi.tech');
        }

        return ['uid' => $id, ...$response->json('result.properties', [])];
    }

    /**
     * Common function to parse API response and return a curated item list
     * @param array $responseData
     * @return array
     */
    private function fetchItems(array $responseData): array {
        $toReturn = [];
        $result = $responseData; // defaulting empty array to avoid builder error data type
        foreach ($result as $item) {
            // Here the xDto::FIELD_UID is not used because the result from swapi could not match our DTO model
            $uid = ArrayUtils::keyExists('uid', $item, "");
            $properties = ArrayUtils::keyExists('properties', $item, []); // reading properties from swapi response data
            $toReturn[] = ['uid' => $uid, ...$properties];
        }

        return $toReturn;
    }
}
