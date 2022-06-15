<?php

namespace App\Http\Controllers\Patients;

use App\Domain\Patients\PatientAuthRepositoryContract;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Test;
use Illuminate\Http\Request;

use Carbon\Carbon;

class SeeTestsAction extends Controller
{
    public function __invoke(PatientAuthRepositoryContract $repo, Request $request)
    {
        $model = $repo->getAuthenticatedPatient();

        $appointments = $model->appointments()
            ->with(['doctor' => function ($q) {
                $q->select('id', 'name', 'lastname');
            }])
            ->where('date', '>', Carbon::now()->subDay(1)->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

            /**
             * 1. La busqueda debe resetear el numero de pagina, pequeño error
             * 2. El error, para variar era pq el doctor no tenía ninguna subfamilia
             */
            
        $rates = $model->rates()
            ->orderBy('id', 'desc')
            ->get();
        
        
        $tests = Test::query()->with('testType', 'doctor')->where('patient_id', $model->id)->latest()->take(3)->get();

        return inertia('Patients/Index/SeeTests', compact('model', 'appointments', 'rates','tests'));
    }
}
