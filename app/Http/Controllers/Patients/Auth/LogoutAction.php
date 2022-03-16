<?php

namespace App\Http\Controllers\Patients\Auth;

use App\Domain\Patients\PatientAuthRepositoryContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutAction extends Controller
{
    public function __invoke(PatientAuthRepositoryContract $repo)
    {
        $repo->logout();

        return redirect()->route('landing.index');
    }
}
