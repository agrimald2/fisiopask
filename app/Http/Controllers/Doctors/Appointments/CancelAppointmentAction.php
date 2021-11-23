<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class CancelAppointmentAction extends Controller
{
    public function __invoke(Appointment $appointment)
    {
        $appointment->cancel();

        return redirect()->route('doctors.appointments.show', $appointment->id);
    }
}
