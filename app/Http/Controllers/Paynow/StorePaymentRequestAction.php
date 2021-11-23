<?php

namespace App\Http\Controllers\Paynow;

use App\Domain\PatientPaymentRequest\Requesters\MercadoPagoRequester;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class StorePaymentRequestAction extends Controller
{
    public function __invoke(Patient $patient, Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        $paymentRequest = $patient
            ->paymentRequests()
            ->create(['amount' => $request->amount]);

        $url = $paymentRequest->request(new MercadoPagoRequester);

        if ($request->inertia()) {
            return inertia()->location($url);
        }

        return redirect()->away($url);
    }
}
