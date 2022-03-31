<?php

namespace App\Http\Controllers\Backend;

use App\Models\Treatment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $model = Treatment::query()->orderBy('name', 'asc')->get();

        return inertia('Backend/MedicalHistories/Treatments/Index', compact('model'));
    }

    public function create()
    {
        return inertia('Backend/MedicalHistories/Treatments/CreateEdit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        Treatment::create($validated);

        return redirect()->route('treatment.index');        
    }

    public function edit($id)
    {
        $model = Treatment::find($id);

        return inertia('Backend/MedicalHistories/Treatments/CreateEdit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $object = Treatment::find($id);
        $object->update($validated);

        toast('success', 'Tratamiento atualizado con éxito');
        return redirect()->route('treatment.index');
    }

    public function destroy($id)
    {
        Treatment::destroy($id);

        toast('success', "Tratamiento eliminado.");
        return redirect()->route('treatment.index');
    }
}