<?php

namespace App\Http\Controllers\Backend\Tests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\BookAppointment\RepositoryContract;
use App\Models\Test;
use App\Models\Doctor;
use App\Models\Company;
use App\Models\TestType;
use App\Models\TestResultType;
use PDF;
use App\Domain\Patients\PatientAuthRepositoryContract;


use App\Models\Patient;
use App\Models\TestData;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->searchQuery;
        $companyQuery = $request->companyQuery;
        $model = Test::query()
            ->with('doctor', 'patient', 'company', 'testType', 'results')
            ->whereHas('patient', function($query) use($searchQuery) {
                $query->when($searchQuery, function ($query, $value) {
                   $query->where('name', 'LIKE', "%$value%")->orWhere('dni', 'LIKE', "%$value%");
                });
            })
            ->where('company_id', 'LIKE', "%$companyQuery%")
            ->orderBy('id', 'desc')
            ->get();

        $companies = Company::query()->get();
        
        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,
            
            'parameters' => $request->all(),

            'title' => 'Lista de Tests',

            'create' => route('tests.showCheckDNI'),

            'grid' => 'Backend/GeneralTests/Tests/grid.js',

            'enableSearch' => true,

            'companies' => $companies,

            'enableCompanySearch' => true,
        ]);
    }

    public function showCheckDNI() 
    {
        return inertia('Backend/GeneralTests/Tests/PatientLookup');
    }

    public function checkDNI(Request $request)
    {
        $dni = $request['dni'];

        $patient = Patient::query()->where('dni', $dni)->first();

        if($patient)
        {
            return redirect()->route('tests.create', ['patient_id' => $patient->id]);
        }
        else
        {
            return inertia('Backend/GeneralTests/Tests/CreatePatient', compact('dni'));
        }
    }

    public function createPatient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'lastname1' => 'required',
            'lastname2' => 'required',
            'dni' => 'required',
            'birth_date' => 'required|date',
            'phone' => 'required',
            'email' => '',
            'district' => 'nullable',
            'sex' => 'required',
            'recommendation_id' => '',
        ]);

        if($validated['sex'] == 0) $validated['sex'] = 'M';
        else if($validated['sex'] == 1) $validated['sex'] = 'F';
        else $validated['sex'] = 'NB';

        $validated['phone'] = '51'.$validated['phone'];

        $patient = patients()->create($validated);

        return redirect()->route('tests.create', ['patient_id' => $patient->id]);
    }

    public function create($patient_id)
    {
        $doctors = Doctor::query()->get();
        $companies = Company::query()->get();
        $testTypes = TestType::query()->get();
        $resultsArray = TestResultType::query()->get();

        return inertia('Backend/GeneralTests/Tests/CreateEdit', compact('doctors', 'companies', 'testTypes', 'resultsArray', 'patient_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required',
            'company_id' => '',
            'test_type_id' => 'required',
            'results' => 'required',
            'resultCount' => 'required',
            'testResultType' => 'required',
            'taken_at' => 'required',
            'result_at' => '',
            'observations' => '',
            'patient_id' => 'required',
        ]);

        $test = Test::create([
            'test_type_id' => $validated['test_type_id'],
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'company_id' => $validated['company_id'],
            'taken_at' => $validated['taken_at'],
            'result_at' => $validated['result_at'],
            'observations' => $validated['observations'],
        ]);

        $results = $validated['results'];

        foreach($results as $key => $item)
        {
            $resultString = $item;
            if($validated["testResultType"] == 0)
            {
                $resultString = "Pendiente";
                if($item != 0)
                {
                    $resultsArray = TestResultType::query()->where('test_type_id', $validated['test_type_id'])->get();
                    $resultString = $resultsArray[$item -1]->result;
                }
            }

            TestData::create([
                'test_id' => $test->id,
                'data' => $resultString,
                'index' => $key,
            ]);
        }

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

    public function downloadPDF($id) {
        $model = Test::find($id);

        $doctors = Doctor::query()->get();
        $companies = Company::query()->get();
        $testType = TestType::query()->where('id', $model->test_type_id)->first();
        $resultsArray = TestResultType::query()->get();

        $data = TestData::query()->where('test_id', $id)->get();

        $resultsData = "";

        foreach($data as $r)
        {
            $resultsData .= $r->data." / ";
        }

        $resultsData = substr($resultsData, 0, -2);

        $pdf = PDF::loadView('pdf.test_results', compact('model', 'resultsData', 'doctors', 'companies', 'testType', 'resultsArray'));

        return $pdf->download('PruebaDeLaboratorio.pdf');
    }  

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'doctor_id' => 'required',
            'company_id' => 'required',
            'test_type_id' => 'required',
            'results' => 'required',
            'resultCount' => 'required',
            'testResultType' => 'required',
            'taken_at' => 'required',
            'result_at' => '',
            'observations' => '',
            'patient_id' => 'required',
        ]);

        $model = Test::find($id);

        $model->test_type_id = $validated['test_type_id'];
        $model->patient_id = $validated['patient_id'];
        $model->doctor_id = $validated['doctor_id'];
        $model->company_id = $validated['company_id'];
        $model->taken_at = $validated['taken_at'];
        $model->result_at = $validated['result_at'];
        $model->observations = $validated['observations'];

        $model->save();

        $oldData = TestData::query()->where('test_id', $id)->get();

        foreach($oldData as $old)
        {
            TestData::destroy($old->id);
        }

        $patient_dni = $model->patient->dni;
        $patient_token = $model->patient->token;

        $dashboardlink = "http://anandamida.test/area/patients/login/".$patient_dni."/".$patient_token;
        // @TODO CHANGE DOMAIN

        $phone = $model->patient->phone;
        $message = "Hola " . $model->patient->fullname . " los resultados de tu prueba ya están listos, puedes verlos en el siguiente link: " . $dashboardlink ;

        $results = $validated['results'];

        foreach($results as $key => $item)
        {
            $resultString = $item;
            if($validated["testResultType"] == 0)
            {
                $resultString = "Pendiente";
                if($item != 0)
                {
                    $resultsArray = TestResultType::query()->where('test_type_id', $validated['test_type_id'])->get();
                    $resultString = $resultsArray[$item -1]->result;
                }
            }

            TestData::create([
                'test_id' => $id,
                'data' => $resultString,
                'index' => $key,
            ]);
        }
        
        chatapi($phone, $message);
        return redirect()->route('tests.index');
    }
}
