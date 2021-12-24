<?php

namespace App\Jobs;

use App\Models\Appointment;

use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckAssistance implements ShouldQueue
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
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->get();
        
        foreach($confirmedAppointments as $appointment)
        {
            $startTime = Carbon::parse($appointment->start);
            
            if($startTime->diffInHours(Carbon::now()->format('Y-m-d H:i:s'), false) > 1)
            {
                $appointment->status = Appointment::STATUS_NOT_ASSISTED;
                $appointment->save();
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
