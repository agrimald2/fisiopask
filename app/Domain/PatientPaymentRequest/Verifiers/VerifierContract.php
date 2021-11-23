<?php

namespace App\Domain\PatientPaymentRequest\Verifiers;

use App\Models\PatientPaymentRequest;

interface VerifierContract
{
    public function verify(PatientPaymentRequest $patientPaymentRequest);
}
