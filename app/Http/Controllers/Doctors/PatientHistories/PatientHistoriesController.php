<?php

namespace App\Http\Controllers\Doctors\PatientHistories;

use App\Domain\ClinicHistories\ClinicHistoriesRepo;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientHistory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientHistoriesController extends Controller
{
    public function index(Patient $patient)
    {
        $patientId = $patient->id;

        $rows = $patient->histories()
            ->select('id', 'name', 'patient_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->with([
                'patient' => function ($q) {
                    $q->select('id', 'name', 'lastname1', 'lastname2');
                }
            ])
            ->get();

        return inertia(
            'Backend/Patients/Histories/Index',
            compact('patientId', 'rows')
        );
    }

    public function create(ClinicHistoriesRepo $repo, $id)
    {
        $patientId = $id;
        $formTypes = $repo->getFormTypesWithName();

        return inertia(
            'Backend/Patients/Histories/Create',
            compact('patientId', 'formTypes')
        );
    }

    public function store(ClinicHistoriesRepo $repo, Request $request, Patient $patient)
    {
        $request->validate([
            'formType' => [
                'required',
                'string',
                Rule::in(
                    $repo->getFormTypes()
                )
            ]
        ]);

        $formName = $repo->getFormName($request->formType);
        $jsonFields = $repo->getFormFieldsWithEmptyValues($request->formType);

        $history = $patient->histories()
            ->create([
                'name' => $formName,
                'json' => $jsonFields
            ]);

        return redirect()->route('histories.edit', $history);
    }

    public function edit(PatientHistory $patientHistory)
    {
        $id = $patientHistory->id;
        $model = $patientHistory->json;

        return inertia('Backend/Patients/Histories/Edit', compact('id', 'model'));
    }

    public function update(Request $request, PatientHistory $patientHistory)
    {
        $patientHistory->json = $request->data;
        $patientHistory->save();

        return redirect()->route('patients.histories.index', $patientHistory->patient_id);
    }

    public function destroy(Request $request, PatientHistory $patientHistory)
    {
        // $patientHistory->delete();

        toast('danger', 'Las historias clínicas no se pueden eliminar');

        return redirect()->route('patients.histories.index', $patientHistory->patient_id);
    }
}
