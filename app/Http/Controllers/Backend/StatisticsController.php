<?php
//TODO REVISAR ESTADÍSTICAS
namespace App\Http\Controllers\Backend;

use App\Exports\PatientsDayExport;
use App\Exports\PatientsExport;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AssistedAppointments;
use App\Models\Patient;
use App\Models\PatientPayment;
use App\Models\PatientRate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController extends Controller
{
    protected $user = null;

    const STATS_REQUEST_DAY = 1;
    const STATS_REQUEST_MONTH = 2;
    const STATS_REQUEST_RANGE = 3;
    const STATS_REQUEST_RECOMMENDATION = 4;
    const STATS_REQUEST_RATE = 5;

    public function index()
    {
        $recommendation = DB::select("SELECT id,recommendation FROM recommendations");
        $offices=DB::select("SELECT id, name FROM offices");

        $patients = [];
        $tickets = [];
        $sales = [];
        $salesBruto = [];
        $services = [];

        $start = Carbon::now()->startOfMonth()->subMonth(5);
        $end = Carbon::now()->endOfMonth()->subMonth(5);
        for($i = 0; $i < 6; $i++)
        {
            $data = $this->getData($start, $end);

            array_push($patients, $data['patients']);
            array_push($tickets, $data['tickets']);
            array_push($sales, $data['sales']);
            array_push($salesBruto, $data['salesBruto']);
            array_push($services, $data['services']);

            $start = $start->addMonth(1);
            $end = $end->addMonth(1);
        }

        return inertia('Backend/Statistics/statistics', ['offices'=>$offices,'recommendation'=>$recommendation,'patient' => $patients, 'sales' => $sales, 'salesBruto' => $salesBruto, 'ticket' => $tickets, 'nServices' => $services]);
    }

    public function statistic(Request $request)
    {
        $this->user = auth()->user();

        $start = null;
        $end = null;
        $recommendation = null;
        $family_id = null;
        $subfamily_id = null;
        $rate_id = null;

        $patients = [];
        $tickets = [];
        $sales = [];
        $salesBruto = [];
        $services = [];

        switch($request->params["advances"])
        {
            case self::STATS_REQUEST_MONTH:
                $start = Carbon::now()->startOfMonth()->subMonth(5);
                $end = Carbon::now()->endOfMonth()->subMonth(5);
                for($i = 0; $i < 6; $i++)
                {
                    $data = $this->getData($start, $end);

                    array_push($patients, $data['patients']);
                    array_push($tickets, $data['tickets']);
                    array_push($sales, $data['sales']);
                    array_push($sales, $data['salesBruto']);
                    array_push($services, $data['services']);

                    $start = $start->addMonth(1);
                    $end = $end->addMonth(1);
                }
                break;
            case self::STATS_REQUEST_DAY:
            {
                $curr = Carbon::now()->format("Y-m-d");
                $curr = substr($curr, 0, 8);
                $curr = $curr.$request->params["daySelected"];
                $nxt = $request->params["daySelected"] + 1;
                $start = new Carbon($curr);
                $curr = Carbon::now()->format("Y-m-d");
                $curr = substr($curr, 0, 8);
                $curr = $curr.$nxt;
                $end = new Carbon($curr);

                for($i = 0; $i < 10; $i++)
                {
                    $data = $this->getData($start, $end);

                    array_push($patients, $data['patients']);
                    array_push($tickets, $data['tickets']);
                    array_push($sales, $data['sales']);
                    array_push($salesBruto, $data['salesBruto']);
                    array_push($services, $data['services']);

                    $start = $start->addDay(1);
                    $end = $end->addDay(1);
                }
                break;
            }
            case self::STATS_REQUEST_RANGE:
                $start = new Carbon($request->dates["start"]);
                $end = new Carbon($request->dates["end"]);
                $start = $start->subDay();
                $start = $start->subMonths(4);
                $end = $end->subMonths(4);

                for($i = 0; $i < 5; $i++)
                {
                    $data = $this->getData($start, $end);

                    array_push($patients, $data['patients']);
                    array_push($tickets, $data['tickets']);
                    array_push($sales, $data['sales']);
                    array_push($salesBruto, $data['salesBruto']);
                    array_push($services, $data['services']);

                    $start = $start->addMonth(1);
                    $end = $end->addMonth(1);
                }
                break;
            case self::STATS_REQUEST_RECOMMENDATION:
                $recommendation = $request->paramsRecommendations["recommendations"];

                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                for($i = 0; $i < 2; $i++)
                {
                    $data = $this->getData($start, $end);

                    array_push($patients, $data['patients']);
                    array_push($tickets, $data['tickets']);
                    array_push($sales, $data['sales']);
                    array_push($salesBruto, $data['salesBruto']);
                    array_push($services, $data['services']);

                    $start = $start->addMonth(1);
                    $end = $end->addMonth(1);
                }
                break;
            case self::STATS_REQUEST_RATE:
                $family_id = $request->params["family_id"];
                $subfamily_id = $request->params["subfamily_id"];
                $rate_id = $request->params["rate_id"];

                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                for($i = 0; $i < 2; $i++)
                {
                    $data = $this->getData($start, $end);

                    array_push($patients, $data['patients']);
                    array_push($tickets, $data['tickets']);
                    array_push($sales, $data['sales']);
                    array_push($salesBruto, $data['salesBruto']);
                    array_push($services, $data['services']);

                    $start = $start->addMonth(1);
                    $end = $end->addMonth(1);
                }
                break;
        }

        return response()->json(['patients' => $patients, 'sales' => $sales, 'salesBruto' => $salesBruto,'tickets' => $tickets, 'nServices' => $services]);
    }

    private function getData($start, $end)
    {
        //Log::info($start);
        //Log::info($end);
        $payments = PatientPayment::query()
                        ->whereBetween('created_at', [
                            $start,
                            $end
                        ])
                        ->get();

        $payments_debug = $payments->toArray();
        //logs()->debug('PAGOS AHORA', $payments_debug);



        $appointments = AssistedAppointments::query()
                            ->whereBetween('created_at', [
                                $start,
                                $end
                            ])
                            ->with('appointment', 'patientRate')
                            ->get();


        //NEW PAYMENTS INFO, JUST CONSUMED
        $appointments_debug = $appointments->toArray();
        //logs()->debug('PAGOS CORREGIDO', $appointments_debug);

        $oldPatientIds = [];
        $newPatientIds = [];
        $oldPatientSales = 0;
        $newPatientSales = 0;
        $oldPatientSalesBruto = 0;
        $newPatientSalesBruto = 0;
        $oldPatientServices = 0;
        $newPatientServices = 0;
        $oldAvrgTicket = 0;
        $newAvrgTicket = 0;
        $oldAvgSale = 0;
        $newAvgSale = 0;
        $newPatientID = $this->getFirstPatientOfMonth(clone $start);
        if($newPatientID != 0)
        {
            //Corrected Consumed SALES
            foreach($appointments as $appointment){
                $app_index = 1;
                $appointments_to_array = $appointment->toArray();
                $consumed_payments = $appointments_to_array['consumed'];

                $patientId = $appointment->appointment->patient_id;

                if($patientId >= $newPatientID)
                {
                    //NEW
                    $newPatientSales += $consumed_payments;
                }
                else
                {
                    //OLD
                    $oldPatientSales += $consumed_payments;
                }
            }

            //Bruto Sales
            foreach($payments as $payment)
            {
                $patientId = $payment->patient_id;
                if($patientId >= $newPatientID)
                {
                    //NEW
                    $newPatientSalesBruto += $payment->ammount;
                }
                else
                {
                    //OLD
                    $oldPatientSalesBruto += $payment->ammount;
                }

            }


            foreach($appointments as $appointment)
            {
                /*
                $app_index = 1;
                $appointments_debug = $appointment->toArray();
                $consumed_payments_debug = $appointments_debug['consumed'];

                logs()->warning('PAGOS CORREGIDO NUEVOS: '.$app_index.' - '. $consumed_payments_debug);
                */
                $patientId = $appointment->appointment->patient_id;
                if($patientId >= $newPatientID)
                {
                    array_push($newPatientIds, $patientId);
                    if($appointment->patientRate) $newAvgSale += $appointment->patientRate->price;
                    $newPatientServices++;
                }
                else
                {
                    array_push($oldPatientIds, $patientId);
                    if($appointment->patientRate) $oldAvgSale += $appointment->patientRate->price;
                    $oldPatientServices++;
                }
                $app_index++;
            }

            $oldPatientIds = array_unique($oldPatientIds, SORT_NUMERIC);
            $newPatientIds = array_unique($newPatientIds, SORT_NUMERIC);

            $oldAvrgTicket = intval($oldPatientSales / (($oldPatientServices == 0) ? 1 : $oldPatientServices));
            $newAvrgTicket = intval($newPatientSales / (($newPatientServices == 0) ? 1 : $newPatientServices));
        }

        $data = [
            'patients' => [
                "Fecha" => $start->format('Y-m-d'),
                "Recurrentes" => count($oldPatientIds),
                "Nuevos" => count($newPatientIds),
                "TotalGeneral" => count($oldPatientIds) + count($newPatientIds),
            ],
            'sales' => [
                "Fecha" => $start->format('Y-m-d'),
                "ValorEjecutadoRecurrentes" => $oldPatientSales,
                "ValorEjecutadoNuevos" => $newPatientSales,
                "TotalGeneral" => $oldPatientSales + $newPatientSales,
            ],
            'tickets' => [
                "Fecha" => $start->format('Y-m-d'),
                "TicketPromedioRecurrentes" => $oldAvrgTicket,
                "TicketPromedioNuevos" => $newAvrgTicket,
                "TotalGeneral" => $oldAvrgTicket + $newAvrgTicket,
            ],
            'services' => [
                "Fecha" => $start->format('Y-m-d'),
                "ServiciosRecurrentes" => $oldPatientServices,
                "ServiciosNuevos" => $newPatientServices,
                "TotalGeneral" => $oldPatientServices + $newPatientServices,
            ],
            'salesBruto' => [
                "Fecha" => $start->format('Y-m-d'),
                "ValorEjecutadoRecurrentes" => $oldPatientSalesBruto,
                "ValorEjecutadoNuevos" => $newPatientSalesBruto,
                "TotalGeneral" => $oldPatientSalesBruto + $newPatientSalesBruto,
            ],

        ];

        return $data;
    }

    private function getFirstPatientOfMonth($start)
    {
        $carbonStart = new Carbon($start);
        $carbonStart = $carbonStart->startOfMonth();

        $startOfDay = clone $carbonStart;
        $endOfDay = clone $carbonStart;
        $endOfDay = $endOfDay->addDay();

        $patient_id = 0;
        $tries = 0;
        while($patient_id <= 0)
        {
            $patient = Patient::query()->whereBetween('created_at', [$startOfDay, $endOfDay])->first();
            $startOfDay = $startOfDay->addDay();
            $endOfDay = $endOfDay->addDay();

            if($patient) $patient_id = $patient->id;
            if($tries > 10) return 0;
            $tries++;
        }

        return $patient_id;
    }

    public function excel(Request $request)
    {
        $carbonStart = new Carbon($request->start);
        $carbonEnd = new Carbon($request->end);

        $this->user = auth()->user();

        if(!$this->user->hasRole('admin'))
        {
            if($carbonEnd->diffInDays($carbonStart) > 4)
            {
                return redirect()->back();
            }
        }

        return Excel::download(new PatientsDayExport($request->office,$request->start,$request->end), 'FISIO-Pacientes-Atendidos-DEL-'.$request->start.'-AL-'.$request->end.'.xlsx');
    }
}
