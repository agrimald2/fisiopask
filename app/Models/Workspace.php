<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Relationships
     */

     public function doctor() 
     {
         return $this->hasOne(Doctor::class);
     }

     public function office()
     {
         return $this->belongsTo(Office::class);
     }
}
