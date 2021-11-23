<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function __invoke()
    {
        $doctor = auth()->user()->doctor;

        $appointments = $doctor->appointments()
            ->with('patient')
            ->where('date', '>', now())
            ->orderBy('date', 'desc')
            ->get();

        $doctorWithSchedules = doctors()->findWithSchedules($doctor->id);

        return inertia('Doctors/Index/Index', compact('appointments', 'doctorWithSchedules'));
    }
}
