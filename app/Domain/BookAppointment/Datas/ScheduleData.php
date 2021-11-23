<?php

namespace App\Domain\BookAppointment\Datas;

use Spatie\DataTransferObject\DataTransferObject;

class ScheduleData extends DataTransferObject
{
    public int $id;
    public int $doctor_id;
    public ?int $office_id;

    public string $start_time;
    public string $end_time;
    public string $doctor;

    public array $specialties;
}
