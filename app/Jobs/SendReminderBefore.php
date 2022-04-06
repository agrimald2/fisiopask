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
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->where('date', Carbon::tomorrow()->format('Y-m-d'))
            ->where('reminder', '0')
            ->get();

        foreach($confirmedAppointments as $appointment)
        {
                $phone = $appointment->patient->phone;
                $patient = $appointment->patient;
                
                $patientDNI = $patient->dni;
                $patientToken = $patient->token;
                $dashboardLink = 'https://fisiosalud.pe/area/patients/login/'.$patientDNI.'/'.$patientToken;


                $date = $appointment->date->format('d/m/Y');
                $startTime = $appointment->start;
                $patientName = $patient->name . " " . $patient->lastname1 . " ". $patient->lastname2;
                $doctorName = $appointment->doctor->name . ' ' . $appointment->doctor->lastname; 
                $doctorWorkspace = [];
                $address = Office::find($appointment->office_id)->address;
                $reference = Office::find($appointment->office_id)->reference;
                if($appointment->doctor->workspace != null) $doctorWorkspace = $appointment->doctor->workspace->name;
        
                $data = compact(
                    'patientName',
                    'date',
                    'startTime',
                    'doctorName',
                    'dashboardLink',
                    'doctorWorkspace',
                    'address',
                    'reference'
                );
               
                $text = $this->getWhatsappPatientReminderText($data);
                chatapi($phone, $text);
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
