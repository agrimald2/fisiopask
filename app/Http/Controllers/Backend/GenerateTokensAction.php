<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Patient;
use Illuminate\Support\Str;

class GenerateTokensAction extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $patients = Patient::query()->where('token', null)->get();

        foreach($patients as $patient)
        {
            $patient->token = Str::random(40);
            $patient->save();
        }
    }
}
