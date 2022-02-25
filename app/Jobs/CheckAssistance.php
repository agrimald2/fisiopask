<?php

namespace App\Jobs;

use App\Models\Appointment;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckAssistance implements ShouldQueue
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
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->get();
        
         //TODO @WHATSAPP 5 estrellas paciente
        foreach($confirmedAppointments as $appointment)
        {
            $startTime = Carbon::parse($appointment->start);
            
            if($startTime->diffInHours(Carbon::now()->format('Y-m-d H:i:s'), false) > 1)
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

                $text = $this->getWhatsappPatientNotAssistedText($data);

                chatapi($phone, $text);
                $appointment->status = Appointment::STATUS_NOT_ASSISTED;
                $appointment->save();
            }
        }
    }

    
    protected function getWhatsappPatientNotAssistedText($data)
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
