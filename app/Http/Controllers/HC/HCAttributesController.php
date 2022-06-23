<?php

namespace App\Http\Controllers\HC;

use App\Http\Controllers\Controller;
use App\Models\HCAttribute;
use Illuminate\Http\Request;

class HCAttributesController extends Controller
{
    public function index($id)
    {
        $model = HCAttribute::query()->where('history_type_id', $id)->get();

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,
            'title' => 'Atributo de Historia Clínica',
            'create' => 'attributes/create',
            'grid' => 'Backend/HC/Attributes/Index.js',
        ]);
    }

    public function create($id)
    {
        $types = [
            0 => 'Texto',
            1 => 'Numérico',
            2 => 'Select',
            3 => 'Multi-Select',
            //4 => 'Checkbox',
        ];

        $models = [
            0 => 'Ninguno',
            1 => 'Áreas Afectadas',
            2 => 'Diagnósticos',
            3 => 'Tratamientos',
            
            4 => 'Apetito',
            5 => 'Sed',
            6 => 'Sueño',
            7 => 'Variación de peso',
            8 => 'Diuresis',
            9 => 'Deposiciones',

            10 => 'Estado de Ánimo',

            11 => 'Antecedentes',
        ];

        return inertia('Backend/HC/Attributes/CreateEdit', compact('id', 'models', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'input_name' => ['required', 'string'],
            'type_id' => ['required', 'numeric'],
            'input_type' => ['required', 'numeric'],
            'related_model' => ['required', 'numeric']
        ]);

        $count = count(HCAttribute::query()->where('history_type_id', $validated['type_id'])->get());

        HCAttribute::create([
            'input_name' => $validated['input_name'],
            'index' => $count,
            'input_type' => $validated['input_type'],
            'history_type_id' => $validated['type_id'],
            'related_model' => $validated['related_model'],
        ]);

        return redirect()->route('hc.attributes.index', $validated['type_id']);
    }

    public function edit($id) 
    {
        $types = [
            0 => 'Texto',
            1 => 'Numérico',
            2 => 'Select',
            3 => 'Multi-Select',
            //4 => 'Checkbox',
        ];

        $models = [
            0 => 'Ninguno',
            1 => 'Áreas Afectadas',
            2 => 'Diagnósticos',
            3 => 'Tratamientos',
            
            4 => 'Apetito',
            5 => 'Sed',
            6 => 'Sueño',
            7 => 'Variación de peso',
            8 => 'Diuresis',
            9 => 'Deposiciones',

            10 => 'Estado de Ánimo',

            11 => 'Antecedentes',
        ];

        $model = HCAttribute::find($id);

        return inertia('Backend/HC/Attributes/CreateEdit', compact('models', 'model', 'types'));
    }

    public function update(Request $request, $id)
    {
        $attribute = HCAttribute::find($id);

        $validated = $request->validate([
            'input_name' => ['required', 'string'],
            'input_type' => ['required', 'numeric'],
            'related_model' => ['required', 'numeric']
        ]);

        $attribute->input_name = $validated["input_name"];
        $attribute->input_type = $validated["input_type"];
        $attribute->related_model = $validated["related_model"];
        $attribute->save();

        return redirect()->route('hc.attributes.index', $attribute->history_type_id);
    }

    public function destroy($id)
    {
        HCAttribute::destroy($id);
        return redirect()->back();
    }
}
