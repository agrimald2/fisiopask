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
        $types = [
            0 => 'Cualitativa',
            1 => 'Cuantitativa',
        ];

        return inertia('Backend/GeneralTests/TestTypes/CreateEdit', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'method' => 'required',
            'type' => 'required',
            'result_count' => 'numeric|required',
        ]);

        TestType::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'method' => $validated['method'],
            'type' => $validated['type'],
            'result_count' => $validated['result_count'],
        ]);

        return redirect()->route('testTypes.index');
    }

    public function edit($id)
    {
        $model = TestType::find($id);

        $results = TestResultType::query()->where('test_type_id', $id)->get();

        $types = [
            0 => 'Cualitativa',
            1 => 'Cuantitativa',
        ];

        return inertia('Backend/GeneralTests/TestTypes/CreateEdit', compact('model', 'results', 'types'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'method' => 'required',
        ]);

        $model = TestType::find($id);

        $model->name = $validated['name'];
        $model->method = $validated['method'];
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
            'interpretation' => '',
            'certificate' => '',
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
                'interpretation' => $validated['interpretation'],
                'certificate' => $validated['certificate'],
           ]);
        }

        return redirect()->back();
    }
}
