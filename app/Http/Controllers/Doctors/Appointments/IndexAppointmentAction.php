<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient; // Added Patient model
use App\Models\Office;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;
use Log;

class IndexAppointmentAction extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $isDoctor = $user->hasRole('doctor');
        $isDoc = 'ADMIN';
        $isAssistant = $user->hasRole('assistant');
        

        $searchQuery = $request->searchQuery;
        $dateQueryFrom = $request->dateQueryFrom;
        $dateQueryTo = $request->dateQueryTo;
        $doctorQuery = $request->doctorQuery;
        $officeQuery = $request->officeQuery;
        $isNew = $request->isNew;
        $haveBalance = $request->haveBalance;


        $statusQuery = $request->statusQuery;


        $fetchAll = $request->fetchAll;
        $canSearchByDoctor = false;

        $doctors = null;

        if($isDoctor)
        {
            $doctors = Doctor::query()->where('user_id', $user->id)->get();
            $doctorQuery = $doctors[0]->id;
            $isDoc = 'DOC';
        }
        else
        {
            $doctors = Doctor::query()->get();
            $isDoc = 'NO-DOC';
        }

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
            'isNew' => $request->isNew,
            'haveBalance' => $request->haveBalance,
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

        $model = $this->getModels($searchQuery, $dateQueryFrom, $dateQueryTo, $doctorQuery, $officeQuery, $statusQuery, $isNew, $haveBalance);

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

            'isAssistant' => $isAssistant
        ]);
    }


    private function getModels($searchQuery, $dateQueryFrom, $dateQueryTo, $doctorQuery, $officeQuery, $statusQuery, $isNew, $haveBalance)
    {
        $appointments = DB::table('appointments')
            ->join('patients', 'patients.id', '=', 'appointments.patient_id')
            ->leftJoinSub(
                DB::table('patient_rates')
                    ->select('patient_id', DB::raw('MAX(id) as id'))
                    ->groupBy('patient_id'),
                'latest_patient_rates',
                function ($join) {
                    $join->on('patients.id', '=', 'latest_patient_rates.patient_id');
                }
            )
            ->join('patient_rates', function ($join) {
                $join->on('patients.id', '=', 'patient_rates.patient_id')
                     ->on('latest_patient_rates.id', '=', 'patient_rates.id');
            }) // Added patient_rates join
            ->select('appointments.*', 'patients.*', 'patient_rates.*', 'appointments.id as id');

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
                    ->orWhere('lastname1', 'LIKE', $searchQuery)
                    ->orWhere('lastname2', 'LIKE', $searchQuery)
                    ->orWhere(DB::raw("CONCAT(`lastname1`, ' ', `lastname2`)"), 'LIKE', $searchQuery)
                    ->orWhere(DB::raw("CONCAT(`lastname1`)"), 'LIKE', $searchQuery)
                    ->orWhere(DB::raw("CONCAT(`lastname2`)"), 'LIKE', $searchQuery)
                    ->orWhere(DB::raw("CONCAT(`lastname1`, ' ', `lastname2`)"), 'LIKE', $searchQuery);
            });
        }

        if(!empty($isNew)){
            if($isNew === "new") {
                $appointments->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('patient_payments')
                          ->whereRaw('patient_payments.patient_id = patients.id');
                });
            }else if($isNew === "old"){
                $appointments->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('patient_payments')
                          ->whereRaw('patient_payments.patient_id = patients.id');
                });
            }
        }

        Log::debug($haveBalance);
        // Added balance filter
        if(!empty($haveBalance)){
            if($haveBalance === "true") {
                $appointments->whereRaw('patient_rates.payed > (patient_rates.price * (patient_rates.sessions_total - patient_rates.sessions_left))');
            } elseif($haveBalance === "false") {
                $appointments->whereRaw('patient_rates.payed <= (patient_rates.price * (patient_rates.sessions_total - patient_rates.sessions_left))');
            }
        }

        $appointments
            ->orderBy('date', 'desc')
            ->orderBy('start', 'desc');

        $result = $appointments->paginate(120);

        return $result;
    }
}

