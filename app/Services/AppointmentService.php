<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Office;
use App\Models\Patient;
use App\Models\Schedule;

class AppointmentService
{

    public function all()
    {
        return Appointment::query()
            ->with('patient')
            ->get();
    }

    public function make($date, Schedule $schedule, Patient $patient)
    {
        return Appointment::create([
            'date' => $date,

            'patient_id' => $patient->id,
            'schedule_id' => $schedule->id,


            'start' => $schedule->start_time,
            'end' => $schedule->end_time,

            'office' => $schedule->office->name,
            'office_id' => $schedule->office->id,
            'doctor_id' => $schedule->doctor->id,
        ]);
    }

    public function forDoctor(Doctor $doctor)
    {
        return $doctor->appointments()
            ->with('patient')
            ->orderBy('date')
            ->get();
    }


    public function show($id)
    {
        return Appointment::query()
            ->whereKey($id)
            ->with('patient', 'doctor.subfamilies')
            ->firstOrFail();
    }
}
