<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientRate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $appends = ['appointments_paid', 'appointments_assisted', 'can_assist', 'can_assist_bool'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A'
    ];

    /**
     * Relationships
     */

    public function patientPayment() {
        return $this->hasMany(PatientPayment::class);
    }

    public function rate() {
        return $this->belongsTo(Rate::class);
    }
    
    function getAppointmentsPaidAttribute() {
        $myRate = $this->rate()->get()->first();

        $stock = floatval($myRate->stock);
        $price = floatval($myRate->price);

        $amountPaid = $this->amount_paid;
        
        $appointmentPrice = $price / $stock;

        $appointmentsPaid = $amountPaid / $appointmentPrice;

        return floor($appointmentsPaid);
    }

    function getAppointmentsAssistedAttribute() {
        $myRate = $this->rate()->get()->first();

        $stock = floatval($myRate->stock);

        $sessionsLeft = $this->sessions_left;

        $appointmentsAssisted = $stock - $sessionsLeft;

        return $appointmentsAssisted;
    }

    public function getCanAssistBoolAttribute() {
        $canAssist = $this->getAppointmentsPaidAttribute() > $this->getAppointmentsAssistedAttribute();

        return $canAssist ? true : false;
    }

    public function getCanAssistAttribute() {
        $canAssist = $this->getAppointmentsPaidAttribute() > $this->getAppointmentsAssistedAttribute();

        return $canAssist ? "Sí" : "No";
    }
}
