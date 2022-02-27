<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PatientRate;

class CancelRateAction extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(PatientRate $rate)
    {
        $rate->state = PatientRate::RATE_STATUS_CANCELED;
        $rate->save();
        return redirect()->back();
    }
}
