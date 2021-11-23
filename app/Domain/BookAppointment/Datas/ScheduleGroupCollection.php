<?php

namespace App\Domain\BookAppointment\Datas;

use Spatie\DataTransferObject\DataTransferObjectCollection;

class ScheduleGroupCollection extends DataTransferObjectCollection
{
    public function current(): ScheduleGroupData
    {
        return parent::current();
    }
}
