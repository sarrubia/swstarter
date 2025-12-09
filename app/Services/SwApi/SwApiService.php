<?php

namespace App\Services\SwApi;

use App\Lib\Utils\ArrayUtils;
use App\Services\SwApi\Decorators\FilmDecorator;
use App\Services\SwApi\Decorators\PersonDecorator;
use App\Services\SwApi\Exceptions\SwApiRequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SwApiService handle requests to SwAPI resources.
 */
class SwApiService
{
    const SWAPI_ROOT_ENDPOINT = 'https://www.swapi.tech';
    const SWAPI_ENDPOINT = self::SWAPI_ROOT_ENDPOINT . '/api/';

    const SWAPI_RESOURCE_PEOPLE = 'people';
    const SWAPI_RESOURCE_FILMS = 'films';

    const CACHE_TTL_MINUTES = 50;
    const CACHE_PREFIX_PEOPLE = 'people';
    const CACHE_PREFIX_FILMS = 'films';

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
        return $this->getResourceItemsFilteredBy(self::SWAPI_RESOURCE_FILMS,'title', $title);
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
        return $this->getResourceById(self::SWAPI_RESOURCE_FILMS, $id, self::CACHE_PREFIX_FILMS);
    }

    public function getFilmByIdWithDecoration(string $id): array {
        return $this->decorateFilm($this->getFilmById($id));
    }

    private function decorateFilm(array $films): array {
        $decoratedCharacterLinks = [];
        foreach ($films["characters"] as $peopleUrl) {
            $personId = $this->getIdFromUrl($peopleUrl, '/api/people/');
            $person = $this->getPersonById($personId);
            $decoratedCharacterLinks[] = PersonDecorator::getLink($person);
        }

        $films['characters'] = $decoratedCharacterLinks;
        return $films;
    }

    /**
     * Fetch people from the StarWars API given a person name
     * @param string $name
     * @return array
     * @throws SwApiRequestException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function getPeople(string $name): array {
        return $this->getResourceItemsFilteredBy(self::SWAPI_RESOURCE_PEOPLE,'name', $name);
    }

    /**
     * Fetch Person by id
     * @param string $id
     * @return array
     * @throws SwApiRequestException
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function getPersonById(string $id): array {
        return $this->getResourceById(
            self::SWAPI_RESOURCE_PEOPLE,
            $id,
            self::CACHE_PREFIX_PEOPLE);
    }

    public function getPersonByIdWithDecoration(string $id): array {
        return $this->decoratePerson($this->getPersonById($id));
    }

    private function decoratePerson(array $person): array {
        $detailedFilm = [];
        foreach ($person["films"] as $filmUrl) {
            $filmId = $this->getIdFromUrl($filmUrl, '/api/films/');
            $film = $this->getFilmById($filmId);
            $detailedFilm[] = FilmDecorator::getFilmLink($film);
        }

        $person['films'] = $detailedFilm;
        return $person;
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
            $properties = $this->cleanupProperties(ArrayUtils::keyExists('properties', $item, [])); // reading properties from swapi response data
            $toReturn[] = ['uid' => $uid, ...$properties];
        }

        return $toReturn;
    }

    private function cleanupProperties(array $properties): array {

        foreach ($properties as $property => $value) {
            if ($property === 'films' || $property === 'characters') {
                $properties[$property] = $this->removeSWEndpointFromList($value);
            }
        }

        return $properties;
    }

    private function getIdFromUrl(string $url, string $prefix): string {
        return str_replace($prefix, '', $url);
    }

    private function removeSWEndpointFromList(array $list): array {
        $curatedList = [];
        foreach ($list as $item) {
           $curatedList[] = str_replace(self::SWAPI_ROOT_ENDPOINT, '', $item );
        }
        return $curatedList;
    }

    private function cacheKey(string $prefix, string $key): string {
        return $prefix . '_' . $key;
    }

    private function getResourceItemsFilteredBy(string $resource, string $filterKey, string $filterValue): array {
        $response = Http::withUrlParameters([
            'endpoint' => self::SWAPI_ENDPOINT,
            'resource' => $resource,
        ])->get('{+endpoint}/{resource}', [$filterKey => $filterValue]);

        if ($response->failed()) {
            Log::error('error fetching info from swapi.tech. statusCode={status} body={body}',
                ['status' => $response->getStatusCode(), 'body' => $response->getBody()]);

            throw new SwApiRequestException('error fetching info from swapi.tech');
        }

        return $this->fetchItems($response->json('result', []));
    }

    private function getResourceById(string $resource, string $id, string $cachePrefix, ): array {

        if (Cache::has($this->cacheKey($cachePrefix, $id))) {
            return Cache::get($this->cacheKey($cachePrefix, $id));
        }

        $response = Http::withUrlParameters([
            'endpoint' => self::SWAPI_ENDPOINT,
            'resource' => $resource,
            'id' => $id,
        ])->get('{+endpoint}/{resource}/{id}');
        if ($response->failed()) {
            Log::error('error fetching {resource} by id from swapi.tech. id={id} statusCode={status} body={body}',
                ['resource' => $resource, 'id' => $id, 'status' => $response->getStatusCode(), 'body' => $response->getBody()]);

            throw new SwApiRequestException('error fetching resource by id from swapi.tech');
        }

        $toReturn = ['uid' => $id, ...$this->cleanupProperties($response->json('result.properties', []))];

        Cache::add($this->cacheKey($cachePrefix, $id), $toReturn, now()->addMinutes(self::CACHE_TTL_MINUTES));

        return $toReturn;
    }
}
