<?php

namespace App\Domain\Reniec\Datas;

use Spatie\DataTransferObject\DataTransferObject;

class PatientData extends DataTransferObject
{
    public $dni;

    public $name;
    public $lastname1;
    public $lastname2;

    public $sex;
    public $birth_date;
}
