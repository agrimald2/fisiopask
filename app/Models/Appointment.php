<?php

namespace App\Models;

use App\Domain\Appointments\AppointmentCanceler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\DoctorSubfamily;
use App\Models\PatientRate;

class Appointment extends Model
{
    use HasFactory;

    const STATUS_CONFIRMED = 1;
    const STATUS_NOT_ASSISTED = 2;
    const STATUS_ASSISTED = 3;
    const STATUS_CANCELED = 4;

    const STATUS_LABEL = [
        1 => 'CONFI',
        2 => 'N A',
        3 => 'ASIS',
        4 => 'CAN',
    ];


    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    protected $appends = [
        'status_label',
        'is_pending',
        'main_rate',
        'appointments_paid',
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

    function getMainRateAttribute()
    {
        $rate = null;

        $doctorSubfamilies = DoctorSubfamily::query()->where('doctor_id' , $this->doctor_id)->get();
        
        foreach($doctorSubfamilies as $subfamily)
        {
            $query = PatientRate::query()
                ->where('subfamily_id', $subfamily->subfamily_id)
                ->where('patient_id', $this->patient_id)
                ->first();
            
            if($query)
            {
                $rate = $query;
                break;
            }
        }

        return $rate;
    }

    function getAppointmentsPaidAttribute()
    {
        $rate = $this->getMainRateAttribute();
        if($rate != null) return $rate->appointments_paid;

        return 0;
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

    //office
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    

    public function survey()
    {
        return $this->hasOne(Survey::class);
    }


    public function assistedAppointments()
    {
        return $this->hasMany(AssistedAppointments::class);
    }

}
