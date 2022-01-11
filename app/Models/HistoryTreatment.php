<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTreatment extends Model
{
    use HasFactory;

    protected $fillable = ['treatment_id', 'medical_history_id', 'medical_revision_id'];

    /**
     * Relationships
     */

     public function treatment()
     {
         return $this->belongsTo(Treatment::class);
     }

     public function medicalHistory()
     {
         return $this->belongsTo(MedicalHistory::class);
     }
}
