<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DoctorSpecialty;
use Illuminate\Http\Request;

class DoctorSpecialtyController extends Controller
{
    public function index()
    {
        $model = doctorSpecialties()->index();

        return inertia('Backend/DoctorSpecialties/Index', compact('model'));
    }

    public function create()
    {
        return inertia('Backend/DoctorSpecialties/CreateEdit');
    }

    public function edit($id)
    {
        $model = doctorSpecialties()->show($id);

        return inertia('Backend/DoctorSpecialties/CreateEdit', [
            'model' => $model,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => "required|string",
        ]);

        doctorSpecialties()->create($validated);

        toast('success', 'Especialidad creada con éxito!');
        return redirect()->route('doctorSpecialties.index');
    }

    public function update(Request $request, DoctorSpecialty $doctorSpecialty)
    {
        $validated = $request->validate([
            'name' => "required|string",
        ]);

        doctorSpecialties()->update($doctorSpecialty, $validated);

        toast('success', 'Especialidad actualizada con éxito!');
        return redirect()->route('doctorSpecialties.index');
    }

    public function destroy(Request $request, DoctorSpecialty $doctorSpecialty)
    {
        $wasDestroyed = doctorSpecialties()->destroy($doctorSpecialty);

        if ($wasDestroyed) {
            toast('success', 'Especialidad eliminada!');
        } else {
            toast('warning', 'No eliminada. Elimina primero los doctores asignados.');
        }

        return redirect()->route('doctorSpecialties.index');
    }
}
