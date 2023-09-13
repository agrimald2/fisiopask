<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Patients\PatientAuthRepositoryContract;

use App\Models\Appointment;
use Log;
class RescheduleAppointment extends Controller
{
    public function pickOffice($appointment)
    {
        $appointment = Appointment::with('patient')->find($appointment);
        $officeOptions = offices()->options();

        return inertia('Doctors/Appointments/RescheduleOffice', compact('appointment', 'officeOptions'));
    }

    public function postOffice($appointment, $office)
    {
        return redirect()->route('reschedule.pickDay', compact('appointment', 'office'));
    }

    public function pickDay($appointment, $office)
    {
        $appointment = Appointment::with('patient')->find($appointment);

        return inertia('Doctors/Appointments/Reschedule', compact('appointment', 'office'));
    }

    public function postDay(Request $request, $appointment, $office)
    {
        $request->validate([
            'date' => 'required|date|date_format:Y-m-d'
        ]);

        $date = $request->date;

        return redirect()->route('reschedule.pickTime', compact('appointment', 'date', 'office'));
    }

    public function pickTime($appointment, $office, $date)
    {
        $appointment = Appointment::find($appointment);
        /*if(now()->parse($date)->lt(now()->toDateString())) {
            return redirect()->route('reschedule.pickDay', compact('appointment'));
        }*/
        $filters = [
            'doctorId' => $appointment->doctor_id,
        ];

        $schedules = schedules()->getAvailableSchedulesFor($date, $office);
        $schedules = schedules()->scheduleCollectionToData($schedules);
        $groupedSchedules = schedules()->groupSchedulesByStartTime($schedules)->toArray();        
        $specialtyOptions = doctorSpecialties()->options();
        
        
        return inertia('Doctors/Appointments/RescheduleTime', compact(
            'appointment',
            'filters',
            'groupedSchedules',
            'specialtyOptions',
            'date',
            'office'
        ));
    }

    public function postTime(Request $request, $appointment)
    {

        $waba_type = 'reeschedule';

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
            return redirect()->route('reschedule.pickTime', compact('appointment', 'date'));
        }

        $appointment = Appointment::find($appointment);
        $patient = $appointment->patient;

        $patientName = $patient->name;
        $patientName = $patient->name . " " . $patient->lastname1 . " " . $patient->lastname2;

        $patientDNI = $patient->dni;
        $patientToken = $patient->token;

        $dashboardLink = $patientDNI.'/'.$patientToken;

        //$dashboardLink = app(PatientAuthRepositoryContract::class)->getAuthLinkForPatient($patient);
        $dashboardDoctor = env('APP_URL').'dashboard/doctors/appointments/'.$appointment->id;


        $appointment->start = $schedule->start_time;
        $appointment->end = $schedule->end_time;
        $appointment->doctor_id = $schedule->doctor->id;
        $appointment->schedule_id = $schedule->id;
        $appointment->office = $schedule->office->name;

        //$appointment->office_id = $schedule->office->id;

        $appointment->date = $date;
        $appointment->status = Appointment::STATUS_CONFIRMED;
        $appointment->reeschedule_by = auth()->user()->name;
        $appointment->save();
        $startTime = $appointment->start;

        $office = $appointment->schedule->office;
        $office_indications = $office->indications;
        $office_address = $office->address;
        $office_reference = $office->reference;
        $office_maps_link = $office->maps_link;

        $doctorName = $appointment->doctor->name . ' ' . $appointment->doctor->lastname;
        $doctorWorkspace = [];

        $data = compact(
            'patientName',
            'date',
            'startTime',
            'doctorName',
            'office_indications',
            'office_address',
            'office_reference',
            'office_maps_link',
            'dashboardLink',
        );

        $phone = $patient->phone;
        //$text = $this->getWhatsappPatientReprogram($data);

        chatapi($phone, $data, $waba_type);

        return redirect()->route('doctors.appointments.index');
    }

        protected function getWhatsappPatientReprogram($data)
    {
        return view('chatapi.reprogram', $data)->render();
    }
}
