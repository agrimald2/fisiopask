<?php

namespace App\Models;
use App\Models\BillsSubfamily;


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
        return $this->hasMany(BillsSubfamily::class);
    }
}
