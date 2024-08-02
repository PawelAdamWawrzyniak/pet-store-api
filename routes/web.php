<?php

use App\Http\Controllers\AddPetsController;
use App\Http\Controllers\GetPetsController;
use App\Http\Controllers\UpdatePetsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add-pets', [AddPetsController::class , 'index'])->name('pets.add');
Route::post('/store-pets', [AddPetsController::class , 'store'])->name('pets.store');

Route::get('/get-pets', [GetPetsController::class, 'index'])->name('pets.get');
Route::get('/get-pets-details', [GetPetsController::class, 'detail'])->name('pets.detail');
Route::get('/get-pets-by-status', [GetPetsController::class, 'status'])->name('pets.status');
Route::get('/get-pets-by-status-list', [GetPetsController::class, 'list'])->name('pets.status.list');

Route::get('/update-pets-form', [UpdatePetsController::class, 'view'])->name('pets.edit');
Route::put('/update-pets', [UpdatePetsController::class, 'update'])->name('pets.update');
