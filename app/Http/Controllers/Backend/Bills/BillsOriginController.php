<?php

namespace App\Http\Controllers\Backend\Bills;

use App\Http\Controllers\DynamicController;
use Illuminate\Http\Request;
use App\Models\BillsOrigin;

class BillsOriginController extends DynamicController
{
    protected $resourceName = "Bills Origin";
    protected $resourcePath = "Backend/Bills/Origins";
    protected $resourceRoute = "billsorigins";


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = BillsOrigin::query()
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

        BillsOrigin::create($validated);

        return $this->redirectIndex();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BillsOrigin  $origin
     * @return \Illuminate\Http\Response
     */
    public function edit(BillsOrigin $origin)
    {
        return $this->form($origin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Family  $origin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillsOrigin $origin)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        $origin->update($validated);

        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BillsOrigin  $origin
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillsOrigin $origin)
    {
        $origin->delete();

        return $this->redirectIndex();
    }
}
