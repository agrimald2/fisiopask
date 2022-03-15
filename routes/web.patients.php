<?php

use App\Http\Controllers\Patients\Auth\LoginAction;
use App\Http\Controllers\Patients\Auth\LogoutAction;
use App\Http\Controllers\Patients\IndexAction;
use App\Http\Controllers\Patients\SeeRatesAction;
use App\Http\Controllers\Patients\SeeAppointmentsAction;
use App\Http\Controllers\Patients\SeePastAppointmentsAction;
use App\Http\Controllers\Patients\ReprogramAppointmentsAction;
use App\Http\Controllers\Patients\CancelAppointmentsAction;
use App\Http\Controllers\Patients\RebookAction;
use App\Http\Controllers\Patients\CancelAction;
use App\Http\Controllers\Patients\PayAction;
use App\Http\Controllers\Patients\CancelConfirmAction;
use App\Http\Controllers\Patients\PatientSurveyController;
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

        Route::get('/seeRates', SeeRatesAction::class)
        ->name('area.patients.rates');        

        Route::get('/seeAppointments', SeeAppointmentsAction::class)
        ->name('area.patients.appointments');      

        Route::get('/seePastAppointments', SeePastAppointmentsAction::class)
        ->name('area.patients.appointments.past');      

        Route::get('/reprogramAppointments', ReprogramAppointmentsAction::class)
        ->name('area.patients.appointments.reprogram');      

        Route::get('/cancelAppointments', CancelAppointmentsAction::class)
        ->name('area.patients.appointments.cancel');              

    Route::get('/rebook', RebookAction::class)
        ->name('area.patients.rebook');

    Route::get('/cancel/{appointment}', CancelAction::class)
        ->name('area.patients.cancel');
    Route::post('/cancel/{appointment}', CancelConfirmAction::class)
        ->name('area.patients.cancelPost');

    Route::post('/pay', PayAction::class)
        ->name('area.patients.pay');
});

Route::get('/survey/appointment/{id}', PatientSurveyController::class)
    ->name('survey.take');

Route::resource('/survey', PatientSurveyController::class)
    ->only('store');