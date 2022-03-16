<?php

namespace App\Http\Controllers\Backend;

use App\Models\Analysis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index(Request $request)
    {
        $model = Analysis::query()->orderBy('id', 'desc')->get();

        return inertia('Backend/MedicalHistories/Analysis/Index', compact('model'));
    }

    public function create()
    {
        return inertia('Backend/MedicalHistories/Analysis/CreateEdit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        Analysis::create($validated);

        return redirect()->route('analysis.index');        
    }

    public function edit($id)
    {
        $model = Analysis::find($id);

        return inertia('Backend/MedicalHistories/Analysis/CreateEdit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $analysis = Analysis::find($id);
        $analysis->update($validated);

        toast('success', 'Análisis atualizado con éxito');
        return redirect()->route('analysis.index');
    }

    public function destroy($id)
    {
        Analysis::destroy($id);

        toast('success', "Análisis eliminado.");
        return redirect()->route('analysis.index');
    }
}
