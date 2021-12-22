<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Rate;
use Illuminate\Http\Request;

class AddRateToPatientAction extends Controller
{
    public function __invoke(Request $request, Patient $patient)
    {
        $request->validate([
            'cart.*.qty' => 'required|integer'
        ]);

        collect($request->cart)
            ->map(function ($cartRate) use ($patient, $request) {
                $rate = Rate::findOrFail($cartRate['id']);

                $qty = 1;

                if($rate->is_product) $qty = $cartRate['qty'];

                $patientRate = $patient->rates()
                    ->create([
                        'rate_id' => $rate->id,
                        'name' => $rate->name,
                        'price' => $rate->price,
                        'qty' => $cartRate['qty'],
                        'sessions_left' => $rate->stock,
                        'appointment_id' => $request->appointment_id,
                    ]);

                $rate->buy($patientRate->qty);
            });

        if ($request->redirect) {
            return redirect($request->redirect);
        }

        return redirect()->route('patients.rates.index', $patient);
    }
}
