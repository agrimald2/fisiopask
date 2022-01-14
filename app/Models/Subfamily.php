<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subfamily extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'family_id'];
    protected $appends = ['name_with_family'];

    /**
     * Attributes
     */

    function getNameWithFamilyAttribute()
    {
        return $this->family->name . " - " . $this->name;
    }

    /**
     * Relationships
     */

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class)->withPivot('subfamily_id');
    }
}
