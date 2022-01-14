<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientRate;
use App\Models\Subfamily;

class ShowAppointmentAction extends Controller
{
    public function __invoke($id)
    {
        $user = auth()->user();

        $role = "admin";

        if ($user->hasRole('assistant')) $role = "assistant";

        $appointment = appointments()->show($id);

        $doctor = $appointment->doctor;
        $doctorSubfamilies = $doctor->subfamilies[0]->get();

        $rate = null;
        
        foreach($doctorSubfamilies as $subfamily)
        {
            $query = PatientRate::query()
                ->where('subfamily_id', $subfamily->id)
                ->where('state', PatientRate::RATE_STATUS_OPEN)
                ->first();

            if($query) 
            {
                $rate = $query;
                break;
            }
        }

        return inertia('Doctors/Appointments/Show', compact('appointment', 'role', 'rate'));
    }
}
