<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'billssubfamily_id',
        'receiver',
        'paymentway',
        'moneyOrigin',
        'payer',
        'quantity',
        'created_by'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function billssubfamily()
    {
        return $this->belongsTo(BillsSubFamily::class);
    }
}
