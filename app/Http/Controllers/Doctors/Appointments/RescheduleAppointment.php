<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Appointment;

class RescheduleAppointment extends Controller
{
    public function pickDay($appointment) 
    {
        $appointment = Appointment::with('patient')->find($appointment);

        return inertia('Doctors/Appointments/Reschedule', compact('appointment'));
    }

    public function postDay(Request $request, $appointment) 
    {
        $request->validate([
            'date' => 'required|date|date_format:Y-m-d'
        ]);

        $date = $request->date;

        return redirect()->route('reschedule.pickTime', compact('appointment', 'date'));
    }

    public function pickTime($appointment, $date)
    {
        $appointment = Appointment::find($appointment);
        if(now()->parse($date)->lt(now()->toDateString())) {
            return redirect()->route('reschedule.pickDay', compact('appointment'));
        }

        $filters = [
            'doctorId' => $appointment->doctor_id,
        ];

        $schedules = schedules()->getAvailableSchedulesFor($date, null);
        $schedules = schedules()->scheduleCollectionToData($schedules);
        $groupedSchedules = schedules()->groupSchedulesByStartTime($schedules)->toArray();
        
        $specialtyOptions = doctorSpecialties()->options();

        return inertia('Doctors/Appointments/RescheduleTime', compact(
            'appointment', 
            'filters',
            'groupedSchedules',
            'specialtyOptions',
            'date'
        ));
    }

    public function postTime(Request $request, $appointment)
    {
        $request->validate([
            'schedule_id' => 'required|integer',
        ]);

        $date = $request->date;

        $schedule = schedules()->available(
            $request->schedule_id, 
            $date,
        );

        if(!$schedule)
        {
            return $request;
        }

        $appointment = Appointment::find($appointment);

        $appointment->start = $schedule->start_time;
        $appointment->end = $schedule->end_time;
        $appointment->date = $date;

        $appointment->save();

        return redirect()->route('doctors.appointments.index');
    }
}
