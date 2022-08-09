<?php

namespace App\Http\Controllers\Backend;

use App\Models\Assistant;
use App\Models\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssistantController extends Controller
{
    public function index(Request $request)
    {
        $model = Assistant::query()
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,

            'parameters' => $request->all(),

            'title' => 'Lista de Asistentes',

            'create' => route('assistants.create'),

            'grid' => 'Backend/Assistants/grid.js',
        ]);
    }

    public function create()
    {
        $roles = [
            0 => "Asistente",
            1 => "Laboratorio",
        ];

        return inertia('Backend/Assistants/CreateEdit', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|min:5',
            'role' => 'required',
        ]);

        $user = User::make($validated['user']);
        $user->name = $validated['name'];
        $user->save();

        if($validated['role'] == 0) $user->assignRole('assistant');
        else if($validated['role'] == 1) $user->assignRole('lab');

        $user->assistant()->create(
                                collect($validated)
                                ->except('user', 'role')
                                ->toArray());

        toast('success', 'Asistente creado correctamente');
        return redirect()->route('assistants.index');
    }

    public function edit($id)
    {
        $model = Assistant::findOrFail($id)->load('user');

        return inertia('Backend/Assistants/CreateEdit', compact('model'));
    }

    public function update(Request $request, Assistant $assistant)
    {
        $validated = $request->validate([
            'user.name' => 'required',
            'user.email' => [
                'required',
                'email',
                //Rule::unique('users', 'email')->ignore($doctor->user_id),
            ],
            'user.password' => 'nullable|min:5',

            'name' => 'required',
            'lastname' => 'required',
        ]);

        if (isset($validated['user']['password']) && $validated['user']['password'] == '') {
            unset($validated['user']['password']);
        }

        $assistant->fill($validated);
        $assistant->user->fill(['user']);
        $assistant->push();

        toast('success', 'Asistente actualizado con éxito');
        return redirect()->route('assistants.index');
    }

    public function destroy(Assistant $assistant)
    {
        $assistant->user->delete();

        $assistant->delete();

        toast('success', "Asistente '{$assistant->user->name}' eliminado.");
        return redirect()->route('assistants.index');
    }
}
