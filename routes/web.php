<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('search');
})->name('home');

// route to people screen details
Route::get('/people/{id}', function ($id) {
    //echo "********* $id ******";
    return Inertia::render('people', [
        'personId' => $id
    ]);
})->name('people');
