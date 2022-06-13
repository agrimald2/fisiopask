<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HCAttribute extends Model
{
    use HasFactory;

    protected $table = "history_attributes";

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
