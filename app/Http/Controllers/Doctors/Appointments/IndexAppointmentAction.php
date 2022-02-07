<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Office;
use Illuminate\Http\Request;

class IndexAppointmentAction extends Controller
{
    public function __invoke(Request $request)
    {
        $searchQuery = $request->searchQuery;
        $dateQueryFrom = $request->dateQueryFrom;
        $dateQueryTo = $request->dateQueryTo;
        $doctorQuery = $request->doctorQuery;
        $officeQuery = $request->officeQuery;
        $canSearchByDoctor = false;
        $doctors = Doctor::query()->get();
        $offices = Office::query()->get();

        $model = $this->getModels($searchQuery, $dateQueryFrom, $dateQueryTo, $doctorQuery, $officeQuery);

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $canSearchByDoctor = true;
        }

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model->items(),

            'links' => $model->linkCollection(),

            'parameters' => $request->all(),

            'title' => 'Lista de Citas',

            'create' => null,

            'grid' => 'Doctors/Appointments/grid.js',

            'doctors' => $doctors,

            'offices' => $offices,

            'enableDateSearch' => true,

            'enableOfficeSearch' => true,   

            'enableDoctorSearch' => $canSearchByDoctor,

            'enableOfficeSearch' => true,
        ]);
    }


    private function getModels($searchQuery, $dateQueryFrom, $dateQueryTo, $doctorQuery, $officeQuery)
    {
        $appointments = $this->getAppointments();

        return $appointments
            ->with('patient')
            ->whereHas('patient', function ($q) use ($searchQuery) {
                $q->when($searchQuery, function ($q, $value) {
                    $q->where('name', 'LIKE', "%$value%")
                    ->orWhere('lastname1', 'LIKE', "%$value%")
                    ->orWhere('lastname2', 'LIKE', "%$value%")
                    ->orWhere('dni', 'LIKE', "%$value%")
                    ->orWhere('phone', 'LIKE', "%$value%")
                    ->orWhere('office', 'LIKE', "%$value%");
                });
            })
            ->whereHas('patient', function ($q) use ($dateQueryFrom, $dateQueryTo) {
                if(!(empty($dateQueryFrom) || empty($dateQueryTo)))
                    $q->whereBetween('date', [$dateQueryFrom, $dateQueryTo]);
            })
            ->whereHas('patient', function ($q) use ($doctorQuery) {
                $q->when($doctorQuery, function ($q, $value) {
                    $q->where(function ($q) use ($value) {
                        $q->where('doctor_id', 'LIKE', "%$value%");
                    });
                });
            })
            ->where('office', 'LIKE', $officeQuery)
            ->orderBy('date', 'desc')
            ->paginate(15);
    }

    private function getAppointments()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return Appointment::query();
        }

        if ($user->hasRole('assistant')) {
            return Appointment::query();
        }

        return $user
            ->doctor
            ->appointments();
    }
}
