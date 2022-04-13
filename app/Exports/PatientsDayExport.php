<?php

namespace App\Exports;

use App\Models\Appointment;
use App\Models\AssistedAppointments;
use App\Models\PatientPayment;
use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PatientsDayExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents //
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $office;
    protected $start;
    protected $end;

    function __construct($office,$start,$end)
    {
        $this->office = $office;
        $this->start = $start;
        $this->end = $end;        
       
    }

    public function array(): array
    {
        $payments = PatientPayment::query()
            ->whereBetween('created_at', 
                array(
                    $this->start." 00:00:00", 
                    $this->end." 23:59:59"
                ))
            ->with('patientRate.appointment.patient', 'patientRate.appointment.doctor', 'patientRate.subfamily.family', 'paymentMethod', 'patient')
            ->get();

        //logs()->warning($this->office);
            
        $data = array();

        $kchiAppointments = array();

        foreach($payments as $payment)
        {
            $rate = $payment->patientRate;

            if($rate == null) continue;

            //if($rate->appointment_id == 0) continue;

            $appointment = Appointment::query()->where('date', substr($payment->created_at, 0, 10))->where('status', Appointment::STATUS_ASSISTED)->where('patient_id', $payment->patient_id)->first();//$rate->appointment;

            if(!$appointment) continue;

            $aa = AssistedAppointments::query()->where('appointment_id', $appointment->id)->first();

            if($aa->patient_rate_id != $rate->id) continue;

            if($appointment->office_id != $this->office) continue;

            if($appointment->doctor_id == 1 || $appointment->status != Appointment::STATUS_ASSISTED) continue;

            $appointment = $appointment->load('doctor');

            $survey = Survey::query()->where('appointment_id', $appointment->id)->first();
            
            $sessionsAssisted = ($rate->sessions_total - $rate->sessions_left) * $rate->price / $rate->sessions_total;
            $date = $payment->created_at;

            $patient = $payment->patient;

            $firstPaymentEver = PatientPayment::query()->where("patient_id", $appointment->patient_id)->first();

            $monthNum = substr($firstPaymentEver->created_at, 5, 2);

            $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));

            $user = auth()->user();

            if($user->hasRole('admin'))
            {
                array_push($data, array(
                    "Nombres y Apellidos del Doctor" => $appointment->doctor->fullname,
                    "Año" => substr($date, 0, 4),
                    "Mes" => substr($date, 5, 2),
                    "Día" => substr($date, 8, 2),
                    "Fecha" => substr($date, 0, 10),
                    "Hora de Atención" => strval($appointment->start)." - ".strval($appointment->end),
                    "Nombres y Apellidos Paciente" => $patient->fullname,
                    "C. Ofi" => $survey ? $survey->office_score : "n/a",
                    "C. Ser" => $survey ? $survey->service_score : "n/a",
                    "C. Doc" => $survey ? $survey->doctor_score : "n/a",
                    "Comentario" => $survey ? $survey->comment : "n/a",
                    "Dni del Paciente" => $patient->dni,
                    "Celular del paciente" => $patient->phone,
                    "Familia" => $rate->subfamily->family->name,
                    "Sub Familia" => $rate->subfamily->name,
                    "Tipo de Tarifa" => $rate->name,
                    "Forma de Pago" => $payment->paymentMethod->payment_method,
                    "Precio Tarifa"	 => $rate->price,
                    "Monto Cobrado"	=> $payment->ammount,
                    "Saldo Cobrado" => $rate->appointment_price,
                    "Valor Ejecutado" => $sessionsAssisted,
                    "Mes Origen" => $monthName,
                    "Sucursal" => $appointment->office,
                    "Historial Medico" => ($appointment->history_created ? "Sí" : "No"),
                ));
            }
            else
            {
                array_push($data, array(
                    "Nombres y Apellidos del Doctor" => $appointment->doctor->fullname,
                    "Año" => substr($date, 0, 4),
                    "Mes" => substr($date, 5, 2),
                    "Día" => substr($date, 8, 2),
                    "Fecha" => substr($date, 0, 10),
                    "Hora de Atención" => strval($appointment->start)." - ".strval($appointment->end),
                    "Nombres y Apellidos Paciente" => $patient->fullname,
                    "C. Ofi" => $survey ? $survey->office_score : "n/a",
                    "C. Ser" => $survey ? $survey->service_score : "n/a",
                    "C. Doc" => $survey ? $survey->doctor_score : "n/a",
                    "Comentario" => $survey ? $survey->comment : "n/a",
                    "Familia" => $rate->subfamily->family->name,
                    "Sub Familia" => $rate->subfamily->name,
                    "Tipo de Tarifa" => $rate->name,
                    "Forma de Pago" => $payment->paymentMethod->payment_method,
                    "Precio Tarifa"	 => $rate->price,
                    "Monto Cobrado"	=> $payment->ammount,
                    "Saldo Cobrado" => $rate->appointment_price,
                    "Valor Ejecutado" => $sessionsAssisted,
                    "Mes Origen" => $monthName,
                    "Sucursal" => $appointment->office,
                ));
            }

            if($appointment->id == 62444) logs()->warning("push 62444");

            array_push($kchiAppointments, $appointment->id);
        }

        $assistances = AssistedAppointments::query()
            ->with('patientRate.subfamily.family')
            ->whereBetween('created_at', 
                array(
                    $this->start." 00:00:00", 
                    $this->end." 23:59:59"
                ))
            ->get();

        foreach($assistances as $assistance)
        {
            $wasMarked = false;
            foreach($kchiAppointments as $kchiAppointment)
            {
                if($assistance->appointment_id == $kchiAppointment)
                {
                    if($assistance->appointment_id == 62444) logs()->warning("mark 62444");
                    $wasMarked = true;
                    break;
                }
            }

            if($wasMarked) continue;


            $appointment = Appointment::with('doctor', 'patient')->find($assistance->appointment_id);

            if($appointment->office_id != $this->office) continue;

            $survey = Survey::query()->where('appointment_id', $appointment->id)->first();
            
            $date = $appointment->date;
            
            $rate = $assistance->patientRate;
            
            $sessionsAssisted = ($rate->sessions_total - $rate->sessions_left) * $rate->price / $rate->sessions_total;

            $firstPaymentEver = PatientPayment::query()->where("patient_id", $appointment->patient_id)->first();
            $monthNum = "n/a";
            $monthName = "n/a";
            if($firstPaymentEver)
            {
                $monthNum = substr($firstPaymentEver->created_at, 5, 2);
                $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));
            }

            $user = auth()->user();

            if($user->hasRole('admin'))
            {
                array_push($data, array(
                    "Nombres y Apellidos del Doctor" => $appointment->doctor->fullname,
                    "Año" => substr($date, 0, 4),
                    "Mes" => substr($date, 5, 2),
                    "Día" => substr($date, 8, 2),
                    "Fecha" => substr($date, 0, 10),
                    "Hora de Atención" => strval($appointment->start)." - ".strval($appointment->end),
                    "Nombres y Apellidos Paciente" => $appointment->patient->fullname,
                    "C. Ofi" => $survey ? $survey->office_score : "n/a",
                    "C. Ser" => $survey ? $survey->service_score : "n/a",
                    "C. Doc" => $survey ? $survey->doctor_score : "n/a",
                    "Comentario" => $survey ? $survey->comment : "n/a",
                    "Dni del Paciente" => $appointment->patient->dni,
                    "Celular del paciente" => $appointment->patient->phone,
                    "Familia" => $rate->subfamily->family->name,
                    "Sub Familia" => $rate->subfamily->name,
                    "Tipo de Tarifa" => $rate->name,
                    "Forma de Pago" => "Contra saldo a favor",
                    "Precio Tarifa"	 => $rate->price,
                    "Monto Cobrado"	=> "0",
                    "Saldo Cobrado" => $rate->appointment_price,
                    "Valor Ejecutado" => $sessionsAssisted,
                    "Mes Origen" => $monthName,
                    "Sucursal" => $appointment->office,
                    "Historial Medico" => ($appointment->history_created ? "Sí" : "No"),
                ));
            }
            else
            {
                array_push($data, array(
                    "Nombres y Apellidos del Doctor" => $appointment->doctor->fullname,
                    "Año" => substr($date, 0, 4),
                    "Mes" => substr($date, 5, 2),
                    "Día" => substr($date, 8, 2),
                    "Fecha" => substr($date, 0, 10),
                    "Hora de Atención" => strval($appointment->start)." - ".strval($appointment->end),
                    "Nombres y Apellidos Paciente" => $appointment->patient->fullname,
                    "C. Ofi" => $survey ? $survey->office_score : "n/a",
                    "C. Ser" => $survey ? $survey->service_score : "n/a",
                    "C. Doc" => $survey ? $survey->doctor_score : "n/a",
                    "Comentario" => $survey ? $survey->comment : "n/a",
                    "Familia" => $rate->subfamily->family->name,
                    "Sub Familia" => $rate->subfamily->name,
                    "Tipo de Tarifa" => $rate->name,
                    "Forma de Pago" => "Contra saldo a favor",
                    "Precio Tarifa"	 => $rate->price,
                    "Monto Cobrado"	=> "0",
                    "Saldo Cobrado" => $rate->appointment_price,
                    "Valor Ejecutado" => $sessionsAssisted,
                    "Mes Origen" => $monthName,
                    "Sucursal" => $appointment->office,
                ));
            }
        }

        return $data;
    }
    public function headings(): array
    {
        $user = auth()->user();
        if($user->hasRole('admin'))
        {
            return [
                "Nombres y Apellidos del Doctor",
                "Año",
                "Mes",
                "Día",
                "Fecha",
                "Hora de Atención",
                "Nombres y Apellidos Paciente",
                "C. Ofi",
                "C. Ser",
                "C. Doc",
                "Comentario",
                "Dni del Paciente",
                "Celular del paciente",
                "Familia",
                "Sub Familia",
                "Tipo de Tarifa",
                "Forma de Pago",
                "Precio Tarifa",
                "Monto Cobrado",
                "Saldo Cobrado",
                "Valor Ejecutado",
                "Mes Origen",
                "Sucursal",
                "Historial Medico",
            ];
        }

        return [
            "Nombres y Apellidos del Doctor",
            "Año",
            "Mes",
            "Día",
            "Fecha",
            "Hora de Atención",
            "Nombres y Apellidos Paciente",
            "C. Ofi",
            "C. Ser",
            "C. Doc",
            "Comentario",
            "Familia",
            "Sub Familia",
            "Tipo de Tarifa",
            "Forma de Pago",
            "Precio Tarifa",
            "Monto Cobrado",
            "Saldo Cobrado",
            "Valor Ejecutado",
            "Mes Origen",
            "Sucursal",
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 35,            
            'B' => 10,
            'C' => 20,
            'D' => 35,
            'E' => 15,
            'F' => 20,
            'G' => 20,
            'H' => 25,
            'I' => 30,
            'J' => 25,
            'K' => 20,
            'L' => 20,
            'M' => 20,
            'N' => 30,
        ];
    }
    public function styles(Worksheet $sheet)
    {


        return [


            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // Styling a specific cell by coordinate.
            //'B' => ['font' => ['italic' => true]],

            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
        ];
    }
    public function registerEvents(): array
    {
        $styleArray = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            )
        );
        return [
            AfterSheet::class => function (AfterSheet $event) use ($styleArray) {
                $event->sheet->getStyle('A1:N1')->applyFromArray($styleArray);
                $event->sheet->getStyle('B1:B300')->applyFromArray($styleArray);
                $event->sheet->getStyle('C1:C300')->applyFromArray($styleArray);
                $event->sheet->getStyle('E1:E300')->applyFromArray($styleArray);
            }
        ];
    }
    public function title(): string
    {
        return 'Pacientes Atendidos Dia';
    }
}
