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
        // if($user!= null && $user->hasRole('admin')
        if($user != null)
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
        // if($user!= null && $user->hasRole('admin')
        if($user!= null){
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
}
