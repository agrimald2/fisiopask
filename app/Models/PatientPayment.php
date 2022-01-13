<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientPayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [
        'id', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i A'
    ];

    /**
     * Relationships
     */

    public function patientInventory()
    {
        return $this->belongsTo(PatientInventory::class);
    }

    public function patientRate() {
        return $this->belongsTo(PatientRate::class);
    }

    public function patient() {
        return $this->belongsTo(Patient::class);
    }
}
