<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class GeneratePaymentRequestLinkAction extends Controller
{
    public function __invoke(Patient $patient)
    {
        $patient->append('fullname');

        $balance = $patient->getRateBalance();

        return inertia('Backend/PatientRates/Link', compact('patient', 'balance'));
    }
}
