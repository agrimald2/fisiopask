<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class IndexAppointmentAction extends Controller
{
    public function __invoke(Request $request)
    {
        $searchQuery = $request->searchQuery;

        $model = $this->getModels($searchQuery);

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model->items(),

            'links' => $model->linkCollection(),

            'parameters' => $request->all(),

            'title' => 'Lista de Citas',

            'create' => null,

            'grid' => 'Doctors/Appointments/grid.js',
        ]);
    }


    private function getModels($searchQuery)
    {
        $appointments = $this->getAppointments();

        return $appointments
            ->with('patient')
            ->whereHas('patient', function ($q) use ($searchQuery) {
                $q->when($searchQuery, function ($q, $value) {
                    $q->where(function ($q) use ($value) {
                        $q->where('name', 'LIKE', "%$value%")
                            ->orWhere('lastname1', 'LIKE', "%$value%")
                            ->orWhere('lastname2', 'LIKE', "%$value%")
                            ->orWhere('dni', 'LIKE', "%$value%")
                            ->orWhere('phone', 'LIKE', "%$value%");
                    });
                });
            })
            ->orderBy('date', 'desc')
            ->paginate(15);
    }

    private function getAppointments()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return Appointment::query();
        }

        return $user
            ->doctor
            ->appointments();
    }
}
