<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HCAttribute extends Model
{
    use HasFactory;

    const ATTRIBUTE_TEXT = 0;
    const ATTRIBUTE_NUMBER = 1;
    const ATTRIBUTE_SELECT = 2;
    const ATTRIBUTE_MULTI = 3;

    protected $table = "history_attributes";

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
