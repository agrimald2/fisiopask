<?php

namespace App\Http\Controllers\Backend;

use App\Models\HistoryGroup;
use App\Models\MedicalRevision;

use App\Models\Treatment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicalRevisionController extends Controller
{
    public function show($id)
    {
        $model = MedicalRevision::with('patient')->get()->find($id);

        return inertia('Backend/Patients/MedicalRevisions/Index', compact('model'));
    }

    public function create($id)
    {
        $history_group = HistoryGroup::find($id);

        $treatments = Treatment::query()
            ->orderBy('id', 'desc')
            ->get();

        return inertia(
            'Backend/Patients/MedicalRevisions/Create', 
            compact(
                'history_group',
                'treatments',
            ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',

            'description' => 'required',
    
            'pain_scale' => 'required',
            'force_scale' => 'required',
            'joint_range' => 'required',
            'recovery_progress' => 'required',
    
            'treatment_id' => 'required',
    
            'history_group_id' => 'required',
        ]);

        MedicalRevision::create($validated);

        return redirect()->route('patients.historygroup.show', $request->history_group_id);
    }
}
