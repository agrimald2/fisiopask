<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PatientsDayExport;
use App\Exports\PatientsExport;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AssistedAppointments;
use App\Models\Office;
use App\Models\Patient;
use App\Models\PatientPayment;
use App\Models\Rate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController extends Controller
{
    protected $newPatientsPrevious = [];
    protected $oldPatientsPrevious = [];

    protected $newPatientsThis = [];
    protected $oldPatientsThis = [];

    protected $ratesPrevious = null;
    protected $ratesThis = null;

    protected $user = null;

    const STATS_REQUEST_DAY = 1;
    const STATS_REQUEST_MONTH = 2;
    const STATS_REQUEST_RANGE = 3;
    const STATS_REQUEST_RECOMMENDATION = 4;
    const STATS_REQUEST_RATE = 5;
    
    public function index()
    {
        $this->user = auth()->user();
        
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        if(!$this->user->hasRole('admin'))
        {
            $start = Carbon::now()->subDay(3);
            $end = Carbon::now();
        }
        $this->setDataRange($start, $end, false, null, null, null);

        $salesNServices = $this->getSalesNServices(clone $start, null, null, null);

        $sales[0] = $salesNServices[0];
        $sales[1] = $salesNServices[1];
        $nServices[0] = $salesNServices[2];
        $nServices[1] = $salesNServices[3];
        
        $patients = $this->getPatientsCount(clone $start);
        $tickets = $this->getAverageTicket($sales, $nServices, clone $start);

        $recommendation = DB::select("SELECT id,recommendation FROM recommendations");
        $offices=DB::select("SELECT id, name FROM offices");
      

        return inertia('Backend/Statistics/statistics', ['offices'=>$offices,'recommendation'=>$recommendation,'patient' => $patients, 'sales' => $sales, 'ticket' => $tickets, 'nServices' => $nServices]);
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
        
        switch($request->params["advances"])
        {
            case self::STATS_REQUEST_MONTH:
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
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
                break;
            }
            case self::STATS_REQUEST_RANGE:
                $start = new Carbon($request->dates["start"]);
                $end = new Carbon($request->dates["end"]);
                break;
            case self::STATS_REQUEST_RECOMMENDATION:
                $start = Carbon::now()->startOfCentury();
                $end = Carbon::now()->endOfCentury();
                $recommendation = $request->paramsRecommendations["recommendations"];
                break;
            case self::STATS_REQUEST_RATE:
                $family_id = $request->params["family_id"];
                $subfamily_id = $request->params["subfamily_id"];
                $rate_id = $request->params["rate_id"];

                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
        }

        if($request->params["advances"] != self::STATS_REQUEST_DAY)
        {
            if(!$this->user->hasRole('admin'))
            {
                $start = Carbon::now()->subDay(3);
                $end = Carbon::now();
            }
        }

        $this->setDataRange($start, $end, $recommendation, $family_id, $subfamily_id, $rate_id);

        $salesNServices = $this->getSalesNServices(clone $start, $family_id, $subfamily_id, $rate_id);

        if($this->user->hasRole('admin'))
        {
            $sales[0] = $salesNServices[0];
            $sales[1] = $salesNServices[1];
            $nServices[0] = $salesNServices[2];
            $nServices[1] = $salesNServices[3];

            $patients = $this->getPatientsCount(clone $start);
            $tickets = $this->getAverageTicket($sales, $nServices, clone $start);
        }
        else
        {
            $sales[0] = $salesNServices[1];
            $nServices[0] = $salesNServices[3];

            $patients = $this->getPatientsCount(clone $start)[1];
            $tickets = $this->getAverageTicket($sales, $nServices, clone $start)[1];
        }

        return response()->json(['patients' => $patients, 'sales' => $sales,'tickets' => $tickets, 'nServices' => $nServices]);
    }

    private function nullEverything() 
    {
        $this->newPatientsPrevious = [];
        $this->oldPatientsPrevious = [];
    
        $this->newPatientsThis = [];
        $this->oldPatientsThis = [];
    
        $this->ratesPrevious = null;
        $this->ratesThis = null;
    }

    public function setDataRange($start, $end, $recommendation, $family_id, $subfamily_id, $rate_id)
    {
        $this->nullEverything();

        $previousStart = clone $start;
        $previousEnd = clone $end;

        $previousStart->subMonth(1);
        $previousEnd->subMonth(1);

        $payments = PatientPayment::query()
            ->with("patientRate.subfamily")
            ->whereBetween(
                'created_at', [
                    $start, 
                    $end
                ])
            ->orWhereBetween(
                'created_at', [
                    $previousStart,
                    $previousEnd
                ])
            ->get();

        $patients = [];
        $patientIds = [];

        foreach($payments as $payment)
        {
            if(!in_array($payment->patient_id, $patientIds))
            {
                $patient = Patient::with('payments')->find($payment->patient_id);
                if($patient != null)
                {
                    array_push($patients, $patient);
                }
                array_push($patientIds, $payment->patient_id);
            }
        }

        //$patients = Patient::query()->with('payments')->where('id', '>', '5000')->get();

        $appointmentsThis = Appointment::query()
            ->where('status', Appointment::STATUS_ASSISTED)
            ->whereBetween('date', [
                $start, 
                $end])
            ->get();

        $appointmentsPrevious = Appointment::query()
            ->where('status', Appointment::STATUS_ASSISTED)
            ->whereBetween('date', [
                $previousStart, 
                $previousEnd])
            ->get();

        foreach($patients as $patient)
        {
            if(count($patient->payments) > 0)
            {
                if($recommendation != null)
                {
                    if($patient->reccomendation_id != $recommendation) continue;
                }

                $paymentDate = new Carbon($patient->payments[0]->created_at);

                if($paymentDate > $start && $paymentDate < $end)
                {
                    array_push($this->newPatientsThis, $patient->id);
                }
                else if($paymentDate > $previousStart && $paymentDate < $previousEnd)
                {
                    array_push($this->newPatientsPrevious, $patient->id);
                }
            }
        }

        foreach($appointmentsThis as $appointment)
        {
            if($recommendation != null)
            {
                $patient = Patient::find($appointment->patient_id);
                if($patient)
                {
                    if($patient->reccomendation_id != $recommendation) continue;
                }
            }

            if(!in_array($appointment->patient_id, $this->oldPatientsThis) 
                && !in_array($appointment->patient_id, $this->newPatientsThis))
            {
                array_push($this->oldPatientsThis, $appointment->patient_id);
            }
        }

        foreach($appointmentsPrevious as $appointment)
        {
            if($recommendation != null)
            {
                $patient = Patient::find($appointment->patient_id);
                if($patient)
                {
                    if($patient->reccomendation_id != $recommendation) continue;
                }
            }

            if(!in_array($appointment->patient_id, $this->oldPatientsPrevious) 
                && !in_array($appointment->patient_id , $this->newPatientsPrevious))
            {
                array_push($this->oldPatientsPrevious, $appointment->patient_id);
            }
        }

        $this->ratesPrevious = AssistedAppointments::query()                
            ->whereBetween('created_at', [
                $previousStart,
                $previousEnd])
            ->with('appointment.patient')
            ->get();


        $this->ratesThis = AssistedAppointments::query()                
            ->whereBetween('created_at', [
                $start, 
                $end])
            ->with('appointment.patient')
            ->get();
    }

    public function getSalesNServices($start, $family_id, $subfamily_id, $rate_id)
    {
        $previousStart = clone $start;
        $servicesPrevious = null;
        $servicesThis = null;
        $servicesPrevious["Fecha"] = $previousStart->subMonth(1)->format('Y-m');
        $servicesThis["Fecha"] = $start->format('Y-m');
        $servicesPrevious["ServiciosRecurrentes"] = 0;
        $servicesPrevious["ServiciosNuevos"] = 0;
        $servicesThis["ServiciosRecurrentes"] = 0;
        $servicesThis["ServiciosNuevos"] = 0;
        
        $previousMonth = null;
        $thisMonth = null;
        $previousMonth["Fecha"] = $servicesPrevious["Fecha"];
        $thisMonth["Fecha"] = $servicesThis["Fecha"];
        $previousMonth["ValorEjecutadoRecurrentes"] = 0;
        $previousMonth["ValorEjecutadoNuevos"] = 0;
        $thisMonth["ValorEjecutadoRecurrentes"] = 0;
        $thisMonth["ValorEjecutadoNuevos"] = 0;

        $rateFound = null;
        if($rate_id != null)
        {
            $rateFound = Rate::find($rate_id);
        }

        foreach($this->ratesPrevious as $rate)
        {
            foreach($this->oldPatientsPrevious as $patient)
            {
                if($rate->appointment->patient_id == $patient)
                {
                    if($family_id == null || $family_id == $rate->patientRate->subfamily->family_id)
                    {
                        if($subfamily_id == null || $subfamily_id == $rate->patientRate->subfamily_id)
                        {
                            if($rateFound == null || $rateFound->name == $rate->patientRate->name)
                            {
                                $previousMonth["ValorEjecutadoRecurrentes"] += $rate->consumed;
                                $servicesPrevious["ServiciosRecurrentes"]++;
                                break;
                            }
                        }
                    }
                }
            }

            foreach($this->newPatientsPrevious as $patient)
            {
                if($rate->appointment->patient_id == $patient)
                {
                    if($family_id == null || $family_id == $rate->patientRate->subfamily->family_id)
                    {
                        if($subfamily_id == null || $subfamily_id == $rate->patientRate->subfamily_id)
                        {
                            if($rateFound == null || $rateFound->name == $rate->patientRate->name)
                            {
                                $previousMonth["ValorEjecutadoNuevos"] += $rate->consumed;
                                $servicesPrevious["ServiciosNuevos"]++;
                                break;
                            }
                        }
                    }
                }
            }
        }

        foreach($this->ratesThis as $rate)
        {
            foreach($this->oldPatientsThis as $patient)
            {
                if($rate->appointment->patient_id == $patient)
                {
                    if($family_id == null || $family_id == $rate->patientRate->subfamily->family_id)
                    {
                        if($subfamily_id == null || $subfamily_id == $rate->patientRate->subfamily_id)
                        {
                            if($rateFound == null || $rateFound->name == $rate->patientRate->name)
                            {
                                $thisMonth["ValorEjecutadoRecurrentes"] += $rate->consumed;
                                $servicesThis["ServiciosRecurrentes"]++;
                                break;
                            }
                        }
                    }
                }
            }

            foreach($this->newPatientsThis as $patient)
            {
                if($rate->appointment->patient_id == $patient)
                {
                    if($family_id == null || $family_id == $rate->patientRate->subfamily->family_id)
                    {
                        if($subfamily_id == null || $subfamily_id == $rate->patientRate->subfamily_id)
                        {
                            if($rateFound == null || $rateFound->name == $rate->patientRate->name)
                            {
                                $thisMonth["ValorEjecutadoNuevos"] += $rate->consumed;
                                $servicesThis["ServiciosNuevos"]++;
                                break;
                            }
                        }
                    }
                }
            }
        }

        $previousMonth["TotalGeneral"] = $previousMonth["ValorEjecutadoRecurrentes"] + $previousMonth["ValorEjecutadoNuevos"];
        $thisMonth["TotalGeneral"] =  $thisMonth["ValorEjecutadoRecurrentes"] + $thisMonth["ValorEjecutadoNuevos"];

        $servicesPrevious["TotalGeneral"] = $servicesPrevious["ServiciosRecurrentes"] + $servicesPrevious["ServiciosNuevos"];
        $servicesThis["TotalGeneral"] = $servicesThis["ServiciosRecurrentes"] + $servicesThis["ServiciosNuevos"];

        return [$previousMonth, $thisMonth, $servicesPrevious, $servicesThis];
    }

    public function getPatientsCount($start)
    {
        $previousMonth = null;
        $thisMonth = null;

        $previousStart = clone $start;

        $thisMonth["Fecha"] = $start->format('Y-m');
        $previousMonth["Fecha"] = $previousStart->subMonth(1)->format('Y-m');

        $previousMonth["Recurrentes"] = count($this->oldPatientsPrevious);
        $previousMonth["Nuevos"] = count($this->newPatientsPrevious);
        $previousMonth["TotalGeneral"] = $previousMonth["Recurrentes"] + $previousMonth["Nuevos"];

        $thisMonth["Recurrentes"] = count($this->oldPatientsThis);
        $thisMonth["Nuevos"] = count($this->newPatientsThis);
        $thisMonth["TotalGeneral"] = $thisMonth["Recurrentes"] + $thisMonth["Nuevos"];

        return [$previousMonth, $thisMonth];
    }

    public function getAverageTicket($sales, $services, $start)
    {
        $previousMonth = null;
        $thisMonth = null;

        $previousStart = clone $start;

        $previousMonth["Fecha"] = $previousStart->subMonth(1)->format('Y-m');
        $thisMonth["Fecha"] = $start->format('Y-m');

        $previousMonth["TicketPromedioRecurrentes"] = intval(($sales[0]["ValorEjecutadoRecurrentes"] / 1.18) / ($services[0]["ServiciosRecurrentes"] > 0 ? $services[0]["ServiciosRecurrentes"] : 1));
        $previousMonth["TicketPromedioNuevos"] = intval(($sales[0]["ValorEjecutadoNuevos"] / 1.18) / ($services[0]["ServiciosNuevos"] > 0 ? $services[0]["ServiciosNuevos"] : 1));

        if($this->user->hasRole('admin'))
        {
            $thisMonth["TicketPromedioRecurrentes"] = intval(($sales[1]["ValorEjecutadoRecurrentes"] / 1.18) / ($services[1]["ServiciosRecurrentes"] > 0 ? $services[1]["ServiciosRecurrentes"] : 1));
            $thisMonth["TicketPromedioNuevos"] = intval(($sales[1]["ValorEjecutadoNuevos"] / 1.18) / ($services[1]["ServiciosNuevos"] > 0 ? $services[1]["ServiciosNuevos"] : 1));
            $thisMonth["TotalGeneral"] = $thisMonth["TicketPromedioRecurrentes"] + $thisMonth["TicketPromedioNuevos"];
        }

        $previousMonth["TotalGeneral"] = $previousMonth["TicketPromedioRecurrentes"] + $previousMonth["TicketPromedioNuevos"];

        return [$previousMonth, $thisMonth];
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

        return Excel::download(new PatientsDayExport($request->office,$request->start,$request->end), 'FISIO-Pacientes-Atendidos.xlsx');
    }
}
