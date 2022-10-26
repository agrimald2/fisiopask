<?php

namespace Database\Seeders;

use App\Models\Family;
use Illuminate\Database\Seeder;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Family::create(['name' => 'Paquetes de terapias'])
            ->subfamilies()
            ->createMany([
                ['name' => 'Paquete 1'],
                ['name' => 'Paquete 2'],
            ]);

        Family::create(['name' => 'Traumatologia'])
            ->subfamilies()
            ->createMany([
                ['name' => 'Huesos rotos'],
                ['name' => 'Musculos dañados']
            ]);

        Family::create(['name' => 'Rehabilitacion'])
            ->subfamilies()
            ->createMany([
                ['name' => 'Aparatos'],
                ['name' => 'Albercas']
            ]);
    }
}
