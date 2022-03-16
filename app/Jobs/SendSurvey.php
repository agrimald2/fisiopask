<?php

namespace App\Jobs;

use \App\Models\Appointment;

use \Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSurvey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const TIME_UNTIL_SURVEY = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $assistedAppointments = Appointment::query()
            ->where('status', Appointment::STATUS_ASSISTED)
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->with('patient')
            ->get();

        foreach($assistedAppointments as $appointment)
        {
            $carbonDate = Carbon::parse($appointment->date);

            $surveyTime = (int)substr($appointment->end, 0, 2) + self::TIME_UNTIL_SURVEY;

            $currentTime = (int)date("H");

            if($surveyTime == $currentTime)
            {
                $patient = $appointment->patient;
                $phone = $appointment->patient->phone;
                $surveyLink = "www.fisiosalud.pe/area/patients/survey/appointment/";
                $surveyLink .= $appointment->id;
                $patientName = $patient->name;
                $data = compact(
                    'patientName',
                    'surveyLink',
                );
                //* Send message only to the 30% of the appointments
                    //? 95% sure there is a better way to do this
                $dice = rand(1,10);
                if ($dice <= 3){
                    $text = $this->getWhatsappSurveyText($data);
                    chatapi($phone, $text);
                }else{
                    return;
                }       
            }
        }
    }

    protected function getWhatsappSurveyText($data)
    {   
        return view('chatapi.survey', $data)->render();
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
