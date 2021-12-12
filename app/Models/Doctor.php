<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     * Relationships
     */
    public function freezes()
    {
        return $this->hasMany(ScheduleFreeze::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }


    public function specialties()
    {
        return $this->belongsToMany(DoctorSpecialty::class);
    }


    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
