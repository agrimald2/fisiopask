<?php

namespace App\Http\Controllers\Backend;

use App\Models\PatientPayment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->searchQuery;
        $dateQueryFrom = $request->dateQueryFrom;
        $dateQueryTo = $request->dateQueryTo;

        $model = $this->getModels($searchQuery, $dateQueryFrom, $dateQueryTo);

        return inertia('Backend/Dynamic/Grid', [
            'model' => $model,

            'parameters' => $request->all(),

            'title' => 'Lista de Pagos',

            'grid' => 'Backend/Payments/grid.js',

            'enableSearch' => true,

            'enableDateSearch' => true,
        ]);
    }

    private function getModels($searchQuery, $dateQueryFrom, $dateQueryTo)
    {
        $payments = PatientPayment::query();

        if(!(empty($dateQueryFrom) || empty($dateQueryTo)))
        {        
            return $payments
                ->with('patient', 'patientRate')
                ->whereBetween('created_at', [$dateQueryFrom, $dateQueryTo])
                ->whereHas('patient', function($query) use($searchQuery) {
                    $query->when($searchQuery, function ($query, $value) {
                       $query->where('name', 'LIKE', "%$value%");
                    });
                })
                ->get();
        }

        return $payments
            ->with('patient', 'patientRate')
            ->whereHas('patient', function($query) use($searchQuery) {
                $query->when($searchQuery, function ($query, $value) {
                   $query->where('name', 'LIKE', "%$value%");
                });
            })
            ->get();
    }
}
