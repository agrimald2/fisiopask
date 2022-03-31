<?php

namespace App\Http\Controllers\Doctors\Appointments\Rates;

use App\Http\Controllers\Controller;
use App\Models\PatientRate;
use App\Models\Appointment;
use App\Models\Rate;
use Illuminate\Http\Request;

use App\Models\AssistedAppointments;
use App\Models\Patient;
use App\Models\Recommendation;

class MarkAssistedAction extends Controller
{
    public function __invoke(PatientRate $patientRate, Appointment $appointment)
    {
        //@Check for District & Recommendation not null

        $appointmentId = $appointment->id;
        $patientId = $appointment->patient_id;
        $patient = Patient::find($patientId);

        $showDistrict = !$patient->district;
        $showRecommendation = $patient->recommendation_id != null;

        
        if($showDistrict || $showRecommendation)
        {
            $recommendations = Recommendation::query()->get();
            return inertia('Backend/Patients/FillData', compact('appointmentId', 'patientId', 'showDistrict', 'showRecommendation', 'recommendations'));
        }

        //@Mark Assisted Logic

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

        //logs()->warning($patientRate);
        
        AssistedAppointments::create([
            'appointment_id' => $appointment->id,
            'patient_rate_id' => $patientRate->id,
            'rate_charged' => $patientRate->name,
            'consumed' => $patientRate->price / $patientRate->sessions_total,
            'marked_by' => auth()->user()->name,
        ]); 

        $appointment->status = Appointment::STATUS_ASSISTED;
        $appointment->save();

        if($appointmentId != null) return redirect()->route('doctors.appointments.show', $appointmentId);

        return redirect()->route('doctors.appointments.index');
    }
}
