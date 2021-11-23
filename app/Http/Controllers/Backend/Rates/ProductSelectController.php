<?php

namespace App\Http\Controllers\Backend\Rates;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Subfamily;
use Illuminate\Http\Request;

class ProductSelectController extends Controller
{
    public function getFamilies()
    {
        return Family::query()
            ->orderBy('id', 'desc')
            ->select('id', 'name')
            ->get();
    }


    public function getSubfamilies(Family $family)
    {
        return $family->subfamilies()
            ->orderBy('id', 'desc')
            ->select('id', 'name')
            ->get();
    }


    public function getRates(Subfamily $subfamily)
    {
        return $subfamily->rates()
            ->orderBy('id', 'desc')
            ->select('id', 'name', 'price')
            ->get();
    }
}
