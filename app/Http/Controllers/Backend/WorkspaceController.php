<?php

namespace App\Http\Controllers\Backend;

use App\Models\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function index()
    {
        $model = Workspace::query()->with('office')->get();
        return inertia('Backend/Workspaces/Index', compact('model'));
    }


    public function create()
    {
        $offices = offices()->index();
        return inertia('Backend/Workspaces/CreateEdit', compact('offices'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => '',
            'office_id' => 'required',
        ]);

        workspaces()->create($validated);

        toast('success', 'Cubículo creado!');
        return redirect()->route('workspaces.index');
    }


    public function edit($id)
    {
        $model = workspaces()->show($id);
        $offices = offices()->index();
        return inertia('Backend/Workspaces/CreateEdit', compact('model', 'offices'));
    }


    public function update(Request $request, Workspace $workspace)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => '',
            'office_id' => 'required',
        ]);

        workspaces()->update($workspace, $validated);

        toast('success', 'Cubículo actualizado!');
        return redirect()->route('workspaces.index');
    }


    public function destroy(Request $request, Workspace $workspace)
    {
        $wasDestroyed = workspaces()->destroy($workspace);

        if ($wasDestroyed) {
            toast('success', 'Cubículo eliminado con éxito.');
        } else {
            toast('warning', 'No permitido: El cubículo no pudo ser eliminado.');
        }

        return redirect()->route('workspaces.index');
    }
}
