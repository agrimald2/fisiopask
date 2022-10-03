<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientRate;
use App\Models\Rate;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Subfamily;
use App\Models\DoctorSubfamily;

class ShowAppointmentAction extends Controller
{
    public function __invoke($id)
    {
        $user = auth()->user();

        $role = "admin";

        if ($user->hasRole('assistant')) {
            $role = "assistant";
        }elseif($user->hasRole('doctor')){
            $role = "doctor";
        }

        $appointment = appointments()->show($id);
        $patient = $appointment->patient;

        $doctor = Doctor::withTrashed()->where('id', $appointment["doctor_id"])->first();

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
        /*
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
        */

        return inertia('Doctors/Appointments/Show', compact('appointment', 'doctor', 'patient', 'role', 'rate'));
    }






}
