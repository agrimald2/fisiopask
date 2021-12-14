<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'udpdated_at'];

    /**
     * Relationships
     */

     public function appointment()
     {
         return $this->belongsTo(Appointment::class);
     }
}
