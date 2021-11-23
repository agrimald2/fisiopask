<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeeScheduleAction extends Controller
{
    public function __invoke(Request $request)
    {
        $doctor = $request->user()->doctor;

        $model = doctors()->getSchedulesOf($doctor);

        return inertia('Doctors/SeeSchedule/SeeSchedule', compact('model'));
    }
}
