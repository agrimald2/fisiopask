<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\PatientRate;

class AbandonRateAction extends Controller
{
    public function __invoke(PatientRate $rate)
    {
        $rate->state = PatientRate::RATE_STATUS_ABANDONED;
        $rate->save();
        return redirect()->back();
    }
}
