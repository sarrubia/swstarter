<?php

namespace App\Http\Controllers;

use App\Http\Dtos\PersonDto;
use App\Services\SwApi\Exceptions\SwApiRequestException;
use App\Services\SwApi\SwApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class PeopleController extends Controller
{
    const UNKNOWN = 'unknown';

    /**
     * @var SwApiService service dependency that allows the interaction with StarWars API
     */
    private SwApiService $swApiService;

    public function __construct(SwApiService $swApiService) {
        $this->swApiService = $swApiService;
    }

    /**
     * Fetch a list of people from the StarWars API given the query string name
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse {

        // reading people name to be searched
        $name = $request->query('name', self::UNKNOWN);
        if($name == self::UNKNOWN) {  // if name is missing response with bad request because it is required field
            Log::error('missing query string name');
            return response()->json(['message' => 'The query string "name" is required'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $people = $this->swApiService->getPeople($name); //fetching people from StarWars API service
            $toReturn = [];
            foreach($people as $person) {
                $toReturn[] = PersonDto::fromArray($person)->toArray();
            }
            return response()->json($toReturn, Response::HTTP_OK);

        } catch (SwApiRequestException|\Exception $exception) { // error handling
            Log::error($exception->getMessage());
            return response()->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Fetch person by id
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
            $person = $this->swApiService->getPersonByIdWithDecoration($id); // fetching person from StarWars API service
            $personDto = PersonDto::fromArray($person); // making the PersonDto to be sent as response
            return response()->json($personDto->toArray(), Response::HTTP_OK);

        } catch (\Exception $exception) { // error handling
            Log::error($exception->getMessage());
            return response()->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
