<?php

namespace App\Http\Controllers\Doctors\Appointments\Rates;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Rate;
use Illuminate\Http\Request;

class ShowRatesIndexAction extends Controller
{
    public function __invoke(Appointment $appointment)
    {
        $appointment->load("patient");
        $appointment->patient->append("fullname");

        $patientRates = $appointment->patientRates()
            ->orderBy('id', 'desc')
            ->get();

        return inertia('Doctors/Appointments/Rates/Index', compact('appointment', 'patientRates'));
    }
}
