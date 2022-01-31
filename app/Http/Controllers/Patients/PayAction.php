<?php

namespace App\Http\Controllers\Patients;

use App\Domain\PatientPaymentRequest\Requesters\MercadoPagoRequester;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientRate;

class PayAction extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'rate_id' => 'required|integer',
        ]);

        $rate = PatientRate::find($request->rate_id);
        $patient = Patient::find($rate->patient_id);

        /**
         * Since this is confusing as fuck, let me explain
         * On Payment Request:
         * name = name of the rate
         * qty = quantity of the product
         * amount = price... apparently
         * 
         * On The request:
         * $rate->name = name of the rate
         * $request->amount = quantity of the product
         * $rate->appointment_price = price of the product
         */

        $paymentRequest = $patient
            ->paymentRequests()
            ->create([
                'product_name' => $rate->name,
                'qty' => $request->amount,
                'amount' => $rate->appointment_price,
                'patient_rate_id' => $request->rate_id,
            ]);

        $url = $paymentRequest->request(new MercadoPagoRequester);

        if ($request->inertia()) {
            return inertia()->location($url);
        }

        return redirect()->away($url);
    }
}
