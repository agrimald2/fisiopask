<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientPayment;
use App\Models\PaymentMethod;
use Inertia\Inertia;
use Log;
use DB;

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
        return inertia('Backend/PatientPayments/Index', ['payment_methods' => $paymentMethods]);
    }
    
    public function filter(Request $request){
        $payment_method_id = $request->payment_method_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $search_query = $request->search_query;

        $sql = "SELECT patient_payments.*, patients.*, patient_rates.*, payment_methods.*, patient_payments.created_at as payment_created_at FROM patient_payments 
        JOIN patients ON patient_payments.patient_id = patients.id 
        JOIN patient_rates ON patient_payments.patient_rate_id = patient_rates.id 
        JOIN payment_methods ON patient_payments.payment_method_id = payment_methods.id 
        WHERE (patients.name LIKE :search_query1 
        OR patients.lastname1 LIKE :search_query2 
        OR patients.lastname2 LIKE :search_query3 
        OR patients.dni LIKE :search_query4)";

        $bindings = [
            'search_query1' => '%' . $search_query . '%',
            'search_query2' => '%' . $search_query . '%',
            'search_query3' => '%' . $search_query . '%',
            'search_query4' => '%' . $search_query . '%',
        ];

        if ($payment_method_id) {
            $sql .= " AND patient_payments.payment_method_id = :payment_method_id";
            $bindings['payment_method_id'] = $payment_method_id;
        }

        if ($start_date) {
            $sql .= " AND DATE(patient_payments.created_at) >= :start_date";
            $bindings['start_date'] = $start_date;
        }

        if ($end_date) {
            $sql .= " AND DATE(patient_payments.created_at) <= :end_date";
            $bindings['end_date'] = $end_date;
        }

        $sql .= " ORDER BY patient_payments.created_at DESC";

        $patientPayments = DB::select($sql, $bindings);

        $totalResults = count($patientPayments);
        $totalAmount = array_sum(array_column($patientPayments, 'ammount'));

        // Create an array to hold the total amount for each payment method
        $totalAmountPerMethod = [];
        foreach ($patientPayments as $payment) {
            if (!isset($totalAmountPerMethod[$payment->payment_method_id])) {
                $totalAmountPerMethod[$payment->payment_method_id] = ['name' => $payment->payment_method, 'total' => 0];
            }
            $totalAmountPerMethod[$payment->payment_method_id]['total'] += $payment->ammount;
        }

        return response()->json([
            'patientPayments' => $patientPayments,
            'totalResults' => $totalResults,
            'totalAmount' => $totalAmount,
            'totalAmountPerMethod' => $totalAmountPerMethod,
        ]);
    }
    
}
