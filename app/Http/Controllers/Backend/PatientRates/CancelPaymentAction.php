<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientPayment;
use App\Models\PatientRate;

class CancelPaymentAction extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientPayment  $payment
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, PatientPayment $payment)
    {
        $rate = PatientRate::find($payment->patient_rate_id);

        $rate->payed -= $payment->ammount;
        $rate->save();

        $payment->delete();

        return redirect()->back();
    }
}
