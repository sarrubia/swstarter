<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('search');
})->name('home');

// route to people screen details
//Route::get('/people/{id}', function () {
//    return Inertia::render('people');
//})->name('people');
