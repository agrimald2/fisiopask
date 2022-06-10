<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTreatment extends Model
{
    use HasFactory;

    protected $fillable = ['treatment_id', 'history_id', 'isRevision'];

    protected $table = "history_has_treatment";

    public $timestamps = false;

    /**
     * Relationships
     */

     public function treatment()
     {
         return $this->belongsTo(Treatment::class);
     }

     public function medicalHistory()
     {
         return MedicalHistory::find($this->history_id);
     }

     public function medicalRevision()
     {
         return MedicalRevision::find($this->history_id);
     }
}
