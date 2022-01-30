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

        $ammountToPay = $rate->appointment_price * $request->amount;

        $paymentRequest = $patient
            ->paymentRequests()
            ->create(['amount' => $ammountToPay]);

        $url = $paymentRequest->request(new MercadoPagoRequester);

        if ($request->inertia()) {
            return inertia()->location($url);
        }

        return redirect()->away($url);
    }
}
