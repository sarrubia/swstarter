<?php

use App\Http\Controllers\FilmsController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\StatsController;
use App\Http\Middleware\RequestStatsMiddleware;
use Illuminate\Support\Facades\Route;

// returns the movies given the query string 'title'
Route::get('/films', [FilmsController::class, 'get'])
    ->middleware([RequestStatsMiddleware::class])
    ->name('films.get');

// returns the movie given the param 'id'
Route::get('/films/{id}', [FilmsController::class, 'getById'])
    ->middleware([RequestStatsMiddleware::class])
    ->name('films.getById');

// returns the people given the query string 'name'
Route::get('/people', [PeopleController::class, 'get'])
    ->middleware([RequestStatsMiddleware::class])
    ->name('people.get');

// returns the people given the param 'id'
Route::get('/people/{id}', [PeopleController::class, 'getById'])
    ->middleware([RequestStatsMiddleware::class])
    ->name('people.getById');

// returns the requests stats
Route::get('/stats', [StatsController::class, 'get'])
    ->name('stats.get');
