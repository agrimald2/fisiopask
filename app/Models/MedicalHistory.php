<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Rleationships
     */
    public function historyGroup()
    {
        return $this->belongsTo(HistoryGroup::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function analysis()
    {
        return $this->belongsTo(Analysis::class);
    }

    public function affectedArea()
    {
        return $this->belongsTo(AfectedArea::class);
    }
}
