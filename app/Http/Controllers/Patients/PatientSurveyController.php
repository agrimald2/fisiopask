<?php

namespace App\Http\Controllers\Patients;

use App\Models\Appointment;
use App\Models\Survey;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientSurveyController extends Controller
{
    public function __invoke($id)
    {
        $survey_done = Survey::where('appointment_id', $id)->exists();
        $appointment_exists = Appointment::find($id) == null;

        if($survey_done || $appointment_exists)
        {
            return inertia('Patients/Survey/Thanks');
        }

        return inertia('Patients/Survey/Index', compact('id'));
    }

    public function store(Request $request) 
    {
        $validated = $request->validate([
            'office_score' => 'required',
            'doctor_score' => 'required',
            'service_score' => 'required',
            'comment' => '',
            'appointment_id' => 'required',
            'survey_date' => 'required|date|date_format:Y-m-d',
        ]);

        Survey::create($validated);

        return inertia('Patients/Survey/Thanks');
    }
}