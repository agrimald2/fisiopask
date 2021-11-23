<?php

namespace App\Http\Controllers\Doctors\Appointments\Rates;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

use Illuminate\Http\Request;

use Illuminate\Support\Str;

class GenerateTicketAction extends Controller
{
    public function __invoke(Appointment $appointment)
    {
        $text = $this->getTicketTextFrom($appointment);
        $text = Str::markdown($text);
        return inertia('Doctors/Appointments/Rates/Ticket', compact('text'));
    }


    private function getTicketTextFrom(Appointment $appointment)
    {
        $date = $appointment->date->format('Y/m/d');

        $t = <<<EOD
        # Información de la Cita
        {$date} {$appointment->start} -> {$appointment->end}

        {$appointment->patient->fullname}
        EOD;
        $t .= "\n# Productos / Servicios";
        foreach ($appointment->patientRates as $rate) {
            $qty = Str::padRight($rate->qty, 3);
            $price = Str::padRight($rate->price, 8);

            $t .= "\n\n{$qty} x \${$price} {$rate->name}";
        }

        $total = $appointment->patientRates
            ->map(function ($rate) {
                return $rate->price * $rate->qty;
            })
            ->sum();

        $t .= "\n\n" . Str::padRight('', 15, '#');
        $t .= "\n\nTotal: \${$total}";

        return $t;
    }
}
