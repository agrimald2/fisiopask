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

    const TIME_UNTIL_SURVEY = 1;

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
            ->where('survey', '0')
            ->with('patient', 'doctor')
            ->get();

        foreach($assistedAppointments as $appointment)
        {
            $waba_type='survey2';

            $carbonDate = Carbon::parse($appointment->date);

            $surveyTime = (int)substr($appointment->end, 0, 2) + self::TIME_UNTIL_SURVEY;

            $currentTime = (int)date("H");

            if($surveyTime == $currentTime)
            {

                $patient = $appointment->patient;
                $phone = $appointment->patient->phone;
                $surveyLink = "https://fisiosalud.pe/area/patients/survey/appointment/";
                //$surveyLink .= $appointment->id;

                $surveyLink = $appointment->id;
                $surveyLink = strval($surveyLink);
                //logs()->warning($surveyLink);

                $patientName = $patient->name;
                $data = compact(
                    'patientName',
                    'surveyLink',
                );

                //$text = $this->getWhatsappSurveyText($data);
                chatapi($phone, $data, $waba_type);

                $appointment->survey = 1;
                $appointment->save();

                if(!$appointment->history_created){
                    $doc_phone = $appointment->doctor->phone;
                    if(substr($doc_phone , 0, 1) == 9){
                        $doc_phone = '51'.$doc_phone;
                    }
                    //$text = $this->getWhatsappDoctorHCText();
                    //chatapi($doc_phone, $text);
                }
            }
        }
    }

    protected function getWhatsappSurveyText($data)
    {
        return view('chatapi.survey', $data)->render();
    }
    protected function getWhatsappDoctorHCText()
    {
        return view('chatapi.doctorHC');
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
