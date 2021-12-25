<?php

namespace App\Http\Controllers\Doctors\Appointments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowAppointmentAction extends Controller
{
    public function __invoke($id)
    {
        $user = auth()->user();

        $role = "admin";

        if ($user->hasRole('assistant')) $role = "assistant";

        $appointment = appointments()->show($id);

        return inertia('Doctors/Appointments/Show', compact('appointment', 'role'));
    }
}
