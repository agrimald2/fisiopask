<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryData extends Model
{
    use HasFactory;

    protected $table = "history_data";
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Relationships
     */

    public function attribute()
    {
        return $this->belongsTo(HCAttribute::class);
    }
}
