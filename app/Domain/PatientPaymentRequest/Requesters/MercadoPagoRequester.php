<?php

namespace App\Domain\PatientPaymentRequest\Requesters;

use App\Domain\PaymentGateways\MercadoPagoGateway;
use App\Models\PatientPaymentRequest;

class MercadoPagoRequester implements RequesterContract
{
    const PAYMENT_METHOD = "Mercadopago";

    public function request(PatientPaymentRequest $patientPaymentRequest)
    {
        $name = $patientPaymentRequest->product_name;
        $qty = $patientPaymentRequest->qty;
        $amount = $patientPaymentRequest->amount;

        $webhookUrl = route('paynow.verify', $patientPaymentRequest);

        list($gatewayReference, $gatewayUrl) = app(MercadoPagoGateway::class)
            ->generate(
                $patientPaymentRequest->id,
                $name,
                $qty,
                $amount,
                $webhookUrl
            );

        $patientPaymentRequest->reference = $gatewayReference;
        $patientPaymentRequest->payment_method = self::PAYMENT_METHOD;
        $patientPaymentRequest->save();

        return $gatewayUrl;
    }
}
