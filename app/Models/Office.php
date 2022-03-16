<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     * Relationships
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    //appointments

    public function workspace()
    {
        return $this->hasMany(Workspace::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
