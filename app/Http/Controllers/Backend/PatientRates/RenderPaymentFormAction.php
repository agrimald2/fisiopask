<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class RenderPaymentFormAction extends Controller
{
    public function __invoke(Request $request, Patient $patient)
    {
        $paymentMethodOptions = $this->getPaymentMethodOptions();

        $balance = $patient->getRateBalance();

        return inertia('Backend/PatientRates/AddPayment', compact('patient', 'paymentMethodOptions', 'balance'));
    }

    private function getPaymentMethodOptions()
    {
        return PaymentMethod::query()
            ->orderBy('id', 'desc')
            ->get()
            ->pluck('payment_method', 'id');
    }
}
