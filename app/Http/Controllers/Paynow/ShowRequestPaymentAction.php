<?php

namespace App\Http\Controllers\Paynow;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class ShowRequestPaymentAction extends Controller
{
    public function __invoke(Patient $patient, $amount)
    {
        return inertia('Paynow/Index', [
            'id' => $patient->id,
            'name' => $patient->name,
            'amount' => $amount
        ]);
    }
}
