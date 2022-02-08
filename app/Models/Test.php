<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $table = "general_test";
    protected $guarded = ["id", "created_at", "updated_at"];

    /**
     * Relationships
     */

    public function doctor() 
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient() 
    {
        return $this->belongsTo(Patient::class);
    }

    public function company() 
    {
        return $this->belongsTo(Company::class);
    }

    public function testType() 
    {
        return $this->belongsTo(TestType::class);
    }
    
}