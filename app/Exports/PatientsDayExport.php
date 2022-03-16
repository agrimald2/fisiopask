<?php

namespace App\Exports;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;
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
        $this->office=$office;
        $this->start = $start;
        $this->end = $end;        
       
    }

    public function array(): array
    {
        $data = DB::select(
            "SELECT 
            CONCAT(d.name, d.lastname) as doctor,
            DATE_FORMAT(a.date,'%d/%m/%y') AS fecha,
            CONCAT(a.start, ' ',a.end) AS horario,
            CONCAT(p.name,' ',p.lastname1,' ',p.lastname2) AS paciente,
            IFNULL(s.service_score,'No calificacion') as score,
            f.name as familias,
            sb.name as subfamilia,
            r.name as tarifa,
            r.description,
            pm.payment_method as metodoPago,
            pr.price as precio,
            pr.payed as pagado,
            TRUNCATE((pr.sessions_total - pr.sessions_left) * (pr.price/pr.sessions_total),2) as valorEjecutado,
            o.name
            FROM appointments a 
            left join surveys s on s.appointment_id=a.id
            join doctors d ON d.id=a.doctor_id 
            join  offices o on a.office_id = o.id
            join patients p 
            join patient_rates pr on p.id=pr.patient_id 
            and a.id=pr.appointment_id
            join subfamilies sb on pr.subfamily_id=sb.id  
            join families f on sb.family_id =f.id
            join rates r on r.subfamily_id = sb.id
            join patient_payments py on py.patient_rate_id= pr.id
            join payment_methods pm on pm.id = py.payment_method_id
            where a.status=3 and o.id='".$this->office."' and (a.date BETWEEN '" . $this->start . "' AND '" . $this->end . "')"
        );
        
        return $data;
    }
    public function headings(): array
    {
        return [
            'Nombres y Apellidos del Doctor',
            'Fecha',
            'Hora de Atención',
            'Nombres y Apellidos Paciente',
            'Calificación',
            'Familia',
            'Sub Familia',
            'Tipo de Tarifa',
            'Tarifa Descripción',
            'Forma de Pago',
            'Precio Tarifa',
            'Monto Cobrado',
            'Valor Ejecutado',
            'Sucursal',
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
