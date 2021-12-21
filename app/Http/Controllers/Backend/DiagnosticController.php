<?php

namespace App\Http\Controllers\Backend;

use App\Models\Diagnostic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiagnosticController extends Controller
{
    public function index(Request $request)
    {
        $model = Diagnostic::query()->orderBy('id', 'desc')->get();

        return inertia('Backend/MedicalHistories/Diagnostics/Index', compact('model'));
    }

    public function create()
    {
        return inertia('Backend/MedicalHistories/Diagnostics/CreateEdit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cie_10' => 'required',
            'name' => 'required'
        ]);

        Diagnostic::create($validated);

        return redirect()->route('diagnostic.index');        
    }

    public function edit($id)
    {
        $model = Diagnostic::find($id);

        return inertia('Backend/MedicalHistories/Diagnostics/CreateEdit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'cie_10' => 'required',
            'name' => 'required'
        ]);

        $area = Diagnostic::find($id);
        $area->update($validated);

        toast('success', 'Diagnóstico atualizado con éxito');
        return redirect()->route('diagnostic.index');
    }

    public function destroy($id)
    {
        Diagnostic::destroy($id);

        toast('success', "Diagnóstico eliminado.");
        return redirect()->route('diagnostic.index');
    }
}
