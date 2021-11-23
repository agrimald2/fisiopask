<?php

namespace App\Domain\Reniec;

use App\Domain\Reniec\Datas\PatientData;

use Illuminate\Support\Facades\DB;

class ReniecService
{

    protected $connection = "mysql_reniec";

    protected $table;

    public function __construct()
    {
        $this->table = env('RENIEC_TABLE', 'reniec');
    }

    /**
     * @return App\Domain\Reniec\Datas\PatientData
     */
    public function get($dni)
    {
        $record = DB::connection($this->connection)
            ->table($this->table)
            ->where('documento', $dni)
            ->first();

        if (!$record) return null;

        return new PatientData([
            'dni' => $record->documento,
            'name' => $record->nombres,
            'lastname1' => $record->paterno,
            'lastname2' => $record->materno,
            'sex' => $record->sexo,
            'birth_date' => $record->nacimiento,
        ]);
    }
}
