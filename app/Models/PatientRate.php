<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientRate extends Model
{
    use HasFactory;
    use SoftDeletes;

    const RATE_STATUS_OPEN = 0;
    const RATE_STATUS_COMPLETE = 1;
    const RATE_STATUS_ABANDONED = 2;
    const RATE_STATUS_CANCELED = 3;

    const RATE_STATUS_LABEL = [
        0 => 'ABIERTA',
        1 => 'COMPLETADA',
        2 => 'ABANDONADA',
        3 => 'ANULADA',
    ];

    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];
    protected $appends = [
        'status_label', 
        'appointments_paid',
        'appointment_price',
        'appointments_assisted',
        'can_assist',
        'can_assist_string'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A'
    ];

    /**
     * Attributes
     */

    function getStatusLabelAttribute()
    {
        return self::RATE_STATUS_LABEL[$this->state];
    }

    function getAppointmentPriceAttribute()
    {
        return $this->price / $this->sessions_total;
    }

    function getAppointmentsPaidAttribute()
    {
        if($this->price == 0) return $this->sessions_total;

        $appointmentPrice = $this->price / $this->sessions_total;
        $appointmentsPaid = $this->payed / $appointmentPrice;

        return floor($appointmentsPaid);
    }

    function getAppointmentsAssistedAttribute()
    {
        return $this->sessions_total - $this->sessions_left;
    }

    function getCanAssistAttribute()
    {
       $canAssist = $this->getAppointmentsPaidAttribute() > $this->getAppointmentsAssistedAttribute();
       return $canAssist ? true : false;
    }

    function getCanAssistStringAttribute()
    {
       $canAssist = $this->getAppointmentsPaidAttribute() > $this->getAppointmentsAssistedAttribute();
       return $canAssist ? "Sí" : "No";
    }

    /**
     * Relationships
     */

     public function patientPayment()
     {
         return $this->hasMany(PatientPayment::class);
     }
}
