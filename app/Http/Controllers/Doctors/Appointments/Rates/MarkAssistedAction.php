<?php

namespace App\Http\Controllers\Doctors\Appointments\Rates;

use App\Http\Controllers\Controller;
use App\Models\PatientRate;
use App\Models\Appointment;
use App\Models\Rate;
use Illuminate\Http\Request;

use App\Models\AssistedAppointments;

class MarkAssistedAction extends Controller
{
    public function __invoke(PatientRate $patientRate, Appointment $appointment)
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

        logs()->warning($patientRate);
        
        AssistedAppointments::create([
            'appointment_id' => $appointment->id,
            'patient_rate_id' => $patientRate->id,
            'rate_charged' => $patientRate->name,
            'consumed' => $patientRate->price / $patientRate->sessions_total,
            'marked_by' => auth()->user()->name,
        ]); 

        $appointment->status = Appointment::STATUS_ASSISTED;
        $appointment->save();

        $appointment_id = $appointment->id;
        if($appointment_id != null) return redirect()->route('doctors.appointments.show', $appointment_id);

        return redirect()->route('doctors.appointments.index');
    }
}
