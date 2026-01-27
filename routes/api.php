<?php

use App\Http\Controllers\Api\Mcp\McpAppointmentController;
use App\Http\Controllers\Api\Mcp\McpAvailabilityController;
use App\Http\Controllers\Api\Mcp\McpCatalogController;
use App\Http\Controllers\Api\Mcp\McpDocsController;
use App\Http\Controllers\Api\Mcp\McpPatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| MCP API Documentation - Swagger UI
|--------------------------------------------------------------------------
*/

Route::prefix('mcp')
    ->name('mcp.')
    ->group(function () {
        // Documentacion Swagger (sin autenticacion)
        Route::get('/docs', [McpDocsController::class, 'index'])
            ->name('docs');

        Route::get('/docs/spec', [McpDocsController::class, 'spec'])
            ->name('docs.spec');
    });

/*
|--------------------------------------------------------------------------
| MCP API Routes - Para consumo por agentes de AI
|--------------------------------------------------------------------------
|
| Estos endpoints estan protegidos por token Bearer configurado en MCP_API_TOKEN
| Documentacion disponible en /api/mcp/docs
|
*/

Route::prefix('mcp')
    ->middleware('mcp.auth')
    ->name('mcp.')
    ->group(function () {

        // Catalogo: Doctores y Especialidades
        Route::get('/doctors', [McpCatalogController::class, 'doctors'])
            ->name('doctors');

        Route::get('/specialties', [McpCatalogController::class, 'specialties'])
            ->name('specialties');

        // Disponibilidad de horarios
        Route::get('/availability/{date}', [McpAvailabilityController::class, 'show'])
            ->name('availability.show')
            ->where('date', '\d{4}-\d{2}-\d{2}');

        // Pacientes
        Route::get('/patient/{dni}', [McpPatientController::class, 'show'])
            ->name('patient.show');

        Route::post('/patient', [McpPatientController::class, 'store'])
            ->name('patient.store');

        Route::get('/patient/{dni}/appointments', [McpPatientController::class, 'appointments'])
            ->name('patient.appointments');

        // Citas
        Route::post('/appointments', [McpAppointmentController::class, 'store'])
            ->name('appointments.store');

        Route::get('/appointments/{id}', [McpAppointmentController::class, 'show'])
            ->name('appointments.show')
            ->where('id', '[0-9]+');

        Route::put('/appointments/{id}/reschedule', [McpAppointmentController::class, 'reschedule'])
            ->name('appointments.reschedule')
            ->where('id', '[0-9]+');

        Route::delete('/appointments/{id}', [McpAppointmentController::class, 'destroy'])
            ->name('appointments.destroy')
            ->where('id', '[0-9]+');
    });
