<?php

namespace App\Http\Controllers\Patients;

use App\Domain\Patients\PatientAuthRepositoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RebookAction extends Controller
{
    public function __invoke(PatientAuthRepositoryContract $repo)
    {
        $patient = $repo->getAuthenticatedPatient();

        return redirect()->route('bookAppointment.index', [
            'dni' => $patient->dni,
        ]);
    }
}
