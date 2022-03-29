<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;

use App\Models\PatientRate;

class CancelRateAction extends Controller
{
    public function __invoke(PatientRate $rate)
    {
        $rate->state = PatientRate::RATE_STATUS_CANCELED;
        $rate->save();
        return redirect()->back();
    }
}
