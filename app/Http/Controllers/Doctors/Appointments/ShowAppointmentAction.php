<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowAppointmentAction extends Controller
{
    public function __invoke($id)
    {
        $appointment = appointments()->show($id);

        return inertia('Doctors/Appointments/Show', compact('appointment'));
    }
}
