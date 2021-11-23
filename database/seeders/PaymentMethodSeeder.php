<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create(['payment_method' => 'Efectivo']);
        PaymentMethod::create(['payment_method' => 'Tarjeta crédito / débito']);
        PaymentMethod::create(['payment_method' => 'Transferencia Bancaria']);
    }
}
