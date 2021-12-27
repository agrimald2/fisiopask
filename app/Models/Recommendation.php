<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recommendation extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = ['recommendation'];

    public function patient() 
    {
        return $this->hasOne(Patient::class);
    }
}
