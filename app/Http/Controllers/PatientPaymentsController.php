<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientPayment;
use App\Models\PaymentMethod;
use Inertia\Inertia;
use Log;

class PatientPaymentsController extends Controller
{
    public function getAllPatientPayments()
    {
        $patientPayments = PatientPayment::with('patient', 'patientRate', 'paymentMethod')
            ->latest()
            ->take(20)
            ->get();
        return response()->json($patientPayments);
    }

    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        Log::warning($paymentMethods);
        return inertia('Backend/PatientPayments/Index', ['payment_methods' => $paymentMethods]);
    }
    
    public function filter(Request $request){
        $payment_method_id = $request->payment_method_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $search_query = $request->search_query;

        $patientPayments = PatientPayment::with('patient', 'patientRate', 'paymentMethod')
            ->whereHas('patient', function ($query) use ($search_query) {
                $query->where('name', 'like', '%' . $search_query . '%')
                    ->orWhere('lastname1', 'like', '%' . $search_query . '%')
                    ->orWhere('lastname2', 'like', '%' . $search_query . '%')
                    ->orWhere('dni', 'like', '%' . $search_query . '%');
            })
            ->when($payment_method_id, function ($query) use ($payment_method_id) {
                $query->where('payment_method_id', $payment_method_id);
            })
            ->when($start_date, function ($query) use ($start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            })
            ->latest() // Order by last
            ->get();

        $totalResults = $patientPayments->count();
        $totalAmount = $patientPayments->sum('ammount');

        return response()->json([
            'patientPayments' => $patientPayments,
            'totalResults' => $totalResults,
            'totalAmount' => $totalAmount,
        ]);
    }
    
}
