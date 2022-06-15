<?php

namespace App\Http\Controllers\Patients;

use App\Domain\Patients\PatientAuthRepositoryContract;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Test;
use Illuminate\Http\Request;

class IndexAction extends Controller
{
    public function __invoke(PatientAuthRepositoryContract $repo, Request $request)
    {
        $model = $repo->getAuthenticatedPatient();

        $appointments = $model->appointments()
            ->with(['doctor' => function ($q) {
                $q->select('id', 'name', 'lastname');
            }])
            ->orderBy('date', 'desc')
            ->paginate(5);

        $rates = $model->rates()
            ->orderBy('id', 'desc')
            ->get();

        $tests = Test::query()->with('testType', 'doctor')->where('patient_id', $model->id)->latest()->take(3)->get();

        return inertia('Patients/Index/Index', compact('model', 'appointments', 'rates', 'tests'));
    }
}
