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
use App\Models\HCAttribute;
use App\Models\HCType;
use App\Models\HistoryData;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    public function show($id)
    {
        $model = MedicalHistory::with('patient', 'doctor')->get()->find($id);

        $data = HistoryData::query()
            ->with('attribute')
            ->where('history_id', $id)
            ->where('is_revision', false)
            ->get();

        $areas = AffectedArea::all();
        $treatments = Treatment::all();
        $diagnostics = Diagnostic::all();

        return inertia('Backend/Patients/MedicalHistories/Index', 
            compact('model', 'data', 'areas', 'treatments', 'diagnostics'));
    }

    public function selectType($id)
    {
        $types = HCType::all();

        return inertia('Backend/Patients/MedicalHistories/SelectType', compact(
            'types',
            'id'
        ));
    }

    public function create($id, $type)
    {
        $history_group = HistoryGroup::find($id);

        $attributes = HCAttribute::query()
            ->where('history_type_id', $type)
            ->get();

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
                'affected_areas',
                'attributes'
            ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'numeric'],
            'doctor_id' => ['required', 'numeric'],    
            'history_group_id' => ['required', 'numeric'],
            'attributes' => ['required']
        ]);
        
        $history = MedicalHistory::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'history_group_id' => $validated['history_group_id'],
        ]);

        $attributes = $validated["attributes"];
        foreach($attributes as $key => $item)
        {
            //Nice hack to have multiple attributes in one single column
            $attr = HCAttribute::find($key);
            if($attr->input_type == HCAttribute::ATTRIBUTE_MULTI)
            {
                $newItem = "";
                foreach($item as $i)
                {
                    $newItem .= $i."^";
                }
                $item = $newItem;
            }

            HistoryData::create([
                'history_id' => $history->id,
                'data' => $item,
                'attribute_id' => $key
            ]);
        }

        return redirect()->route('patients.historygroup.show', $request->history_group_id);
    }
}
