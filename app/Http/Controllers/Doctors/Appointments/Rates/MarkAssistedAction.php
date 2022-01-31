<?php

namespace App\Http\Controllers\Doctors\Appointments\Rates;

use App\Http\Controllers\Controller;
use App\Models\PatientRate;
use App\Models\Rate;
use Illuminate\Http\Request;

class MarkAssistedAction extends Controller
{
    public function __invoke(PatientRate $patientRate)
    {
        $sessions_left = $patientRate->sessions_left;

        if($sessions_left > 0) 
        {
            $patientRate->sessions_left = $sessions_left - 1;
            $patientRate->save();

            if($patientRate->sessions_left == 0)
            {
                $patientRate->state = PatientRate::RATE_STATUS_COMPLETE;
                $patientRate->save();
            }
            
        }

        $appointment_id = $patientRate->appointment_id;
        if($appointment_id != null) return redirect()->route('doctors.appointments.show', $appointment_id);

        return redirect()->route('doctors.appointments.index');
    }

    private function patientHasActiveRates($patientId)
    {
        $query = PatientRate::query()
            ->where('patient_id', $patientId)
            ->where('state', PatientRate::RATE_STATUS_OPEN)
            ->get();

        return !($query->isEmpty());
    }
}
