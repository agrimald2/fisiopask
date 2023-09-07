<?php

namespace App\Http\Controllers\Backend\Bills;

use App\Http\Controllers\DynamicController;
use App\Models\BillsFamily;
use App\Models\BillsSubFamily;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BillsSubFamilyController extends DynamicController
{
    protected $resourceName = "Bills Subfamilies";
    protected $resourcePath = "Backend/Bills/SubFamilies";
    protected $resourceRoute = "billssubfamilies";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $test = BillsFamily::query()
            ->orderBy('id', 'desc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $model = BillsSubFamily::query()
            ->with('billsfamily')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return $this->grid($model, [], ['enableSearch' => false]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->form(null, [
            'families' => $this->getFamilyOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'billsfamily_id' => 'required|integer',
        ]);

        BillsSubFamily::create($validated);

        return $this->redirectIndex();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BillsSubFamily  $subfamily
     * @return \Illuminate\Http\Response
     */
    public function edit(BillsSubFamily $subfamily)
    {
        return $this->form($subfamily, [
            'families' => $this->getFamilyOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BillsSubFamily  $subfamily
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillsSubFamily $subfamily)
    {
        $validated = $request->validate([
            'name' => 'required',
            'billsfamily_id' => 'required|integer',
        ]);

        $subfamily->update($validated);

        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BillsSubFamily  $subfamily
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillsSubFamily $subfamily)
    {
        $subfamily->delete();

        return $this->redirectIndex();
    }


    private function getFamilyOptions()
    {
        return BillsFamily::query()
            ->orderBy('id', 'desc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }
}
