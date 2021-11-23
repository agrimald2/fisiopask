<?php

namespace App\Http\Controllers\Patients\Auth;

use App\Domain\Patients\PatientAuthRepositoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAction extends Controller
{

    public function __invoke(PatientAuthRepositoryContract $repo, Request $request, $dni, $token)
    {
        $patient = patients()->getByDni($dni);

        if ($patient && $patient->isTokenValid($token)) {
            $repo->login($patient);

            return $repo->getLoginAttemptSuccessResponse($patient);
        }

        return $repo->getLoginAttemptErrorResponse($patient);
    }
}
