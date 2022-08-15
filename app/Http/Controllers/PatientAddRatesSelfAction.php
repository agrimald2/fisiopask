<?php

namespace App\Http\Controllers;

use App\Domain\Patients\PatientAuthRepositoryContract;
use Illuminate\Http\Request;

class PatientAddRatesSelfAction extends Controller
{
    public function __invoke(PatientAuthRepositoryContract $repo, Request $request)
    {
        $model = $repo->getAuthenticatedPatient();
        
        return inertia('Patients/Index/AddRates', compact('model'));
    }
}
