<?php

namespace App\Http\Controllers\Backend\PatientRates;

use App\Http\Controllers\Controller;
use App\Models\PatientRate;

class AbandonRateAction extends Controller
{
    public function __invoke(PatientRate $rate)
    {

        $user = auth()->user();
        
        $role = "admin";

        if ($user->hasRole('assistant')) $role = "assistant";

        if($role ==  'admin'){
            $rate->state = PatientRate::RATE_STATUS_ABANDONED;
            $rate->save();
            return redirect()->back();
        }else{
            toast('warning', "Contactate con el administrador para realizar esta función");
            return redirect()->back();
        }
    }
}
