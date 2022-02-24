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
            ->get();

        foreach($confirmedAppointments as $appointment)
        {
            $carbonDate = Carbon::parse($appointment->date);
            
            //TODO @WHATSAPP RECORDATORIO UN DÍA ANTES
            if($carbonDate->isTomorrow())
            {
                $phone = $appointment->patient->phone;
                
                $date = $appointment->date->format('d/m/Y');
                $startTime = $appointment->start;
                $patientName = $patient->name;
                $patientName = $patient->name . " " . $patient->lastname1;
                $doctorName = $appointment->doctor->name . ' ' . $appointment->doctor->lastname; 
                $doctorWorkspace = [];
                if($appointment->doctor->workspace != null) $doctorWorkspace = $appointment->doctor->workspace->name;
                $dashboardLink = app(PatientAuthRepositoryContract::class)->getAuthLinkForPatient($patient);
        
                $data = compact(
                    'patientName',
                    'date',
                    'startTime',
                    'doctorName',
                    'dashboardLink',
                    'doctorWorkspace'
                );
                
                $text = $this->getWhatsappDoctorConfirmationText($data);
                chatapi($phone, $text);
            }
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
