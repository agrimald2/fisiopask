<?php

namespace App\Models;

use App\Domain\Rates\RateBuyer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at', 'stock'];

    protected $casts = [
        'is_product' => 'boolean',
    ];

    public function subfamily()
    {
        return $this->belongsTo(Subfamily::class);
    }


    public function buy($qty = 1, RateBuyer $buyer = null)
    {
        $buyer = $buyer ?: new RateBuyer;
        return $buyer->buy($this, $qty);
    }

    public function patientRate() {
        return $this->belongsTo(PatientRate::class);
    }
}
