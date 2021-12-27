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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $assistedAppointments = Appointment::query()
            ->where('status', Appointment::STATUS_ASSISTED)
            ->with('patient')
            ->get();

        foreach($assistedAppointments as $appointment)
        {
            $carbonDate = Carbon::parse($appointment->date);

            //TODO @WHATSAPP ENCUESTA
            if($carbonDate->isYesterday())
            {
                $phone = $appointment->patient->phone;
                $surveyLink = "www.fisiosalud.pe/area/patients/surveys/appointment/";
                $surveyLink .= $appointment->id;
                chatapi($phone, $text);
            }
        }
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
