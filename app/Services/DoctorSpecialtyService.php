<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\DoctorSpecialty;

class DoctorSpecialtyService
{

    public function show($id)
    {
        return DoctorSpecialty::query()
            ->with('doctors.user')
            ->whereKey($id)
            ->firstOrFail();
    }

    public function create($data)
    {
        return DoctorSpecialty::create($data);
    }

    public function update(DoctorSpecialty $doctorSpecialty, $data)
    {
        return $doctorSpecialty->update($data);
    }

    public function index()
    {
        return DoctorSpecialty::query()
            ->orderBy('id', 'desc')
            ->with('doctors')
            ->get();
    }

    public function options()
    {
        return DoctorSpecialty::query()
            ->get()
            ->pluck('name', 'id');
    }

    public function destroy(DoctorSpecialty $doctorSpecialty)
    {
        if ($doctorSpecialty->doctors()->count()) {
            return false;
        }

        $doctorSpecialty->delete();
        return true;
    }


    public function doctorAdd($doctor_id, $specialty_id)
    {
        $specialty = DoctorSpecialty::findOrFail($specialty_id);

        return Doctor::findOrFail($doctor_id)
            ->specialties()
            ->syncWithoutDetaching($specialty);
    }


    public function doctorRemove($doctor_id, $specialty_id)
    {
        $specialty = DoctorSpecialty::findOrFail($specialty_id);

        return Doctor::findOrFail($doctor_id)
            ->specialties()
            ->detach($specialty);
    }
}
