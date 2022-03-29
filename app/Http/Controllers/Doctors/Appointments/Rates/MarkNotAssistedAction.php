<?php

namespace App\Http\Controllers\Doctors\Appointments\Rates;

use App\Models\Appointment;
use App\Models\PatientRate;

use App\Http\Controllers\Controller;
use App\Models\AssistedAppointments;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class MarkNotAssistedAction extends Controller
{
    public function __invoke(PatientRate $patientRate, Appointment $appointment)
    {
        if($appointment->status == Appointment::STATUS_ASSISTED)
        {
            $pog = AssistedAppointments::query()->where('appointment_id', $appointment->id)->first();

            if($pog)
            {
                $pog->delete();
            }

            if($patientRate != null)
            {
                $sessions_left = $patientRate->sessions_left;
                $patientRate->sessions_left = $sessions_left + 1;
                $patientRate->save();

                if($patientRate->state == PatientRate::RATE_STATUS_COMPLETE)
                {
                    $patientRate->state = PatientRate::RATE_STATUS_OPEN;
                    $patientRate->save();
                }
            }

            $appointment->status = Appointment::STATUS_NOT_ASSISTED;
            $appointment->save();
        }

        $appointment_id = $appointment->id;
        if($appointment_id != null) return redirect()->route('doctors.appointments.show', $appointment_id);

        return redirect()->route('doctors.appointments.index');
    }
}
