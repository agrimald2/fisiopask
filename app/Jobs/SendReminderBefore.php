<?php

namespace App\Jobs;

use \App\Models\Appointment;
use App\Models\Office;
use \Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderBefore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $confirmedAppointments = Appointment::query()
            ->with('schedule')
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->where('date', Carbon::tomorrow()->format('Y-m-d'))
            ->where('reminder', '0')
            ->get();

        foreach($confirmedAppointments as $appointment)
        {
                $waba_type = 'reminder';

                $phone = $appointment->patient->phone;
                $patient = $appointment->patient;

                $patientDNI = $patient->dni;
                $patientToken = $patient->token;
                //$dashboardLink = 'https://fisiosalud.pe/area/patients/login/'.$patientDNI.'/'.$patientToken;
                $dashboardLink = $patientDNI.'/'.$patientToken;

                $date = $appointment->date->format('d/m/Y');
                $startTime = $appointment->start;
                $patientName = $patient->name . " " . $patient->lastname1 . " ". $patient->lastname2;
                $doctorName = $appointment->doctor->name . ' ' . $appointment->doctor->lastname;
                $doctorWorkspace = [];

                /*$address = Office::find($appointment->office_id)->address;
                $reference = Office::find($appointment->office_id)->reference;
                if($appointment->doctor->workspace != null) $doctorWorkspace = $appointment->doctor->workspace->name;
                */



                $office = Office::find($appointment->office_id);
                $office_indications = $office->indications;
                $office_address = $office->address;
                $office_reference = $office->reference;
                $office_maps_link = $office->maps_link;


                $data = compact(
                    'date',
                    'startTime',
                    'office_indications',
                    'office_address',
                    'office_reference',
                    'office_maps_link',
                    'dashboardLink'
                );

                $created_date = $appointment->date->format('d/m/Y');
                $today_date = Carbon::now()->format('d/m/Y');


                //$text = $this->getWhatsappPatientReminderText($data);

                if($created_date != $today_date){
                    chatapi($phone, $data, $waba_type);
                }

                $appointment->reminder = '1';

                $appointment->save();
        }
    }

    protected function getWhatsappPatientReminderText($data)
    {
        return view('chatapi.reminder', $data)->render();
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
