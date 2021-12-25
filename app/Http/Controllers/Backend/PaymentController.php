<?php

namespace App\Http\Controllers\Backend;

use App\Models\PatientPayment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $model = PatientPayment::query()->with('patient', 'patientRate.rate')->get();

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,

            'parameters' => $request->all(),

            'title' => 'Lista de Pagos',

            'grid' => 'Backend/Payments/grid.js',

            'enableSearch' => false,
        ]);
    }
}
