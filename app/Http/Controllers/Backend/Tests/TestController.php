<?php

namespace App\Http\Controllers\Backend\Tests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Test;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $model = Test::query()
            ->orderBy('id', 'desc')
            ->get();
        
        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,
            
            'parameters' => $request->all(),

            'title' => 'Lista de Tests',

            'create' => route('tests.create'),

            'grid' => 'Backend/GeneralTests/Tests/grid.js',

            'enableSearch' => false,
        ]);
    }

    public function create()
    {
        return inertia('Backend/GeneralTests/Tests/CreateEdit');
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

        return redirect()->route('tests.index');
    }

    public function edit($id)
    {
        $model = TestType::find($id);

        $results = TestResultType::query()->where('test_type_id', $id)->get();

        return inertia('Backend/GeneralTests/Tests/CreateEdit', compact('model', 'results'));
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

        return redirect()->route('tests.index');
    }
}
