<?php

namespace App\Http\Controllers\Backend\Tests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Test;
use App\Models\Doctor;
use App\Models\Company;
use App\Models\TestType;
use App\Models\TestResultType;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $model = Test::query()
            ->with('doctor', 'patient', 'company', 'testType')
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
        $doctors = Doctor::query()->get();
        $companies = Company::query()->get();
        $testTypes = TestType::query()->get();
        $resultsArray = TestResultType::query()->get();

        return inertia('Backend/GeneralTests/Tests/CreateEdit', compact('doctors', 'companies', 'testTypes', 'resultsArray'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required',
            'company_id' => 'required',
            'test_type_id' => 'required',
            'result' => 'required',
            'taken_at' => 'required',
            'result_at' => '',
            'observations' => '',
        ]);

        $resultString = "Pendiente";

        if($validated['result'] != 0)
        {
            $resultsArray = TestResultType::query()->where('test_type_id', $validated['test_type_id'])->get();
            $resultString = $resultsArray[$validated['result'] -1]->result;
        }

        Test::create([
            'test_type_id' => $validated['test_type_id'],
            'patient_id' => 1,  //@todo: change
            'doctor_id' => $validated['doctor_id'],
            'company_id' => $validated['company_id'],
            'result' => $resultString,
            'taken_at' => $validated['taken_at'],
            'result_at' => $validated['result_at'],
            'observations' => $validated['observations'],
        ]);

        return redirect()->route('tests.index');
    }

    public function edit($id)
    {
        $model = Test::find($id);

        $doctors = Doctor::query()->get();
        $companies = Company::query()->get();
        $testTypes = TestType::query()->get();
        $resultsArray = TestResultType::query()->get();

        return inertia('Backend/GeneralTests/Tests/CreateEdit', compact('model', 'doctors', 'companies', 'testTypes', 'resultsArray'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'doctor_id' => 'required',
            'company_id' => 'required',
            'test_type_id' => 'required',
            'result' => 'required',
            'taken_at' => 'required',
            'result_at' => '',
            'observations' => '',
        ]);

        $resultString = "Pendiente";

        if($validated['result'] != 0)
        {
            $resultsArray = TestResultType::query()->where('test_type_id', $validated['test_type_id'])->get();
            $resultString = $resultsArray[$validated['result'] -1]->result;
        }

        $model = Test::find($id);

        $model->test_type_id = $validated['test_type_id'];
        $model->patient_id = 1;  //@todo: change
        $model->doctor_id = $validated['doctor_id'];
        $model->company_id = $validated['company_id'];
        $model->result = $resultString;
        $model->taken_at = $validated['taken_at'];
        $model->result_at = $validated['result_at'];
        $model->observations = $validated['observations'];

        $model->save();

        return redirect()->route('tests.index');
    }
}
