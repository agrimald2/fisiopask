<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class RenderPatientRatesAction extends Controller
{
    public function __invoke(Request $request, Patient $patient)
    {
        $patient->load([
            'rates' => function ($q) {
                return $q->orderBy('state', 'asc');
            },
            'payments' => function ($q) {
                return $q->orderBy('id', 'desc');
            },
        ]);

        return inertia('Backend/PatientRates/Index', compact('patient'));
    }
}
