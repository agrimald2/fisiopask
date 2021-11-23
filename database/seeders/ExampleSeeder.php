<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createSpecialties();
        $this->createOffices();
        #$this->createDoctorJuan();
        #$this->createDoctorPedro();
        #$this->createPatients();
    }


    public function createSpecialties()
    {
        doctorSpecialties()->create(['name' => 'Traumatología']);
        doctorSpecialties()->create(['name' => 'Masaje']);
        doctorSpecialties()->create(['name' => 'Radiofrecuencia']);
    }


    public function createOffices()
    {
        offices()->create(['name' => 'Spa Chalet de Lakshmi', 'address' => 'Mexico #59']);
        #offices()->create(['name' => 'Fisio Salud Lima', 'address' => 'Lima #12']);
        #offices()->create(['name' => 'Fisio Salud Chile', 'address' => 'Chile #42']);
    }


    public function createDoctorPedro()
    {
        $doctor = doctors()->create([
            'user' => [
                'name' => 'Pedro Páramo Gonzales',
                'email' => 'pedro@pedro.com',
                'password' => 'pedro'
            ],

            'birth_date' => '2000-03-03',

            'sex' => 'M',
            'phone' => '1234567',

            'document_type' => 'DNI',
            'document_reference' => '9876543210',
        ]);

        $doctor->specialties()->attach(1);

        schedules()->storeMany($doctor, [
            'office_id' => 1,
            'days' => [1, 2, 3, 4, 5],
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        schedules()->storeMany($doctor, [
            'office_id' => 1,
            'days' => [1, 2, 3, 4, 5],
            'start_time' => '11:00',
            'end_time' => '11:30',
        ]);

        schedules()->storeMany($doctor, [
            'office_id' => 1,
            'days' => [1, 2, 3, 4, 5],
            'start_time' => '11:30',
            'end_time' => '12:00',
        ]);

        schedules()->storeMany($doctor, [
            'office_id' => 1,
            'days' => [1, 2, 3, 4, 5],
            'start_time' => '12:00',
            'end_time' => '13:00',
        ]);

        schedules()->storeMany($doctor, [
            'office_id' => 1,
            'days' => [1, 2, 3, 4, 5],
            'start_time' => '16:15',
            'end_time' => '17:45',
        ]);
    }


    public function createDoctorJuan()
    {
        $doctor = doctors()->create([
            'user' => [
                'name' => 'Juan Jesus Diaz',
                'email' => 'juan@juan.com',
                'password' => 'juan'
            ],

            'birth_date' => '2000-03-03',

            'sex' => 'M',
            'phone' => '1234567',

            'document_type' => 'DNI',
            'document_reference' => '9876543210',
        ]);

        $doctor->specialties()->attach(2);

        schedules()->storeMany($doctor, [
            'office_id' => 1,
            'days' => [1, 2, 3, 4, 5],
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        schedules()->storeMany($doctor, [
            'office_id' => 1,
            'days' => [1, 2, 3, 4, 5],
            'start_time' => '11:00',
            'end_time' => '12:00',
        ]);

        schedules()->storeMany($doctor, [
            'office_id' => 1,
            'days' => [1, 2, 3, 4, 5],
            'start_time' => '12:00',
            'end_time' => '13:00',
        ]);
    }


    public function createPatients()
    {
        patients()->create([
            'name' => 'Carlos',
            'lastname1' => 'Martinez',
            'lastname2' => 'Gonzales',

            'email' => 'carlos@carlos.com',

            'dni' => '0123123123',

            'birth_date' => '2000-03-03',
            'sex' => 'M',
            'phone' => '1234567',
        ]);
    }
}
