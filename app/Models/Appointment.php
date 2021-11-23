<?php

namespace App\Models;

use App\Domain\Appointments\AppointmentCanceler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    const STATUS_CONFIRMED = 1;
    const STATUS_NOT_ASSISTED = 2;
    const STATUS_PAID = 3;
    const STATUS_CANCELED = 4;

    const STATUS_LABEL = [
        1 => 'CONFIRMADO',
        2 => 'NO ASISTIÓ',
        3 => 'PAGADA',
        4 => 'CANCELADA',
    ];


    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    protected $appends = [
        'status_label',
        'is_pending',
    ];

    /**
     * Attributes
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABEL[$this->status];
    }

    public function getIsPendingAttribute()
    {
        return $this->date->gt(now());
    }


    /**
     * Helpers
     */
    public function isOld()
    {
        return $this->date->lt(now());
    }


    public function cancel(AppointmentCanceler $canceler = null)
    {
        $canceler = $canceler ?: new AppointmentCanceler;
        return $canceler->cancel($this);
    }


    /**
     * Relationships
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }


    public function patientRates()
    {
        return $this->hasMany(PatientRate::class);
    }
}
