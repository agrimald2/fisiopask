<?php

namespace App\Http\Controllers\Backend;

use App\Models\AffectedArea;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AffectedAreaController extends Controller
{
    public function index(Request $request)
    {
        $model = AffectedArea::query()->orderBy('id', 'desc')->get();

        return inertia('Backend/MedicalHistories/AffectedAreas/Index', compact('model'));
    }

    public function create()
    {
        return inertia('Backend/MedicalHistories/AffectedAreas/CreateEdit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required',
            'sub_category' => 'required'
        ]);

        AffectedArea::create($validated);

        return redirect()->route('affectedarea.index');        
    }

    public function edit($id)
    {
        $model = AffectedArea::find($id);

        return inertia('Backend/MedicalHistories/AffectedAreas/CreateEdit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category' => 'required',
            'sub_category' => 'required'
        ]);

        $area = AffectedArea::find($id);
        $area->update($validated);

        toast('success', 'Área atualizada con éxito');
        return redirect()->route('affectedarea.index');
    }

    public function destroy($id)
    {
        AffectedArea::destroy($id);

        toast('success', "Área eliminada.");
        return redirect()->route('affectedarea.index');
    }
}
