<?php

namespace App\Http\Controllers;

use App\Http\Dtos\FilmDto;
use App\Services\SwApi\Exceptions\SwApiRequestException;
use App\Services\SwApi\SwApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FilmsController extends Controller
{
    const UNKNOWN = 'unknown';

    /**
     * @var SwApiService service dependency that allows the interaction with StarWars API
     */
    private SwApiService $swApiService;

    /**
     * @param SwApiService $swApiService
     */
    public function __construct(SwApiService $swApiService) {
        $this->swApiService = $swApiService;
    }

    /**
     * Fetch a list of movies from the StarWars API given the query string title
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse {

        // reading movie title to be searched
        $title = $request->query('title', self::UNKNOWN);
        if($title == self::UNKNOWN) { // if title is missing response with bad request because it is required field
            Log::error('missing query string title');
            return response()->json(['message' => 'The query string "title" is required'], Response::HTTP_BAD_REQUEST);
        }

        try {

            $films = $this->swApiService->getFilms($title); // fetching films from StarWars API service
            $toReturn = [];
            foreach($films as $film) {
                $toReturn[] = FilmDto::fromArray($film)->toArray(); // making the presentation layer
            }
            return response()->json($toReturn, Response::HTTP_OK);

        } catch (SwApiRequestException|\Exception $exception) { // error handling
            Log::error($exception->getMessage());
            return response()->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Fetch a movie by id
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function getById(string $id, Request $request): JsonResponse {

        // this condition maybe never will be true because Laravel
        // will handle the route as an error if the id is empty.
        if(empty($id)) {
            Log::error('missing path param id');
            return response()->json(['message' => 'The path param "id" is required'], Response::HTTP_BAD_REQUEST);
        }

        try {

            $result = $this->swApiService->getFilmById($id); // fetching film from StarWars API service
            $filmsDto = FilmDto::fromArray($result); // making the FilmDto to be sent as response
            return response()->json($filmsDto->toArray(), Response::HTTP_OK);

        } catch (\Exception $exception) { // error handling
            Log::error($exception->getMessage());
            return response()->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
