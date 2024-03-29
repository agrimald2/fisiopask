<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientRate;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PaymentMethod;

class PayRateActionApp extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientRate $patientRate
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, PatientRate $patientRate, $appointment_id)
    {
        $paymentMethodOptions = $this->getPaymentMethodOptions();

        $balance = $patientRate->price - $patientRate->payed;

        $patient = Patient::find($patientRate->patient_id);

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
