<?php

namespace App\Domain\PatientPaymentRequest\Requesters;

use App\Domain\PaymentGateways\MercadoPagoGateway;
use App\Models\PatientPaymentRequest;

class MercadoPagoRequester implements RequesterContract
{
    const PAYMENT_METHOD = "mercadopago";

    public function request(PatientPaymentRequest $patientPaymentRequest)
    {
        $amount = $patientPaymentRequest->amount;

        $webhookUrl = route('paynow.verify', $patientPaymentRequest);

        list($gatewayReference, $gatewayUrl) = app(MercadoPagoGateway::class)
            ->generate(
                $patientPaymentRequest->id,
                $amount,
                $webhookUrl
            );

        $patientPaymentRequest->reference = $gatewayReference;
        $patientPaymentRequest->payment_method = self::PAYMENT_METHOD;
        $patientPaymentRequest->save();

        return $gatewayUrl;
    }
}
