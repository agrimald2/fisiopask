<?php

namespace App\Events;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OnPatientCancelAppointment
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $patient;
    public $appointment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Patient $patient, Appointment $appointment)
    {
        $this->patient = $patient;
        $this->appointment = $appointment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
