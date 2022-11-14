<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientRate;
use App\Models\Patient;
use App\Models\PaymentMethod;

class PayRateAction extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientRate $patientRate
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, PatientRate $patientRate)
    {
        $paymentMethodOptions = $this->getPaymentMethodOptions();

        $balance = $patientRate->price - $patientRate->payed;

        $patient = Patient::find($patientRate->patient_id);

        $appointment_id = 0;

        return inertia('Backend/PatientRates/AddSpecificPayment', compact('patient', 'paymentMethodOptions', 'balance', 'patientRate','appointment_id'));
    }

    private function getPaymentMethodOptions()
    {
        return PaymentMethod::query()
            ->orderBy('id', 'desc')
            ->get()
            ->pluck('payment_method', 'id');
    }
}
