<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSubfamily extends Model
{
    use HasFactory;

    protected $table = 'doctor_subfamily';

    /**
     * Relationships
     */

     public function doctor()
     {
         return $this->hasOne(Doctor::class);
     }

     public function subfamily()
     {
         return $this->hasOne(Subfamily::class);
     }
}
