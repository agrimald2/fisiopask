<?php

namespace App\Http\Controllers\Patients;

use App\Domain\Patients\PatientAuthRepositoryContract;
use App\Events\OnPatientCancelAppointment;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class CancelConfirmAction extends Controller
{
    public function __invoke(
        Request $request,
        Appointment $appointment,
        PatientAuthRepositoryContract $repo
    ) {
        $patient = $repo->getAuthenticatedPatient();

        OnPatientCancelAppointment::dispatch($patient, $appointment);

        $appointment->cancel();

        if ($request->rebook) {
            return redirect()->route('area.patients.rebook');
        }

        return redirect()->route('area.patients.index');
    }
}
