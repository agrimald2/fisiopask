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

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A'
    ];

    /**
     * Relationships
     */

    public function patientPayment() {
        return $this->belongsTo(PatientPayment::class);
    }

    public function rate() {
        return $this->hasOne(Rate::class);
    }
    
    public function getAppointmentsPaid() {
        $stock = $this->rate()->get();

        var_dump($stock);

        $price = $this->price;
        $amountPaid = $this->amount_paid;

        $appointmentPrice = $price / $stock;

        $appointmentsPaid = $amountPaid / $appointmentPrice;

        return $appointmentsPaid;
    }
}
