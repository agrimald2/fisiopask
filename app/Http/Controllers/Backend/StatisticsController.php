<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PatientsDayExport;
use App\Exports\PatientsExport;
use App\Http\Controllers\Controller;
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
        //dd($date_before);


        //CLIENTES
        $patient = DB::select("SELECT
            a.months AS Fecha,
            COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS Recurrentes,
            COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS Nuevos,
            IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
            FROM
            (
                SELECT 
                COUNT(*) AS nuevo,
                DATE_FORMAT(created_at, '%Y-%m') AS months
                FROM patient_payments
                where (created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                GROUP BY patient_id, months
            ) AS a
            GROUP BY Fecha");

        //VENTAS
        $sales = DB::select("SELECT
            a.Mes AS Fecha,  
            IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
            IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
            IFNULL(IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) + TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) as TotalGeneral
            FROM (
            SELECT 
               DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                COUNT(*) AS NumAsistencias,
                a.patient_id as paciente,
                Sum((sessions_total - sessions_left) * (price/sessions_total)) as valorEjecutado 
                from patient_rates a
                join appointments b on a.appointment_id = b.id
                where b.status = '3' AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
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
                         COUNT(*) AS NumAsistencias,
                        a.patient_id AS paciente,
                        SUM((sessions_total - sessions_left) *(price / sessions_total)) AS valorEjecutado
                        FROM
                            patient_rates a
                        JOIN appointments b ON
                            a.appointment_id = b.id
                        WHERE
                            b.status = '3' AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                        GROUP BY  Mes, a.patient_id
                    ) AS a
                    GROUP BY Fecha 
            ) AS b"
        );

        $nServcices = DB::select(
            "SELECT a.months AS Fecha,
            COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS ServiciosRecurrentes,
            COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS ServiciosNuevos,
            IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
            FROM
            (
                SELECT 
                COUNT(*) AS nuevo,
                DATE_FORMAT(created_at, '%Y-%m') AS months            
                FROM appointments
                where status = '3' AND (created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                GROUP BY patient_id, months
            ) AS a
            GROUP BY Fecha"
        );

        $recommendation = DB::select("SELECT id,recommendation FROM recommendations");
      

        return inertia('Backend/Statistics/statistics', ['recommendation'=>$recommendation,'patient' => $patient, 'sales' => $sales, 'ticket' => $ticket, 'nServices' => $nServcices]);
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
                    COUNT(*) AS nuevo,
                    DATE_FORMAT(created_at, '%Y-%m') AS months
                    FROM patient_payments
                    where DATE_FORMAT(created_at, '%d') <= '".$request->params['daySelected']."'
                    AND (created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    GROUP BY patient_id, months
                ) AS a
                GROUP BY Fecha"
            );

            //VENTAS
            $sales = DB::select("SELECT
                a.Mes AS Fecha,  
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
                IFNULL(IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) + TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) as TotalGeneral
                FROM (
                SELECT 
                DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                    COUNT(a.patient_id) AS NumAsistencias,
                    a.patient_id as paciente,
                    Sum((a.sessions_total - a.sessions_left) * (a.price/a.sessions_total)) as valorEjecutado 
                    from patient_rates a
                    join appointments b on a.appointment_id = b.id
                    where b.status = '3' AND DATE_FORMAT(a.created_at, '%d') <= '".$request->params['daySelected']."'
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
                            a.patient_id AS paciente,
                            SUM((a.sessions_total - a.sessions_left) *(a.price / a.sessions_total)) AS valorEjecutado
                            FROM
                                patient_rates a
                            JOIN appointments b ON
                                a.appointment_id = b.id
                            WHERE
                                b.status = '3' AND DATE_FORMAT(a.created_at, '%d') <= '".$request->params['daySelected']."'
                                AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                            GROUP BY  Mes, a.patient_id
                        ) AS a
                        GROUP BY Fecha 
                ) AS b"
            );

            $nServices = DB::select(
                "SELECT a.months AS Fecha,
                COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS ServiciosRecurrentes,
                COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS ServiciosNuevos,
                IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(*) AS nuevo,
                    DATE_FORMAT(created_at, '%Y-%m') AS months            
                    FROM appointments
                    where status = '3' AND DATE_FORMAT(created_at, '%d') <= '".$request->params['daySelected']."'
                    AND (created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    GROUP BY patient_id, months
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
                    COUNT(*) AS nuevo,
                    DATE_FORMAT(created_at, '%Y-%m') AS months
                    FROM patient_payments
                    WHERE (created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    GROUP BY patient_id, months                    
                ) AS a
                GROUP BY Fecha"
            );

            //VENTAS
            $sales = DB::select("SELECT
                a.Mes AS Fecha,  
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
                IFNULL(IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) + TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) as TotalGeneral
                FROM (
                SELECT 
                DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                    COUNT(a.patient_id) AS NumAsistencias,
                    a.patient_id as paciente,
                    Sum((a.sessions_total - a.sessions_left) * (a.price/a.sessions_total)) as valorEjecutado 
                    from patient_rates a
                    join appointments b on a.appointment_id = b.id
                    where b.status = '3' 
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
                            a.patient_id AS paciente,
                            SUM((a.sessions_total - a.sessions_left) *(a.price / a.sessions_total)) AS valorEjecutado
                            FROM
                                patient_rates a
                            JOIN appointments b ON
                                a.appointment_id = b.id
                            WHERE
                                b.status = '3' 
                            AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                            GROUP BY  Mes, a.patient_id
                        ) AS a
                        GROUP BY Fecha 
                ) AS b"
            );

            $nServices = DB::select(
                "SELECT a.months AS Fecha,
                COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS ServiciosRecurrentes,
                COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS ServiciosNuevos,
                IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(*) AS nuevo,
                    DATE_FORMAT(created_at, '%Y-%m') AS months            
                    FROM appointments
                    where status = '3' 
                    AND (created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    GROUP BY patient_id, months
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
                    COUNT(*) AS nuevo,
                    DATE_FORMAT(created_at, '%Y-%m') AS months
                    FROM patient_payments
                    where (created_at BETWEEN '" . $request->dates['start'] . "' AND '" . $request->dates['end'] . "')
                    GROUP BY patient_id, months
                ) AS a
                GROUP BY Fecha"
            );

            //VENTAS
            $sales = DB::select("SELECT
                a.Mes AS Fecha,  
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
                IFNULL(IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) + TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) as TotalGeneral
                FROM (
                SELECT 
                DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                    COUNT(a.patient_id) AS NumAsistencias,
                    a.patient_id as paciente,
                    Sum((a.sessions_total - a.sessions_left) * (a.price/a.sessions_total)) as valorEjecutado 
                    from patient_rates a
                    join appointments b on a.appointment_id = b.id
                    where b.status = '3' AND (a.created_at BETWEEN '" . $request->dates['start'] . "' AND '" . $request->dates['end'] . "')
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
                            a.patient_id AS paciente,
                            SUM((a.sessions_total - a.sessions_left) *(a.price / a.sessions_total)) AS valorEjecutado
                            FROM
                                patient_rates a
                            JOIN appointments b ON
                                a.appointment_id = b.id
                            WHERE
                                b.status = '3' AND (a.created_at BETWEEN '" . $request->dates['start'] . "' AND '" . $request->dates['end'] . "')
                            GROUP BY  Mes, a.patient_id
                        ) AS a
                        GROUP BY Fecha 
                ) AS b"
            );

            $nServices = DB::select(
                "SELECT a.months AS Fecha,
                COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS ServiciosRecurrentes,
                COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS ServiciosNuevos,
                IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                    SELECT 
                    COUNT(*) AS nuevo,
                    DATE_FORMAT(created_at, '%Y-%m') AS months            
                    FROM appointments
                    where status = '3' AND (created_at BETWEEN  '" . $request->dates['start'] . "' AND '" . $request->dates['end'] . "')
                    GROUP BY patient_id, months
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
                    COUNT(py.patient_id) AS nuevo,
                    DATE_FORMAT(py.created_at, '%Y-%m') AS months
                    FROM patient_payments py
                    join patients p on py.patient_id = p.id 
                    JOIN recommendations r on p.reccomendation_id = r.id
                    where r.id= ".$request->paramsRecommendations['recommendations']."
                    AND (py.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    GROUP BY py.patient_id, months
                ) AS a
                GROUP BY Fecha"
            );

            //VENTAS
            
            $sales = DB::select("SELECT
                a.Mes AS Fecha,  
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
                IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
                IFNULL(IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) + TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) as TotalGeneral
                FROM (
                SELECT 
                DATE_FORMAT(a.created_at, '%Y-%m') AS Mes,
                    COUNT(*) AS NumAsistencias,
                    a.patient_id as paciente,
                    Sum((sessions_total - sessions_left) * (price/sessions_total)) as valorEjecutado
                    from patient_rates a
                    join appointments b on a.appointment_id = b.id
                    join patients p on p.id = a.patient_id
                    join recommendations r on r.id = p.reccomendation_id
                    where b.status = '3' and r.id = ".$request->paramsRecommendations['recommendations']."
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
                            a.patient_id AS paciente,
                            SUM((a.sessions_total - a.sessions_left) *(a.price / a.sessions_total)) AS valorEjecutado
                            FROM
                                patient_rates a
                            JOIN appointments b ON
                                a.appointment_id = b.id
                            join patients p on p.id = a.patient_id
                            join recommendations r on r.id = p.reccomendation_id                   
                            WHERE b.status = '3' AND r.id = ".$request->paramsRecommendations['recommendations']."
                            AND (a.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                            GROUP BY  Mes, a.patient_id
                        ) AS a
                        GROUP BY Fecha 
                ) AS b"
            );

            $nServices = DB::select(
                "SELECT a.months AS Fecha,
                COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS ServiciosRecurrentes,
                COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS ServiciosNuevos,
                IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
                FROM
                (
                SELECT 
                    COUNT(ap.patient_id) AS nuevo,
                    DATE_FORMAT(ap.created_at, '%Y-%m') AS months
                    FROM appointments ap
                    join patients p on p.id = ap.patient_id
                    join recommendations r on r.id = p.reccomendation_id 
                    where ap.status = '3' AND r.id = ".$request->paramsRecommendations['recommendations']."
                    AND (ap.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')
                    GROUP BY ap.patient_id, months
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
                    COUNT(py.patient_id) AS nuevo,
                    DATE_FORMAT(py.created_at, '%Y-%m') AS months
                    FROM patient_payments py
                    join patient_rates pr on pr.id = py.patient_rate_id
                    join subfamilies sb on sb.id =  pr.subfamily_id 
                    join families f on f.id = sb.family_id
                    join rates r on r.subfamily_id =sb.id
                    where 
                    IF('".$request->params['rate_id'] ."'<>'',
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."'  AND r.id = '".$request->params['rate_id'] ."'),
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."' ))   
                    AND (py.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "')                  
                    GROUP BY py.patient_id, months
               ) AS a
               GROUP BY Fecha"
           );

           //SALES           
           $sales = DB::select("SELECT
               a.Mes AS Fecha,  
               IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoRecurrentes,
               IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) AS ValorEjecutadoNuevos,
               IFNULL(IFNULL(TRUNCATE(SUM(CASE WHEN a.NumAsistencias > 1 THEN a.valorEjecutado  END),2),0) + TRUNCATE(SUM(CASE WHEN a.NumAsistencias < 2 THEN a.valorEjecutado  END),2),0) as TotalGeneral
               FROM (
                SELECT 
                   DATE_FORMAT(pr.created_at, '%Y-%m') AS Mes,
                   COUNT(pr.patient_id) AS NumAsistencias,
                   pr.patient_id as paciente,
                   Sum((pr.sessions_total - pr.sessions_left) * (pr.price/pr.sessions_total)) as valorEjecutado
                   from patient_rates pr
                   join appointments b on pr.appointment_id = b.id
                   join subfamilies sb on sb.id =  pr.subfamily_id                    
                   join families f on f.id = sb.family_id
                   join rates r on r.subfamily_id =sb.id                   
                   where b.status = '3' AND IF('".$request->params['rate_id'] ."'<>'',
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."'  AND r.id = '".$request->params['rate_id'] ."'),
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."' ))
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
                            pr.patient_id as paciente,
                            Sum((pr.sessions_total - pr.sessions_left) * (pr.price/pr.sessions_total)) as valorEjecutado
                            from patient_rates pr
                            join appointments b on pr.appointment_id = b.id
                            join subfamilies sb on sb.id =  pr.subfamily_id                    
                            join families f on f.id = sb.family_id
                            join rates r on r.subfamily_id =sb.id                   
                            where b.status = '3' AND IF('".$request->params['rate_id'] ."'<>'',
                            (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."'  AND r.id = '".$request->params['rate_id'] ."'),
                            (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."' ))
                            AND (pr.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "') 
                            GROUP BY Mes,pr.patient_id 
                       ) AS a
                       GROUP BY Fecha 
               ) AS b"
           );
            //N° DE SERVICES
           $nServices = DB::select(
               "SELECT a.months AS Fecha,
               COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) AS ServiciosRecurrentes,
               COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END) AS ServiciosNuevos,
               IFNULL(COUNT(CASE WHEN a.nuevo > 1 THEN a.nuevo END) + COUNT(CASE WHEN a.nuevo < 2 THEN a.nuevo END),0) as TotalGeneral  
               FROM
               (
                SELECT 
                   COUNT(ap.patient_id) AS nuevo,
                   DATE_FORMAT(ap.created_at, '%Y-%m') AS months
                   FROM appointments ap
                   join patients p on p.id = ap.patient_id
                   join patient_rates pr on pr.patient_id = p.id
                   join subfamilies sb on sb.id =  pr.subfamily_id                    
                   join families f on f.id = sb.family_id
                   join rates r on r.subfamily_id =sb.id    
                   where ap.status = '3'  AND IF('".$request->params['rate_id'] ."'<>'',
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."'  AND r.id = '".$request->params['rate_id'] ."'),
                    (f.id = '".$request->params['family_id'] ."'  AND sb.id = '".$request->params['subfamily_id'] ."' ))
                   AND (ap.created_at BETWEEN '" . $date_before . "' AND '" . $date_now . "') 
                   GROUP BY ap.patient_id, months
               ) AS a
               GROUP BY Fecha"
           );
           
       }

        return response()->json(['patients' => $patient, 'sales' => $sales,'tickets' => $ticket, 'nServices' => $nServices]);
       

        //return response()->json($nServices);
    }
    public function excel(Request $request){
    
        if($request->start == $request->end){
            return Excel::download(new PatientsDayExport($request->start,$request->end), 'FISIO-Pacientes-Atendidos-Dia.xlsx');
        }else{
             return Excel::download(new PatientsExport($request->start,$request->end), 'FISIO-Pacientes-Atendidos.xlsx');
        }

    
  }
}
