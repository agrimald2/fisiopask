<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\PatientRate;
use Illuminate\Http\Request;

class DestroyPatientRateAction extends Controller
{
    public function __invoke(PatientRate $patientRate)
    {
        $patientRate->delete();

        return redirect()->back();
    }
}
