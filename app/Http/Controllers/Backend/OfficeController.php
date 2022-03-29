<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        $model = offices()->index();
        return inertia('Backend/Offices/Index', compact('model'));
    }


    public function create()
    {
        return inertia('Backend/Offices/CreateEdit');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'reference' => 'required|string',
            'indications' => 'required|string',
            'maps_link' => 'required|string',
        ]);

        offices()->create($validated);

        toast('success', 'Sucursal creada!');
        return redirect()->route('offices.index');
    }


    public function edit($id)
    {
        $model = offices()->show($id);
        return inertia('Backend/Offices/CreateEdit', compact('model'));
    }


    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'reference' => 'required|string',
            'indications' => 'required|string',
            'maps_link' => 'required|string',
        ]);

        offices()->update($office, $validated);

        toast('success', 'Sucursal actualizada!');
        return redirect()->route('offices.index');
    }


    public function destroy(Request $request, Office $office)
    {
        $wasDestroyed = offices()->destroy($office);

        if ($wasDestroyed) {
            toast('success', 'Sucursal eliminada con éxito.');
        } else {
            toast('warning', 'No permitido: La sucursal tiene horarios activos.');
        }

        return redirect()->route('offices.index');
    }
}
