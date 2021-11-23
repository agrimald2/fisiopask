<?php


use App\Http\Controllers\Doctors\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)
    ->name('doctors.dashboard');
