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

use App\Domain\Patients\PatientAuthRepositoryContract;

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

        $currentTime = Carbon::now()->format('Y-m-d H:i:s');
        $currentHour = Carbon::now()->format('H');

        //TODO @WHATSAPP 5 estrellas paciente
        foreach($confirmedAppointments as $appointment)
        {
            $waba_type = 'not_assisted';

            $startTime = Carbon::parse($appointment->start);
            $startHour = Carbon::parse($appointment->start)->format('H');

            if($startTime->diffInHours($currentTime) > 4)
            {
                if($startHour < $currentHour)
                {
                    $phone = $appointment->patient->phone;

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
                    $dashboardLink = 'https://fisiosalud.pe/area/patients/login/'.$patientDNI.'/'.$patientToken;

                    $data = compact(
                        'date',
                        'startTime',
                    );

                    //$text = $this->getWhatsappPatientNotAssistedText($data);

                    chatapi($phone, $data, $waba_type);
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
