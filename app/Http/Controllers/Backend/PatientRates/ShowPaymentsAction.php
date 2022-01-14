<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientRate;

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
        return inertia('Backend/PatientRates/Show', compact('patientRate'));
    }
}
