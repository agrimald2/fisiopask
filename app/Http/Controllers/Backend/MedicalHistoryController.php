<?php

namespace App\Http\Controllers\Backend;

use App\Models\HistoryGroup;
use App\Models\MedicalHistory;

use App\Models\Diagnostic;
use App\Models\Treatment;
use App\Models\HistoryTreatment;
use App\Models\Analysis;
use App\Models\AffectedArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    public function show($id)
    {
        $model = MedicalHistory::with('patient','treatment','diagnostic','analysis','doctor','affectedArea', 'historyTreatments.treatment')->get()->find($id);

        return inertia('Backend/Patients/MedicalHistories/Index', compact('model'));
    }

    public function create($id)
    {
        $history_group = HistoryGroup::find($id);

        $diagnostics = Diagnostic::query()
            ->orderBy('id', 'desc')
            ->get();

        $treatments = Treatment::query()
            ->orderBy('id', 'desc')
            ->get();

        $analysis = Analysis::query()
            ->orderBy('id', 'desc')
            ->get();

        $affected_areas = AffectedArea::query()
            ->orderBy('id', 'desc')
            ->get();

        return inertia(
            'Backend/Patients/MedicalHistories/Create', 
            compact(
                'history_group', 
                'diagnostics', 
                'treatments', 
                'analysis',
                'affected_areas'
            ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',

            'background' => 'required',
            'warnings' => 'required',
            'description' => 'required',
    
            'pain_scale' => 'required',
            'force_scale' => 'required',
            'joint_range' => 'required',
            'recovery_progress' => 'required',
    
            'diagnostic_id' => 'required',
            'treatment_id' => 'required',
            'analysis_id' => 'required',
            'affected_area_id' => 'required',
    
            'history_group_id' => 'required',
        ]);

        MedicalHistory::create($validated);

        return redirect()->route('patients.historygroup.show', $request->history_group_id);
    }
}
