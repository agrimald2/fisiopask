<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResultType extends Model
{
    use HasFactory;

    protected $table = "test_result_type";
    protected $fillable = ["test_type_id", "result"];

    public $timestamps = false;
}
