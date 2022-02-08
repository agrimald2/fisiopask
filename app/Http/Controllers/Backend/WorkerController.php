<?php

namespace App\Http\Controllers\Backend;

use App\Models\Worker;
use App\Models\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        $model = Worker::query()
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,

            'parameters' => $request->all(),

            'title' => 'Lista de Encargados',

            'create' => route('workers.create'),

            'grid' => 'Backend/Workers/grid.js',
        ]);
    }

    public function create()
    {
        $companies = companies()->index();
        return inertia('Backend/Workers/CreateEdit', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'company_id' => 'required',
            'user.email' => 'required|email|unique:users,email',
            'user.password' => 'required|min:5',
        ]);

        $user = User::make($validated['user']);
        $user->name = $validated['name'];
        $user->save();

        $user->assignRole('worker');

        $user->worker()->create(
                                collect($validated)
                                ->except('user')
                                ->toArray());

        toast('success', 'Trabajador creado correctamente');
        return redirect()->route('workers.index');
    }

    public function edit($id)
    {
        $model = Worker::findOrFail($id)->load('user');
        $companies = companies()->index();

        return inertia('Backend/Workers/CreateEdit', compact('model','companies'));
    }

    public function update(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            'user.name' => 'required',
            'user.email' => [
                'required',
                'email',
                //Rule::unique('users', 'email'),
            ],
            'user.password' => 'nullable|min:5',

            'name' => 'required',
            'lastname' => 'required',
            'company_id' => 'required',
        ]);

        if (isset($validated['user']['password']) && $validated['user']['password'] == '') {
            unset($validated['user']['password']);
        }

        $worker->fill($validated);
        //$worker->user->fill($data['user']);
        $worker->user->fill(['user']);
        $worker->push();

        toast('success', 'Trabajador actualizado con éxito');
        return redirect()->route('workers.index');
    }

    public function destroy(Worker $worker)
    {
        $worker->user->delete();

        $worker->delete();

        toast('success', "Trabajador '{$worker->user->name}' eliminado.");
        return redirect()->route('workers.index');
    }
}
