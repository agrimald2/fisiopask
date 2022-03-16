<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Relationships
     */

    public function historyTreatment()
    {
        return $this->hasOne(HistoryTreatment::class);
    }

    public function medicalHistory()
    {
        return $this->hasOne(MedicalHistory::class);
    }
}
