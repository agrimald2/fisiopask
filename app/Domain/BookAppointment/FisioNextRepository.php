<?php

namespace App\Domain\BookAppointment;

use App\Domain\BookAppointment\Datas\ScheduleGroupCollection;
use App\Domain\Patients\PatientAuthRepositoryContract;
use App\Models\Appointment;
use Illuminate\Support\Str;

class FisioNextRepository implements RepositoryContract
{

    /**
     * Options
     */
    public function getOfficeOptions()
    {
        return offices()->options();
    }


    public function getSexOptions()
    {
        return config('doctors.sex');
    }


    public function getSpecialtyOptions()
    {
        return doctorSpecialties()->options();
    }


    /**
     * Index
     */
    public function doesPatientWithDniExists($dni)
    {
        return !!patients()->getByDni($dni);
    }


    public function getPatientPhoneByDni($dni)
    {
        $patient = patients()->getByDni($dni);

        if ($patient) {
            return $patient->phone;
        }

        return null;
    }


    public function getPatientNameByDni($dni)
    {
        $patient = patients()->getByDni($dni);

        if ($patient) {
            return $patient->name;
        }

        return null;
    }


    public function attemptToCreatePatientFromReniecByDni($dni): bool
    {
        $reniecPatient = reniec($dni);

        if (!$reniecPatient) {
            return false;
        }

        patients()->createFromReniec($reniecPatient);
        return true;
    }


    /**
     * Patients
     */
    public function createPatientIfNotExists($data)
    {
        return patients()->createIfNotExists($data);
    }


    public function updatePatientPhoneIfIsMissing($dni, $phone)
    {
        $patient = patients()->getByDni($dni);

        // Prevent update phones that already setted
        if ($patient && !$patient->phone) {
            $patient->phone = $phone;
            $patient->save();
        }
    }

    /**
     * Pick time
     */
    public function getOfficeById($id)
    {
        return offices()->show($id);
    }


    public function getAvailableSchedulesGroupedByStartTime($date, $officeId): ScheduleGroupCollection
    {
        $schedules = schedules()->getAvailableSchedulesFor($date, $officeId);

        $schedules = schedules()->scheduleCollectionToData($schedules);

        return schedules()->groupSchedulesByStartTime($schedules);
    }


    public function getScheduleIfIsAvailable($id, $date)
    {
        return schedules()->available($id, $date);
    }


    public function getLastDoctorIdForPatientByDni($dni)
    {
        $patient = patients()->getByDni($dni);

        $appointment = $patient->appointments()
            ->orderBy('id', 'desc')
            ->first();

        return optional($appointment)->doctor_id;
    }


    public function makeAppointment($dni, $date, $schedule)
    {
        $patient = patients()->getByDni($dni);

        //$this->makeGoogleCalendarAppointment($date, $schedule, $patient);

        $appointment = Appointment::query()->where('date', $date)->where('status', '!=', Appointment::STATUS_CANCELED)->where('patient_id', $patient->id)->get();
        
        if(!$appointment->isEmpty()) return $appointment;

        return appointments()->make(
            $date,
            $schedule,
            $patient,
        );
    }

    private function makeGoogleCalendarAppointment($date, $schedule, $patient)
    {
        $token = $schedule->doctor->user->google_access_token;
        $token = json_decode($token, true);

        if ($token) {
            $name = Str::title("{$patient->name} {$patient->lastname1} {$patient->lastname2}");
            $name = "Cita con $name";

            $start = "$date {$schedule->start_time}";
            $end = "$date {$schedule->end_time}";
            $description = "De {$schedule->start_time} a {$schedule->end_time}";

            calendar()->insert(
                $token,
                $name,
                $start,
                $end,
                $description
            );
        }
    }

    public function sendConfirmationToPatient($dni, $appointment, $type)
    {
        $patient = patients()->getByDni($dni);

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

        // Send message to patient
        $patientText = $this->getWhatsappPatientConfirmationText($data, $type);
        if ($patientText) {
            chatapi($patient->phone, $patientText);
        } 
        // Send message to doctor
        //$doctorText = $this->getWhatsappDoctorConfirmationText($data);
        //if ($doctorText) {
        //    chatapi($appointment->doctor->phone, $doctorText);
        //}           
    }
    

    protected function getWhatsappPatientConfirmationText($data, $type)
    {   
        switch ($type) {
            case "credit":
                return view('chatapi.confirmation.credit', $data)->render();
              break;
            case "no_credit":
                return view('chatapi.confirmation.no_credit', $data)->render();
              break;
            default:
                return view('chatapi.confirmation.new', $data)->render();
          }

    }

    protected function getWhatsappDoctorConfirmationText($data)
    {
        return view('chatapi.doctor', $data)->render();
    }


    public function getThankYouPageButtonUrl()
    {
        return route('bookAppointment.index');
    }

    public function getPatientDashboardLink($dni)
    {
        $patient = patients()->getByDni($dni);
        $dashboardLink = app(PatientAuthRepositoryContract::class)->getAuthLinkForPatient($patient);
        
        return $dashboardLink;
    }


}
