<?php

namespace App\Http\Controllers\Backend;

use App\Models\Survey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyShowController extends Controller
{
    public function index()
    {
        $model = Survey::query()->with('appointment.patient', 'appointment.doctor')
        ->orderBy("created_at", "desc")
        ->get();
        return inertia('Backend/Surveys/Index', compact('model'));
    }

    public function show($id)
    {
        $model = Survey::query()->with('appointment.doctor','appointment.patient')
        ->where('id', '=', $id)
        ->get();

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,

            'links' => null,
            
            'parameters' => null,
            
            'title' => 'Encuesta',

            'create' => null,

            'grid' => 'Backend/Surveys/grid.js',
            
            'enableSearch' => false,
        ]);
    }

    public function showAll()
    {
        $model = Survey::query()->with('appointment.doctor','appointment.patient')->get();

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,

            'links' => null,
            
            'parameters' => null,
            
            'title' => 'Encuesta',

            'create' => null,

            'grid' => 'Backend/Surveys/grid.js',
            
            'enableSearch' => false,
        ]);
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        toast('success', 'Encuesta eliminada');
        return redirect()->route('surveys.index');
    }
}
