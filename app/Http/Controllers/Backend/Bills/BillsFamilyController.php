<?php

namespace App\Http\Controllers\Backend\Bills;

use App\Http\Controllers\DynamicController;
use App\Models\BillsFamily;
use Illuminate\Http\Request;

class BillsFamilyController extends DynamicController
{
    protected $resourceName = "Bills Families";
    protected $resourcePath = "Backend/Bills/Families";
    protected $resourceRoute = "billsfamilies";


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = BillsFamily::query()
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
        return $this->form();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(BillsFamily $family)
    {
        return $this->form($family);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        BillsFamily::create($validated);

        return $this->redirectIndex();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BillsFamily  $family
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillsFamily $family)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        $family->update($validated);

        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BillsFamily  $family
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillsFamily $family)
    {
        $family->delete();

        return $this->redirectIndex();
    }
}
