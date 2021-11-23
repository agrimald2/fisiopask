<?php

namespace App\Http\Controllers\Paynow;

use App\Domain\PatientPaymentRequest\Verifiers\MercadoPagoVerifier;
use App\Http\Controllers\Controller;
use App\Models\PatientPaymentRequest;
use Illuminate\Http\Request;

class VerifyPaymentRequestAction extends Controller
{
    public function __invoke(PatientPaymentRequest $patientPaymentRequest)
    {
        $message = $patientPaymentRequest->verify(
            new MercadoPagoVerifier
        );

        return inertia('Paynow/VerificationResult', [
            'isVerified' => $patientPaymentRequest->is_completed,
            'homeUrl' => '/',
            'message' => $message
        ]);
    }
}
