<?php

namespace App\Models;

use Google\Service\SemanticTile\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryArea extends Model
{
    use HasFactory;

    protected $fillable = ['affected_area_id', 'history_id', 'isRevision'];
    protected $table = "history_has_affected_area";
    public $timestamps = false;

    public function affectedArea()
    {
        return $this->belongsTo(AffectedArea::class);
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
