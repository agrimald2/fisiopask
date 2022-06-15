<?php

namespace App\Http\Controllers;

use App\Models\Appointment;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Http\Request;

use App\Domain\Patients\PatientAuthRepositoryContract;

class TestAssistanceController extends Controller
{
    public function test(){
        $confirmedAppointments = Appointment::query()
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->get();

        $currentTime = Carbon::now()->format('Y-m-d H:i:s');
        $currentHour = Carbon::now()->format('H');

        //TODO @WHATSAPP 5 estrellas paciente
        foreach($confirmedAppointments as $appointment)
        {
            $startTime = Carbon::parse($appointment->start);
            $startHour = Carbon::parse($appointment->start)->format('H');

            if($startTime->diffInHours($currentTime) > 3)
            {
                if($startHour < $currentHour)
                {
                    //$phone = $appointment->patient->phone;
                    
                    $date = $appointment->date->format('d/m/Y');
                    $startTime = $appointment->start;
                    $patientName = $appointment->patient->name;
                    $patientName = $appointment->patient->name . " " . $appointment->patient->lastname1;
                    $doctorName = $appointment->doctor->name . ' ' . $appointment->doctor->lastname; 
                    $doctorWorkspace = [];
                    if($appointment->doctor->workspace != null) $doctorWorkspace = $appointment->doctor->workspace->name;
                    $dashboardLink = app(PatientAuthRepositoryContract::class)->getAuthLinkForPatient($appointment->patient);
                    
                    $patientDNI = $appointment->patient->dni;
                    $patientToken = $appointment->patient->token;
                    $dashboardLink = env('APP_URL').'area/patients/login/'.$patientDNI.'/'.$patientToken;
    

                    $data = compact(
                        'patientName',
                        'date',
                        'startTime',
                        'doctorName',
                        'dashboardLink',
                        'doctorWorkspace'
                    );
                    
                    $text = $this->getWhatsappPatientNotAssistedText($data);
                    
                    //chatapi($phone, $text);
                    $appointment->status = Appointment::STATUS_NOT_ASSISTED;
                    $appointment->save();
                }
            }
        }
    }

    protected function getWhatsappPatientNotAssistedText($data)
    {   
        return view('chatapi.notAssisted', $data)->render();
    }
}
