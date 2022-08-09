<?php

namespace App\Http\Controllers\Backend;

use App\Models\Patient;
use App\Models\HistoryGroup;
use App\Models\MedicalRevision;

use App\Http\Controllers\Controller;
use App\Models\HCType;
use App\Models\Test;
use Illuminate\Http\Request;

class HistoryGroupController extends Controller
{
    public function index(Patient $patient, Request $request)
    {
        $patientId = $patient->id;
        $doctor = $request->user()->doctor;

        $hgs = $patient->historyGroup()
            ->select('id', 'patient_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->with([
                'patient' => function ($q) {
                    $q->select('id', 'name', 'lastname1', 'lastname2');
                }, 'medicalHistory'
            ])
            ->get();

        $rows = [];

        foreach($hgs as $hg)
        {
            if($hg->medicalHistory)
            {
                array_push($rows, $hg);
            }
        }

        return inertia(
            'Backend/Patients/HistoryGroups/Index',
            compact('patientId', 'rows', 'doctor')
        );
    }

    public function selectType($patientId, $doctorId)
    {
        $types = HCType::all();

        return inertia('Backend/Patients/MedicalHistories/SelectType', compact(
            'patientId',
            'doctorId',
            'types',
        ));
    }

    public function store(Request $request)//$patientId, $doctorId)
    {
        $group = HistoryGroup::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'type_id' => $request->type_id,
            'closed' => false,
        ]);

        $id = $group->id;
        $type = $group->type_id;

        return redirect()->route('medicalhistory.create', compact('id', 'type'));
    }

    public function show($id)
    {
        $group = HistoryGroup::find($id);

        $medicalHistory = $group->medicalHistory()->with('doctor')->get();

        $revisions = $group->medicalRevision()->with('doctor')->get();

        $files = $group->medicalHistoryFile()->get();

        $tests = Test::query()
                    ->with('doctor', 'patient', 'company', 'testType', 'results')
                    ->where('patient_id', $group->patient_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return inertia(
            'Backend/Patients/HistoryGroups/Show', 
            compact('id', 'medicalHistory', 'revisions', 'files', 'tests'));
    }
}
