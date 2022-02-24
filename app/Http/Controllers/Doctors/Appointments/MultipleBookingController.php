<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Patient;
use App\Models\User;
use App\Models\Doctor;

class MultipleBookingController extends Controller
{
    public function pickDay($patient)
    {
        $patient = Patient::find($patient);
        return inertia('Doctors/Appointments/MultipleBooking/PickDay', compact('patient'));
    }

    public function postDay(Request $request, $patient)
    {
        $request->validate([
            'date' => 'required|date|date_format:Y-m-d'
        ]);

        $date = $request->date;

        return redirect()->route('multipleBooking.pickTime', compact('patient', 'date'));
    }

    public function pickTime($patient, $date) 
    {
        $patient = Patient::find($patient);

        if(now()->parse($date)->lt(now()->toDateString())) {
            return redirect()->route('multipleBooking.pickDay', compact('patient'));
        }

        $schedules = schedules()->getAvailableSchedulesFor($date, null);
        $schedules = schedules()->scheduleCollectionToData($schedules);
        $groupedSchedules = schedules()->groupSchedulesByStartTime($schedules)->toArray();

        $user = auth()->user();

        $doctorId = Doctor::query()->where('name', $user->name)->first()->id;

        $filters = [
            'doctorId' => $doctorId,
        ];

        $specialtyOptions = doctorSpecialties()->options();

        return inertia('Doctors/Appointments/MultipleBooking/PickTime', compact(
            'patient',
            'filters',
            'groupedSchedules',
            'specialtyOptions',
            'date',
        ));
    }

    public function postTime(Request $request, $patient) 
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
            return redirect()->route('multipleBooking.pickTime', compact(
                'appointment', 
                'date'
            ));
        }

        $patient = Patient::find($patient);

        appointments()->make($date, $schedule, $patient);

        return redirect()->route('multipleBooking.pickDay', compact('patient'));

        return redirect()->route('doctors.appointments.index');
    }
}
