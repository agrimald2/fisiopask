<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Office;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorScheduleOverviewController extends Controller
{
    /**
     * Muestra la vista principal del overview de horarios
     */
    public function index(Request $request)
    {
        $doctors = Doctor::query()
            ->with('user')
            ->orderBy('name')
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->fullname,
                ];
            });

        $offices = Office::query()
            ->orderBy('name')
            ->get()
            ->map(function ($office) {
                return [
                    'id' => $office->id,
                    'name' => $office->name,
                ];
            });

        $defaultOffice = Office::where('name', 'LIKE', '%PRIMAVERA%')->first();
        $defaultOfficeId = $request->get('office_id', $defaultOffice?->id);

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
     */
    private function getSchedulesForWeek(Carbon $weekStart, Carbon $weekEnd, array $doctorIds = [], $officeId = null)
    {
        $query = Schedule::query()
            ->with(['doctor', 'office'])
            ->orderBy('start_time');

        if (!empty($doctorIds)) {
            $query->whereIn('doctor_id', $doctorIds);
        }

        if ($officeId) {
            $query->where('office_id', $officeId);
        }

        $schedules = $query->get();

        $groupedByDay = [];
        for ($day = 1; $day <= 7; $day++) {
            $groupedByDay[$day] = [];
        }

        foreach ($schedules as $schedule) {
            $weekDay = (int) $schedule->week_day;
            if (!isset($groupedByDay[$weekDay])) {
                $groupedByDay[$weekDay] = [];
            }

            $dateForDay = $weekStart->copy()->addDays($weekDay - 1)->format('Y-m-d');
            
            $appointment = $schedule->appointment()
                ->where('date', $dateForDay)
                ->with('patient')
                ->first();
            
            $isOccupied = $appointment !== null;

            $groupedByDay[$weekDay][] = [
                'id' => $schedule->id,
                'doctor_id' => $schedule->doctor_id,
                'doctor_name' => $schedule->doctor->fullname,
                'office_id' => $schedule->office_id,
                'office_name' => $schedule->office->name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'week_day' => $weekDay,
                'date' => $dateForDay,
                'is_occupied' => $isOccupied,
                'patient_name' => $isOccupied ? ($appointment->patient->fullname ?? 'Sin nombre') : null,
                'appointment_status' => $isOccupied ? $appointment->status : null,
                'appointment_status_label' => $isOccupied ? $appointment->status_label : null,
            ];
        }

        foreach ($groupedByDay as $day => $daySchedules) {
            usort($groupedByDay[$day], function ($a, $b) {
                return strcmp($a['start_time'], $b['start_time']);
            });
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
