<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('search');
})->name('home');

// route to people screen details
Route::get('/people/{id}', function ($id) {
    return Inertia::render('people', [
        'personId' => $id
    ]);
})->name('people');


// route to movie screen details
Route::get('/films/{id}', function ($id) {
    return Inertia::render('films', [
        'filmId' => $id
    ]);
})->name('films');
