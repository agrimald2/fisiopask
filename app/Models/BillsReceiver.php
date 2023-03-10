<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Description;

class BillsReceiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'document',
        'description'
    ];
}
