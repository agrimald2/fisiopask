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

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BillController extends Controller
{


    public function index()
    {

        $today = Carbon::today()->startOfDay();
        $bills = Bill::query()->with('billsSubFamily.billsfamily')->where('status', 1)->where('created_at', '>=', $today)->orderBy('created_at', 'desc')->get();

        $subfamilies = BillsSubFamily::query()->get();
        $families = BillsFamily::query()->get();
        $receivers = BillsReceiver::query()->get();
        $payers = BillsPayer::query()->get();

        $total = Bill::where('created_at', '>=', $today)->where('status', 1)->sum('quantity');
        $SumOfSubFamilies = Bill::with('billsSubFamily.billsfamily')
            ->where('status', 1)
            ->where('created_at', '>=', $today)
            ->selectRaw('SUM(quantity) as total_quantity, billssubfamily_id')
            ->groupBy('billssubfamily_id')
            ->orderBy('total_quantity', 'desc')
            ->get();


        $todayDate = Carbon::today()->format('Y-m-d');
        return Inertia::render('Backend/Bills/Bill/Index', [
            'subfamilies' => $subfamilies,
            'families' => $families,
            'receivers' => $receivers,
            'payers' => $payers,
            'bills' => $bills,
            'SumOfSubFamilies' => $SumOfSubFamilies,
            'total' => $total,
            'todayDate' => $todayDate
        ]);
    }

    public function getFilteredData(Request $request)
    {
        if ($request->input('showRequests')) {
            $query = Bill::query()->with('billsSubFamily.billsfamily')->where('status', 0)->orWhere('status', 2);
        } else {
            $query = Bill::query()->with('billsSubFamily.billsfamily')->where('status', 1);
        }

        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();

        if ($request->input('start_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
        }
        if ($request->input('end_date')) {
            $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
        }

        $query->whereBetween('created_at', [$startDate, $endDate]);

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
        $total = $query->sum('quantity');
        $sumOfSubfamilies = $query
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('SUM(quantity) as total_quantity, billssubfamily_id')
            ->groupBy('billssubfamily_id')
            ->orderBy('total_quantity', 'desc')
            ->get();

        $filteredData = [
            'bills' => $bills,
            'total' => $total,
            'sumOfSubfamilies' => $sumOfSubfamilies,
        ];

        return $filteredData;
    }

    public function create()
    {
        $subfamilies = BillsSubFamily::query()->get();
        $origins = BillsOrigin::query()->get();
        $payers = BillsPayer::query()->get();
        $paymentMethods = BillsPaymentMethod::query()->get();
        $receivers = BillsReceiver::query()->get();

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
            'status' => '',
            'isDoubleChecked' => '',
            'created_by' => ''
        ]);

        $receiver = BillsReceiver::find($validated['receiver']);
        $paymentway = BillsPaymentMethod::find($validated['paymentway']);
        $moneyOrigin = BillsOrigin::find($validated['moneyOrigin']);
        $payer = BillsPayer::find($validated['payer']);

        $validated['isDoubleChecked'] = $validated['isDoubleChecked'] ? 1 : 0;

        $validated['receiver'] = $receiver->name;
        $validated['paymentway'] = $paymentway->name;
        $validated['moneyOrigin'] = $moneyOrigin->name;
        $validated['payer'] = $payer->name;
        $validated['status'] = 0;
        $validated['created_by'] = auth()->user()->name;


        /**
         * Message data variables
         */

        $created_bill = bills()->create($validated);

        if ($validated['isDoubleChecked'] == 0) {
            $m_level = 1;
        } else {
            $m_level = 2;
        }


        $m_level = strval($validated['isDoubleChecked']);
        $m_amount = $validated['quantity'];
        $m_receiver = $validated['receiver'];
        $m_family = 'Software';
        $m_subfamily = 'Software';
        $m_description = $validated['description'];
        $m_id = strval($created_bill->getAttribute('id'));

        $messageData = compact(
            'm_level',
            'm_amount',
            'm_receiver',
            'm_family',
            'm_subfamily',
            'm_description',
            'm_id',
        );

        $waba_type = 'bill_notification';


        chatapi('51992219307', $messageData, $waba_type);
        if ($validated['isDoubleChecked']) {
            chatapi('51992219307', $messageData, $waba_type);
        }

        toast('success', 'Transacción creado!');
        return redirect()->route('bills.index');
    }

    public function showBillDetails($id)
    {
        $bill = Bill::with('billsSubFamily.billsfamily')->find($id);
        return inertia('Backend/Bills/Bill/Details', compact('bill'));
    }
    public function aprroveBill($id)
    {
        $user = auth()->user()->name;
        $bill = Bill::find($id);

        if ($bill->isApproved) {
            if ($user == $bill->approved_by) {
                return;
            } else {
                $bill->secondIsApproved = "SI";
                $bill->second_approved_by = $user;
                $bill->second_approved_at = Carbon::now();
            }
        } else {
            $bill->isApproved = "SI";
            $bill->approved_by = $user;
            $bill->approved_at = Carbon::now();
        }

        if (!$bill->isDoubleChecked && $bill->isApproved == "SI" || $bill->isDoubleChecked && $bill->isApproved == "SI" && $bill->secondIsApproved == "SI") {
            $bill->status = 1;
        }

        $bill->update();
        return;
    }

    public function denyBill($id)
    {
        $user = auth()->user()->name;
        $bill = Bill::find($id);

        if ($bill->isApproved) {
            if ($user == $bill->approved_by) {
                return;
            } else {
                $bill->secondIsApproved = "NO";
                $bill->second_approved_by = $user;
                $bill->second_approved_at = Carbon::now();
            }
        } else {
            $bill->isApproved = "NO";
            $bill->approved_by = $user;
            $bill->approved_at = Carbon::now();
        }

        if (!$bill->isDoubleChecked && $bill->isApproved || $bill->isDoubleChecked && $bill->secondIsApproved) {
            $bill->status = 1;
        }

        $bill->update();
        return;
    }

    public function stadistics()
    {

        $today = Carbon::today()->endOfDay();

        //Get Past 6 Months of Stadistics
        $bills = Bill::where('status', 1)
            ->where('created_at', '>', $today->subMonths(6))
            ->get()
            ->groupBy(function ($bill) {
                return Carbon::parse($bill->created_at)->format('M');
            })
            ->map(function ($bills) {
                return $bills->sum('quantity');
            });

        $data = json_decode($bills, true);

        $months = array_keys($data);
        $amounts = array_values($data);


        $results = Bill::select('billssubfamily_id', \DB::raw('SUM(quantity) as total_quantity'))
            ->where('status', 1)
            ->with('billsSubFamily')
            ->groupBy('billssubfamily_id')
            ->get();


        $amountsBySubFamilies = $results->pluck('total_quantity')->toArray();
        $subFamilies = $results->pluck('billsSubFamily.name')->toArray();

        return Inertia::render('Backend/Bills/Stadistics/Index', [
            'months' => $months,
            'amounts' => $amounts,
            'amountsBySubFamilies' => $amountsBySubFamilies,
            'subFamilies' => $subFamilies,
        ]);
    }

    public function filterStadistics(Request $request)
    {
        $today = Carbon::today()->endOfDay();

        //Get Past 6 Months of Stadistics
        $bills = Bill::where('status', 1)
            ->where('created_at', '>', $today->subMonths(6))
            ->get()
            ->groupBy(function ($bill) {
                return Carbon::parse($bill->created_at)->format('M');
            })
            ->map(function ($bills) {
                return $bills->sum('quantity');
            });

        $data = json_decode($bills, true);

        $months = array_keys($data);
        $amounts = array_values($data);

        /*Filter Subfamilies*/

        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();

        if ($request->input('start_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
        }
        if ($request->input('end_date')) {
            $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
        }

        $results = Bill::select('billssubfamily_id', \DB::raw('SUM(quantity) as total_quantity'))
            ->where('status', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('billsSubFamily')
            ->groupBy('billssubfamily_id')
            ->get();


        $amountsBySubFamilies = $results->pluck('total_quantity')->toArray();
        $subFamilies = $results->pluck('billsSubFamily.name')->toArray();

        $filteredData = [
            'amountsBySubFamilies' => $amountsBySubFamilies,
            'subFamilies' => $subFamilies,
        ];

        return $filteredData;
    }
}
