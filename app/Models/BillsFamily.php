<?php

namespace App\Models;
use App\Models\BillsSubFamily;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillsFamily extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function billssubfamilies()
    {
        return $this->hasMany(BillsSubFamily::class);
    }
}
