<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class AddPaymentToPatientAction extends Controller
{
    public function __invoke(Request $request, Patient $patient)
    {
        $request->validate([
            'payment_method_id' => 'required|integer',
            'ammount' => 'required|numeric',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        $patient->payments()
            ->create([
                'ammount' => $request->ammount,
                'concept' => $request->concept,
                'payment_method_id' => $paymentMethod->id,
                'payment_method' => $paymentMethod->payment_method,
            ]);

        return redirect()->route('patients.rates.index', $patient);
    }
}
