<?php

namespace App\Domain\BookAppointment\Datas;

use Spatie\DataTransferObject\DataTransferObject;

class ScheduleGroupData extends DataTransferObject
{
    public string $start_time;

    public ScheduleCollection $schedules;
}
