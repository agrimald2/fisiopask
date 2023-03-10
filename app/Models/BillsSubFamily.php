<?php

namespace App\Models;
use App\Models\Billsfamily;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillsSubFamily extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'billsfamily_id'
    ];

    public function billsfamily()
    {
        return $this->belongsTo(BillsFamily::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

}

