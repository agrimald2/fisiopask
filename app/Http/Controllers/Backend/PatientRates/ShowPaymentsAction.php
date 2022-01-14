<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientRate;
use App\Models\PatientPayment;

class ShowPaymentsAction extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientRate  $patientRate
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, PatientRate $patientRate)
    {
        $patientRate->load('patientPayment.patient');

        $payments = PatientPayment::withTrashed()->with('patient')->where('patient_rate_id', $patientRate->id)->orderBy('deleted_at', 'asc')->get();

        return inertia('Backend/PatientRates/Show', compact('patientRate', 'payments'));
    }
}
