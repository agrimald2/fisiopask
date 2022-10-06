<?php

namespace App\Domain\Appointments;

use App\Models\Appointment;

class AppointmentCanceler
{
    public function cancel(Appointment $appointment)
    {

        $waba_type = 'cancel';

        $appointment->load('patient');
        $phone = $appointment->patient->phone;

        $patient = $appointment->patient;

        $patientDNI = $patient->dni;
        $patientToken = $patient->token;
        $dashboardLink = 'https://fisiosalud.pe/area/patients/login/'.$patientDNI.'/'.$patientToken;

        $date = $appointment->date->format('d/m/Y');
        $startTime = $appointment->start;
        $patientName = $patient->name . " " . $patient->lastname1 . " ". $patient->lastname2;
        $doctorName = $appointment->doctor->name . ' ' . $appointment->doctor->lastname;

        $data = compact(
            'dashboardLink',
            'date',
            'startTime',
            'patientName'
        );

        //$text = $this->getWhatsappPatientCancelText($data);

        chatapi($phone, $data, $waba_type);

        $appointment->status = Appointment::STATUS_CANCELED;
        $appointment->schedule_id = NULL;

        if(auth()->user() != null){
            $appointment->cancel_by = auth()->user()->name;
        }else{
            $appointment->cancel_by = "Paciente";
        }

        $appointment->save();
    }

    protected function getWhatsappPatientCancelText($data)
    {
        return view('chatapi.cancel', $data)->render();
    }
}
