<?php

namespace App\Http\Controllers\Backend\PatientRates;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PaymentMethod;
use App\Models\PatientRate;
use Illuminate\Http\Request;
class AddPaymentToPatientActionApp extends Controller
{
    public function __invoke(Request $request, Patient $patient, $appointment_id)
    {
        logs()->warning('B');
        logs()->warning($appointment_id);

        $request->validate([
            'payment_method_id' => 'required|integer',
            'ammount' => 'required|numeric',
            'rate_id' => 'required|integer',
        ]);
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);
        $rate = PatientRate::findOrFail($request->rate_id);
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


        if($appointment_id == 0){
            $appointment = $request->appointment_id;
            return redirect()->route('patients.rates.index', [$patient, $appointment]);
        }else{
            $appointment = $request->appointment_id;

            return redirect()->route('patients.rates.assisted', [$rate->id,$appointment_id]);
        }
    }
}
