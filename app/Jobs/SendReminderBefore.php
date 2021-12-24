<?php

namespace App\Jobs;

use \App\Models\Appointment;

use \Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderBefore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $confirmedAppointments = Appointment::query()
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->get();

        foreach($confirmedAppointments as $appointment)
        {
            $carbonDate = Carbon::parse($appointment->date);
            
            if($carbonDate->isTomorrow())
            {
                $phone = $appointment->patient->phone;
                $text = "oe";
                chatapi($phone, $text);
            }
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
