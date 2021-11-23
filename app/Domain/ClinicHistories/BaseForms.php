<?php

namespace App\Domain\ClinicHistories;

class BaseForms
{
    public function form($name, $fields)
    {
        return [
            "name" => $name,
            "fields" => $fields
        ];
    }
}
