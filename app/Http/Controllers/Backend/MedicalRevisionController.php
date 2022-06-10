<?php

namespace App\Http\Controllers\Backend;

use App\Models\HistoryGroup;
use App\Models\MedicalRevision;

use App\Models\Treatment;
use App\Models\HistoryTreatment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class MedicalRevisionController extends Controller
{
    public function show($id)
    {
        $model = MedicalRevision::with('patient','doctor')->find($id);

        $treatments = HistoryTreatment::query()->with('treatment')->where('history_id', $model->id)->where('isRevision', true)->get();

        return inertia('Backend/Patients/MedicalRevisions/Index', compact('model', 'treatments'));
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

        $model = MedicalRevision::create($validated);

        foreach($treatments as $treatment)
        {
            if($treatment != null)
            {
                HistoryTreatment::Create([
                    'treatment_id' => $treatment,
                    'history_id' => $model->id,
                    'isRevision' => true,
                ]);
            }
        }

        $appointment = Appointment::query()->where('patient_id', $validated["patient_id"])->where('status', Appointment::STATUS_ASSISTED)->orderBy('date', 'desc')->first();

        if($appointment)
        {
            $appointment->history_created = true;
            $appointment->save();
        }

        return redirect()->route('patients.historygroup.show', $request->history_group_id);
    }
}
