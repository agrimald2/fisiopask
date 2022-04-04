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
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    public function show($id)
    {
        $model = MedicalHistory::with('patient','treatment','diagnostic','analysis','doctor','affectedArea', 'historyTreatments.treatment')->get()->find($id);

        $treatments = HistoryTreatment::query()->where('medical_history_id', $model->id)->get();

        return inertia('Backend/Patients/MedicalHistories/Index', compact('model', 'treatments'));
    }

    public function create($id)
    {
        $history_group = HistoryGroup::find($id);

        $diagnostics = Diagnostic::query()
            ->orderBy('id', 'desc')
            ->get();

        $treatments = Treatment::query()
            ->orderBy('name', 'asc')
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

            't1' => '',
            't2' => '',
            't3' => '',
    
            'history_group_id' => 'required',
        ]);

        $treatments = [];
        array_push($treatments, $validated["treatment_id"]);
        array_push($treatments, $validated["t1"]);
        array_push($treatments, $validated["t2"]);
        array_push($treatments, $validated["t3"]);

        $appointment = Appointment::query()->where('patient_id', $validated["patient_id"])->where('status', Appointment::STATUS_ASSISTED)->orderBy('date', 'desc')->first();

        if($appointment)
        {
            $appointment->history_created = true;
            $appointment->save();
        }

        $model = MedicalHistory::create($validated);

        foreach($treatments as $treatment)
        {
            if($treatment != null)
            {
                HistoryTreatment::Create([
                    'treatment_id' => $treatment,
                    'medical_history_id' => $model->id,
                ]);
            }
        }

        return redirect()->route('patients.historygroup.show', $request->history_group_id);
    }
}
