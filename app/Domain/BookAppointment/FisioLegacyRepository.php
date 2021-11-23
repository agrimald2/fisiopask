<?php

namespace App\Domain\BookAppointment;

use App\Domain\BookAppointment\Datas\ScheduleGroupCollection;
use Illuminate\Support\Facades\DB;

class FisioLegacyRepository implements RepositoryContract
{

    const CONNECTION = 'mysql_old';
    const COUNTRY_EXTENSION = 51;

    /**
     * Private
     * @return \Illuminate\Database\ConnectionInterface
     */
    private function db()
    {
        return DB::connection(self::CONNECTION);
    }


    private function getPatientByDni($dni)
    {
        return $this->db()
            ->table('patients')
            ->whereDni($dni)
            ->first();
    }


    /**
     * Contract
     */

    public function getOfficeOptions()
    {
        return ['Default'];
    }

    public function getSexOptions()
    {
        return [
            'M' => 'Masculino',
            'F' => 'Femenino',
        ];
    }

    public function getSpecialtyOptions()
    {
        return [];
    }

    /**
     * Index page
     */

    public function doesPatientWithDniExists($dni)
    {
        $patient = $this->getPatientByDni($dni);

        return !!$patient;
    }

    public function getPatientPhoneByDni($dni)
    {
        $patient = $this->getPatientByDni($dni);

        if ($patient && $patient->phone) {
            return self::COUNTRY_EXTENSION . $patient->phone;
        }

        return null;
    }

    public function attemptToCreatePatientFromReniecByDni($dni): bool
    {
        $patient = reniec($dni);

        if ($patient) {
            $this->db()
                ->table('patients')
                ->insert([
                    'dni' => $patient->dni,
                    'first_name' => $patient->name,
                    'last_name' => "{$patient->lastname1} {$patient->lastname2}",
                    'sex' => $patient->sex,
                    'date_born' => $patient->birth_date,
                    'email' => 'a@abc.com',
                    'phone' => '',
                ]);
            return true;
        }

        return false;
    }

    /**
     * Create patient
     * if not exists
     *
     * - dni
     * - name
     * - lastname1
     * - lastname2
     * - birth_date
     * - sex
     * - phone
     */
    public function createPatientIfNotExists($data)
    {
        $patient = $this->getPatientByDni($data['dni']);

        if (!$patient) {
            $this->db()
                ->table('patients')
                ->insert([
                    'dni' => substr($data['dni'], -11),
                    'first_name' => $data['name'],
                    'last_name' => $data['lastname1'] . " " . $data['lastname2'],
                    'date_born' => $data['birth_date'],
                    'sex' => $data['sex'],
                    'phone' => substr($data['phone'], -9),
                    'email' => 'a@abc.com',
                ]);
        }
    }

    public function getPatientNameByDni($dni)
    {
        $patient = $this->getPatientByDni($dni);

        if ($patient) {
            return $patient->first_name;
        }

        return null;
    }

    public function updatePatientPhoneIfIsMissing($dni, $phone)
    {
        $phone = substr($phone, -9);

        $patient = $this->getPatientByDni($dni);


        if ($patient && !$patient->phone) {
            $this->db()
                ->table('patients')
                ->where('id', $patient->id)
                ->update([
                    'phone' => $phone
                ]);
        }
    }

    /**
     * Pick time
     */

    public function getOfficeById($id)
    {
        return null;
    }


    public function getLastDoctorIdForPatientByDni($dni)
    {
        $patient = $this->db()
            ->table('patients')
            ->whereDni($dni)
            ->first();

        if (!$patient) {
            return null;
        }

        $appointment = $this->db()
            ->table('appointments')
            ->wherePatientId($patient->id)
            ->join('schedules', 'schedules.id', 'appointments.schedule_id')
            ->orderBy('appointments.id', 'desc')
            ->select([
                'schedules.doc_id as doctor_id'
            ])
            ->first();

        if (!$appointment) {
            return null;
        }

        return $appointment->doctor_id;
    }


    public function getAvailableSchedulesGroupedByStartTime($date, $officeId): ScheduleGroupCollection
    {
        $isToday = $date == now()->toDateString();

        $start = $date . ' 00:00';
        $end = $date . ' 23:59';

        $schedules = $this->db()
            ->table('schedules')
            ->whereBetween('start_date', [$start, $end])
            ->whereNull('schedules.deleted_at')
            // Doctor
            ->join('doctors', 'schedules.doc_id', '=', 'doctors.id')
            ->whereNull('doctors.deleted_at')
            // Appointments
            ->leftJoin('appointments', 'appointments.schedule_id', '=', 'schedules.id')
            ->whereNull('appointments.id')
            // Select
            ->select([
                'schedules.id',
                'schedules.doc_id',
                'schedules.start_date',
                'schedules.end_date',
                'doctors.first_name',
                'doctors.last_name',
            ])
            ->orderBy('schedules.start_date')
            ->get()
            ->map(function ($s) use ($isToday) {
                if ($isToday && now()->parse($s->start_date)->lt(now())) {
                    return null;
                }

                $start_time = now()->parse($s->start_date)->format("H:i");
                $end_time = now()->parse($s->end_date)->format("H:i");
                return [
                    'id' => $s->id,
                    'doctor_id' => $s->doc_id,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'doctor' => "{$s->first_name} {$s->last_name}",
                    'specialties' => [],
                ];
            })
            ->filter();

        $groupedSchedules = schedules()
            ->groupSchedulesByStartTime($schedules);

        // abort(response()->json($groupedSchedules->toArray()));

        return $groupedSchedules;
    }

    /**
     * Make appointment
     */
    public function getScheduleIfIsAvailable($id, $date)
    {
        $schedule = $this->db()
            ->table('schedules')
            ->join('appointments', 'appointments.schedule_id', '=', 'schedules.id', 'left')
            ->whereNull('appointments.schedule_id')
            ->where('schedules.id', $id)
            ->select([
                'schedules.id',
                'schedules.doc_id',
                'schedules.start_date',
                'schedules.end_date',
            ])
            ->first();

        return $schedule;
    }


    public function makeAppointment($dni, $date, $schedule)
    {
        $patient = $this->getPatientByDni($dni);

        if (!$patient) abort(400, 'Bad request');

        // Create medical history
        $historyId = $this->db()
            ->table('medical_histories')
            ->insertGetId([
                'patient_id' => $patient->id,
                'doc_id' => $schedule->doc_id,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $appointmentId = $this->db()
            ->table('appointments')
            ->insertGetId([
                'patient_id' => $patient->id,
                'schedule_id' => $schedule->id,
                'history_id' => $historyId,
                'status' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        return $this->db()
            ->table('appointments')
            ->find($appointmentId);
    }

    public function sendConfirmationToPatient($dni, $appointment)
    {
        $patient = $this->getPatientByDni($dni);

        if ($patient) {

            $text = $this->getWhatsappConfirmationText($patient, $appointment);

            if ($patient->phone && $text) {
                $phone = self::COUNTRY_EXTENSION . $patient->phone;

                chatapi($phone, $text);
            }
        }
    }

    private function getWhatsappConfirmationText($patient, $appointment)
    {
        $schedule = $this->db()
            ->table('schedules')
            ->find($appointment->schedule_id);

        if (!$schedule) return null;

        $doctor = $this->db()
            ->table('doctors')
            ->find($schedule->doc_id);

        if (!$doctor) return null;

        $date = now()->parse($schedule->start_date)->format('d/m/Y');
        $startTime = now()->parse($schedule->start_date)->format('h:i A');
        $patientName = $patient->first_name . ' ' . $patient->last_name;
        $doctorName = $doctor->first_name . ' ' . $doctor->last_name;

        $s = "\n*FISIOSALUD:  CONFIRMACIÓN DE CITA*";
        $s .= "\n";
        $s .= "\n*Paciente:*";
        $s .= "\n$patientName";
        $s .= "\n";
        $s .= "\n*Fecha de cita:*";
        $s .= "\n$date";
        $s .= "\n";
        $s .= "\n*Hora de cita:*";
        $s .= "\n$startTime";
        $s .= "\n";
        $s .= "\n*DIRECCIÓN:*";
        $s .= "\n Av. José Gálvez Barnechea 148. San Isidro (límite con Sanborja). ";
        $s .= "\n *REFERENCIA: * ";
        $s .= "\n A 2 cuadras de la Clínica Ricardo Palma, esquina con Javier Prado a la altura del *Puente Quiñones*. ";
        $s .= "\n";
        $s .= "\n*Fisioterapeuta:*";
        $s .= "\n$doctorName";
        $s .= "\n";
        $s .= "\nVer *mapa* aquí: ";
        $s .= "\n https://goo.gl/maps/hp6fbftYYrdKnkcr7 ";
        $s .= "\n";
        $s .= "\nVer *protocolo sanitario* aquí: ";
        $s .= "\n https://youtu.be/-gCQnhVOsCs";
        $s .= "\n";
        $s .= "\n*Pago:* te sugerimos pagar tu cita con anticipación y evitar mayores esperas: ";
        $s .= "\n";
        $s .= "\n1. Con tu Tarjeta de Débito o Crédito aquí";
        $s .= "\nhttps://fisiosalud.pe/pagar ";
        $s .= "\n";
        $s .= "\n2. Vía Transferencia Bancaria en Soles";
        $s .= "\n*BBVA: 001100230200074705*";
        $s .= "\n*CCI: 01102300020007470591*";
        $s .= "\n";
        $s .= "\nTe esperamos, FISIOSALUD: ";
        $s .= "\n https://fisiosalud.pe";
        $s .= "\n";
        $s .= "\n*Por favor, agréganos a tus contactos para activar los links*";

        return $s;
    }


    public function getThankYouPageButtonUrl()
    {
        return 'https://fisiosalud.pe/';
    }
}
