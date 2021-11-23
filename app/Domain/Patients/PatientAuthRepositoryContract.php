<?php

namespace App\Domain\Patients;


interface PatientAuthRepositoryContract
{
    public function isLoggedIn();

    public function getNotLoggedInResponse();

    public function logout();

    public function getAuthenticatedPatient();

    public function login($patient);

    public function getLoginAttemptSuccessResponse($patient);

    public function getLoginAttemptErrorResponse($patient);

    public function getAuthLinkForPatient($patient);
}
