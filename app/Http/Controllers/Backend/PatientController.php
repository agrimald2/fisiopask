<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $model = patients()->index($request->searchQuery);

        return inertia('Backend/Dynamic/Grid', [
            'model' => collect($model->items())->each->append('link'),

            'links' => $model->linkCollection(),

            'parameters' => $request->all(),

            'title' => 'Lista de Pacientes',

            'create' => null,

            'grid' => 'Backend/Patients/grid.js',
        ]);
    }


    public function edit(Patient $patient)
    {
        return inertia('Backend/Dynamic/Form', [
            'title' => [
                'resource' => 'Pacientes',
                'action' => 'Editar',
                'url' => route('patients.index'),
            ],

            'form' => 'Backend/Patients/form.js',
            'model' => $patient,

            'sexOptions' => config('doctors.sex'),
        ]);
    }


    public function update(Patient $patient, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'lastname1' => 'required|string',
            'lastname2' => 'required|string',
            'dni' => 'required',
            'birth_date' => 'required|date:Y-m-d',
            'sex' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|integer',
            'district' => 'nullable|string',
        ]);

        $patient->update($validated);

        toast('success', 'Actualizado con éxito!');
        return redirect()->back();
    }


    public function destroy(Patient $patient)
    {
        $patient->delete();

        toast('success', 'Eliminado con exito!');
        return redirect()->route('patients.index');
    }
}
