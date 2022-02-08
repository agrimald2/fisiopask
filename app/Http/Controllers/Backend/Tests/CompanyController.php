<?php

namespace App\Http\Controllers\Backend\Tests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Company;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $model = Company::query()
            ->orderBy('id', 'desc')
            ->get();
        
        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,
            
            'parameters' => $request->all(),

            'title' => 'Lista de Compañías',

            'create' => route('companies.create'),

            'grid' => 'Backend/GeneralTests/Companies/grid.js',

            'enableSearch' => false,
        ]);
    }

    public function create()
    {
        return inertia('Backend/GeneralTests/Companies/CreateEdit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'ruc' => 'required|numeric',
            'domain' => 'required',
            'is_active' => 'required',
        ]);

        Company::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'ruc' => $validated['ruc'],
            'domain' => $validated['domain'],
            'is_active' => $validated['is_active'] == "on" ? true : false,
        ]);

        return redirect()->route('companies.index');
    }

    public function edit($id)
    {
        $model = Company::find($id);

        return inertia('Backend/GeneralTests/Companies/CreateEdit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'ruc' => 'required|numeric',
            'domain' => 'required',
            'is_active' => 'required|boolean',
        ]);

        $model = Company::find($id);

        $model->name = $validated['name'];
        $model->description = $validated['description'];
        $model->ruc = $validated['ruc'];
        $model->domain = $validated['domain'];
        $model->is_active = $validated['is_active'];

        $model->save();

        return redirect()->route('companies.index');
    }

    public function destroy($id)
    {
        Company::destroy($id);

        toast('success', 'Compañía eliminada');
        return redirect()->route('companies.index');
    }
}
