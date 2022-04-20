<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryAnalysis extends Model
{
    use HasFactory;

    protected $fillable = ['analisis_id', 'history_id', 'isRevision'];
    protected $table = "history_has_analisis";
    public $timestamps = false;

    public function analysis()
    {
        return $this->belongsTo(Analysis::class, 'analisis_id');
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
