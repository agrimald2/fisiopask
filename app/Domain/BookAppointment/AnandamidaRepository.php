<?php

namespace App\Domain\BookAppointment;


class AnandamidaRepository extends FisioNextRepository
{
    protected function getWhatsappPatientConfirmationText($data)
    {
        return view('chatapi.anandamida.patient', $data)->render();
    }


    protected function getWhatsappDoctorConfirmationText($data)
    {
        return view('chatapi.anandamida.doctor', $data)->render();
    }
}
