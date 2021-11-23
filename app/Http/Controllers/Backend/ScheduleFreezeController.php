<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\DynamicController;
use App\Models\Doctor;
use App\Models\ScheduleFreeze;
use Illuminate\Http\Request;

class ScheduleFreezeController extends DynamicController
{

    protected $resourceName = "Bloqueos del Doctor";
    protected $resourcePath = "Backend/ScheduleFreezes";
    protected $resourceRoute = "doctors.freezes";

    public function index(Doctor $doctor)
    {
        $model = $doctor->freezes()
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->grid($model, [], [
            'enableSearch' => false
        ], $doctor);
    }


    public function create(Doctor $doctor)
    {
        return $this->form(null, [
            'doctorId' => $doctor->id,
        ], $doctor);
    }


    public function edit(ScheduleFreeze $scheduleFreeze)
    {
        $doctor = $scheduleFreeze->doctor;

        return $this->form($scheduleFreeze, [], $doctor);
    }


    public function store(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required',
            'start' => 'date|date_format:Y-m-d',
            'end' => 'date|date_format:Y-m-d',
        ]);

        $doctor->freezes()
            ->create($validated);

        return $this->redirectIndex($doctor);
    }


    public function update(Request $request, ScheduleFreeze $scheduleFreeze)
    {
        $validated = $request->validate([
            'name' => 'required',
            'start' => 'date|date_format:Y-m-d',
            'end' => 'date|date_format:Y-m-d',
        ]);

        $scheduleFreeze->update($validated);

        return $this->redirectIndex($scheduleFreeze->doctor);
    }


    public function destroy(Request $request, ScheduleFreeze $scheduleFreeze)
    {
        $doctor = $scheduleFreeze->doctor;

        $scheduleFreeze->delete();

        return $this->redirectIndex($doctor);
    }
}
