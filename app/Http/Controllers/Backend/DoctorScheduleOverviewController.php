<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Office;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorScheduleOverviewController extends Controller
{
    /**
     * Muestra la vista principal del overview de horarios
     */
    public function index(Request $request)
    {
        // Consultas optimizadas: solo campos necesarios, sin eager loading innecesario
        $doctors = Doctor::query()
            ->select('id', 'name', 'lastname')
            ->orderBy('name')
            ->get()
            ->map(fn ($doctor) => [
                'id' => $doctor->id,
                'name' => trim($doctor->name . ' ' . $doctor->lastname),
            ]);

        $offices = Office::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn ($office) => [
                'id' => $office->id,
                'name' => $office->name,
            ]);

        // Buscar office por defecto con consulta optimizada
        $defaultOfficeId = $request->get('office_id');
        if ($defaultOfficeId === null) {
            $defaultOfficeId = Office::where('name', 'LIKE', '%PRIMAVERA%')
                ->value('id');
        }

        $currentDate = $request->get('date', now()->format('Y-m-d'));
        $weekStart = Carbon::parse($currentDate)->startOfWeek();
        $weekEnd = Carbon::parse($currentDate)->endOfWeek();

        $schedules = $this->getSchedulesForWeek(
            $weekStart,
            $weekEnd,
            $request->get('doctor_ids', []),
            $defaultOfficeId
        );

        return inertia('Backend/ScheduleOverview/Index', [
            'doctors' => $doctors,
            'offices' => $offices,
            'schedules' => $schedules,
            'weekStart' => $weekStart->format('Y-m-d'),
            'weekEnd' => $weekEnd->format('Y-m-d'),
            'currentDate' => $currentDate,
            'filters' => [
                'doctor_ids' => $request->get('doctor_ids', []),
                'office_id' => $defaultOfficeId,
                'date' => $currentDate,
            ],
        ]);
    }

    /**
     * API para obtener horarios filtrados (para actualización dinámica)
     */
    public function filter(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'doctor_ids' => 'nullable|array',
            'doctor_ids.*' => 'numeric',
            'office_id' => 'nullable|numeric',
        ]);

        $currentDate = $validated['date'] ?? now()->format('Y-m-d');
        
        if (isset($validated['date_from']) && isset($validated['date_to'])) {
            $weekStart = Carbon::parse($validated['date_from']);
            $weekEnd = Carbon::parse($validated['date_to']);
        } else {
            $weekStart = Carbon::parse($currentDate)->startOfWeek();
            $weekEnd = Carbon::parse($currentDate)->endOfWeek();
        }

        $schedules = $this->getSchedulesForWeek(
            $weekStart,
            $weekEnd,
            $validated['doctor_ids'] ?? [],
            $validated['office_id'] ?? null
        );

        return response()->json([
            'schedules' => $schedules,
            'weekStart' => $weekStart->format('Y-m-d'),
            'weekEnd' => $weekEnd->format('Y-m-d'),
        ]);
    }

    /**
     * Obtiene los horarios para un rango de semana con información de disponibilidad
     * OPTIMIZADO: Una sola consulta para schedules + una consulta para appointments
     */
    private function getSchedulesForWeek(Carbon $weekStart, Carbon $weekEnd, array $doctorIds = [], $officeId = null)
    {
        // 1. Obtener todos los schedules con doctor y office en UNA sola consulta
        $query = Schedule::query()
            ->select('schedules.*', 'doctors.name as doctor_first_name', 'doctors.lastname as doctor_lastname', 'offices.name as office_name')
            ->join('doctors', 'schedules.doctor_id', '=', 'doctors.id')
            ->join('offices', 'schedules.office_id', '=', 'offices.id')
            ->whereNull('doctors.deleted_at')
            ->orderBy('schedules.start_time');

        if (!empty($doctorIds)) {
            $query->whereIn('schedules.doctor_id', $doctorIds);
        }

        if ($officeId) {
            $query->where('schedules.office_id', $officeId);
        }

        $schedules = $query->get();

        if ($schedules->isEmpty()) {
            return $this->getEmptyWeekStructure();
        }

        // 2. Calcular las fechas de la semana
        $weekDates = [];
        for ($day = 1; $day <= 7; $day++) {
            $weekDates[$day] = $weekStart->copy()->addDays($day - 1)->format('Y-m-d');
        }

        // 3. Obtener TODAS las citas de la semana en UNA sola consulta
        $scheduleIds = $schedules->pluck('id')->toArray();
        
        $appointments = Appointment::query()
            ->select('appointments.schedule_id', 'appointments.date', 'appointments.status', 
                     'patients.name as patient_first_name', 
                     'patients.lastname1 as patient_lastname1', 
                     'patients.lastname2 as patient_lastname2')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->whereIn('appointments.schedule_id', $scheduleIds)
            ->whereBetween('appointments.date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
            ->get()
            ->groupBy(function ($appointment) {
                // Agrupar por schedule_id + date para lookup rápido
                return $appointment->schedule_id . '_' . $appointment->date->format('Y-m-d');
            });

        // 4. Construir la respuesta sin consultas adicionales
        $groupedByDay = $this->getEmptyWeekStructure();

        $statusLabels = [
            1 => 'CONFI',
            2 => 'N A',
            3 => 'ASIS',
            4 => 'CAN',
        ];

        foreach ($schedules as $schedule) {
            $weekDay = (int) $schedule->week_day;
            $dateForDay = $weekDates[$weekDay] ?? null;
            
            if (!$dateForDay) continue;

            // Lookup O(1) en el array de appointments
            $lookupKey = $schedule->id . '_' . $dateForDay;
            $appointment = $appointments->get($lookupKey)?->first();
            $isOccupied = $appointment !== null;

            $doctorFullname = trim($schedule->doctor_first_name . ' ' . $schedule->doctor_lastname);

            $groupedByDay[$weekDay][] = [
                'id' => $schedule->id,
                'doctor_id' => $schedule->doctor_id,
                'doctor_name' => $doctorFullname,
                'office_id' => $schedule->office_id,
                'office_name' => $schedule->office_name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'week_day' => $weekDay,
                'date' => $dateForDay,
                'is_occupied' => $isOccupied,
                'patient_name' => $isOccupied 
                    ? trim($appointment->patient_first_name . ' ' . $appointment->patient_lastname1 . ' ' . $appointment->patient_lastname2) 
                    : null,
                'appointment_status' => $isOccupied ? $appointment->status : null,
                'appointment_status_label' => $isOccupied ? ($statusLabels[$appointment->status] ?? null) : null,
            ];
        }

        // 5. Ordenar por hora de inicio (ya viene ordenado de la query, pero por seguridad)
        foreach ($groupedByDay as $day => $daySchedules) {
            usort($groupedByDay[$day], function ($a, $b) {
                return strcmp($a['start_time'], $b['start_time']);
            });
        }

        return $groupedByDay;
    }

    /**
     * Retorna estructura vacía de la semana
     */
    private function getEmptyWeekStructure(): array
    {
        $groupedByDay = [];
        for ($day = 1; $day <= 7; $day++) {
            $groupedByDay[$day] = [];
        }
        return $groupedByDay;
    }

    /**
     * Obtiene los horarios de un día específico
     */
    public function dayDetail(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'doctor_ids' => 'nullable|array',
            'doctor_ids.*' => 'numeric',
            'office_id' => 'nullable|numeric',
        ]);

        $date = Carbon::parse($validated['date']);
        $weekDay = $date->isoWeekday();

        $query = Schedule::query()
            ->with(['doctor', 'office', 'appointment' => function ($q) use ($date) {
                $q->where('date', $date->format('Y-m-d'))
                    ->with('patient');
            }])
            ->where('week_day', $weekDay)
            ->orderBy('start_time');

        if (!empty($validated['doctor_ids'])) {
            $query->whereIn('doctor_id', $validated['doctor_ids']);
        }

        if ($validated['office_id'] ?? null) {
            $query->where('office_id', $validated['office_id']);
        }

        $schedules = $query->get()->map(function ($schedule) use ($date) {
            $hasAppointment = $schedule->appointment !== null;
            
            return [
                'id' => $schedule->id,
                'doctor_id' => $schedule->doctor_id,
                'doctor_name' => $schedule->doctor->fullname,
                'office_id' => $schedule->office_id,
                'office_name' => $schedule->office->name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'week_day' => $schedule->week_day,
                'has_appointment' => $hasAppointment,
                'appointment' => $hasAppointment ? [
                    'id' => $schedule->appointment->id,
                    'patient_name' => $schedule->appointment->patient->fullname ?? 'Sin nombre',
                    'status' => $schedule->appointment->status,
                    'status_label' => $schedule->appointment->status_label,
                ] : null,
            ];
        });

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'day_name' => $this->getDayName($weekDay),
            'schedules' => $schedules,
        ]);
    }

    /**
     * Obtiene el nombre del día en español
     */
    private function getDayName(int $weekDay): string
    {
        $days = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];

        return $days[$weekDay] ?? '';
    }
}
