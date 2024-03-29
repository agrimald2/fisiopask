<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaymentMethodSeeder::class);

        $this->call(RoleSeeder::class);

        $this->call(UserSeeder::class);

        //$this->call(ExampleSeeder::class);

        //$this->call(FamilySeeder::class);
    }
}
