<?php

namespace App\Domain\Patients;

use App\Models\Patient;

class DefaultRepository implements PatientAuthRepositoryContract
{

    protected $guard;

    public function __construct()
    {
        $this->guard = auth()->guard('patient');
    }


    public function isLoggedIn()
    {
        return $this->guard->check();
    }


    public function getNotLoggedInResponse()
    {
        return response('Por favor inicie sesión desde el link que mandamos a su whatsapp', 400);
    }


    public function logout()
    {
        return $this->guard->logout();
    }


    public function login($patient)
    {
        return $this->guard->login($patient);
    }


    public function getAuthenticatedPatient()
    {
        return $this->guard->user();
    }


    public function getLoginAttemptSuccessResponse($patient)
    {
        return redirect()->route('area.patients.index');
    }

    public function getLoginAttemptErrorResponse($patient)
    {
        return response('Error', 400);
    }

    public function getAuthLinkForPatient($patient)
    {
        return $patient->link;
    }
}
