<?php

use App\Http\Controllers\Backend\DoctorController;
use App\Http\Controllers\Backend\DoctorSpecialtyController;
use App\Http\Controllers\Backend\SurveyShowController;
use App\Http\Controllers\Backend\FamilyController;
use App\Http\Controllers\Backend\IndexAction;
use App\Http\Controllers\Backend\OfficeController;
use App\Http\Controllers\Backend\WorkspaceController;
use App\Http\Controllers\Backend\PatientController;
use App\Http\Controllers\Backend\PatientAppointmentController;
use App\Http\Controllers\Backend\PatientRates\AddPaymentToPatientAction;
use App\Http\Controllers\Backend\PatientRates\AddRateToPatientAction;
use App\Http\Controllers\Backend\PatientRates\DestroyPatientRateAction;
use App\Http\Controllers\Backend\PatientRates\GeneratePaymentRequestLinkAction;
use App\Http\Controllers\Backend\PatientRates\RenderPatientPosAction;
use App\Http\Controllers\Backend\PatientRates\RenderPatientRatesAction;
use App\Http\Controllers\Backend\PatientRates\RenderPaymentFormAction;
use App\Http\Controllers\Backend\PatientRates\RenderPosAction;
use App\Http\Controllers\Backend\PaymentMethodController;
use App\Http\Controllers\Backend\RateController;
use App\Http\Controllers\Backend\Rates\ProductSelectController;
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\ScheduleFreezeController;
use App\Http\Controllers\Backend\SubfamilyController;


use App\Http\Controllers\Backend\HistoryGroupController;
use App\Http\Controllers\Backend\MedicalHistoryController;
use App\Http\Controllers\Backend\MedicalRevisionController;

use App\Http\Controllers\Backend\AffectedAreaController;
use App\Http\Controllers\Backend\AnalysisController;
use App\Http\Controllers\Backend\DiagnosticController;
use App\Http\Controllers\Backend\TreatmentController;


use App\Http\Controllers\Doctors\Appointments\CancelAppointmentAction;
use App\Http\Controllers\Doctors\PatientHistories\PatientHistoriesController;
use App\Http\Controllers\Doctors\SeeScheduleAction;
use App\Http\Controllers\Doctors\Appointments\IndexAppointmentAction;
use App\Http\Controllers\Doctors\Appointments\Rates\GenerateTicketAction;
use App\Http\Controllers\Doctors\Appointments\Rates\ShowRatesIndexAction;
use App\Http\Controllers\Doctors\Appointments\Rates\ShowRatesAction;
use App\Http\Controllers\Doctors\Appointments\Rates\MarkAssistedAction;
use App\Http\Controllers\Doctors\Appointments\Rates\StoreRateAction;
use App\Http\Controllers\Doctors\Appointments\ShowAppointmentAction;
use App\Http\Controllers\GoogleCalendar\GoogleCalendarController;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexAction::class)
    ->name('dashboard');


/**
 * Doctors
 */
Route::resource('doctors', DoctorController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

Route::prefix('doctors')->group(function () {
    Route::post('/specialties/add', [DoctorController::class, 'specialtiesAdd'])
        ->name("doctors.specialties.add");
    Route::post('/specialties/remove', [DoctorController::class, 'specialtiesRemove'])
        ->name("doctors.specialties.remove");
});


Route::resource('doctors.schedules', ScheduleController::class)
    ->only('index', 'store', 'destroy')
    ->shallow();

Route::resource('doctors.freezes', ScheduleFreezeController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy')
    ->parameters(['freezes' => 'scheduleFreeze'])
    ->shallow();

/**
 * Patients
 */
Route::resource('patients', PatientController::class)
    ->only('index', 'create', 'edit', 'store', 'update', 'destroy');
/**
 * Patient Appointments
 */
Route::resource('patients.appointment', PatientAppointmentController::class)
    ->only('index', 'create', 'edit', 'store', 'update', 'destroy');
// Pick time
Route::post('/dni/{dni}/day', [PatientAppointmentController::class, 'pickDatePost'])
    ->name('patients.appointment.pickDate.post');
Route::get('/dni/{dni}/day/{date}/time/', [PatientAppointmentController::class, 'pickTime'])
    ->name('patients.appointment.pickTime');
/**
 * Patient wallet
 */
Route::delete('/patients/rates/{patientRate}', DestroyPatientRateAction::class)
    ->name('patients.rates.destroy');

Route::get('/patients/rates/{patientRate}/assisted', MarkAssistedAction::class)
    ->name('patients.rates.assited');

Route::namespace(null)
    ->name('patients.rates.')
    ->prefix('/patients/{patient}/rates')
    ->group(function () {
        Route::get('/', RenderPatientRatesAction::class)
            ->name('index');

        Route::get('/link', GeneratePaymentRequestLinkAction::class)
            ->name('link');

        Route::get('/create', RenderPatientPosAction::class)
            ->name('create');

        Route::post('/', AddRateToPatientAction::class)
            ->name('store');

        Route::get('/payments/create', RenderPaymentFormAction::class)
            ->name('payments.create');
        Route::post('/payments', AddPaymentToPatientAction::class)
            ->name('payments.store');
    });

/**
 * Offices
 */
Route::resource('offices', OfficeController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

/**
 * Workspaces
 */
Route::resource('workspaces', WorkspaceController::class)
->only('index', 'create', 'store', 'edit', 'update', 'destroy');

/**
 * Doctor specialties
 */
Route::resource('doctorSpecialties', DoctorSpecialtyController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

/**
 * Clinic history
 */

Route::resource('patients.histories', PatientHistoriesController::class)
    ->parameters(['histories' => 'patient_history'])
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy')
    ->shallow();

/**
 * Surveys
 */

Route::resource('surveys', SurveyShowController::class)
    ->only('index', 'destroy');

Route::get('/show/{id}', [SurveyShowController::class, 'show'])
    ->name("surveys.show");

/**
 * Payment methods
 */
Route::resource('paymentMethods', PaymentMethodController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');


/**
 * Families & subfamilies
 */
Route::resource('families', FamilyController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

Route::resource('subfamilies', SubfamilyController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

/**
 * Families
 */
Route::resource('rates', RateController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

/**
 * Google calendar
 */
Route::prefix('google-calendar')
    ->name('google.calendar.')
    ->group(function () {
        Route::post('/connect', [GoogleCalendarController::class, 'connect'])
            ->name('connect');

        Route::post('/disconnect', [GoogleCalendarController::class, 'disconnect'])
            ->name('disconnect');

        Route::get('/connected', [GoogleCalendarController::class, 'connected'])
            ->name('connected');
    });


/**
 * Product selection
 */
Route::prefix('productSelect')
    ->name('productSelect.')
    ->group(function () {
        Route::get('/families', [ProductSelectController::class, 'getFamilies'])
            ->name('families');
        Route::get('/families/{family}/subfamilies', [ProductSelectController::class, 'getSubfamilies'])
            ->name('subfamilies');
        Route::get('/subfamilies/{subfamily}/rates   ', [ProductSelectController::class, 'getRates'])
            ->name('rates');
    });

///////////////////////////////
/**
 * CRUD MEDICAL HISTORIES
 */

//History Group
Route::resource('patients.historygroup', HistoryGroupController::class)
    ->only('index');

Route::get('patients/{patientId}/historygroup/create/{doctorId}', [HistoryGroupController::class, 'store'])
    ->name('patients.historygroup.create');

Route::get('/historygroup/show/{id}', [HistoryGroupController::class, 'show'])
    ->name('patients.historygroup.show');

//Medical History
Route::resource('medicalhistory', MedicalHistoryController::class)
    ->only('store');

Route::get('medicalhistory/create/{id}', [MedicalHistoryController::class, 'create'])
    ->name('medicalhistory.create');

Route::get('/medicalhistory/show/{id}', [MedicalHistoryController::class, 'show'])
    ->name('medicalhistory.show');

//Medical Revision
Route::resource('medicalrevision', MedicalRevisionController::class)
    ->only('store');

Route::get('medicalrevision/create/{id}', [MedicalRevisionController::class, 'create'])
    ->name('medicalrevision.create');

Route::get('/medicalrevision/show/{id}', [MedicalRevisionController::class, 'show'])
    ->name('medicalrevision.show');

//Affected Areas
Route::resource('affectedarea', AffectedAreaController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

//Analysis
Route::resource('analysis', AnalysisController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

//Diagnostic
Route::resource('diagnostic', DiagnosticController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

//Treatment
Route::resource('treatment', TreatmentController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

////////////////////////////////

/**
 * AREA: Doctors
 */

Route::middleware(['role:doctor|admin'])
    ->prefix('doctors')
    ->group(function () {
        Route::get('/see-schedule', SeeScheduleAction::class)
            ->name('doctors.seeSchedule');

        Route::get('/appointments', IndexAppointmentAction::class)
            ->name('doctors.appointments.index');

        Route::get('/appointments/{appointment}', ShowAppointmentAction::class)
            ->name('doctors.appointments.show');

        Route::post('/appointments/{appointment}/cancel', CancelAppointmentAction::class)
            ->name('doctors.appointments.cancel');

        Route::get('/appointments/{appointment}/rates', ShowRatesIndexAction::class)
            ->name('doctors.appointments.rates.index');

        Route::get('/appointments/{appointment}/selectrate', ShowRatesAction::class)
            ->name('doctors.appointments.rates.show');

        Route::get('/appointments/{appointment}/ticket', GenerateTicketAction::class)
            ->name('doctors.appointments.ticket.index');
    });

Route::prefix('test')
    ->group(function () {
        Route::post('/doctors', function () {
            $search = request()->search;

            return Doctor::query()
                ->where('name', 'LIKE', "%$search%")
                ->limit(5)
                ->get()
                ->pluck('name', 'id');
        })->name('test.doctors');

        Route::post('/doctors/ids', function () {
            $ids = request()->ids;

            return Doctor::query()
                ->whereIn('id', $ids)
                ->get()
                ->pluck('name', 'id');
        });

        Route::get('/', function () {
            return inertia('Test/Dropdown');
        });
    });
