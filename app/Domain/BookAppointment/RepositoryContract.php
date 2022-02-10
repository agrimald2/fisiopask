<?php

namespace App\Domain\BookAppointment;

use App\Domain\BookAppointment\Datas\ScheduleGroupCollection;

interface RepositoryContract
{

    /**
     * Options
     */

    public function getOfficeOptions();

    public function getSexOptions();

    public function getSpecialtyOptions();


    /**
     * Index page
     */

    public function doesPatientWithDniExists($dni);

    public function getPatientPhoneByDni($dni);

    public function attemptToCreatePatientFromReniecByDni($dni): bool;

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
    public function createPatientIfNotExists($data);

    public function getPatientNameByDni($dni);

    public function updatePatientPhoneIfIsMissing($dni, $phone);

    /**
     * Pick time
     */

    public function getLastDoctorIdForPatientByDni($dni);

    public function getOfficeById($id);

    public function getAvailableSchedulesGroupedByStartTime($date, $officeId): ScheduleGroupCollection;

    /**
     * Make appointment
     */
    public function getScheduleIfIsAvailable($id, $date);

    public function makeAppointment($dni, $date, $schedule);

    public function sendConfirmationToPatient($dni, $appointment, $type);

    public function getThankYouPageButtonUrl();

    public function getPatientDashboardLink($dni);

}
