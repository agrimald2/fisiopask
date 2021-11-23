<?php


namespace App\Domain\PatientRates;

use App\Models\Patient;

class PatientBalanceResolver
{
    public function resolve(Patient $patient)
    {
        $debts = $patient->rates
            ->map(function ($rate) {
                return $rate->price * $rate->qty;
            })
            ->reduce(function ($a, $b) {
                return $a + $b;
            }, 0);

        $payments = $patient->payments
            ->map(function ($rate) {
                return $rate->ammount;
            })
            ->reduce(function ($a, $b) {
                return $a + $b;
            }, 0);

        return [
            'debts' => $debts,
            'payments' => $payments,
            'balance' => $debts - $payments,
        ];
    }
}
