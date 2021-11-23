<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexAction extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $roles = $user
            ->roles
            ->pluck('name');


        /**
         * Doctor
         */
        $doctorSchedules = [];
        $doctorAppointments = [];
        if ($user->hasRole('doctor')) {
            $doctorSchedules = doctors()
                ->findWithSchedules($user->doctor->id)
                ->schedules;

            $doctorAppointments = $user->doctor
                ->appointments()
                ->with('patient')
                ->where('date', '>', now())
                ->orderBy('date', 'desc')
                ->get()
                ->each
                ->append('status_label');
        }


        return inertia('Backend/Index/Index', compact(
            'roles',
            'doctorSchedules',
            'doctorAppointments',
        ));
    }
}
