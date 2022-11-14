<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Rate;
use App\Models\PatientRate;
use Illuminate\Http\Request;

class AddRateToPatientAction extends Controller
{
    public function __invoke(Request $request, Patient $patient, $appointment_id)
    {
        $request->validate([
            'cart.*.qty' => 'required|integer'
        ]);

        $count = PatientRate::withTrashed()->count();

        $request->appointment_id;

        collect($request->cart)
            ->map(function ($cartRate) use ($patient, $request) {
                $rate = Rate::findOrFail($cartRate['id']);

                if($rate->is_product)
                {
                    $qty = $cartRate['qty'];

                    $patientRate = $patient->rates()
                        ->create([
                            'name' => $rate->name,
                            'subfamily_id' => $rate->subfamily_id,
                            'price' => $rate->price,
                            'is_product' => true,
                            'qty' => $qty,
                            'state' => PatientRate::RATE_STATUS_OPEN,
                            'appointment_id' => $request->appointment_id,
                        ]);

                    $rate->buy($patientRate->qty);
                }
                else
                {
                    $patientRate = $patient->rates()
                        ->create([
                            'name' => $rate->name,
                            'subfamily_id' => $rate->subfamily_id,
                            'price' => $rate->price,
                            'is_product' => false,
                            'sessions_total' => $rate->stock,
                            'sessions_left' => $rate->stock,
                            'state' => PatientRate::RATE_STATUS_OPEN,
                            'appointment_id' => $request->appointment_id,
                        ]);

                    $rate->buy($patientRate->qty);
                }
            });


        if ($request->redirect) {
            return redirect()->route('patients.rates.payApp', [$count+1,$request->appointment_id]);
        }

            return redirect()->route('patients.rates.payApp', [$count+1,$request->appointment_id]);
    }
}
