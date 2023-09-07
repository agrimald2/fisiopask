<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\User;

class DoctorService
{

    public function __construct()
    {
        //
    }


    public function index($searchQuery)
    {
        return Doctor::query()
            ->with('user')
            ->with('workspace')
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('name', 'like', "%$searchQuery%")
                    ->orWhere('lastname', 'like', "%$searchQuery%");
            })
            ->orderBy('id', 'desc')
            ->with('user')
            ->paginate(20);
    }


    public function show($id)
    {
        return Doctor::findOrFail($id)
            ->load('user')
            ->load('specialties')
            ->load('subfamilies');
    }


    public function getSchedulesOf(Doctor $doctor)
    {
        return $doctor->load([
            'schedules' => function ($q) {
                return $q->with('office')->orderBy('start_time');
            }
        ]);
    }


    public function findWithSchedules($id)
    {
        return $this->getSchedulesOf(
            $this->show($id)
        );
    }


    public function create($data)
    {
        $user = User::make($data['user']);
        $user->name =  $user['name'];
        $user->save();

        $user->assignRole('doctor');

        return $user->doctor()->create(
            collect($data)
                ->except('user')
                ->toArray()
        );
    }


    public function update(Doctor $doctor, $data)
    {
        if (isset($data['user']['password']) && $data['user']['password'] == '') {
            unset($data['user']['password']);
        }

        $doctor->fill($data);
        $doctor->user->fill($data['user']);
        $doctor->push();

        return $doctor;
    }


    public function destroy(Doctor $doctor)
    {
        $doctor->user->delete();

        return $doctor->delete();
    }
}
