<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PatientsDayExport;
use App\Exports\PatientsExport;
use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StatisticsController extends Controller
{
    //
    
    public function index()
    {

        $date_now = Carbon::now()->format('Y-m-d H:i:s');
        $date_before = Carbon::now()->subMonth(6)->format('Y-m-d H:i:s');
        $officeId=Office::select('id')->first();
       
        //CLIENTES
        $patient = DB::select("SELECT
            a.months AS Fecha,
            COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS Recurrentes,
            COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS Nuevos,
            IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
            FROM
            (
                SELECT 
                COUNT(pr.patient_id) AS nuevo,
                DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                from appointments a 
                join offices o on a.office_id = o.id 
                join patient_rates pr on pr.appointment_id=a.id
                where a.status=3 
                and (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "') 
                AND o.id = '" . $officeId->id . "'
                GROUP BY pr.patient_id, months
            ) AS a
            GROUP BY Fecha
            ");

        //VENTAS
        $sales = DB::select("SELECT
            a.Mes AS Fecha,  
            IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
            IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
            TRUNCATE(IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),0) + IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),0),2) as TotalGeneral
            FROM (
            SELECT 
               DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                COUNT(a.id) AS NumAsistencias,
                a.patient_id as paciente,
                Sum((sessions_total - sessions_left) * (price/sessions_total)) as valorEjecutado 
                from patient_rates a
                join appointments b on a.appointment_id = b.id
                join  offices o on b.office_id = o.id
                where b.status = '3' AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                AND o.id = '" . $officeId->id . "'
                GROUP BY Mes,a.patient_id 
               ) AS a GROUP BY Fecha
               ");

        //TICKET
        $ticket = DB::select(
            "SELECT
            b.Fecha AS Fecha,
            IFNULL(TRUNCATE((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes, 2), 0) AS TicketPromedioRecurrentes,
            IFNULL(TRUNCATE((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,2), 0) AS TicketPromedioNuevos,
            TRUNCATE(IFNULL((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes,0) + IFNULL((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,0),2) AS TotalGeneral
            FROM
            (
                SELECT
                a.Mes AS Fecha,
                IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.NumAsistencias END),0) AS TotalAsistenciasRecurrentes,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoRecurrentes,
                IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.NumAsistencias END),0) AS TotalAsistenciasNuevos,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoNuevos
                    FROM
                    (
                        SELECT
                        DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                         COUNT(a.id) AS NumAsistencias,
                        a.patient_id AS paciente,
                        SUM((a.sessions_total - a.sessions_left) * (a.price / a.sessions_total)) AS valorEjecutado
                        FROM patient_rates a
                        join appointments b ON a.appointment_id = b.id
                        join  offices o on b.office_id = o.id
                        WHERE b.status = '3' AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                        AND o.id = '" . $officeId->id . "'
                        GROUP BY  Mes, a.patient_id
                    ) AS a
                    GROUP BY Fecha 
            ) AS b"
        );
        //SERVICES NUMBER
        $nServcices = DB::select(
            "SELECT a.months AS Fecha,
            IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) AS ServiciosRecurrentes,
            IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) AS ServiciosNuevos,
            IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) + IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
            FROM
            (
                SELECT 
                COUNT(pr.patient_id) AS nuevo,
                DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                from appointments a 
                join offices o on a.office_id = o.id 
                join patient_rates pr on pr.appointment_id=a.id
                where a.status = '3' 
                AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                AND o.id = '" . $officeId->id . "'
                GROUP BY pr.patient_id, months
            ) AS a
            GROUP BY Fecha"
        );

        $recommendation = DB::select("SELECT id,recommendation FROM recommendations");
        $offices=DB::select("SELECT id, name FROM offices");
      

        return inertia('Backend/Statistics/statistics', ['offices'=>$offices,'recommendation'=>$recommendation,'patient' => $patient, 'sales' => $sales, 'ticket' => $ticket, 'nServices' => $nServcices]);
    }
    public function statistic(Request $request)
    {
        $date_now = Carbon::now()->format('Y-m-d');
        $date_before = Carbon::now()->subMonth(6)->format('Y-m-d');

        //FILTER OF DAY
        if ($request->params['advances'] == 1) {

            $patient = DB::select(
                "SELECT
                a.months AS Fecha,
                COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS Recurrentes,
                COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS Nuevos,
                IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(pr.patient_id) AS nuevo,
                    DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                    from appointments a 
                    join offices o on a.office_id = o.id 
                    join patient_rates pr on pr.appointment_id=a.id
                    where a.status=3 
                    AND DATE_FORMAT(pr.created_at, '%d') <= '".$request->params['daySelected']."'
                    AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    AND o.id = '" . $request->params['office'] . "'
                    GROUP BY pr.patient_id, months
                ) AS a
                GROUP BY Fecha"
            );

            //Sales
            $sales = DB::select("SELECT
                a.Mes AS Fecha,  
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
                TRUNCATE(IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),0) + IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),0),2) as TotalGeneral
                FROM (
                    SELECT 
                    DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                    COUNT(a.patient_id) AS NumAsistencias,
                    Sum((a.sessions_total - a.sessions_left) * (a.price/a.sessions_total)) as valorEjecutado 
                    from patient_rates a
                    join appointments b on a.appointment_id = b.id
                    join  offices o on b.office_id = o.id
                    where b.status = '3' AND DATE_FORMAT(a.created_at, '%d') <= '".$request->params['daySelected']."'
                    AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    AND o.id = '" . $request->params['office'] . "'
                    GROUP BY Mes,a.patient_id 
                ) AS a GROUP BY Fecha"
            );

            //TICKET
            $ticket = DB::select(
                "SELECT
                b.Fecha AS Fecha,
                IFNULL(TRUNCATE((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes, 2), 0) AS TicketPromedioRecurrentes,
                IFNULL(TRUNCATE((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,2), 0) AS TicketPromedioNuevos,
                TRUNCATE(IFNULL((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes,0) + IFNULL((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,0),2) AS TotalGeneral
                FROM
                (
                    SELECT
                    a.Mes AS Fecha,
                    IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.NumAsistencias END),0) AS TotalAsistenciasRecurrentes,
                    IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoRecurrentes,
                    IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.NumAsistencias END),0) AS TotalAsistenciasNuevos,
                    IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoNuevos
                        FROM
                        (
                            SELECT
                            DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                            COUNT(a.patient_id) AS NumAsistencias,
                            SUM((a.sessions_total - a.sessions_left) *(a.price / a.sessions_total)) AS valorEjecutado
                            FROM patient_rates a
                            JOIN appointments b ON a.appointment_id = b.id
                            JOIN  offices o on b.office_id = o.id
                            WHERE b.status = '3' AND DATE_FORMAT(a.created_at, '%d') <= '".$request->params['daySelected']."'
                            AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                            AND o.id = '" . $request->params['office'] . "'
                            GROUP BY  Mes, a.patient_id
                        ) AS a
                        GROUP BY Fecha 
                ) AS b"
            );

            $nServices = DB::select(
                "SELECT a.months AS Fecha,
                IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) AS ServiciosRecurrentes,
                IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) AS ServiciosNuevos,
                IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) + IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(pr.patient_id) AS nuevo,
                    DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                    from appointments a 
                    join offices o on a.office_id = o.id 
                    join patient_rates pr on pr.appointment_id=a.id
                    where a.status = '3' 
                    AND DATE_FORMAT(pr.created_at, '%d') <= '".$request->params['daySelected']."'
                    AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    AND o.id = '" . $request->params['office'] . "'
                    GROUP BY pr.patient_id, months
                ) AS a
                GROUP BY Fecha"
            );
        }
        
        //EVOLUTION OF MONTH
        if ($request->params['advances'] == 2) {

            $patient = DB::select(
                "SELECT
                a.months AS Fecha,
                COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS Recurrentes,
                COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS Nuevos,
                IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(pr.patient_id) AS nuevo,
                    DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                    from appointments a 
                    join offices o on a.office_id = o.id 
                    join patient_rates pr on pr.appointment_id=a.id
                    where a.status=3 
                    AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    AND o.id = '" . $request->params['office'] . "'
                    GROUP BY pr.patient_id, months                    
                ) AS a
                GROUP BY Fecha"
            );

            //VENTAS
            $sales = DB::select("SELECT
                a.Mes AS Fecha,  
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
                TRUNCATE(IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),0) + IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),0),2) as TotalGeneral
                FROM (
                SELECT 
                DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                    COUNT(a.patient_id) AS NumAsistencias,
                    Sum((a.sessions_total - a.sessions_left) * (a.price/a.sessions_total)) as valorEjecutado 
                    from patient_rates a
                    join appointments b on a.appointment_id = b.id
                    join  offices o on b.office_id = o.id
                    where b.status = '3' 
                    AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    AND o.id = '" . $request->params['office'] . "'
                    GROUP BY Mes,a.patient_id 
                ) AS a GROUP BY Fecha"
            );

            //TICKET
            $ticket = DB::select(
                "SELECT
                b.Fecha AS Fecha,
                IFNULL(TRUNCATE((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes, 2), 0) AS TicketPromedioRecurrentes,
                IFNULL(TRUNCATE((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,2), 0) AS TicketPromedioNuevos,
                TRUNCATE(IFNULL((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes,0) + IFNULL((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,0),2) AS TotalGeneral
                FROM
                (
                    SELECT
                    a.Mes AS Fecha,
                    IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.NumAsistencias END),0) AS TotalAsistenciasRecurrentes,
                    IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoRecurrentes,
                    IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.NumAsistencias END),0) AS TotalAsistenciasNuevos,
                    IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoNuevos
                        FROM
                        (
                            SELECT
                            DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                            COUNT(a.patient_id) AS NumAsistencias,
                            SUM((a.sessions_total - a.sessions_left) *(a.price / a.sessions_total)) AS valorEjecutado
                            FROM patient_rates a
                            JOIN appointments b ON a.appointment_id = b.id
                            JOIN  offices o on b.office_id = o.id
                            WHERE b.status = '3' 
                            AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                            AND o.id = '" . $request->params['office'] . "'
                            GROUP BY  Mes, a.patient_id
                        ) AS a
                        GROUP BY Fecha 
                ) AS b"
            );

            $nServices = DB::select(
                "SELECT a.months AS Fecha,
                IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) AS ServiciosRecurrentes,
                IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) AS ServiciosNuevos,
                IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) + IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(pr.patient_id) AS nuevo,
                    DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                    from appointments a 
                    join offices o on a.office_id = o.id 
                    join patient_rates pr on pr.appointment_id=a.id
                    where a.status = '3' 
                    AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    AND o.id = '" . $request->params['office'] . "'
                    GROUP BY pr.patient_id, months
                ) AS a
                GROUP BY Fecha"
            );
        }

        //FILTER OF DATE
        if ($request->params['advances'] == 3) {

            $patient = DB::select(
                "SELECT
                a.months AS Fecha,
                COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS Recurrentes,
                COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS Nuevos,
                IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(pr.patient_id) AS nuevo,
                    DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                    from appointments a 
                    join offices o on a.office_id = o.id 
                    join patient_rates pr on pr.appointment_id=a.id
                    where a.status=3 
                    AND (pr.created_at BETWEEN '" . $request->dates['start'] . "' AND '" . $request->dates['end'] . "')
                    AND o.id = '" . $request->params['office'] . "'
                    GROUP BY pr.patient_id, months
                ) AS a
                GROUP BY Fecha"
            );

            //VENTAS
            $sales = DB::select("SELECT
                a.Mes AS Fecha,  
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
                TRUNCATE(IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),0) + IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),0),2) as TotalGeneral
                FROM (
                    SELECT 
                    DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                    COUNT(a.patient_id) AS NumAsistencias,
                    Sum((a.sessions_total - a.sessions_left) * (a.price/a.sessions_total)) as valorEjecutado 
                    from patient_rates a
                    join appointments b on a.appointment_id = b.id
                    join  offices o on b.office_id = o.id
                    where b.status = '3' AND (a.created_at BETWEEN '" . $request->dates['start'] . "' AND '" . $request->dates['end'] . "')
                    AND o.id = '" . $request->params['office'] . "'
                    GROUP BY Mes,a.patient_id 
                ) AS a GROUP BY Fecha"
            );

            //TICKET
            $ticket = DB::select(
                "SELECT
                b.Fecha AS Fecha,
                IFNULL(TRUNCATE((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes, 2), 0) AS TicketPromedioRecurrentes,
                IFNULL(TRUNCATE((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,2), 0) AS TicketPromedioNuevos,
                TRUNCATE(IFNULL((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes,0) + IFNULL((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,0),2) AS TotalGeneral
                FROM
                (
                    SELECT
                    a.Mes AS Fecha,
                    IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.NumAsistencias END),0) AS TotalAsistenciasRecurrentes,
                    IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoRecurrentes,
                    IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.NumAsistencias END),0) AS TotalAsistenciasNuevos,
                    IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoNuevos
                        FROM
                        (
                            SELECT
                            DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                            COUNT(a.patient_id) AS NumAsistencias,
                            SUM((a.sessions_total - a.sessions_left) *(a.price / a.sessions_total)) AS valorEjecutado
                            FROM patient_rates a
                            JOIN appointments b ON a.appointment_id = b.id
                            JOIN  offices o on b.office_id = o.id
                            WHERE b.status = '3' 
                            AND (a.created_at BETWEEN '" . $request->dates['start'] . "' AND '" . $request->dates['end'] . "')
                            AND o.id = '" . $request->params['office'] . "'
                           GROUP BY  Mes, a.patient_id
                        ) AS a
                        GROUP BY Fecha 
                ) AS b"
            );

            $nServices = DB::select(
                "SELECT a.months AS Fecha,
                IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) AS ServiciosRecurrentes,
                IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) AS ServiciosNuevos,
                IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) + IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(pr.patient_id) AS nuevo,
                    DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                    from appointments a 
                    join offices o on a.office_id = o.id 
                    join patient_rates pr on pr.appointment_id=a.id
                    where a.status = '3' 
                    AND (pr.created_at BETWEEN  '" . $request->dates['start'] . "' AND '" . $request->dates['end'] . "')
                    AND o.id = '" . $request->params['office'] . "'
                    GROUP BY pr.patient_id, months
                ) AS a
                GROUP BY Fecha"
            );
        }

        //RECOMMENDATIONS       
        if ($request->params['advances'] == 4) {
 
             $patient = DB::select(
                "SELECT
                a.months AS Fecha,
                COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS Recurrentes,
                COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS Nuevos,
                IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(pr.patient_id) AS nuevo,
                    DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                    from appointments a 
                    join  offices o on a.office_id = o.id
                    join patient_rates pr on pr.appointment_id=a.id 
                    join patients p on pr.patient_id = p.id 
                    JOIN recommendations r on p.reccomendation_id = r.id
                    where a.status=3 
                    AND r.id= ".$request->paramsRecommendations['recommendations']."
                    and o.id = '" . $request->params['office'] . "'
                    AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    GROUP BY pr.patient_id, months
                ) AS a
                GROUP BY Fecha"
            );

            //VENTAS            
            $sales = DB::select("SELECT
                a.Mes AS Fecha,  
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
                TRUNCATE(IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),0) + IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),0),2) as TotalGeneral
                FROM (
                    SELECT 
                    DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                    COUNT(a.id) AS NumAsistencias,
                    Sum((a.sessions_total - a.sessions_left) * (a.price/a.sessions_total)) as valorEjecutado
                    from patient_rates a
                    join appointments b on a.appointment_id = b.id
                    join offices o on b.office_id = o.id
                    join patients p on p.id = a.patient_id
                    join recommendations r on r.id = p.reccomendation_id
                    where b.status = '3' 
                    and r.id = ".$request->paramsRecommendations['recommendations']."
                    AND o.id = '" . $request->params['office'] . "'
                    AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    GROUP BY Mes,a.patient_id 
                ) AS a GROUP BY Fecha"
            );

            //TICKET
            $ticket = DB::select(
                "SELECT
                b.Fecha AS Fecha,
                IFNULL(TRUNCATE((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes, 2), 0) AS TicketPromedioRecurrentes,
                IFNULL(TRUNCATE((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,2), 0) AS TicketPromedioNuevos,
                TRUNCATE(IFNULL((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes,0) + IFNULL((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,0),2) AS TotalGeneral
                FROM
                (
                    SELECT
                    a.Mes AS Fecha,
                    IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.NumAsistencias END),0) AS TotalAsistenciasRecurrentes,
                    IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoRecurrentes,
                    IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.NumAsistencias END),0) AS TotalAsistenciasNuevos,
                    IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoNuevos
                        FROM
                        (
                            SELECT
                            DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                            COUNT(a.patient_id) AS NumAsistencias,
                            SUM((a.sessions_total - a.sessions_left) *(a.price / a.sessions_total)) AS valorEjecutado
                            FROM patient_rates a
                            JOIN appointments b ON a.appointment_id = b.id
                            join patients p on p.id = a.patient_id
                            join offices o on b.office_id = o.id
                            join recommendations r on r.id = p.reccomendation_id                   
                            WHERE b.status = '3' 
                            AND r.id = ".$request->paramsRecommendations['recommendations']."
                            and o.id = '" . $request->params['office'] . "'
                            AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                            GROUP BY  Mes, a.patient_id
                        ) AS a
                        GROUP BY Fecha 
                ) AS b"
            );

            $nServices = DB::select(
                "SELECT a.months AS Fecha,
                IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) AS ServiciosRecurrentes,
                IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) AS ServiciosNuevos,
                IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) + IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral    
                FROM
                (
                    SELECT 
                    COUNT(pr.patient_id) AS nuevo,
                    DATE_FORMAT(pr.created_at, '%Y-%m') AS months
                    FROM appointments ap
                    join offices o on ap.office_id = o.id
                    join patient_rates pr on pr.appointment_id=ap.id
                    join patients p on p.id = pr.patient_id
                    join recommendations r on r.id = p.reccomendation_id 
                    where ap.status = '3' 
                    AND r.id = ".$request->paramsRecommendations['recommendations']."
                    AND o.id = '" . $request->params['office'] . "'
                    AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    GROUP BY pr.patient_id, months
                ) AS a
                GROUP BY Fecha"
            );
            
        }
        //FAMILIES RATES      
        if ($request->params['advances'] == 5) {
 
            $patient = DB::select(
               "SELECT
               a.months AS Fecha,
               COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS Recurrentes,
               COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS Nuevos,
               IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
               FROM
               (
                    SELECT 
                    COUNT(pr.patient_id) AS nuevo,
                    DATE_FORMAT(pr.created_at, '%Y-%m') AS months                   
                    from appointments a 
                    join offices o on a.office_id = o.id 
                    join patient_rates pr on pr.appointment_id = a.id
                    join subfamilies sb on sb.id =  pr.subfamily_id 
                    join families f on f.id = sb.family_id
                    join rates r on r.subfamily_id =sb.id
                    where a.status=3  
                    AND IF('".$request->params['rate_id'] ."'<>'',
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."'  AND r.id = '".$request->params['rate_id'] ."'),
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."' ))   
                    AND o.id = '" . $request->params['office'] . "'
                    AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')                  
                    GROUP BY pr.patient_id, months
               ) AS a
               GROUP BY Fecha"
           );

           //SALES           
           $sales = DB::select("SELECT
               a.Mes AS Fecha,  
               IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
               IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
               TRUNCATE(IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),0) + IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),0),2) as TotalGeneral
               FROM (
                SELECT 
                   DATE_FORMAT(pr.created_at, '%Y-%m') AS Mes,
                   COUNT(pr.patient_id) AS NumAsistencias,
                   Sum((pr.sessions_total - pr.sessions_left) * (pr.price/pr.sessions_total)) as valorEjecutado
                   from patient_rates pr
                   join appointments b on pr.appointment_id = b.id
                   join offices o on b.office_id = o.id
                   join subfamilies sb on sb.id =  pr.subfamily_id                    
                   join families f on f.id = sb.family_id
                   join rates r on r.subfamily_id =sb.id                   
                   where b.status = '3' AND IF('".$request->params['rate_id'] ."'<>'',
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."'  AND r.id = '".$request->params['rate_id'] ."'),
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."' ))
                    AND o.id = '" . $request->params['office'] . "'
                    AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "') 
                   GROUP BY Mes,pr.patient_id 
               ) AS a GROUP BY Fecha"
           );

           //TICKET
           $ticket = DB::select(
               "SELECT
               b.Fecha AS Fecha,
               IFNULL(TRUNCATE((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes, 2), 0) AS TicketPromedioRecurrentes,
               IFNULL(TRUNCATE((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,2), 0) AS TicketPromedioNuevos,
               TRUNCATE(IFNULL((b.ValorEjecutadoRecurrentes / '1.18') / b.TotalAsistenciasRecurrentes,0) + IFNULL((b.ValorEjecutadoNuevos / '1.18') / b.TotalAsistenciasNuevos,0),2) AS TotalGeneral
               FROM
               (
                   SELECT
                   a.Mes AS Fecha,
                   IFNULL(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.NumAsistencias END),0) AS TotalAsistenciasRecurrentes,
                   IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoRecurrentes,
                   IFNULL(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.NumAsistencias END),0) AS TotalAsistenciasNuevos,
                   IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado END),2),0) AS ValorEjecutadoNuevos
                       FROM
                       (
                        SELECT 
                            DATE_FORMAT(pr.created_at, '%Y-%m') AS Mes,
                            COUNT(pr.patient_id) AS NumAsistencias,
                            Sum((pr.sessions_total - pr.sessions_left) * (pr.price/pr.sessions_total)) as valorEjecutado
                            from patient_rates pr
                            join appointments b on pr.appointment_id = b.id
                            JOIN offices o on b.office_id = o.id
                            join subfamilies sb on sb.id =  pr.subfamily_id                    
                            join families f on f.id = sb.family_id
                            join rates r on r.subfamily_id =sb.id                   
                            where b.status = '3' AND IF('".$request->params['rate_id'] ."'<>'',
                            (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."'  AND r.id = '".$request->params['rate_id'] ."'),
                            (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."' ))
                            AND o.id = '" . $request->params['office'] . "'
                            AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "') 
                            GROUP BY Mes,pr.patient_id 
                       ) AS a
                       GROUP BY Fecha 
               ) AS b"
           );
            //N° DE SERVICES
           $nServices = DB::select(
               "SELECT a.months AS Fecha,
               IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) AS ServiciosRecurrentes,
               IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) AS ServiciosNuevos,
               IFNULL(SUM(CASE WHEN a.nuevo > 1 THEN a.nuevo END),0) + IFNULL(SUM(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
               FROM
               (
                   SELECT 
                   COUNT(pr.patient_id) AS nuevo,
                   DATE_FORMAT(pr.created_at, '%Y-%m') AS months
                   FROM appointments ap
                   join offices o on ap.office_id = o.id
                   join patient_rates pr on pr.appointment_id = ap.id
                   join subfamilies sb on sb.id =  pr.subfamily_id                    
                   join families f on f.id = sb.family_id
                   join rates r on r.subfamily_id =sb.id 
                   where ap.status = '3'  AND IF('".$request->params['rate_id'] ."'<>'',
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."'  AND r.id = '".$request->params['rate_id'] ."'),
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."' ))
                    AND o.id = '" . $request->params['office'] . "'
                    AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "') 
                   GROUP BY pr.patient_id, months
               ) AS a
               GROUP BY Fecha"
           );
           
       }

        return response()->json(['patients' => $patient, 'sales' => $sales,'tickets' => $ticket, 'nServices' => $nServices]);
       

    }
    public function excel(Request $request){
    
       
        if($request->start == $request->end){
            return Excel::download(new PatientsDayExport($request->office,$request->start,$request->end), 'FISIO-Pacientes-Atendidos-Dia.xlsx');
        }else{
             return Excel::download(new PatientsExport($request->office,$request->start,$request->end), 'FISIO-Pacientes-Atendidos.xlsx');
        }


  }
}
