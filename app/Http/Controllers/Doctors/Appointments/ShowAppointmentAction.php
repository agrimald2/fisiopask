<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientRate;
use App\Models\Rate;
use App\Models\Subfamily;
use App\Models\DoctorSubfamily;

class ShowAppointmentAction extends Controller
{
    public function __invoke($id)
    {
        $user = auth()->user();

        $role = "admin";

        if ($user->hasRole('assistant')) $role = "assistant";

        $appointment = appointments()->show($id);
        $patient = $appointment->patient;

        $doctor = $appointment->doctor;
        $doctorSubfamilies = DoctorSubfamily::query()->where('doctor_id' , $doctor->id)->get();

        $rate = null;
        
        foreach($doctorSubfamilies as $subfamily)
        {
            $query = PatientRate::query()
                ->where('subfamily_id', $subfamily->subfamily_id)
                ->where('state', PatientRate::RATE_STATUS_OPEN)
                ->where('patient_id', $patient->id)
                ->first();

            if($query) 
            {
                $rate = $query;
                break;
            }
        }

        if($rate == null)
        {
            $constantRate = Rate::find(1);
            $query = PatientRate::query()
                ->where('name', $constantRate->name)
                ->where('state', PatientRate::RATE_STATUS_OPEN)
                ->where('patient_id', $patient->id)
                ->first();

            if($query) $rate = $query;
        }

        return inertia('Doctors/Appointments/Show', compact('appointment', 'role', 'rate'));
    }
}
