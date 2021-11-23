<?php

namespace App\Domain\Appointments;

use App\Models\Appointment;

class AppointmentCanceler
{
    public function cancel(Appointment $appointment)
    {
        $appointment->status = Appointment::STATUS_CANCELED;
        $appointment->save();
    }
}
