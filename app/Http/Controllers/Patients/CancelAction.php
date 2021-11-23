<?php

namespace App\Http\Controllers\Patients;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class CancelAction extends Controller
{
    public function __invoke(Appointment $appointment)
    {
        if ($appointment->isOld()) {
            return redirect()->back();
        }

        $url = route('area.patients.cancelPost', $appointment);

        $urlRebook = route('area.patients.cancelPost', [
            'appointment' => $appointment,
            'rebook' => true,
        ]);

        $model = $appointment;
        return inertia('Patients/Cancel/Confirm', compact('url', 'urlRebook', 'model'));
    }
}
