<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Office;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexAppointmentAction extends Controller
{
    public function __invoke(Request $request)
    {
        $searchQuery = $request->searchQuery;
        $dateQueryFrom = $request->dateQueryFrom;
        $dateQueryTo = $request->dateQueryTo;
        $doctorQuery = $request->doctorQuery;
        $officeQuery = $request->officeQuery;
        
        $statusQuery = $request->statusQuery;

        $fetchAll = $request->fetchAll;
        $canSearchByDoctor = false;
        $doctors = Doctor::query()->get();
        $offices = Office::query()->get();

        $date = new DateTime();

        $dateFormated = $date->format('Y-m-d');

        $rData = [ 
            'searchQuery' => $request->searchQuery, 
            'dateQueryFrom' => $request->dateQueryFrom, 
            'dateQueryTo' => $request->dateQueryTo, 
            'doctorQuery' => $request->doctorQuery, 
            'officeQuery' => $request->officeQuery,
            'statusQuery' => $request->statusQuery,
            'fetchAll' => true,
        ];

        if(!$fetchAll)
        {
            if(!$request->page)
            {
                $rData['dateQueryTo'] = $dateFormated;
                $rData['dateQueryFrom'] = $dateFormated;
                return redirect()->route('doctors.appointments.index', $rData);
            }
        }

        $model = $this->getModels($searchQuery, $dateQueryFrom, $dateQueryTo, $doctorQuery, $officeQuery, $statusQuery );

        $user = auth()->user();
        if ($user->hasRole('admin') || $user->hasRole('assistant')) {
            $canSearchByDoctor = true;
        }

        $model->appends($_GET)->links();

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


    private function getModels($searchQuery, $dateQueryFrom, $dateQueryTo, $doctorQuery, $officeQuery, $statusQuery)
    {        
        $appointments = DB::table('appointments')
            ->join('patients', 'patients.id', '=', 'appointments.patient_id')
            ->select(DB::raw("appointments.*, patients.*, appointments.id as id"));

        if(!empty($statusQuery))
        {
            $appointments
                ->where('status', 'LIKE', $statusQuery);
        }

        if(!empty($officeQuery))
        {
            $appointments
                ->where('office', 'LIKE', $officeQuery);
        }
            
        if(!(empty($dateQueryFrom) || empty($dateQueryTo)))
        {
            $appointments
                ->whereBetween('date', array($dateQueryFrom, $dateQueryTo));
        }

        if(!empty($doctorQuery)) $appointments->where('doctor_id', $doctorQuery);

        if(!empty($searchQuery))
        {
            $appointments->where(function($q) use ($searchQuery) {
                $q->where('dni', 'LIKE', $searchQuery)
                    ->orWhere('phone', 'LIKE', $searchQuery)
                    ->orWhere('name', 'LIKE', $searchQuery)
                    ->orWhere('lastname1', 'LIKE', $searchQuery)
                    ->orWhere('lastname2', 'LIKE', $searchQuery)
                    ->orWhere(DB::raw("CONCAT(`name`, ' ', `lastname1`, ' ', `lastname2`)"), 'LIKE', $searchQuery)
                    ->orWhere(DB::raw("CONCAT(`name`, ' ', `lastname1`)"), 'LIKE', $searchQuery)
                    ->orWhere(DB::raw("CONCAT(`name`, ' ', `lastname2`)"), 'LIKE', $searchQuery)
                    ->orWhere(DB::raw("CONCAT(`lastname1`, ' ', `lastname2`)"), 'LIKE', $searchQuery);
            });
        }
        
        $appointments
            ->orderBy('date', 'desc')
            ->orderBy('start', 'desc');

        $result = $appointments->paginate(120);

        return $result;
    }    
}
