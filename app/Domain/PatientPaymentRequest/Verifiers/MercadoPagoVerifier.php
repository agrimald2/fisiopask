<?php

namespace App\Domain\PatientPaymentRequest\Verifiers;

use App\Domain\PatientPaymentRequest\Requesters\MercadoPagoRequester;
use App\Domain\PaymentGateways\MercadoPagoGateway;
use App\Models\PatientPaymentRequest;
use App\Models\PatientRate;

class MercadoPagoVerifier implements VerifierContract
{

    /**
     * "collection_id" => "1243296143"
     * "collection_status" => "approved"
     * "payment_id" => "1243296143"
     * "status" => "approved"
     * "external_reference" => "1"
     * "payment_type" => "credit_card"
     * "merchant_order_id" => "3572899847"
     * "preference_id" => "786337653-2fc63f25-4beb-4a34-b322-511b24089456"
     * "site_id" => "MPE"
     * "processing_mode" => "aggregator"
     * "merchant_account_id" => "null"
     */
    public function verify(PatientPaymentRequest $patientPaymentRequest)
    {
        $request = request();

        $paymentId = $request->payment_id;

        // Abort if preference_id differs from reference in patientPaymentRequest
        // prevent hacking
        abort_if($request->preference_id != $patientPaymentRequest->reference, 400);

        $payment = app(MercadoPagoGateway::class)
            ->get($paymentId);

        /**
         * Check if its approved.
         */
        $isApproved = $payment->status == 'approved';
        if ($isApproved) {
            $patientPaymentRequest->is_completed = true;
            $patientPaymentRequest->save();

            if (!$patientPaymentRequest->patient_payment_id) {
                $patientPayment = $patientPaymentRequest->payment()
                    ->create([
                        'payment_method' => MercadoPagoRequester::PAYMENT_METHOD,
                        'ammount' => $patientPaymentRequest->amount * $patientPaymentRequest->qty,
                        'patient_rate_id' => $patientPaymentRequest->patient_rate_id,
                        'concept' => 'system',
                        'patient_id' => $patientPaymentRequest->patient_id,
                    ]);

                $patientPaymentRequest
                    ->payment()
                    ->associate($patientPayment)
                    ->save();

                $patientRate = PatientRate::find($patientPaymentRequest->patient_rate_id);
                $patientRate->payed = $patientPaymentRequest->amount * $patientPaymentRequest->qty;
                $patientRate->save();
            }
        }

        $message = "{$payment->status}: {$payment->status_detail}";

        return $message;
    }
}
