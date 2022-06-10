<?php

namespace App\Http\Controllers\Backend\Tests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TestType;
use App\Models\TestResultType;

class TestTypeController extends Controller
{
    public function index(Request $request)
    {
        $model = TestType::query()
            ->orderBy('id', 'desc')
            ->get();
        
        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,
            
            'parameters' => $request->all(),

            'title' => 'Lista de tipos de Tests',

            'create' => route('testTypes.create'),

            'grid' => 'Backend/GeneralTests/TestTypes/grid.js',

            'enableSearch' => false,
        ]);
    }

    public function create()
    {
        return inertia('Backend/GeneralTests/TestTypes/CreateEdit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        TestType::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('testTypes.index');
    }

    public function edit($id)
    {
        $model = TestType::find($id);

        $results = TestResultType::query()->where('test_type_id', $id)->get();

        return inertia('Backend/GeneralTests/TestTypes/CreateEdit', compact('model', 'results'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $model = TestType::find($id);

        $model->name = $validated['name'];
        $model->description = $validated['description'];

        $model->save();

        return redirect()->route('testTypes.index');
    }

    public function destroy($id)
    {
        TestType::destroy($id);

        toast('success', 'Tipo de test eliminado');
        return redirect()->route('testTypes.index');
    }

    public function addResult(Request $request)
    {
        $validated = $request->validate([
            'result' => 'required',
        ]);

        $query = TestResultType::query()->where('test_type_id', $request->id)->get();

        $found = false;

        foreach($query as $option) 
        {
            if($option->result == $validated['result']) $found = true;
        }

        if(!$found)
        {
            TestResultType::create([
                'test_type_id' => $request->id,
                'result' => $validated['result'],
           ]);
        }

        return redirect()->back();
    }
}