<?php

namespace App\Services;

use App\Domain\BookAppointment\Datas\ScheduleCollection;
use App\Domain\BookAppointment\Datas\ScheduleData;
use App\Domain\BookAppointment\Datas\ScheduleGroupCollection;
use App\Domain\BookAppointment\Datas\ScheduleGroupData;
use App\Models\Doctor;
use App\Models\Schedule;
use Carbon\Carbon;

class ScheduleService
{

    public function storeMany(Doctor $doctor, $validatedData)
    {
        $scheduleBase = collect($validatedData)
            ->only('start_time', 'end_time', 'office_id')
            ->toArray();

        $records = collect($validatedData['days'])
            ->map(function ($day) use ($scheduleBase) {
                $scheduleBase['week_day'] = $day;
                return $scheduleBase;
            });

        return $doctor
            ->schedules()
            ->createMany(
                $records->toArray()
            );
    }

    public function destroy(Schedule $schedule)
    {
        return $schedule->delete();
    }

    public function available($id, $date)
    {

        $user = auth()->user();
        // if($user!= null && $user->hasRole('admin') || $user->hasRole('assistant')
        if($user != null && ($user->hasRole('admin') || $user->hasRole('assistant')))
        {
            return Schedule::query()
                ->whereKey($id)
                ->first();
        }

        return Schedule::query()
            ->whereKey($id)
            ->whereDoesntHave('appointment', function ($q) use ($date) {
                return $q->where('date', $date);
            })
            ->first();
    }


    public function getAvailableSchedulesFor($date, $officeId = null)
    {
        $weekDay = carbon_parse($date)->isoWeekday();

        $user = auth()->user();

        /*
        @TODO -> BOOK MULTIPLE APPOINTMENTS IN THE SAME SCHEDULE 
        */
        // if($user!= null && ($user->hasRole('admin') || $user->hasRole('assistant')))
        if($user != null && ($user->hasRole('admin') || $user->hasRole('assistant'))){
            return Schedule::query()
                ->where('week_day', $weekDay)
                ->whereDoesntHave('doctor.freezes', function ($q) use ($date) {
                    return $q->where('start', '<=', $date)
                        ->where('end', '>=', $date);
                })
                ->when($officeId, fn ($q) => $q->where('office_id', $officeId))
                ->orderBy('start_time')
                ->with(['doctor' => fn ($q) => $q->with('specialties')])
                ->get();
        }else{
            return Schedule::query()
            ->where('week_day', $weekDay)
            ->whereDoesntHave('appointment', function ($q) use ($date) {
                return $q->where('date', $date);
            })
            ->whereDoesntHave('doctor.freezes', function ($q) use ($date) {
                return $q->where('start', '<=', $date)
                    ->where('end', '>=', $date);
            })
            ->when($officeId, fn ($q) => $q->where('office_id', $officeId))
            ->orderBy('start_time')
            ->with(['doctor' => fn ($q) => $q->with('specialties')])
            ->get();
        }
    }



    public function scheduleCollectionToData($schedules)
    {
        $schedules = collect($schedules)
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'doctor_id' => $s->doctor_id,
                    'office_id' => $s->office_id,
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                    'doctor' => ucwords(strtolower($s->doctor->name)),
                    'specialties' => $s->doctor
                        ->specialties
                        ->pluck('name', 'id')
                        ->toArray(),
                ];
            });

        return $schedules->toArray();
    }


    public function groupSchedulesByStartTime($schedules): ScheduleGroupCollection
    {
        // Group all schedules with the same `start_time`
        $matrix = [];
        collect($schedules)
            ->each(function ($schedule) use (&$matrix) {
                $matrix[$schedule['start_time']][] = $schedule;
            });

        // Format the response
        $matrix = collect($matrix)
            ->map(function ($value, $key) {
                return [
                    'start_time' => $key,
                    'schedules' => $value,
                ];
            });

        // Return only the values
        $groupedSchedules = $matrix
            ->values()
            ->toArray();

        return new ScheduleGroupCollection($groupedSchedules);
    }

    /**
     * Obtiene todos los horarios agrupados por día de la semana
     * para la vista de overview de administradores
     */
    public function getAllSchedulesGroupedByDay(array $doctorIds = [], $officeId = null): array
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
            
            $groupedByDay[$weekDay][] = [
                'id' => $schedule->id,
                'doctor_id' => $schedule->doctor_id,
                'doctor_name' => $schedule->doctor->fullname,
                'office_id' => $schedule->office_id,
                'office_name' => $schedule->office->name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'week_day' => $weekDay,
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
     * Obtiene los horarios de un día específico con información de citas
     */
    public function getSchedulesForSpecificDate(string $date, array $doctorIds = [], $officeId = null): array
    {
        $parsedDate = carbon_parse($date);
        $weekDay = $parsedDate->isoWeekday();

        $query = Schedule::query()
            ->with(['doctor', 'office', 'appointment' => function ($q) use ($date) {
                $q->where('date', $date)
                    ->with('patient');
            }])
            ->where('week_day', $weekDay)
            ->whereDoesntHave('doctor.freezes', function ($q) use ($date) {
                return $q->where('start', '<=', $date)
                    ->where('end', '>=', $date);
            })
            ->orderBy('start_time');

        if (!empty($doctorIds)) {
            $query->whereIn('doctor_id', $doctorIds);
        }

        if ($officeId) {
            $query->where('office_id', $officeId);
        }

        return $query->get()->map(function ($schedule) {
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
                'is_available' => !$hasAppointment,
                'appointment' => $hasAppointment ? [
                    'id' => $schedule->appointment->id,
                    'patient_name' => $schedule->appointment->patient->fullname ?? 'Sin nombre',
                    'status' => $schedule->appointment->status,
                    'status_label' => $schedule->appointment->status_label,
                ] : null,
            ];
        })->toArray();
    }

    /**
     * Obtiene estadísticas de ocupación para un rango de fechas
     */
    public function getOccupancyStats(string $dateFrom, string $dateTo, array $doctorIds = []): array
    {
        $startDate = carbon_parse($dateFrom);
        $endDate = carbon_parse($dateTo);
        
        $stats = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $weekDay = $currentDate->isoWeekday();
            $dateStr = $currentDate->format('Y-m-d');

            $query = Schedule::query()
                ->where('week_day', $weekDay)
                ->whereDoesntHave('doctor.freezes', function ($q) use ($dateStr) {
                    return $q->where('start', '<=', $dateStr)
                        ->where('end', '>=', $dateStr);
                });

            if (!empty($doctorIds)) {
                $query->whereIn('doctor_id', $doctorIds);
            }

            $totalSlots = $query->count();

            $occupiedSlots = Schedule::query()
                ->where('week_day', $weekDay)
                ->whereHas('appointment', function ($q) use ($dateStr) {
                    $q->where('date', $dateStr);
                })
                ->when(!empty($doctorIds), fn($q) => $q->whereIn('doctor_id', $doctorIds))
                ->count();

            $stats[$dateStr] = [
                'date' => $dateStr,
                'day_name' => $this->getDayNameSpanish($weekDay),
                'total_slots' => $totalSlots,
                'occupied_slots' => $occupiedSlots,
                'available_slots' => $totalSlots - $occupiedSlots,
                'occupancy_percentage' => $totalSlots > 0 
                    ? round(($occupiedSlots / $totalSlots) * 100, 1) 
                    : 0,
            ];

            $currentDate->addDay();
        }

        return $stats;
    }

    /**
     * Obtiene el nombre del día en español
     */
    private function getDayNameSpanish(int $weekDay): string
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
