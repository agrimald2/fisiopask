<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientPayment;

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
        $payment->delete();

        return redirect()->back();
    }
}
