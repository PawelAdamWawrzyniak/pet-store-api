<?php

use App\Http\Controllers\AddPetsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add-pets', [AddPetsController::class , 'index'])->name('pets.add');
Route::post('/store-pets', [AddPetsController::class , 'store'])->name('pets.store');
