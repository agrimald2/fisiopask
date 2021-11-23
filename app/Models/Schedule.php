<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     * Relationships
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }


    public function office()
    {
        return $this->belongsTo(Office::class);
    }


    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
