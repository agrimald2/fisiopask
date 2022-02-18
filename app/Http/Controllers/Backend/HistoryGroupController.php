<?php

namespace App\Http\Controllers\Backend;

use App\Models\Patient;
use App\Models\HistoryGroup;
use App\Models\MedicalRevision;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryGroupController extends Controller
{
    public function index(Patient $patient, Request $request)
    {
        $patientId = $patient->id;
        $doctor = $request->user()->doctor;

        $rows = $patient->historyGroup()
            ->select('id', 'patient_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->with([
                'patient' => function ($q) {
                    $q->select('id', 'name', 'lastname1', 'lastname2');
                }
            ])
            ->get();

        return inertia(
            'Backend/Patients/HistoryGroups/Index',
            compact('patientId', 'rows', 'doctor')
        );
    }

    public function store($patientId, $doctorId)
    {
        $group = HistoryGroup::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'closed' => false,
        ]);

        $id = $group->id;

        return redirect()->route('medicalhistory.create', compact('id'));
    }

    public function show($id)
    {
        $group = HistoryGroup::find($id);

        $medicalHistory = $group->medicalHistory()->with('doctor')->get();

        $revisions = $group->medicalRevision()->with('doctor')->get();

        $files = $group->medicalHistoryFile()->get();

        return inertia(
            'Backend/Patients/HistoryGroups/Show', 
            compact('id', 'medicalHistory', 'revisions', 'files'));
    }
}
