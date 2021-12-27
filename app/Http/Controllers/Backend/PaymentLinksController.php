<?php

namespace App\Http\Controllers\Backend;

use App\Models\PatientPaymentRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentLinksController extends Controller
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

            'title' => 'Lista de Links de Pagos',

            'grid' => 'Backend/PaymentLinks/grid.js',

            'enableSearch' => true,

            'enableDateSearch' => true,
        ]);
    }

    private function getModels($searchQuery, $dateQueryFrom, $dateQueryTo)
    {
        $payments = PatientPaymentRequest::query();

        if(!(empty($dateQueryFrom) || empty($dateQueryTo)))
        {        
            return $payments
                ->with('patient')
                ->whereBetween('created_at', [$dateQueryFrom, $dateQueryTo])
                ->whereHas('patient', function($query) use($searchQuery) {
                    $query->when($searchQuery, function ($query, $value) {
                       $query->where('name', 'LIKE', "%$value%");
                    });
                })
                ->get();
        }

        return $payments
            ->with('patient')
            ->whereHas('patient', function($query) use($searchQuery) {
                $query->when($searchQuery, function ($query, $value) {
                   $query->where('name', 'LIKE', "%$value%");
                });
            })
            ->get();
    }
}
