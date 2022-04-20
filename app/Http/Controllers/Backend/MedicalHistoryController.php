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
use App\Models\HistoryAnalysis;
use App\Models\HistoryArea;
use App\Models\Patient;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    public function show($id)
    {
        $model = MedicalHistory::with('patient','treatment','diagnostic','analysis','doctor','affectedArea')->find($id);

        $treatments = HistoryTreatment::query()->with('treatment')->where('history_id', $model->id)->where('isRevision', false)->get();
        $analyses = HistoryAnalysis::query()->with('analysis')->where('history_id', $model->id)->where('isRevision', false)->get();
        $areas = HistoryArea::query()->with('affectedArea')->where('history_id', $model->id)->where('isRevision', false)->get();

        return inertia('Backend/Patients/MedicalHistories/Index', compact('model', 'treatments', 'analyses', 'areas'));
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
            
            'history_group_id' => 'required',
        ]);

        $treatments = [];
        $analyses = [];
        $areas = [];
        
        $appointment = Appointment::query()->where('patient_id', $validated["patient_id"])->where('status', Appointment::STATUS_ASSISTED)->orderBy('date', 'desc')->first();
        
        if($appointment)
        {
            $appointment->history_created = true;
            $appointment->save();
        }
        
        $model = MedicalHistory::create([
            'patient_id' => $validated["patient_id"],
            'doctor_id' => $validated["doctor_id"],
            'background' => $validated["background"],
            'warnings' => $validated["warnings"],
            'description' => $validated["description"],
            'pain_scale' => $validated["pain_scale"],
            'force_scale' => $validated["force_scale"],
            'joint_range' => $validated["joint_range"],
            'recovery_progress' => $validated["recovery_progress"],
            'history_group_id' => $validated["history_group_id"],
            'diagnostic_id' => $validated["diagnostic_id"],
            'analysis_id' => 0,
            'treatment_id' => 0,
            'affected_area_id' => 0,
        ]);

        foreach($validated["analysis_id"] as $anal) array_push($analyses, $anal);
        foreach($validated["treatment_id"] as $treat) array_push($treatments, $treat);
        foreach($validated["affected_area_id"] as $area) array_push($areas, $area);

        foreach($treatments as $treatment)
        {
            if($treatment != null)
            {
                HistoryTreatment::Create([
                    'treatment_id' => $treatment,
                    'history_id' => $model->id,
                    'isRevision' => false,
                ]);
            }
        }

        foreach($areas as $area)
        {
            if($area != null)
            {
                HistoryArea::Create([
                    'affected_area_id' => $area,
                    'history_id' => $model->id,
                    'isRevision' => false,
                ]);
            }
        }

        foreach($analyses as $anal)
        {
            if($anal != null)
            {
                HistoryAnalysis::Create([
                    'analisis_id' => $anal,
                    'history_id' => $model->id,
                    'isRevision' => false,
                ]);
            }
        }

        return redirect()->route('patients.historygroup.show', $request->history_group_id);
    }
}
