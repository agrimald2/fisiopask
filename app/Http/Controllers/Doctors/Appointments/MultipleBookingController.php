<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Patient;
use App\Domain\Patients\PatientAuthRepositoryContract;
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

        $doctor = Doctor::query()->where('name', $user->name)->first();

        $doctorId = null;
        
        if($doctor) $doctorId = $doctor->id;

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

        $appointment = appointments()->make($date, $schedule, $patient);


        $date = $appointment->date->format('d/m/Y');
        $startTime = $appointment->start;
        
        $patientName = $patient->name;
        $patientName = $patient->name . " " . $patient->lastname1 . " " . $patient->lastname2;
        
        $doctorName = $appointment->doctor->name . ' ' . $appointment->doctor->lastname; 
        $doctorWorkspace = [];
        
        if($appointment->doctor->workspace != null) $doctorWorkspace = $appointment->doctor->workspace->name;
        
        $dashboardLink = app(PatientAuthRepositoryContract::class)->getAuthLinkForPatient($patient);
        $dashboardDoctor = env('APP_URL').'dashboard/doctors/appointments/'.$appointment->id;
        
        $office = $appointment->schedule->office;
        $office_indications = $office->indications;
        $office_address = $office->address;
        $office_reference = $office->reference;
        $office_maps_link = $office->maps_link;

        $data = compact(
            'patientName',
            'date',
            'startTime',
            'doctorName',
            'dashboardLink',
            'doctorWorkspace',
            'office_indications',
            'office_address',
            'office_reference',
            'office_maps_link',
        );

        $phone = $patient->phone;
        $text = $this->getWhatsappPatientMultiBooking($data);

        chatapi($phone, $text);

        return redirect()->route('multipleBooking.pickDay', compact('patient'));

        return redirect()->route('doctors.appointments.index');
    }

    protected function getWhatsappPatientMultiBooking($data)
    {   
        return view('chatapi.confirmation.no_credit', $data)->render();
    }
}
