<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'json'];

    protected $casts = [
        'json' => 'json'
    ];


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
