<?php

namespace App\Http\Controllers\Backend\Bills;

use App\Http\Controllers\DynamicController;
use Illuminate\Http\Request;
use App\Models\BillsPayer;

class BillsPayerController extends DynamicController
{
    protected $resourceName = "Bills Payers";
    protected $resourcePath = "Backend/Bills/Payers";
    protected $resourceRoute = "billspayers";


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = BillsPayer::query()
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        BillsPayer::create($validated);

        return $this->redirectIndex();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BillsPayer  $payer
     * @return \Illuminate\Http\Response
     */
    public function edit(BillsPayer $payer)
    {
        return $this->form($payer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Family  $payer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillsPayer $payer)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        $payer->update($validated);

        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BillsPayer  $payer
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillsPayer $payer)
    {
        $payer->delete();

        return $this->redirectIndex();
    }
}
