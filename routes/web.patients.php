<?php

use App\Http\Controllers\Patients\Auth\LoginAction;
use App\Http\Controllers\Patients\Auth\LogoutAction;
use App\Http\Controllers\Patients\IndexAction;
use App\Http\Controllers\Patients\RebookAction;
use App\Http\Controllers\Patients\CancelAction;
use App\Http\Controllers\Patients\CancelConfirmAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/login/{dni}/{token}', LoginAction::class)
    ->name('area.patients.login')
    ->middleware('patients.guest', 'throttle:patients');

Route::get('/logout', LogoutAction::class)
    ->name('area.patients.logout')
    ->middleware('patients.auth');

Route::middleware('patients.auth')->group(function () {
    Route::get('/', IndexAction::class)
        ->name('area.patients.index');

    Route::get('/rebook', RebookAction::class)
        ->name('area.patients.rebook');

    Route::get('/cancel/{appointment}', CancelAction::class)
        ->name('area.patients.cancel');
    Route::post('/cancel/{appointment}', CancelConfirmAction::class)
        ->name('area.patients.cancelPost');
});
