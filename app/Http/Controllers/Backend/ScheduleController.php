<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    public function index($id)
    {
        $doctor = doctors()->findWithSchedules($id);
        $offices = offices()->index();

        return inertia('Backend/Doctors/Schedules/Index', compact('doctor', 'offices'));
    }

    public function store(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'office_id' => 'required|numeric',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'days' => 'required|array',
            'days.*' => 'numeric',
        ]);

        schedules()->storeMany($doctor, $validated);

        return redirect()->route('doctors.schedules.index', $doctor->id);
    }

    public function destroy(Schedule $schedule)
    {
        schedules()->destroy($schedule);

        toast('success', 'Horario eliminado.');
        return redirect()->route('doctors.schedules.index', $schedule->doctor->id);
    }
}
