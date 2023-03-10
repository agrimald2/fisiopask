<?php

namespace App\Http\Controllers\Backend\Bills;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\BillsFamily;
use App\Models\BillsSubFamily;
use App\Models\BillsOrigin;
use App\Models\BillsPayer;
use App\Models\BillsPaymentMethod;
use App\Models\BillsReceiver;

use Illuminate\Support\Facades\DB;


use Carbon\Carbon;
use Google\Service\CloudBuild\Warning;
use Illuminate\Http\Request;

class BillController extends Controller
{

    public function index()
    {

        $today = Carbon::today()->startOfDay();

        $bills = Bill::query()->with('billsSubFamily.billsfamily')->where('created_at', '>=', $today)->orderBy('created_at', 'desc')->get();


        $subfamilies = BillsSubFamily::query()->get();
        $families = BillsFamily::query()->get();
        $receivers = BillsReceiver::query()->get();
        $payers = BillsPayer::query()->get();


        $total = Bill::where('created_at', '>=', $today)->sum('quantity');

        $SumOfSubFamilies = Bill::with('billsSubFamily.billsfamily')
            ->where('created_at', '>=', $today)
            ->selectRaw('SUM(quantity) as total_quantity, billssubfamily_id')
            ->groupBy('billssubfamily_id')
            ->orderBy('total_quantity', 'desc')
            ->get();


        $todayDate = Carbon::today()->format('Y-m-d');

        return inertia(
            'Backend/Bills/Bill/Index',
            compact(
                'subfamilies',
                'families',
                'receivers',
                'payers',
                'bills',
                'SumOfSubFamilies',
                'total',
                'todayDate'
            )
        );
    }

    public function getFilteredData(Request $request)
    {
        $today_start = Carbon::today()->startOfDay();
        $today_end = Carbon::today()->endOfDay();

        $query = Bill::query()->with('billsSubFamily.billsfamily');

        $startDate = $today_start;
        $endDate = $today_end;



        if ($request->input('start_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
        }

        if ($request->input('end_date')) {
            $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
        }

        $query->where('created_at', '>=', $startDate);
        $query->where('created_at', '<=', $endDate);

        if ($request->input('family_id') != NULL && $request->input('family_id') != 0) {
            $query->whereHas('billssubfamily.billsfamily', function ($query) use ($request) {
                $query->where('id', $request->input('family_id'));
            });
        } else {
            if ($request->input('subfamily_id') && $request->input('subfamily_id') != 0) {
                $query->where('billssubfamily_id', $request->input('subfamily_id'));
            }
        }

        if ($request->has('receiver') && $request->input('receiver') != 0) {
            $query->where('receiver', 'LIKE', $request->input('receiver'));
        }

        if ($request->has('payer') && $request->input('payer') != 0) {
            $query->where('payer', 'LIKE', $request->input('payer'));
        }


        $bills = $query->get();

        $SumOfSubFamilies = Bill::with('billsSubFamily.billsfamily')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->selectRaw('SUM(quantity) as total_quantity, billssubfamily_id')
            ->groupBy('billssubfamily_id')
            ->orderBy('total_quantity', 'desc')
            ->get();

        $total = $query->sum('quantity');

        logs()->error('<!-- REQUEST AND CONSULT -->');
        logs()->debug($request);
        logs()->debug($query->toSql());

        logs()->error('<!-- BILLS INFO -->');
        logs()->debug($bills);
        logs()->debug(count($bills));

        logs()->error('<!-- TOTAL SUM -->');
        logs()->debug($total);

        logs()->error('<!-- SUM BY SUBFAMILIES -->');
        logs()->debug($SumOfSubFamilies);
        logs()->debug(count($SumOfSubFamilies));


    }

    public function create()
    {
        $subfamilies = BillsSubFamily::query()->get();
        $origins = BillsOrigin::query()->get();
        $payers = BillsPayer::query()->get();
        $paymentMethods = BillsPaymentMethod::query()->get();
        $receivers = BillsReceiver::query()->get();

        //$offices = offices()->index();
        return inertia(
            'Backend/Bills/Bill/CreateEdit',
            compact(
                'subfamilies',
                'origins',
                'payers',
                'paymentMethods',
                'receivers',
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required',
            'billssubfamily_id' => 'required',
            'receiver' => 'required',
            'paymentway' => 'required',
            'moneyOrigin' => 'required',
            'payer' => 'required',
            'quantity' => 'required|numeric ',
        ]);


        // TODO FindReceiverById and get Name to save in BD
        /*
        $patientRate = PatientRate::find($patientPaymentRequest->patient_rate_id);
        */
        $receiver = BillsReceiver::find($validated['receiver']);
        $paymentway = BillsPaymentMethod::find($validated['paymentway']);
        $moneyOrigin = BillsOrigin::find($validated['moneyOrigin']);
        $payer = BillsPayer::find($validated['payer']);

        $validated['receiver'] = $receiver->name;
        $validated['paymentway'] = $paymentway->name;
        $validated['moneyOrigin'] = $moneyOrigin->name;
        $validated['payer'] = $payer->name;

        bills()->create($validated);

        toast('success', 'Transacción creado!');
        return redirect()->route('bills.index');
    }
}
