<?php

namespace App\Http\Controllers\Doctors\Appointments\Rates;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\PatientRate;
use App\Models\Rate;
use Illuminate\Http\Request;

class ShowRatesAction extends Controller
{
    //This controller also sets the appointment to assisted
    public function __invoke(Appointment $appointment)
    {
        $appointment->load("patient");
        $appointment->patient->append("fullname");

        $appointment->status = Appointment::STATUS_ASSISTED;
        $appointment->save();

        $patientRates = PatientRate::query()
            ->with('rate')
            ->where('patient_id', $appointment->patient->id)
            ->orderBy('id', 'desc')
            ->get();

        return inertia('Doctors/Appointments/Rates/Show', compact('appointment', 'patientRates'));
    }
}
