<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class RenderPatientPosAction extends Controller
{
    public function __invoke(Patient $patient)
    {
        return inertia('Backend/PatientRates/Pos', compact('patient'));
    }
}
