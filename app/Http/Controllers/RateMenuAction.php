<?php

namespace App\Http\Controllers;

use App\Domain\Patients\PatientAuthRepositoryContract;
use App\Models\PatientRate;
use App\Models\Rate;

class RateMenuAction extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(PatientAuthRepositoryContract $repo, $id)
    {
        $model = $repo->getAuthenticatedPatient();

        $rate = Rate::find($id);

        $patientRate = PatientRate::create([
            'name' => $rate->name,
            'subfamily_id' => $rate->subfamily_id,
            'patient_id' => $model->id,
            'price' => $rate->price,
            'payed' => 0,
            'is_product' => false,
            'qty' => 1,
            'sessions_total' => $rate->stock,
            'sessions_left' => $rate->stock,
            'state' => PatientRate::RATE_STATUS_OPEN,
        ]);
        
        return redirect()->back();
    }
}
