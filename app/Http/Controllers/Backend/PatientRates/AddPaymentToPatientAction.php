<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PaymentMethod;
use App\Models\PatientRate;
use Illuminate\Http\Request;

class AddPaymentToPatientAction extends Controller
{
    public function __invoke(Request $request, Patient $patient)
    {
        $request->validate([
            'payment_method_id' => 'required|integer',
            'ammount' => 'required|numeric',
            'rate_id' => 'required|integer',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        $rate = PatientRate::findOrFail($request->rate_id);

        if($request->appointment_id != null)
        {
            $rate->payed += $request->ammount * $rate->appointment_price;
            $rate->save();

            $patient->payments()
                ->create([
                    'ammount' => $request->ammount * $rate->appointment_price,
                    'concept' => $request->concept,
                    'payment_method_id' => $paymentMethod->id,
                    'payment_method' => $paymentMethod->payment_method,
                    'patient_rate_id' => $request->rate_id,
                ]);

            return redirect()->route('doctors.appointments.show', $request->appointment_id);
        }

        $rate->payed += $request->ammount;
        $rate->save();

        $patient->payments()
            ->create([
                'ammount' => $request->ammount,
                'concept' => $request->concept,
                'payment_method_id' => $paymentMethod->id,
                'payment_method' => $paymentMethod->payment_method,
                'patient_rate_id' => $request->rate_id,
            ]);
        
        return redirect()->route('patients.rates.index', $patient);
    }
}
