<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\BookAppointment\RepositoryContract;
use App\Http\Controllers\Controller;
use App\Models\PatientPayment;
use App\Models\PatientRate;
use App\Models\Rate;
use Illuminate\Http\Request;

class PatientLoginController extends Controller
{
    const BASE = 'Patients/Login';

    protected $repo;

    public function __construct(RepositoryContract $repo)
    {
        $this->repo = $repo;
    }


    /**
     * Index (dni, office_id)
     */
    public function index()
    {
        $dni = request()->dni;
        return inertia(self::BASE . '/LoginDNI', compact('dni'));
    }


    public function indexPost(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|min:5',
        ]);

        $dni = str_replace(' ', '', $request->dni);

        $patientExists = $this->repo->doesPatientWithDniExists($dni);


        // If existent patient & has valid phone
        if ($patientExists) {
            // Pick day
            return redirect()->route('area.patients.login', [
                'dni' => $dni
            ]);
        }else{
            // If unknown patient
            // Register patient
            return redirect()->route('index', [
                'dni' => $dni,
            ]);
        }


    }
}
