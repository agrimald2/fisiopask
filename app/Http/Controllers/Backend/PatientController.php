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

        $model->appends($_GET)->links();

        return inertia('Backend/Dynamic/Grid', [
            'model' => collect($model->items())->each->append('link'),

            'links' => $model->linkCollection(),

            'parameters' => $request->all(),

            'title' => 'Lista de Pacientes',

            'create' => route('patients.create'),

            'grid' => 'Backend/Patients/grid.js',
        ]);
    }

    public function create()
    {   
        return inertia('Backend/Dynamic/Form', [
            'title' => [
                'resource' => 'Pacientes',
                'action' => 'Crear',
                'url' => route('patients.index'),
            ],

            'form' => 'Backend/Patients/form.js',

            'sexOptions' => config('doctors.sex'),
            
            'recommendations' => recommendations()->index(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'lastname1' => 'required',
            'lastname2' => 'required',
            'dni' => 'required',
            'birth_date' => 'required|date',
            'phone' => 'required',
            'email' => 'nullable',
            'district' => 'nullable',
            'sex' => 'required',
            'recommendation_id' => '',
        ]);

        patients()->create($validated);

        toast('success', 'Paciente creado correctamente');
        return redirect()->route('patients.index');
    }

    public function edit(Patient $patient)
    {   

        $recommendations = recommendations()->index();

        $recommendationsMap = [];

        foreach($recommendations as $recommendation) $recommendationsMap[$recommendation->id] = $recommendation->recommendation;

        $status = [
            "Soltero" => "Soltero",
            "Casado" => "Casado",
            "Viudo" => "Viudo",
            "Divorciado" => "Divorciado",
        ];

        $documentTypes = [
            "DNI" => "DNI",
            "CARNET DE EXTRANJERIA" => "CARNET DE EXTRANJERIA",
            "PASAPORTE" => "PASAPORTE",
            "OTRO" => "OTRO",
        ];

        $education = [
            "Sin Nivel/Inicial" => "Sin Nivel/Inicial",
            "Primaria" => "Primaria",
            "Secundaria" => "Secundaria",
            "Superior no universitaria" => "Superior no universitaria",
            "Superior universitaria" => "Superior universitaria",
        ];

        $religion = [
            "Cristiano" => "Cristiano",
            "Judío" => "Judío",
            "Musulmán" => "Musulmán",
            "Budista" => "Budista",
            "Irreligión" => "Irreligión",
            "Otro" => "Otro",
            "No sabe/No especifica" => "No sabe/No especifica",
        ];
        
        return inertia('Backend/Dynamic/Form', [
            'title' => [
                'resource' => 'Pacientes',
                'action' => 'Editar',
                'url' => route('patients.index')
            ],


            'form' => 'Backend/Patients/form.js',
            'model' => $patient,

            'sexOptions' => config('doctors.sex'),

            'recommendations' => $recommendationsMap,
            'status' => $status,
            'documentTypes' => $documentTypes,
            'education' => $education,
            'religion' => $religion,
        ]);
    }


    public function update(Patient $patient, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'lastname1' => 'required',
            'lastname2' => 'required',
            'document_type' => 'required',
            'dni' => 'required',
            'birth_date' => 'required|date:Y-m-d',
            'sex' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|integer',
            'district' => 'nullable|string',
            'address' => 'nullable|string',
            'insurance' => 'nullable|string',
            'education' => 'nullable|string',
            'ocupation' => 'nullable|string',
            'religion' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'status' => 'nullable|string',
            'recommendation_id' => '',
        ]);

        $patient->update($validated);

        toast('success', 'Actualizado con éxito!');
        return redirect()->route('patients.index');
    }


    public function destroy(Patient $patient)
    {
        $patient->delete();

        toast('success', 'Eliminado con exito!');
        return redirect()->route('patients.index');
    }
}
