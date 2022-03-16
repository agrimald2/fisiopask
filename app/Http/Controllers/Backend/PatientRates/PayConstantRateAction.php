<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Rate;
use App\Models\Patient;
use App\Models\PatientRate;
use App\Models\PaymentMethod;

class PayConstantRateAction extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Appointment $appointment)
    {
        $patient = $appointment->patient;
        $rates = $patient->rates();

        $found = false;
        $patientRate = [];
        $constantRateModel = Rate::find(1);

        foreach($rates as $rate)
        {
            if($rate.name == $constantRateModel.name)
            {
                if($rate.state == RATE_STATUS_OPEN)
                {
                    $found = true;
                    $patientRate = $rate;
                    break;
                }
            }
        }

        if(!$found)
        {
            $patientRate = PatientRate::create([
                'name' => $constantRateModel->name,
                'subfamily_id' => $constantRateModel->subfamily_id,
                'patient_id' => $patient->id,
                'price' => $constantRateModel->price,
                'appointment_id' => $appointment->id,
                'payed' => 0,
                'is_product' => false,
                'qty' => 1,
                'sessions_total' => 1,
                'sessions_left' => 1,
                'state' => PatientRate::RATE_STATUS_OPEN,
            ]);
        }

        $paymentMethodOptions = $this->getPaymentMethodOptions();
        $balance = $constantRateModel->price;
        
        return inertia('Backend/PatientRates/AddSpecificPayment', compact('patient', 'paymentMethodOptions', 'balance', 'patientRate'));
    }

    private function getPaymentMethodOptions()
    {
        return PaymentMethod::query()
            ->orderBy('id', 'desc')
            ->get()
            ->pluck('payment_method', 'id');
    }
}
