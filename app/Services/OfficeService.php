<?php

namespace App\Services;

use App\Models\Office;

class OfficeService
{

    public function options()
    {
        return Office::query()
            ->first()
            ->pluck('name', 'id');
    }

    public function index()
    {
        return Office::query()
            ->orderBy('id', 'desc')
            ->get();
    }

    public function create($data)
    {
        return Office::create($data);
    }


    public function show($id)
    {
        return Office::findOrFail($id);
    }


    public function update(Office $office, $data)
    {
        return $office->update($data);
    }


    private function canBeDeleted(Office $office)
    {
        return $office->schedules()->count() == 0;
    }

    public function destroy(Office $office)
    {
        if ($this->canBeDeleted($office)) {
            $office->delete();

            return true;
        }

        return false;
    }
}
