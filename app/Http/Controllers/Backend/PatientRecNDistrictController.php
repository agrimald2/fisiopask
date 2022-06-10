<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientRecNDistrictController extends Controller
{
    public function post(Request $request, $id, $appointment_id)
    {
        $patient = Patient::find($id);

        $validated = $request->validate([
            'district' => 'nullable',
            'recommendation_id' => 'nullable'
        ]);

        $patient->district = $validated["district"];
        $patient->reccomendation_id = $validated["recommendation_id"];
        $patient->save();

        return redirect()->route('doctors.appointments.show', $appointment_id);
    }
}
