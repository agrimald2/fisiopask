<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssistedAppointments extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patientRate()
    {
        return $this->belongsTo(PatientRate::class);
    }
}
