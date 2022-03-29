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
    protected $appends = ['fullname'];

    /**
     * Attributes
     */

    public function getFullnameAttribute()
    {
        return "{$this->name} {$this->lastname}";
    }

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

    public function subfamilies()
    {
        return $this->belongsToMany(Subfamily::class);
    }


    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function medicalHistory()
    {
        return $this->hasOne(MedicalHistory::class);
    }

    public function medicalRevision()
    {
        return $this->hasOne(MedicalRevision::class);
    }

    public function historyGroup()
    {
        return $this->hasMany(HistoryGroup::class);
    }

    public function test()
    {
        return $this->hasMany(Test::class);
    }
}
