<?php

namespace App\Domain\BookAppointment\Datas;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class ScheduleCollection extends DataTransferObjectCollection
{
    public function current(): ScheduleData
    {
        return parent::current();
    }
}
