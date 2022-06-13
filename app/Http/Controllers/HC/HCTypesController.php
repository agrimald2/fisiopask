<?php

namespace App\Http\Controllers\HC;

use App\Http\Controllers\Controller;
use App\Models\HCType;
use Illuminate\Http\Request;

class HCTypesController extends Controller
{
    /** Types */
    public function index()
    {
        $model = HCType::all();

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,
            'title' => 'Tipos de Historias Clínicas',
            'create' => 'hc/create',
            'grid' => 'Backend/HC/Types/Index.js',
        ]);
    }

    public function create()
    {
        return inertia('Backend/HC/Types/CreateEdit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['string', 'required'],
        ]);

        HCType::create([
            'name' => $validated['name'],
        ]);

        return redirect()->route('hc.index');
    }

    public function destroy($id)
    {
        HCType::destroy($id);
        return redirect()->route('hc.index');
    }
}
