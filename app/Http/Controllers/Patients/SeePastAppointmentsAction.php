<?php

namespace App\Http\Controllers\Patients;

use App\Domain\Patients\PatientAuthRepositoryContract;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

use Carbon\Carbon;

class SeePastAppointmentsAction extends Controller
{
    public function __invoke(PatientAuthRepositoryContract $repo, Request $request)
    {
        $model = $repo->getAuthenticatedPatient();

        $appointmentsAll = $model->appointments()
            ->with(['doctor' => function ($q) {
                $q->select('id', 'name', 'lastname');
            }])
            ->where('date', '<', Carbon::now()->format('Y-m-d'))
            ->orderBy('date', 'desc')
            ->get();

        $appointments = [];

        $i = 0;
            
        foreach($appointmentsAll as $appointment)
        {
            if($i < 10)
            {
                array_push($appointments, $appointment);
                $i++;
            }
        }

            /**
             * 1. La busqueda debe resetear el numero de pagina, pequeño error
             * 2. El error, para variar era pq el doctor no tenía ninguna subfamilia
             */

            
        $rates = $model->rates()
            ->orderBy('id', 'desc')
            ->get();

        return inertia('Patients/Index/SeePastAppointments', compact('model', 'appointments', 'rates'));
    }
}
