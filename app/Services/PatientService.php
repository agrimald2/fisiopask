<?php

namespace App\Services;

use App\Domain\Reniec\Datas\PatientData;
use App\Models\Patient;
use App\Models\User;
use App\Models\PatientRate;
use App\Models\Rate;

class PatientService
{

    public function __construct()
    {
        //
    }


    public function index($searchString)
    {
        return Patient::query()
            ->orderBy('id', 'desc')
            ->when($searchString, function ($query, $searchString) {
                return $query->where('name', 'like', "%$searchString%")
                    ->orWhere('dni', 'like', "%$searchString%")
                    ->orWhere('phone', 'like', "%$searchString%")
                    ->orWhere('lastname1', 'like', "%$searchString%")
                    ->orWhere('lastname2', 'like', "%$searchString%");
            })
            ->paginate(20);
    }

    public function createFromReniec(PatientData $patientReniec)
    {
        return $this->create([
            'phone' => null,

            'name' => $patientReniec->name,
            'lastname1' => $patientReniec->lastname1,
            'lastname2' => $patientReniec->lastname2,
            'dni' => $patientReniec->dni,
            'sex' => $patientReniec->sex,
            'birth_date' => $patientReniec->birth_date,
        ]);
    }


    public function getByDni($dni)
    {
        return Patient::query()
            ->firstWhere('dni', $dni);
    }


    public function create($data)
    {
        $patient = Patient::create($data);

        $constantRate = Rate::find(1);

        PatientRate::create([
            'name' => $constantRate->name,
            'subfamily_id' => $constantRate->subfamily_id,
            'patient_id' => $patient->id,
            'price' => $constantRate->price,
            'appointment_id' => 1,
            'payed' => 0,
            'is_product' => false,
            'qty' => 1,
            'sessions_total' => 1,
            'sessions_left' => 1,
            'state' => PatientRate::RATE_STATUS_OPEN,
        ]);

        return $patient;
    }


    public function createIfNotExists($data)
    {
        $patient = $this->getByDni($data['dni']);

        if (!$patient) {
            $patient = $this->create($data);
        }

        return $patient;
    }


    public function getLinkFor(Patient $patient)
    {
        if (!$patient->token) {
            $patient->token = \Str::random(8);
            $patient->save();
        }

        $link = route('area.patients.login', [
            'dni' => $patient->dni,
            'token' => $patient->token,
        ]);

        return $link;
    }
}
