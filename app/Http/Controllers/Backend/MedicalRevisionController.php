<?php

namespace App\Http\Controllers\Backend;

use App\Models\HistoryGroup;
use App\Models\MedicalRevision;

use App\Models\Treatment;
use App\Models\HistoryTreatment;

use App\Http\Controllers\Controller;
use App\Models\AffectedArea;
use App\Models\Analysis;
use App\Models\Appointment;
use App\Models\Diagnostic;
use App\Models\HCAttribute;
use App\Models\HistoryData;
use App\Models\MedicalHistory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class MedicalRevisionController extends Controller
{
    public function show($id)
    {
        $model = MedicalRevision::with('patient','doctor')->find($id);

        $data = HistoryData::query()
            ->with('attribute')
            ->where('history_id', $id)
            ->where('is_revision', true)
            ->get();

        $areas = AffectedArea::all();
        $treatments = Treatment::all();
        $diagnostics = Diagnostic::all();

        return inertia('Backend/Patients/MedicalRevisions/Index',
            compact('model', 'data', 'areas', 'treatments', 'diagnostics'));
    }

    public function pdf($id){
        $history = MedicalHistory::find($id);
        $patient = Patient::find($history->patient_id);
        $age = date_diff(date_create($patient->birth_date), date_create('now'))->y;

        $data = [];

        $hd = HistoryData::query()
                    ->where('history_id', $id)
                    ->where('is_revision', false)
                    ->get();

        foreach($hd as $d)
        {
            $attr = HCAttribute::find($d->attribute_id);
            
            $value = $this->getValueFromData($d, $attr);

            $info = [
                'name' => $attr->input_name,
                'value' => $value,
            ];

            array_push($data, $info);
        }

        $code = 1000000 + $id;

        return view('pdf.medical_history', compact('patient', 'age', 'data', 'code'));
    }

    public function revpdf($id){
        $history = MedicalRevision::find($id);
        $patient = Patient::find($history->patient_id);
        $age = date_diff(date_create($patient->birth_date), date_create('now'))->y;

        $data = [];

        $hd = HistoryData::query()
                ->where('history_id', $id)
                ->where('is_revision', true)
                ->get();

        foreach($hd as $d)
        {
            $attr = HCAttribute::find($d->attribute_id);
            
            $value = $this->getValueFromData($d, $attr);

            $info = [
                'name' => $attr->input_name,
                'value' => $value,
            ];

            array_push($data, $info);
        }

        $code = 2000000 + $id;

        return view('pdf.medical_history', compact('patient', 'age', 'data', 'code'));
    }

    private function getValueFromData($data, $attr)
    {
        if($attr->input_type <= 1)
        {
            return $data->data;
        }
        else
        {
            $model = $attr->related_model;

            $r = "";

            $first = true;

            foreach(explode('^', $data->data) as $exploded)
            {
                if(!$first)
                {
                    $r .= " - ";
                }
                $r .= $this->getModeledData($data, $model);
                $first = false;
            }

            return $r;
        }
    }

    private function getModeledData($data, $model)
    {
        switch($model)
        {
            case 0:
                return $data;
                break;
            case 1:
                $x = AffectedArea::find($data);
                return $x[0]->category . " " . $x[0]->sub_category;
                break;
            case 2:
                $x = Diagnostic::find($data);
                return $x[0]->name;
                break;
            case 3:
                $x = Treatment::find($data);
                return $x[0]->name;
                break;
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                if($data->data == 0) return "Aumentado";
                else if($data->data == 1) return "Conservado";
                else if($data->data == 2) return "Disminuido";
                break;
            case 10:
                if($data->data == 0) return "Eutímico";
                else if($data->data == 1) return "Distímico";
                break;
            case 11:
                if($data->data == 0) return "Sí";
                else if($data->data == 1) return "No";
                break;
        }
    }

    public function create($id)
    {
        $history_group = HistoryGroup::find($id);

        $attributes = HCAttribute::query()
            ->where('history_type_id', $history_group->type_id)
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
            'Backend/Patients/MedicalRevisions/Create',
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

        $revision = MedicalRevision::create([
            'patient_id' => $validated["patient_id"],
            'doctor_id' => $validated["doctor_id"],
            'history_group_id' => $validated["history_group_id"],
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
                'history_id' => $revision->id,
                'data' => $item,
                'attribute_id' => $key,
                'is_revision' => true,
            ]);
        }

        return redirect()->route('patients.historygroup.show', $request->history_group_id);
    }
}
