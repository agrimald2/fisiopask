<?php

use App\Http\Controllers\Backend\AssistantController;
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
use App\Http\Controllers\Backend\PatientRates\AddPaymentToPatientActionApp;
use App\Http\Controllers\Backend\PatientRates\AddRateToPatientAction;
use App\Http\Controllers\Backend\PatientRates\DestroyPatientRateAction;
use App\Http\Controllers\Backend\PatientRates\GeneratePaymentRequestLinkAction;
use App\Http\Controllers\Backend\PatientRates\RenderPatientPosAction;
use App\Http\Controllers\Backend\PatientRates\RenderPatientRatesAction;
use App\Http\Controllers\Backend\PatientRates\RenderPaymentFormAction;
use App\Http\Controllers\Backend\PatientRates\ShowPaymentsAction;
use App\Http\Controllers\Backend\PatientRates\CancelPaymentAction;
use App\Http\Controllers\Backend\PatientRates\PayRateAction;
use App\Http\Controllers\Backend\PatientRates\PayRateActionApp;
use App\Http\Controllers\Backend\PatientRates\PayConstantRateAction;
use App\Http\Controllers\Backend\PatientRates\CancelRateAction;
use App\Http\Controllers\Backend\PatientRates\AbandonRateAction;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\PaymentLinksController;
use App\Http\Controllers\Backend\PaymentMethodController;
use App\Http\Controllers\Backend\RecommendationController;
use App\Http\Controllers\Backend\RateController;
use App\Http\Controllers\Backend\Rates\ProductSelectController;
use App\Http\Controllers\Backend\ScheduleController;
use App\Http\Controllers\Backend\ScheduleFreezeController;
use App\Http\Controllers\Backend\StatisticsController;
use App\Http\Controllers\Backend\SubfamilyController;
use App\Http\Controllers\Backend\FileController;
use App\Http\Controllers\PatientPaymentsController;

use App\Http\Controllers\TestAssistanceController;


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
use App\Http\Controllers\Doctors\Appointments\Rates\MarkNotAssistedAction;
use App\Http\Controllers\Doctors\Appointments\Rates\StoreRateAction;
use App\Http\Controllers\Doctors\Appointments\RescheduleAppointment;
use App\Http\Controllers\Doctors\Appointments\ShowAppointmentAction;
use App\Http\Controllers\GoogleCalendar\GoogleCalendarController;

use App\Http\Controllers\Doctors\Appointments\MultipleBookingController;


use App\Http\Controllers\Backend\Bills\BillsFamilyController;
use App\Http\Controllers\Backend\Bills\BillsSubFamilyController;
use App\Http\Controllers\Backend\Bills\BillsReceiverController;
use App\Http\Controllers\Backend\Bills\BillsPayerController;
use App\Http\Controllers\Backend\Bills\BillsPaymentMethodController;
use App\Http\Controllers\Backend\Bills\BillsOriginController;
use App\Http\Controllers\Backend\Bills\BillController;


use App\Http\Controllers\Backend\GenerateTokensAction;
use App\Http\Controllers\Backend\PatientRecNDistrictController;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexAction::class)
    ->name('dashboard');

/**
 * Assistants
 */
Route::resource('assistants', AssistantController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

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

    Route::post('/subfamilies/add', [DoctorController::class, 'subfamiliesAdd'])
        ->name("doctors.subfamilies.add");
    Route::post('/subfamilies/remove', [DoctorController::class, 'subfamiliesRemove'])
        ->name("doctors.subfamilies.remove");

    Route::get('/wame/{phone}', [DoctorController::class, 'wame'])
        ->name("doctors.wame");
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

Route::get('/patients/rates/{patientRate}/assisted/{appointment}', MarkAssistedAction::class)
    ->name('patients.rates.assisted');

Route::post('/patient/{id}/appointment/{appointment_id}/fillData', [PatientRecNDistrictController::class, 'post'])
    ->name('patients.fillData');

Route::get('/patients/rates/{patientRate}/notAssisted/{appointment}', MarkNotAssistedAction::class)
    ->name('patients.rates.notAssisted');

Route::get('/patients/rates/{patientRate}/pay', PayRateAction::class)
    ->name('patients.rates.pay');

Route::get('/patients/rates/{patientRate}/{appointment_id}/pay', PayRateActionApp::class)
    ->name('patients.rates.payApp');

Route::get('/patients/constantrate/{appointment}/pay', PayConstantRateAction::class)
    ->name('patients.constantrate.pay');

Route::get('/rate/{rate}/cancel', CancelRateAction::class)
    ->name('patients.rates.cancel');

Route::get('/rate/{rate}/abandon', AbandonRateAction::class)
    ->name('patients.rates.abandon');

Route::get('/patients/rates/{patientRate}/payments', ShowPaymentsAction::class)
    ->name('patients.rates.payments');

Route::get('/patients/payments/{payment}/cancel', CancelPaymentAction::class)
    ->name('patients.rates.payments.cancel');

Route::
    namespace (null)
    ->name('patients.rates.')
    ->prefix('/patients/{patient}/appointment/{appointment}/rates')
    ->group(function () {
        Route::get('/', RenderPatientRatesAction::class)
            ->name('index');
    });

Route::
    namespace (null)
    ->name('patients.rates.')
    ->prefix('/patients/{patient}/rates')
    ->group(function () {
        Route::get('/link', GeneratePaymentRequestLinkAction::class)
            ->name('link');

        Route::get('/create', RenderPatientPosAction::class)
            ->name('create');

        Route::post('/{appointment_id}', AddRateToPatientAction::class)
            ->name('store');

        Route::get('/payments/create', RenderPaymentFormAction::class)
            ->name('payments.create');
        Route::post('/payments/{appointment_id}', AddPaymentToPatientActionApp::class)
            ->name('payments.storeApp');
        Route::post('/payments', AddPaymentToPatientAction::class)
            ->name('payments.store');
    });

/**
 * Payment Indexer
 */
Route::resource('payments', PaymentController::class)
    ->only('index');
/**
 * Payment Links
 */
Route::resource('paymentlinks', PaymentLinksController::class)
    ->only('index');
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

Route::get('/show}', [SurveyShowController::class, 'showAll'])
    ->name("surveys.showAll");

/**
 * Payment methods
 */
Route::resource('paymentMethods', PaymentMethodController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

/**
 * Recomendations
 */
Route::resource('recommendation', RecommendationController::class)
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

Route::middleware(['role:doctor|admin|assistant'])
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

Route::get('/reschedule/{appointment}/pickOffice', [RescheduleAppointment::class, 'pickOffice'])
    ->name('reschedule.pickOffice');

Route::post('/reschedule/{appointment}/postOffice/{office}', [RescheduleAppointment::class, 'postOffice'])
    ->name('reschedule.postOffice');

Route::get('/reschedule/{appointment}/office/{office}/pickDay', [RescheduleAppointment::class, 'pickDay'])
    ->name('reschedule.pickDay');

Route::post('/reschedule/{appointment}/office/{office}/postDay', [RescheduleAppointment::class, 'postDay'])
    ->name('reschedule.postDay');

Route::get('/reschedule/{appointment}/office/{office}/pickTime/{date}', [RescheduleAppointment::class, 'pickTime'])
    ->name('reschedule.pickTime');

Route::post('/reschedule/{appointment}/postTime', [RescheduleAppointment::class, 'postTime'])
    ->name('reschedule.postTime');

Route::prefix('test')
    ->group(function () {
        Route::post(
            '/doctors',
            function () {
                    $search = request()->search;

                    return Doctor::query()
                        ->where('name', 'LIKE', "%$search%")
                        ->limit(5)
                        ->get()
                        ->pluck('name', 'id');
                }
        )->name('test.doctors');

        Route::post(
            '/doctors/ids',
            function () {
                    $ids = request()->ids;

                    return Doctor::query()
                        ->whereIn('id', $ids)
                        ->get()
                        ->pluck('name', 'id');
                }
        );

        Route::get(
            '/',
            function () {
                    return inertia('Test/Dropdown');
                }
        );
    });

Route::get('/generateTokens', GenerateTokensAction::class);

Route::get('/booking/{patient}/pickDay', [MultipleBookingController::class, 'pickDay'])
    ->name('multipleBooking.pickDay');

Route::post('/booking/{patient}/postDay', [MultipleBookingController::class, 'postDay'])
    ->name('multipleBooking.postDay');

Route::get('/booking/{patient}/pickTime/{date}', [MultipleBookingController::class, 'pickTime'])
    ->name('multipleBooking.pickTime');

Route::post('/booking/{patient}/postTime', [MultipleBookingController::class, 'postTime'])
    ->name('multipleBooking.postTime');

/**
 * AREA: Statistics
 */
Route::post('/statistic', [StatisticsController::class, 'statistic'])->name('statistic');
Route::get('/excel', [StatisticsController::class, 'excel'])->name('excel');
//Route::post('/excel', [StatisticsController::class,'excel'])->name('excel');
Route::resource('statistics', StatisticsController::class)
    ->only('index');


//Route::get('/testa', [TestAssistanceController::class,'test'])->name('test');


/*
Route::prefix('bills')
->name('bills.')
->group(function () {
Route::post('/connect', [GoogleCalendarController::class, 'connect'])
->name('connect');
});
*/

//? BillsFamily
Route::resource('billsfamilies', BillsFamilyController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

//? BillsSubFamily
Route::resource('billssubfamilies', BillsSubFamilyController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

//? BillsReceiver
Route::resource('billsreceivers', BillsReceiverController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

//? BillsPayer
Route::resource('billspayers', BillsPayerController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

//? BillsPaymentMethod
Route::resource('billspaymentmethods', BillsPaymentMethodController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

//? BillsOrigin
Route::resource('billsorigins', BillsOriginController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');

//? BillsTransaction
Route::resource('bills', BillController::class)
    ->only('index', 'create', 'store', 'edit', 'update', 'destroy');


Route::get('/filtered-bills', [BillController::class, 'getFilteredData'])->name('bills.filter');
Route::get('/showBillDetails/{id}', [BillController::class, 'showBillDetails'])->name('bills.details');
Route::post('/approveBill/{id}', [BillController::class, 'aprroveBill'])->name('bills.approve');
Route::post('/denyBill/{id}', [BillController::class, 'denyBill'])->name('bills.deny');
Route::get('/stadistics/bills', [BillController::class, 'stadistics'])->name('bills.stadistics');
Route::get('/filterStadistics/bills', [BillController::class, 'filterStadistics'])->name('bills.stadistics.filter');

Route::post('/rate/file/store', [FileController::class, 'storeRate'])->name('rate.file.store');


Route::get('/patientPayments', [PatientPaymentsController::class, 'index'])->name('paymentsPayments.index');


/**
 * Marketing
 */
Route::prefix('marketing')
    ->name('marketing.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\Backend\MarketingController::class, 'index'])
            ->name('index');
        Route::post('/export', [App\Http\Controllers\Backend\MarketingController::class, 'export'])
            ->name('export');
        Route::post('/preview', [App\Http\Controllers\Backend\MarketingController::class, 'preview'])
            ->name('preview');
    });

/**
 * Schedule Overview - Visualización de horarios de doctores
 */
Route::prefix('schedule-overview')
    ->name('scheduleOverview.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\Backend\DoctorScheduleOverviewController::class, 'index'])
            ->name('index');
        Route::post('/filter', [App\Http\Controllers\Backend\DoctorScheduleOverviewController::class, 'filter'])
            ->name('filter');
        Route::post('/day-detail', [App\Http\Controllers\Backend\DoctorScheduleOverviewController::class, 'dayDetail'])
            ->name('dayDetail');
        Route::post('/preview-availability', [App\Http\Controllers\Backend\AvailabilityExportController::class, 'preview'])
            ->name('previewAvailability');
        Route::post('/export-pdf', [App\Http\Controllers\Backend\AvailabilityExportController::class, 'exportPdf'])
            ->name('exportPdf');
    });