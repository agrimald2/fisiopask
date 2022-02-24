<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Doctor;
use App\Models\Subfamily;
use App\Models\Schedule;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Inertia\Inertia;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $model = doctors()->index($request->searchQuery);

        $user = auth()->user();

        if ($user->hasRole('assistant')) {
            return inertia('Backend/Dynamic/Grid', [
                'model' => $model->items(),
    
                'links' => $model->linkCollection(),
    
                'parameters' => $request->all(),
    
                'title' => 'Lista de Doctores',
    
                'grid' => 'Backend/Doctors/gridAssistant.js',
            ]);
        }

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model->items(),

            'links' => $model->linkCollection(),

            'parameters' => $request->all(),

            'title' => 'Lista de Doctores',

            'create' => route('doctors.create'),

            'grid' => 'Backend/Doctors/grid.js',
        ]);
    }

    public function create()
    {
        $workspaces = workspaces()->index();

        Inertia::share('doctorsConfig', config('doctors'));

        return inertia('Backend/Doctors/CreateEdit', compact('workspaces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|min:5',
            'birth_date' => 'required|date',
            'sex' => 'required',
            'phone' => 'required',
            'document_type' => 'required',
            'document_reference' => 'required',
            'workspace_id' => '',
        ]);

        doctors()->create($validated);

        toast('success', 'Doctor creado correctamente');
        return redirect()->route('doctors.index');
    }

    public function edit($id)
    {
        $model = doctors()->show($id);

        $specialties = doctorSpecialties()->index();

        $subfamilies = Subfamily::query()
            ->orderBy('id', 'desc')
            ->with('doctors')
            ->get();

        $workspaces = workspaces()->index();

        Inertia::share('doctorsConfig', config('doctors'));
        return inertia('Backend/Doctors/CreateEdit', compact('model', 'specialties', 'subfamilies', 'workspaces'));
    }


    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'user.name' => 'required',
            'user.email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($doctor->user_id),
            ],
            'user.password' => 'nullable|min:5',

            'name' => 'required',
            'lastname' => 'required',

            'birth_date' => 'required|date',
            'sex' => 'required',
            'phone' => 'required',
            'document_type' => 'required',
            'document_reference' => 'required',
            'workspace_id' => '',
        ]);

        doctors()->update($doctor, $validated);

        toast('success', 'Doctor actualizado con éxito');
        return redirect()->route('doctors.index');
    }


    public function destroy(Doctor $doctor)
    {
        $schedules = Schedule::query()->where('doctor_id', $doctor->id)->get();

        foreach($schedules as $schedule)
        {
            $schedule->delete();
        }

        doctors()->destroy($doctor);
        toast('success', "Doctor '{$doctor->user->name}' eliminado.");
        return redirect()->route('doctors.index');
    }


    public function specialtiesAdd(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|numeric',
            'specialty_id' => 'required|numeric',
        ]);

        doctorSpecialties()->doctorAdd($request->doctor_id, $request->specialty_id);

        return redirect()->route('doctors.edit', $request->doctor_id);
    }


    public function specialtiesRemove(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|numeric',
            'specialty_id' => 'required|numeric',
        ]);

        doctorSpecialties()->doctorRemove($request->doctor_id, $request->specialty_id);

        return redirect()->route('doctors.edit', $request->doctor_id);
    }

    public function subfamiliesAdd(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|numeric',
            'subfamily_id' => 'required|numeric',
        ]);

        $subfamily = SubFamily::findOrFail($request->subfamily_id);

        Doctor::findOrFail($request->doctor_id)
            ->subfamilies()
            ->syncWithoutDetaching($subfamily);

        return redirect()->route('doctors.edit', $request->doctor_id);
    }


    public function subfamiliesRemove(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|numeric',
            'subfamily_id' => 'required|numeric',
        ]);

        $subfamily = SubFamily::findOrFail($request->subfamily_id);

        Doctor::findOrFail($request->doctor_id)
            ->subfamilies()
            ->detach($subfamily);

        return redirect()->route('doctors.edit', $request->doctor_id);
    }
}
