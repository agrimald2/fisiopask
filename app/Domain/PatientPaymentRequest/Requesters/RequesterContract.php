<?php

namespace App\Domain\PatientPaymentRequest\Requesters;

use App\Models\PatientPaymentRequest;

interface RequesterContract
{
    public function request(PatientPaymentRequest $patientPaymentRequest);
}
