<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicalRevisionController extends Controller
{
    public function index(Request $request)
    {
        $model = MedicalHistory::query()->orderBy('id', 'desc')->get();

        return inertia('Backend/Patients/MedicalRevisions/Index', compact('model'));
    }

    public function create($id)
    {
        $history_group = HistoryGroup::find($id);

        $treatments = Treatment::query()
            ->orderBy('id', 'desc')
            ->get();

        return inertia(
            'Backend/Patients/MedicalRevisions/Create', 
            compact(
                'history_group',
                'treatments',
            ));
    }
}
