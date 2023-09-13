<?php

use App\Http\Controllers\LoginSuccessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\GenerateTokensAction;
use App\Http\Controllers\Backend\FileController;

use Inertia\Inertia;

use App\Http\Controllers\TestAssistanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * Backend routes
 */

Route::namespace(null)
    ->middleware(['auth:sanctum', 'verified'])
    ->prefix('dashboard')
    ->group(base_path('routes/web.backend.php'));


/**
 * Frontend routes
 */

Route::namespace(null)
    ->group(base_path('routes/web.frontend.php'));

/**
 * Patients routes
 */
Route::namespace(null)
    ->prefix('area/patients')
    ->group(base_path('routes/web.patients.php'));



    Route::get('/generateTokens', GenerateTokensAction::class);


    Route::get('/cita', function () {
        return redirect('/book-appointment');
    });
        
        
        Route::get('/citas', function () {
        return redirect('/book-appointment');
    });


    

    Route::get('/testa', [TestAssistanceController::class,'test'])->name('test'); 


    Route::post('/rate/file/store', [FileController::class, 'storeRate'])->name('rate.file.store');
    Route::get('/tarifario', [FileController::class, 'seeRatePdf'])->name('rate.file');