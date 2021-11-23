<?php

namespace App\Domain\Rates;

use App\Models\Rate;

class RateBuyer
{

    public function buy(Rate $rate, $qty = 1)
    {
        if ($rate->is_product) {
            $rate->stock -= $qty;
            $rate->save();
        }
    }
}
