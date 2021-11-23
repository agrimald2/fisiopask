<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    public function __invoke()
    {
        return redirect()->route('bookAppointment.index');
    }
}
