<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = "company";
    protected $guarded = ["id", "created_at", "updated_at"];


    public function worker() 
    {
        return $this->hasOne(Worker::class);
    }
}
