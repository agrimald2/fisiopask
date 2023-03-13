<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * ! Status
     * ? 0 -> Solicitado
     * ? 1 -> Aprobado
     * ? 2 -> Anulado
     */
    protected $fillable = [
        'name',
        'description',
        'billssubfamily_id',
        'receiver',
        'paymentway',
        'moneyOrigin',
        'payer',
        'quantity',

        'status',
        'isDoubleChecked',

        'isApproved',
        'approved_by',
        'approved_at',

        'secondIsApproved',
        'second_approved_by',
        'second_approved_at',

        'created_by'
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function billssubfamily()
    {
        return $this->belongsTo(BillsSubFamily::class);
    }
}
