<?php

namespace App\Http\Controllers\Patients;

use App\Models\Appointment;
use App\Models\Survey;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
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
        //Store Survey details
        $validated = $request->validate([
            'office_score' => 'required',
            'doctor_score' => 'required',
            'service_score' => 'required',
            'comment' => '',
            'appointment_id' => 'required',
            'survey_date' => 'required|date|date_format:Y-m-d',
        ]);

        $appointment = Appointment::with('patient')->find($validated['appointment_id']);

        $patient = $appointment->patient()->first();

        //Survey Message Variables
        $phone = $patient->phone;
        $patientName = $patient->name;
        
        //Get Random Link
        $dice = rand(1,5);
        $facebook_link = 'https://www.facebook.com/fisiosaludperu/reviews/?ref=page_internal';
        $google_link = 'https://g.page/r/CXWZUJP5kbUKEAU/review';
        $review_link = '';

        if ($dice <= 2){
            $review_link = $google_link;
        }else{
            $review_link = $facebook_link;
        }      
    
        $data = compact(
            'patientName',
            'review_link',
        );

        $text = $this->getWhatsappPatientReview($data);

        if( $validated['office_score'] == 5 && 
            $validated['doctor_score'] == 5 && 
            $validated['service_score'] == 5)
        {
            //logs()->error("$text");

            $now = Carbon::now();
            $before = clone $now;
            $before->subMonth(1);

            $appointments = Appointment::query()->whereBetween('date', [$before, $now])->where('status', Appointment::STATUS_ASSISTED)->get();

            if(count($appointments) >= 6)
            {
                chatapi($phone, $text);
            }

        }

        Survey::create($validated);

        return inertia('Patients/Survey/Thanks');
    }

    protected function getWhatsappPatientReview($data)
    {
        return view('chatapi.review', $data)->render();
    }
}
